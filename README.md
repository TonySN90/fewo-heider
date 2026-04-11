# fewo-heider-2026

Ferienwohnung Heider – Rügen · Laravel 13 + Blade + Vite/TypeScript

---

## Multi-Tenant-System

### Grundidee

Eine Laravel-Instanz betreibt mehrere **Ferienwohnungs-Websites** gleichzeitig. Jede Website ist ein „Tenant" (Instanz) mit eigener Domain, eigenem Inhalt und eigenem Theme. Der Code läuft einmal, die Daten sind aber strikt getrennt.

---

### Was ein Tenant ist

Ein Tenant (`app/Models/Tenant.php`) hat:

| Feld | Beschreibung |
|---|---|
| `domain` | Öffentliche Domain (z.B. `fewo-heider.de`) |
| `slug` | Für lokale Vorschau (z.B. `heider`) |
| `template_id` | Zugewiesenes Layout-Template |
| `is_active` | Ob die Instanz aktiv ist |

Relationen: `theme` (eigenes Farbschema), `users` (zugeordnete Admins/Clients)

---

### Tenant-Auflösung pro Request

Die `ResolveTenant`-Middleware (`app/Http/Middleware/ResolveTenant.php`) läuft auf allen öffentlichen und Admin-Routen und probiert drei Strategien in dieser Reihenfolge:

```
1. Super-Admin hat per Session eine Instanz gewählt  →  admin_tenant_id in Session
2. Client hat seine Instanz per Session gewählt      →  current_tenant_id in Session
                                                         (muss zu seinen eigenen gehören)
3. Domain-Lookup                                     →  tenants.domain = request()->getHost()
```

Wird ein Tenant gefunden, wird er ins Laravel Service Container gebunden:

```php
app()->instance('currentTenant', $tenant);
```

Danach ist er überall per Hilfsfunktion verfügbar:

```php
current_tenant()  // → app/helpers.php
```

---

### Lokale Entwicklung (ohne echte Domains)

Da man lokal keine echten Domains hat, gibt es die Route:

```
GET /preview/{tenantSlug}
```

Sie setzt den Tenant manuell per Slug, **ohne** Domain-Lookup. Diese Route steht bewusst **vor** der `resolve.tenant`-Middleware-Gruppe in `routes/web.php`.

---

### Datentrennung in der Datenbank

Alle tenant-spezifischen Daten tragen eine `tenant_id`-Spalte:

| Tabelle | Beschreibung |
|---|---|
| `bookings` | Buchungen |
| `seasons` / `season_prices` | Saisons & Preise |
| `pricing_notes` | Preiszusatzinfos |
| `template_sections` | Seiteninhalte (Kopie pro Tenant) |
| `gallery_images` | Galeriebilder |
| `page_groups`, `pages`, `page_entries` | Seitenstruktur |
| `tenant_themes` | Farbschema |

Controller filtern immer explizit: `->where('tenant_id', $tenant->id)`.

---

### Template-System

Ein globales **Template** definiert die Seitenstruktur (Sektionen wie `hero`, `about`, `gallery`, …). Wenn einem Tenant ein Template zugewiesen wird, legt `Template::ensureTenantSections()` **Kopien** aller Sektionen mit der `tenant_id` an – idempotent, also sicher mehrfach aufrufbar.

```
Globale TemplateSection (tenant_id = null)
        ↓ ensureTenantSections()
Tenant-TemplateSection (tenant_id = 42)  ← eigene Inhalte, Sichtbarkeit, Reihenfolge
```

Der `HomeController` wählt immer die Tenant-spezifischen Sektionen, falls vorhanden.

---

### Rollen & Zugriffsebenen

| Rolle | Tenant-Kontext | Zugang |
|---|---|---|
| `super-admin` | optional (wählbar) | Plattform-Verwaltung, alle Instanzen |
| `admin` | fest (eigene Instanzen) | Instanz-Verwaltung |
| `client` | wählbar (aus zugeordneten) | eingeschränkte Verwaltung |

Der Super-Admin kann via `POST /admin/tenant-switch` den Kontext wechseln – das schreibt `admin_tenant_id` in die Session. `GET /admin/tenants/clear-context` löscht den Kontext wieder.