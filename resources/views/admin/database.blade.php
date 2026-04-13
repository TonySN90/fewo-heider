@extends('layouts.admin')

@section('title', 'Export / Import')

@section('content')
  <h1>Datenbank Export / Import</h1>

  {{-- ── EXPORT ─────────────────────────────────────────────────────────── --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Export</h2>
    </div>

    @if ($isSuperAdmin)
      <div class="database-export-grid">

        {{-- Vollständiger Export --}}
        <form class="database-export-card" method="POST" action="{{ route('admin.database.export') }}">
          @csrf
          <input type="hidden" name="scope" value="full" />
          <div class="database-export-card__icon">
            <span class="material-symbols-rounded">database</span>
          </div>
          <div class="database-export-card__body">
            <p class="database-export-card__label">Vollständiger Export</p>
            <p class="database-export-card__desc">Alle Instanzen inkl. Benutzer, Templates und Rollen.</p>
          </div>
          <div class="database-export-card__footer">
            <div class="database-format-toggle" role="group" aria-label="Format wählen">
              <label class="database-format-toggle__option">
                <input type="radio" name="format" value="json" checked />
                <span>JSON</span>
              </label>
              <label class="database-format-toggle__option">
                <input type="radio" name="format" value="sql" />
                <span>SQL</span>
              </label>
            </div>
            <button type="submit" class="btn-text-edit">
              <span class="material-symbols-rounded">download</span>
              Herunterladen
            </button>
          </div>
        </form>

        {{-- Instanz-Export --}}
        <form class="database-export-card" method="POST" action="{{ route('admin.database.export') }}">
          @csrf
          <input type="hidden" name="scope" value="tenant" />
          <div class="database-export-card__icon">
            <span class="material-symbols-rounded">apartment</span>
          </div>
          <div class="database-export-card__body">
            <p class="database-export-card__label">Instanz-Export</p>
            <p class="database-export-card__desc">Nur die Daten einer einzelnen Instanz exportieren.</p>
          </div>
          <div class="database-export-card__footer database-export-card__footer--col">
            <select name="tenant_id" class="database-export-card__select" required>
              <option value="">– Instanz wählen –</option>
              @foreach ($tenants as $t)
                <option value="{{ $t->id }}" {{ $currentTenant?->id === $t->id ? 'selected' : '' }}>
                  {{ $t->name }}
                </option>
              @endforeach
            </select>
            <div class="database-export-card__actions">
              <div class="database-format-toggle" role="group" aria-label="Format wählen">
                <label class="database-format-toggle__option">
                  <input type="radio" name="format" value="json" checked />
                  <span>JSON</span>
                </label>
                <label class="database-format-toggle__option">
                  <input type="radio" name="format" value="sql" />
                  <span>SQL</span>
                </label>
              </div>
              <button type="submit" class="btn-text-edit">
                <span class="material-symbols-rounded">download</span>
                Exportieren
              </button>
            </div>
          </div>
        </form>

      </div>
    @else
      {{-- Admin: nur eigene Instanz --}}
      @if ($currentTenant)
        <form class="database-export-single" method="POST" action="{{ route('admin.database.export') }}">
          @csrf
          <input type="hidden" name="scope" value="tenant" />
          <div class="database-export-single__icon">
            <span class="material-symbols-rounded">apartment</span>
          </div>
          <div class="database-export-single__info">
            <p class="database-export-single__name">{{ $currentTenant->name }}</p>
            <p class="database-export-single__sub">Instanz-Export</p>
          </div>
          <div class="database-export-single__actions">
            <div class="database-format-toggle" role="group" aria-label="Format wählen">
              <label class="database-format-toggle__option">
                <input type="radio" name="format" value="json" checked />
                <span>JSON</span>
              </label>
              <label class="database-format-toggle__option">
                <input type="radio" name="format" value="sql" />
                <span>SQL</span>
              </label>
            </div>
            <button type="submit" class="btn-text-edit">
              <span class="material-symbols-rounded">download</span>
              Exportieren
            </button>
          </div>
        </form>
      @else
        <p class="database-empty">Bitte zuerst eine Instanz auswählen.</p>
      @endif
    @endif
  </div>

  {{-- ── IMPORT ─────────────────────────────────────────────────────────── --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Import</h2>
    </div>

    <div class="database-import-warning">
      <span class="material-symbols-rounded">warning</span>
      <div>
        <strong>Achtung:</strong> Der Import löscht alle bestehenden Daten im betroffenen Bereich
        unwiderruflich und ersetzt sie durch den Inhalt der hochgeladenen Datei.
        Erstelle vorher einen Export als Sicherung.
      </div>
    </div>

    @if ($errors->any())
      <div class="database-import-errors">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form
      class="database-import-form"
      method="POST"
      action="{{ route('admin.database.import') }}"
      enctype="multipart/form-data"
      id="importForm"
    >
      @csrf

      <div class="database-import-dropzone" id="dropzone">
        <span class="material-symbols-rounded database-import-dropzone__icon">upload_file</span>
        <p class="database-import-dropzone__label">Exportdatei hier ablegen oder klicken</p>
        <p class="database-import-dropzone__sub">JSON oder SQL, max. 100 MB</p>
        <input type="file" id="backup_file" name="backup_file" accept=".json,.sql" required />
        <p class="database-import-dropzone__filename" id="dropzoneFilename"></p>
      </div>

      <label class="database-import-confirm">
        <input type="checkbox" name="confirm" value="1" required />
        <span>Ich bestätige, dass bestehende Daten unwiderruflich überschrieben werden.</span>
      </label>

      <div class="database-import-submit">
        <button type="submit" class="btn-text-delete">
          <span class="material-symbols-rounded">upload</span>
          Import starten
        </button>
      </div>

    </form>
  </div>

@endsection

@push('scripts')
<script>
  // Dropzone: Dateiname anzeigen + Drag & Drop
  const input    = document.getElementById('backup_file');
  const dropzone = document.getElementById('dropzone');
  const label    = document.getElementById('dropzoneFilename');

  function showFilename(file) {
    label.textContent = file ? file.name : '';
    dropzone.classList.toggle('database-import-dropzone--selected', !!file);
  }

  input.addEventListener('change', () => showFilename(input.files[0]));

  dropzone.addEventListener('click', (e) => {
    if (e.target !== input) input.click();
  });

  dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('database-import-dropzone--drag');
  });

  dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('database-import-dropzone--drag');
  });

  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('database-import-dropzone--drag');
    const file = e.dataTransfer.files[0];
    if (file) {
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      showFilename(file);
    }
  });

  // Bestätigungs-Dialog
  document.getElementById('importForm').addEventListener('submit', function (e) {
    if (! confirm('Wirklich importieren? Bestehende Daten werden unwiderruflich überschrieben.')) {
      e.preventDefault();
    }
  });
</script>
@endpush
