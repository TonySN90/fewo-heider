<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{
    /** Tabellen mit direkter tenant_id-Spalte */
    private const TENANT_DIRECT_TABLES = [
        'bookings',
        'seasons',
        'pricing_notes',
        'pages',
        'page_groups',
        'template_sections',
        'tenant_themes',
    ];

    /** Tabellen ohne tenant_id (nur Full-Export, Super-Admin) */
    private const GLOBAL_TABLES = [
        'users',
        'tenants',
        'tenant_user',
        'templates',
        'icons',
        'roles',
        'permissions',
        'model_has_roles',
        'model_has_permissions',
        'role_has_permissions',
    ];

    public function index()
    {
        $isSuperAdmin = auth()->user()->hasRole('super-admin');
        $tenants = $isSuperAdmin ? Tenant::orderBy('name')->get() : collect();
        $currentTenant = current_tenant();

        return view('admin.database', compact('isSuperAdmin', 'tenants', 'currentTenant'));
    }

    public function export(Request $request)
    {
        $isSuperAdmin = auth()->user()->hasRole('super-admin');

        $scope = $request->input('scope', 'tenant');
        $format = $request->input('format', 'json'); // 'json' | 'sql'
        $tenantId = $request->input('tenant_id');

        if (! $isSuperAdmin) {
            $tenant = current_tenant();
            abort_if(! $tenant, 403);
            $tenantId = $tenant->id;
            $scope = 'tenant';
        }

        if ($scope === 'full' && ! $isSuperAdmin) {
            abort(403);
        }

        $tenant = null;
        if ($scope === 'tenant') {
            $tenant = $tenantId ? Tenant::findOrFail($tenantId) : current_tenant();
            abort_if(! $tenant, 400, 'Keine Instanz gewählt.');
            $tenantId = $tenant->id;
        }

        $slug = $scope === 'full' ? 'full' : ($tenant->slug ?? $tenantId);
        $timestamp = now()->format('Y-m-d_His');

        if ($format === 'sql') {
            $filename = ($scope === 'full' ? 'full-export' : "tenant-{$slug}")."-{$timestamp}.sql";

            return response()->streamDownload(
                function () use ($scope, $tenantId) {
                    echo $this->buildSqlExport($scope, $tenantId);
                },
                $filename,
                ['Content-Type' => 'application/sql']
            );
        }

        $payload = $this->buildJsonExport($scope, $tenantId);
        $filename = ($scope === 'full' ? 'full-export' : "tenant-{$slug}")."-{$timestamp}.json";

        return response()->streamDownload(
            function () use ($payload) {
                echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            },
            $filename,
            ['Content-Type' => 'application/json']
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:json,sql,txt', 'max:102400'],
            'confirm' => ['required', 'accepted'],
        ]);

        $isSuperAdmin = auth()->user()->hasRole('super-admin');
        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $content = file_get_contents($file->getRealPath());

        if ($extension === 'sql') {
            return $this->importSql($content, $isSuperAdmin);
        }

        return $this->importJson($content, $isSuperAdmin);
    }

    // ── JSON Export ────────────────────────────────────────────────────────

    private function buildJsonExport(string $scope, ?int $tenantId): array
    {
        $payload = [
            'version' => 1,
            'scope' => $scope,
            'tenant_id' => $tenantId,
            'exported_at' => now()->toISOString(),
            'tables' => [],
        ];

        if ($scope === 'full') {
            foreach (self::GLOBAL_TABLES as $table) {
                if (Schema::hasTable($table)) {
                    $payload['tables'][$table] = DB::table($table)->get()->toArray();
                }
            }
        }

        foreach (self::TENANT_DIRECT_TABLES as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $query = DB::table($table);
            if ($scope === 'tenant' && $tenantId !== null) {
                $query->where('tenant_id', $tenantId);
            }
            $payload['tables'][$table] = $query->get()->toArray();
        }

        // season_prices → via seasons
        $seasonIds = DB::table('seasons')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->pluck('id')->toArray();
        $payload['tables']['season_prices'] = Schema::hasTable('season_prices')
            ? DB::table('season_prices')->whereIn('season_id', $seasonIds)->get()->toArray()
            : [];

        // page_entries → via pages
        $pageIds = DB::table('pages')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->pluck('id')->toArray();
        $payload['tables']['page_entries'] = Schema::hasTable('page_entries')
            ? DB::table('page_entries')->whereIn('page_id', $pageIds)->get()->toArray()
            : [];

        // page_entry_blocks → via page_entries
        $entryIds = DB::table('page_entries')->whereIn('page_id', $pageIds)->pluck('id')->toArray();
        $payload['tables']['page_entry_blocks'] = Schema::hasTable('page_entry_blocks')
            ? DB::table('page_entry_blocks')->whereIn('page_entry_id', $entryIds)->get()->toArray()
            : [];

        // template_section_content + gallery_images → via template_sections
        $sectionIds = DB::table('template_sections')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('template_sections.tenant_id', $tenantId))
            ->pluck('id')->toArray();
        $payload['tables']['template_section_content'] = Schema::hasTable('template_section_content')
            ? DB::table('template_section_content')->whereIn('template_section_id', $sectionIds)->get()->toArray()
            : [];
        $payload['tables']['gallery_images'] = Schema::hasTable('gallery_images')
            ? DB::table('gallery_images')->whereIn('template_section_id', $sectionIds)->get()->toArray()
            : [];

        // seo (polymorph)
        $payload['tables']['seo'] = Schema::hasTable('seo')
            ? $this->exportSeo($scope, $tenantId, $pageIds, $entryIds)->toArray()
            : [];

        return $payload;
    }

    // ── SQL Export ─────────────────────────────────────────────────────────

    private function buildSqlExport(string $scope, ?int $tenantId): string
    {
        $lines = [];
        $lines[] = '-- fewo-heider export';
        $lines[] = '-- scope: '.$scope;
        $lines[] = '-- tenant_id: '.($tenantId ?? 'all');
        $lines[] = '-- exported_at: '.now()->toISOString();
        $lines[] = '-- format_version: 1';
        $lines[] = '';

        // Alle Tabellen sammeln (gleiche Logik wie JSON)
        $allTables = $this->collectAllRows($scope, $tenantId);

        foreach ($allTables as $table => $rows) {
            $lines[] = "-- Table: {$table}";
            $lines[] = "DELETE FROM \"{$table}\"".$this->buildDeleteWhere($table, $scope, $tenantId).';';

            foreach ($rows as $row) {
                $row = (array) $row;
                $columns = '"'.implode('", "', array_keys($row)).'"';
                $values = implode(', ', array_map(fn ($v) => $this->sqlValue($v), array_values($row)));
                $lines[] = "INSERT INTO \"{$table}\" ({$columns}) VALUES ({$values});";
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /** Gibt einen SQL-Wert korrekt escaped zurück */
    private function sqlValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return "'".str_replace(["\\", "'", "\n", "\r"], ["\\\\", "\\'", "\\n", "\\r"], (string) $value)."'";
    }

    /** WHERE-Klausel für DELETE beim SQL-Export */
    private function buildDeleteWhere(string $table, string $scope, ?int $tenantId): string
    {
        if ($scope === 'full') {
            return '';
        }

        if (in_array($table, array_merge(self::TENANT_DIRECT_TABLES, ['bookings']))) {
            return " WHERE \"tenant_id\" = {$tenantId}";
        }

        return '';
    }

    // ── SQL Import ─────────────────────────────────────────────────────────

    private function importSql(string $sql, bool $isSuperAdmin): \Illuminate\Http\RedirectResponse
    {
        // Metadaten aus Kommentaren lesen
        $scope = null;
        $tenantId = null;

        foreach (explode("\n", $sql) as $line) {
            if (str_starts_with($line, '-- scope: ')) {
                $scope = trim(substr($line, 10));
            }
            if (str_starts_with($line, '-- tenant_id: ')) {
                $raw = trim(substr($line, 14));
                $tenantId = is_numeric($raw) ? (int) $raw : null;
            }
            if ($scope !== null && $tenantId !== null) {
                break;
            }
        }

        if ($scope === null) {
            return back()->withErrors(['backup_file' => 'Ungültige SQL-Exportdatei (fehlende Metadaten).']);
        }

        if (! $isSuperAdmin) {
            if ($scope === 'full') {
                abort(403, 'Admins können keinen vollständigen Import durchführen.');
            }
            $tenant = current_tenant();
            abort_if(! $tenant, 403);
            if ($tenantId !== $tenant->id) {
                return back()->withErrors(['backup_file' => 'Die Exportdatei gehört nicht zu dieser Instanz.']);
            }
        }

        // Statements parsen: Zeilen zusammenführen bis ';'
        $statements = $this->parseSqlStatements($sql);

        $driver = DB::getDriverName();

        $this->withFkDisabled(function () use ($statements, $driver) {
            DB::transaction(function () use ($statements, $driver) {
                foreach ($statements as $stmt) {
                    $stmt = trim($stmt);

                    if ($stmt === ''
                        || str_starts_with($stmt, '--')
                        || stripos($stmt, 'FOREIGN_KEY_CHECKS') !== false
                        || stripos($stmt, 'PRAGMA foreign_keys') !== false
                    ) {
                        continue;
                    }

                    // Backticks → ANSI-Identifier (kompatibel mit SQLite und MySQL)
                    $stmt = $this->normalizeIdentifiers($stmt);

                    // Auf SQLite: INSERT → INSERT OR REPLACE damit Unique-Konflikte nicht auftreten,
                    // falls ein DELETE-Statement zuvor nicht alle Zeilen erfasst hat
                    if ($driver === 'sqlite' && preg_match('/^INSERT\s+INTO\s/i', $stmt)) {
                        $stmt = preg_replace('/^INSERT\s+INTO\s/i', 'INSERT OR REPLACE INTO ', $stmt);
                    }

                    DB::statement($stmt);
                }
            });
        });

        return back()->with('success', 'SQL-Import erfolgreich abgeschlossen.');
    }

    /**
     * Ersetzt Backtick-Identifier durch ANSI-konforme doppelte Anführungszeichen.
     * Verändert keine String-Inhalte in einfachen Anführungszeichen.
     */
    private function normalizeIdentifiers(string $sql): string
    {
        $result = '';
        $i = 0;
        $len = strlen($sql);

        while ($i < $len) {
            $char = $sql[$i];

            if ($char === "'") {
                // String-Inhalt unverändert übernehmen
                $result .= $char;
                $i++;
                while ($i < $len) {
                    $c = $sql[$i];
                    $result .= $c;
                    $i++;
                    if ($c === '\\') {
                        $result .= $sql[$i] ?? '';
                        $i++;
                    } elseif ($c === "'") {
                        break;
                    }
                }
            } elseif ($char === '`') {
                // Backtick-Identifier → doppelte Anführungszeichen
                $result .= '"';
                $i++;
                while ($i < $len && $sql[$i] !== '`') {
                    $result .= $sql[$i];
                    $i++;
                }
                $result .= '"';
                $i++; // schließendes Backtick überspringen
            } else {
                $result .= $char;
                $i++;
            }
        }

        return $result;
    }

    /** Splittet SQL-Text in einzelne Statements (berücksichtigt Strings mit Semikolon) */
    private function parseSqlStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $i = 0;
        $len = strlen($sql);

        while ($i < $len) {
            $char = $sql[$i];

            if ($inString) {
                if ($char === '\\') {
                    $current .= $char.($sql[$i + 1] ?? '');
                    $i += 2;
                    continue;
                }
                if ($char === $stringChar) {
                    $inString = false;
                }
                $current .= $char;
            } else {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                    $current .= $char;
                } elseif ($char === ';') {
                    $stmt = trim($current);
                    if ($stmt !== '') {
                        $statements[] = $stmt;
                    }
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
            $i++;
        }

        $stmt = trim($current);
        if ($stmt !== '') {
            $statements[] = $stmt;
        }

        return $statements;
    }

    // ── JSON Import ────────────────────────────────────────────────────────

    private function importJson(string $json, bool $isSuperAdmin): \Illuminate\Http\RedirectResponse
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! isset($data['version'], $data['scope'], $data['tables'])) {
            return back()->withErrors(['backup_file' => 'Ungültige oder beschädigte JSON-Exportdatei.']);
        }

        if (! $isSuperAdmin) {
            if ($data['scope'] === 'full') {
                abort(403, 'Admins können keinen vollständigen Import durchführen.');
            }
            $tenant = current_tenant();
            abort_if(! $tenant, 403);
            if ((int) $data['tenant_id'] !== $tenant->id) {
                return back()->withErrors(['backup_file' => 'Die Exportdatei gehört nicht zu dieser Instanz.']);
            }
        }

        $this->withFkDisabled(function () use ($data, $isSuperAdmin) {
            DB::transaction(fn () => $this->runImport($data, $isSuperAdmin));
        });

        return back()->with('success', 'JSON-Import erfolgreich abgeschlossen.');
    }

    // ── Gemeinsame Helpers ─────────────────────────────────────────────────

    /**
     * Sammelt alle Tabellenzeilen für Export (JSON und SQL nutzen dieselbe Logik).
     *
     * @return array<string, array<int, object>>
     */
    private function collectAllRows(string $scope, ?int $tenantId): array
    {
        $result = [];

        if ($scope === 'full') {
            foreach (self::GLOBAL_TABLES as $table) {
                if (Schema::hasTable($table)) {
                    $result[$table] = DB::table($table)->get()->toArray();
                }
            }
        }

        foreach (self::TENANT_DIRECT_TABLES as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $query = DB::table($table);
            if ($scope === 'tenant' && $tenantId !== null) {
                $query->where('tenant_id', $tenantId);
            }
            $result[$table] = $query->get()->toArray();
        }

        $seasonIds = DB::table('seasons')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->pluck('id')->toArray();
        $result['season_prices'] = Schema::hasTable('season_prices')
            ? DB::table('season_prices')->whereIn('season_id', $seasonIds)->get()->toArray()
            : [];

        $pageIds = DB::table('pages')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->pluck('id')->toArray();
        $result['page_entries'] = Schema::hasTable('page_entries')
            ? DB::table('page_entries')->whereIn('page_id', $pageIds)->get()->toArray()
            : [];

        $entryIds = DB::table('page_entries')->whereIn('page_id', $pageIds)->pluck('id')->toArray();
        $result['page_entry_blocks'] = Schema::hasTable('page_entry_blocks')
            ? DB::table('page_entry_blocks')->whereIn('page_entry_id', $entryIds)->get()->toArray()
            : [];

        $sectionIds = DB::table('template_sections')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->pluck('id')->toArray();
        $result['template_section_content'] = Schema::hasTable('template_section_content')
            ? DB::table('template_section_content')->whereIn('template_section_id', $sectionIds)->get()->toArray()
            : [];
        $result['gallery_images'] = Schema::hasTable('gallery_images')
            ? DB::table('gallery_images')->whereIn('template_section_id', $sectionIds)->get()->toArray()
            : [];

        $result['seo'] = Schema::hasTable('seo')
            ? $this->exportSeo($scope, $tenantId, $pageIds, $entryIds)->toArray()
            : [];

        return $result;
    }

    /** @return \Illuminate\Support\Collection<int, object> */
    private function exportSeo(string $scope, ?int $tenantId, array $pageIds, array $entryIds): \Illuminate\Support\Collection
    {
        $groupIds = DB::table('page_groups')
            ->when($scope === 'tenant' && $tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->pluck('id')->toArray();

        $rows = collect();

        $types = [
            'App\\Models\\Tenant' => $scope === 'full'
                ? DB::table('tenants')->pluck('id')->toArray()
                : ($tenantId ? [$tenantId] : []),
            'App\\Models\\PageGroup' => $groupIds,
            'App\\Models\\Page' => $pageIds,
            'App\\Models\\PageEntry' => $entryIds,
        ];

        foreach ($types as $modelType => $ids) {
            if (empty($ids)) {
                continue;
            }
            $rows = $rows->merge(
                DB::table('seo')
                    ->where('model_type', $modelType)
                    ->whereIn('model_id', $ids)
                    ->get()
            );
        }

        return $rows->values();
    }

    private function runImport(array $data, bool $isSuperAdmin): void
    {
        $scope = $data['scope'];
        $tables = $data['tables'];
        $tenantId = isset($data['tenant_id']) ? (int) $data['tenant_id'] : null;

        if ($scope === 'full' && $isSuperAdmin) {
            foreach (self::GLOBAL_TABLES as $table) {
                $this->importTable($table, $tables[$table] ?? [], truncate: true);
            }
        }

        $this->deleteTenantData($tenantId, $scope);

        $this->importTable('page_groups', $tables['page_groups'] ?? []);
        $this->importTable('pages', $tables['pages'] ?? []);
        $this->importTable('page_entries', $tables['page_entries'] ?? []);
        $this->importTable('page_entry_blocks', $tables['page_entry_blocks'] ?? []);
        $this->importTable('seasons', $tables['seasons'] ?? []);
        $this->importTable('season_prices', $tables['season_prices'] ?? []);
        $this->importTable('pricing_notes', $tables['pricing_notes'] ?? []);
        $this->importTable('bookings', $tables['bookings'] ?? []);
        $this->importTable('template_sections', $tables['template_sections'] ?? []);
        $this->importTable('template_section_content', $tables['template_section_content'] ?? []);
        $this->importTable('gallery_images', $tables['gallery_images'] ?? []);
        $this->importTable('tenant_themes', $tables['tenant_themes'] ?? []);
        $this->importTable('seo', $tables['seo'] ?? []);
    }

    private function deleteTenantData(?int $tenantId, string $scope): void
    {
        $isFullScope = $scope === 'full';

        if (Schema::hasTable('seo')) {
            if ($isFullScope) {
                DB::table('seo')->delete();
            } else {
                $pageIds = DB::table('pages')->where('tenant_id', $tenantId)->pluck('id');
                $entryIds = DB::table('page_entries')->whereIn('page_id', $pageIds)->pluck('id');
                $groupIds = DB::table('page_groups')->where('tenant_id', $tenantId)->pluck('id');

                DB::table('seo')
                    ->where(function ($q) use ($tenantId, $pageIds, $entryIds, $groupIds) {
                        $q->where(fn ($q2) => $q2->where('model_type', 'App\\Models\\Tenant')->where('model_id', $tenantId))
                            ->orWhere(fn ($q2) => $q2->where('model_type', 'App\\Models\\Page')->whereIn('model_id', $pageIds))
                            ->orWhere(fn ($q2) => $q2->where('model_type', 'App\\Models\\PageGroup')->whereIn('model_id', $groupIds))
                            ->orWhere(fn ($q2) => $q2->where('model_type', 'App\\Models\\PageEntry')->whereIn('model_id', $entryIds));
                    })
                    ->delete();
            }
        }

        if (Schema::hasTable('gallery_images') && Schema::hasTable('template_sections')) {
            $sectionIds = DB::table('template_sections')
                ->when(! $isFullScope, fn ($q) => $q->where('tenant_id', $tenantId))
                ->pluck('id');
            DB::table('gallery_images')->whereIn('template_section_id', $sectionIds)->delete();
        }

        if (Schema::hasTable('template_section_content') && Schema::hasTable('template_sections')) {
            $sectionIds = DB::table('template_sections')
                ->when(! $isFullScope, fn ($q) => $q->where('tenant_id', $tenantId))
                ->pluck('id');
            DB::table('template_section_content')->whereIn('template_section_id', $sectionIds)->delete();
        }

        if (Schema::hasTable('page_entry_blocks') && Schema::hasTable('page_entries') && Schema::hasTable('pages')) {
            $pageIds = DB::table('pages')
                ->when(! $isFullScope, fn ($q) => $q->where('tenant_id', $tenantId))
                ->pluck('id');
            $entryIds = DB::table('page_entries')->whereIn('page_id', $pageIds)->pluck('id');
            DB::table('page_entry_blocks')->whereIn('page_entry_id', $entryIds)->delete();
        }

        if (Schema::hasTable('page_entries') && Schema::hasTable('pages')) {
            $pageIds = DB::table('pages')
                ->when(! $isFullScope, fn ($q) => $q->where('tenant_id', $tenantId))
                ->pluck('id');
            DB::table('page_entries')->whereIn('page_id', $pageIds)->delete();
        }

        if (Schema::hasTable('season_prices') && Schema::hasTable('seasons')) {
            $seasonIds = DB::table('seasons')
                ->when(! $isFullScope, fn ($q) => $q->where('tenant_id', $tenantId))
                ->pluck('id');
            DB::table('season_prices')->whereIn('season_id', $seasonIds)->delete();
        }

        $directTables = array_merge(self::TENANT_DIRECT_TABLES, ['bookings']);
        foreach ($directTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $query = DB::table($table);
            if (! $isFullScope) {
                $query->where('tenant_id', $tenantId);
            }
            $query->delete();
        }
    }

    private function importTable(string $table, array $rows, bool $truncate = false): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }
        if ($truncate) {
            // Immer löschen, auch wenn $rows leer ist — sonst bleiben Altdaten stehen
            DB::table($table)->delete();
        }
        if (empty($rows)) {
            return;
        }
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table($table)->insert(array_map(fn ($r) => (array) $r, $chunk));
        }
    }

    /**
     * Führt einen Callback mit deaktivierten Foreign-Key-Constraints aus.
     * PRAGMA muss bei SQLite AUSSERHALB einer Transaktion gesetzt werden.
     */
    private function withFkDisabled(callable $callback): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        try {
            $callback();
        } finally {
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            }
        }
    }
}
