@extends('layouts.admin')

@section('title', 'Theme')

@section('content')
<div class="page-header">
  <div>
    <h1>Farbtheme</h1>
  </div>
</div>

<div class="form-card">
  <form method="POST" action="{{ route('admin.theme.update') }}" id="themeForm">
    @csrf
    @method('PUT')

    <div class="theme-editor">

      {{-- Primärfarbe --}}
      <div class="theme-editor__primary">
        <div class="form-field">
          <label>Primärfarbe</label>
          <p class="form-hint">Alle anderen Farben werden automatisch abgeleitet. Du kannst einzelne Werte manuell anpassen.</p>
          <div class="theme-color-row theme-color-row--primary">
            <input type="color"
                   id="color_primary"
                   name="color_primary"
                   value="{{ $theme->color_primary ?? '#3d7a6e' }}"
                   class="theme-color-input theme-color-input--large" />
            <input type="text"
                   id="color_primary_text"
                   value="{{ $theme->color_primary ?? '#3d7a6e' }}"
                   class="theme-color-hex"
                   maxlength="7"
                   pattern="#[0-9a-fA-F]{6}" />
            <button type="button" class="btn btn--outline-sm" id="deriveBtn">
              <span class="material-symbols-rounded">auto_fix_high</span>
              Palette ableiten
            </button>
            <button type="button" class="btn btn--outline-sm btn--reset-primary" title="Auf Standard zurücksetzen" data-default="#3d7a6e" data-field="color_primary">
              <span class="material-symbols-rounded">restart_alt</span>
            </button>
          </div>
        </div>
      </div>

      {{-- Abgeleitete Farben --}}
      <div class="theme-editor__palette">
        <h3 class="theme-editor__palette-title">Farbpalette</h3>

        <div class="theme-editor__grid">

          @php
            $fields = [
              'color_primary_dark' => ['label' => 'Primär (Hover)', 'default' => '#2e5f55', 'hint' => 'Hover-Zustand von Buttons und Links'],
              'color_secondary'    => ['label' => 'Sekundär', 'default' => '#a8c5b5', 'hint' => 'Sekundäre Akzente, Hover-Rahmen'],
              'color_bg'           => ['label' => 'Hintergrund (primär)', 'default' => '#f7faf8', 'hint' => 'Heller Hintergrund der normalen Sektionen'],
              'color_bg_alt'       => ['label' => 'Hintergrund (sekundär)', 'default' => '#edf3ef', 'hint' => 'Etwas dunklerer Hintergrund der alternierenden Sektionen'],
              'color_border'       => ['label' => 'Rahmenfarbe', 'default' => '#cfe0d8', 'hint' => 'Rahmen von Karten und Elementen'],
              'color_footer_top'   => ['label' => 'Footer oben', 'default' => '#2c4a42', 'hint' => 'Hintergrund oberer Footer'],
              'color_footer_bot'   => ['label' => 'Footer unten', 'default' => '#161f1d', 'hint' => 'Hintergrund unterer Footer'],
            ];
          @endphp

          @foreach($fields as $name => $meta)
          <div class="theme-color-field">
            <label>{{ $meta['label'] }}</label>
            <p class="form-hint">{{ $meta['hint'] }}</p>
            <div class="theme-color-row">
              <input type="color"
                     id="{{ $name }}"
                     name="{{ $name }}"
                     value="{{ $theme->$name ?? $meta['default'] }}"
                     class="theme-color-input"
                     data-derived="{{ $name }}" />
              <input type="text"
                     id="{{ $name }}_text"
                     value="{{ $theme->$name ?? $meta['default'] }}"
                     class="theme-color-hex"
                     maxlength="7"
                     pattern="#[0-9a-fA-F]{6}"
                     data-for="{{ $name }}" />
              <button type="button"
                      class="btn btn--outline-sm btn--icon"
                      title="Auf abgeleitet zurücksetzen"
                      data-reset="{{ $name }}"
                      data-default="{{ $meta['default'] }}">
                <span class="material-symbols-rounded">restart_alt</span>
              </button>
            </div>
          </div>
          @endforeach

        </div>
      </div>

      {{-- Vorschau --}}
      <div class="theme-editor__preview">
        <h3 class="theme-editor__palette-title">Vorschau</h3>
        <div class="theme-preview" id="themePreview">
          <div class="theme-preview__section theme-preview__section--primary">
            <span class="theme-preview__label">Primärfarbe</span>
          </div>
          <div class="theme-preview__section theme-preview__section--dark">
            <span class="theme-preview__label">Primär (Hover)</span>
          </div>
          <div class="theme-preview__section theme-preview__section--secondary">
            <span class="theme-preview__label">Sekundär</span>
          </div>
          <div class="theme-preview__section theme-preview__section--bg">
            <span class="theme-preview__label theme-preview__label--dark">Hintergrund</span>
          </div>
          <div class="theme-preview__section theme-preview__section--bg-alt">
            <span class="theme-preview__label theme-preview__label--dark">Hintergrund alt</span>
          </div>
          <div class="theme-preview__section theme-preview__section--border">
            <span class="theme-preview__label theme-preview__label--dark">Rahmen</span>
          </div>
          <div class="theme-preview__section theme-preview__section--footer-top">
            <span class="theme-preview__label">Footer oben</span>
          </div>
          <div class="theme-preview__section theme-preview__section--footer-bot">
            <span class="theme-preview__label">Footer unten</span>
          </div>
        </div>
      </div>

      {{-- Dark-Mode-Palette --}}
      <div class="theme-editor__palette theme-editor__palette--dark">
        <h3 class="theme-editor__palette-title">Dark-Mode-Palette</h3>
        <p class="form-hint">Diese Farben werden aktiv wenn der Besucher den Dark-Mode aktiviert.</p>

        <div class="theme-color-row theme-color-row--primary" style="margin-bottom: 1.5rem;">
          <button type="button" class="btn btn--outline-sm" id="deriveDarkBtn">
            <span class="material-symbols-rounded">dark_mode</span>
            Dark-Palette ableiten
          </button>
        </div>

        <div class="theme-editor__grid">

          @php
            $darkFields = [
              'dark_color_primary'      => ['label' => 'Primärfarbe (Dark)', 'default' => '#5aab9b', 'hint' => 'Hauptfarbe im Dark-Mode'],
              'dark_color_primary_dark' => ['label' => 'Primär Hover (Dark)', 'default' => '#4a8f82', 'hint' => 'Hover-Zustand im Dark-Mode'],
              'dark_color_secondary'    => ['label' => 'Sekundär (Dark)', 'default' => '#6b9e8e', 'hint' => 'Sekundäre Akzente im Dark-Mode'],
              'dark_color_bg'           => ['label' => 'Hintergrund (Dark)', 'default' => '#1a2422', 'hint' => 'Dunkler Seitenhintergrund'],
              'dark_color_bg_alt'       => ['label' => 'Hintergrund alt (Dark)', 'default' => '#222e2b', 'hint' => 'Alternierender Hintergrund im Dark-Mode'],
              'dark_color_border'       => ['label' => 'Rahmen (Dark)', 'default' => '#2e4440', 'hint' => 'Rahmenfarbe im Dark-Mode'],
              'dark_color_footer_top'   => ['label' => 'Footer oben (Dark)', 'default' => '#111b19', 'hint' => 'Oberer Footer im Dark-Mode'],
              'dark_color_footer_bot'   => ['label' => 'Footer unten (Dark)', 'default' => '#0a0f0e', 'hint' => 'Unterer Footer im Dark-Mode'],
            ];
          @endphp

          @foreach($darkFields as $name => $meta)
          <div class="theme-color-field">
            <label>{{ $meta['label'] }}</label>
            <p class="form-hint">{{ $meta['hint'] }}</p>
            <div class="theme-color-row">
              <input type="color"
                     id="{{ $name }}"
                     name="{{ $name }}"
                     value="{{ $theme->$name ?? $meta['default'] }}"
                     class="theme-color-input"
                     data-derived-dark="{{ $name }}" />
              <input type="text"
                     id="{{ $name }}_text"
                     value="{{ $theme->$name ?? $meta['default'] }}"
                     class="theme-color-hex"
                     maxlength="7"
                     pattern="#[0-9a-fA-F]{6}"
                     data-for-dark="{{ $name }}" />
              <button type="button"
                      class="btn btn--outline-sm btn--icon"
                      title="Auf abgeleitet zurücksetzen"
                      data-reset-dark="{{ $name }}"
                      data-default="{{ $meta['default'] }}">
                <span class="material-symbols-rounded">restart_alt</span>
              </button>
            </div>
          </div>
          @endforeach

        </div>
      </div>

      {{-- Dark-Mode-Vorschau --}}
      <div class="theme-editor__preview">
        <h3 class="theme-editor__palette-title">Vorschau Dark-Mode</h3>
        <div class="theme-preview theme-preview--dark" id="themePreviewDark">
          <div class="theme-preview__section" id="dp-primary">
            <span class="theme-preview__label">Primärfarbe</span>
          </div>
          <div class="theme-preview__section" id="dp-primary-dark">
            <span class="theme-preview__label">Primär (Hover)</span>
          </div>
          <div class="theme-preview__section" id="dp-secondary">
            <span class="theme-preview__label">Sekundär</span>
          </div>
          <div class="theme-preview__section" id="dp-bg">
            <span class="theme-preview__label">Hintergrund</span>
          </div>
          <div class="theme-preview__section" id="dp-bg-alt">
            <span class="theme-preview__label">Hintergrund alt</span>
          </div>
          <div class="theme-preview__section" id="dp-border">
            <span class="theme-preview__label">Rahmen</span>
          </div>
          <div class="theme-preview__section" id="dp-footer-top">
            <span class="theme-preview__label">Footer oben</span>
          </div>
          <div class="theme-preview__section" id="dp-footer-bot">
            <span class="theme-preview__label">Footer unten</span>
          </div>
        </div>
      </div>

    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn--primary">Speichern</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
  // ── Hex ↔ HSL Konvertierung ───────────────────────────────────────────────

  function hexToHsl(hex) {
    let r = parseInt(hex.slice(1, 3), 16) / 255;
    let g = parseInt(hex.slice(3, 5), 16) / 255;
    let b = parseInt(hex.slice(5, 7), 16) / 255;

    const max = Math.max(r, g, b), min = Math.min(r, g, b);
    let h, s, l = (max + min) / 2;

    if (max === min) {
      h = s = 0;
    } else {
      const d = max - min;
      s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
      switch (max) {
        case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
        case g: h = ((b - r) / d + 2) / 6; break;
        case b: h = ((r - g) / d + 4) / 6; break;
      }
    }
    return [h * 360, s, l];
  }

  function hslToHex(h, s, l) {
    h = ((h % 360) + 360) % 360;
    s = Math.max(0, Math.min(1, s));
    l = Math.max(0, Math.min(1, l));

    const c = (1 - Math.abs(2 * l - 1)) * s;
    const x = c * (1 - Math.abs((h / 60) % 2 - 1));
    const m = l - c / 2;
    let r, g, b;

    if      (h < 60)  { r = c; g = x; b = 0; }
    else if (h < 120) { r = x; g = c; b = 0; }
    else if (h < 180) { r = 0; g = c; b = x; }
    else if (h < 240) { r = 0; g = x; b = c; }
    else if (h < 300) { r = x; g = 0; b = c; }
    else              { r = c; g = 0; b = x; }

    const toHex = v => Math.round((v + m) * 255).toString(16).padStart(2, '0');
    return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
  }

  function deriveFromPrimaryDark(hex) {
    const [h, s, l] = hexToHsl(hex);
    return {
      dark_color_primary:      hslToHex(h, s * 0.9,  l + (1 - l) * 0.25),
      dark_color_primary_dark: hslToHex(h, s * 0.85, l + (1 - l) * 0.15),
      dark_color_secondary:    hslToHex(h, s * 0.6,  l + (1 - l) * 0.1),
      dark_color_bg:           hslToHex(h, s * 0.5,  l * 0.2),
      dark_color_bg_alt:       hslToHex(h, s * 0.5,  l * 0.27),
      dark_color_border:       hslToHex(h, s * 0.45, l * 0.35),
      dark_color_footer_top:   hslToHex(h, s * 0.5,  l * 0.15),
      dark_color_footer_bot:   hslToHex(h, s * 0.4,  l * 0.09),
    };
  }

  function deriveFromPrimary(hex) {
    const [h, s, l] = hexToHsl(hex);
    return {
      color_primary_dark: hslToHex(h, s,        l * 0.75),
      color_secondary:    hslToHex(h, s * 0.45,  l + (1 - l) * 0.58),
      color_bg:           hslToHex(h, s * 0.12,  l + (1 - l) * 0.93),
      color_bg_alt:       hslToHex(h, s * 0.28,  l + (1 - l) * 0.86),
      color_border:       hslToHex(h, s * 0.42,  l + (1 - l) * 0.72),
      color_footer_top:   hslToHex(h, s,          l * 0.60),
      color_footer_bot:   hslToHex(h, s * 0.7,    l * 0.32),
    };
  }

  // ── Hilfsfunktionen ───────────────────────────────────────────────────────

  function setColor(name, hex) {
    const picker = document.getElementById(name);
    const text   = document.getElementById(name + '_text');
    if (picker) picker.value = hex;
    if (text)   text.value  = hex;
  }

  function updatePreview() {
    const colors = {
      'primary':    document.getElementById('color_primary')?.value,
      'dark':       document.getElementById('color_primary_dark')?.value,
      'secondary':  document.getElementById('color_secondary')?.value,
      'bg':         document.getElementById('color_bg')?.value,
      'bg-alt':     document.getElementById('color_bg_alt')?.value,
      'border':     document.getElementById('color_border')?.value,
      'footer-top': document.getElementById('color_footer_top')?.value,
      'footer-bot': document.getElementById('color_footer_bot')?.value,
    };
    Object.entries(colors).forEach(([key, val]) => {
      const el = document.querySelector(`.theme-preview__section--${key}`);
      if (el && val) el.style.backgroundColor = val;
    });
  }

  function applyDerived(primaryHex) {
    const derived = deriveFromPrimary(primaryHex);
    Object.entries(derived).forEach(([name, hex]) => setColor(name, hex));
    updatePreview();
  }

  function updateDarkPreview() {
    const map = {
      'dp-primary':     'dark_color_primary',
      'dp-primary-dark':'dark_color_primary_dark',
      'dp-secondary':   'dark_color_secondary',
      'dp-bg':          'dark_color_bg',
      'dp-bg-alt':      'dark_color_bg_alt',
      'dp-border':      'dark_color_border',
      'dp-footer-top':  'dark_color_footer_top',
      'dp-footer-bot':  'dark_color_footer_bot',
    };
    Object.entries(map).forEach(([id, field]) => {
      const el  = document.getElementById(id);
      const val = document.getElementById(field)?.value;
      if (el && val) el.style.backgroundColor = val;
    });
  }

  function applyDerivedDark(primaryHex) {
    const derived = deriveFromPrimaryDark(primaryHex);
    Object.entries(derived).forEach(([name, hex]) => setColor(name, hex));
    updateDarkPreview();
  }

  // ── Event Listeners ───────────────────────────────────────────────────────

  const primaryPicker = document.getElementById('color_primary');
  const primaryText   = document.getElementById('color_primary_text');

  // Primärfarbe picker → Text + Ableitung
  primaryPicker?.addEventListener('input', function () {
    if (primaryText) primaryText.value = this.value;
    updatePreview();
  });

  // Primärfarbe Textfeld → Picker + Ableitung
  primaryText?.addEventListener('input', function () {
    if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
      if (primaryPicker) primaryPicker.value = this.value;
      updatePreview();
    }
  });

  // "Palette ableiten"-Button
  document.getElementById('deriveBtn')?.addEventListener('click', function () {
    const hex = primaryPicker?.value;
    if (hex) applyDerived(hex);
  });

  // Abgeleitete Picker → Textfelder + Vorschau
  document.querySelectorAll('[data-derived]').forEach(picker => {
    picker.addEventListener('input', function () {
      const textField = document.getElementById(this.id + '_text');
      if (textField) textField.value = this.value;
      updatePreview();
    });
  });

  // Textfelder für abgeleitete Farben → Picker
  document.querySelectorAll('[data-for]').forEach(input => {
    input.addEventListener('input', function () {
      if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
        const picker = document.getElementById(this.dataset.for);
        if (picker) picker.value = this.value;
        updatePreview();
      }
    });
  });

  // Reset-Buttons für abgeleitete Felder
  document.querySelectorAll('[data-reset]').forEach(btn => {
    btn.addEventListener('click', function () {
      const field   = this.dataset.reset;
      const primary = primaryPicker?.value;
      if (!primary) return;
      const derived = deriveFromPrimary(primary);
      if (derived[field]) setColor(field, derived[field]);
      updatePreview();
    });
  });

  // "Dark-Palette ableiten"-Button
  document.getElementById('deriveDarkBtn')?.addEventListener('click', function () {
    const hex = primaryPicker?.value;
    if (hex) applyDerivedDark(hex);
  });

  // Dark-Picker → Textfelder + Vorschau
  document.querySelectorAll('[data-derived-dark]').forEach(picker => {
    picker.addEventListener('input', function () {
      const textField = document.getElementById(this.id + '_text');
      if (textField) textField.value = this.value;
      updateDarkPreview();
    });
  });

  // Dark-Textfelder → Picker
  document.querySelectorAll('[data-for-dark]').forEach(input => {
    input.addEventListener('input', function () {
      if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
        const picker = document.getElementById(this.dataset.forDark);
        if (picker) picker.value = this.value;
        updateDarkPreview();
      }
    });
  });

  // Dark Reset-Buttons
  document.querySelectorAll('[data-reset-dark]').forEach(btn => {
    btn.addEventListener('click', function () {
      const field   = this.dataset.resetDark;
      const primary = primaryPicker?.value;
      if (!primary) return;
      const derived = deriveFromPrimaryDark(primary);
      if (derived[field]) setColor(field, derived[field]);
      updateDarkPreview();
    });
  });

  // Initiale Vorschau
  updatePreview();
  updateDarkPreview();
})();
</script>
@endpush
