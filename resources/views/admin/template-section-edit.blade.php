@extends('layouts.admin')

@section('title', 'Sektion bearbeiten')

@php
use App\Models\Icon;
$highlightIcons = array_merge(['' => '– kein Icon –'], Icon::forSelect());
@endphp

@section('content')
  <div class="page-header">
    <div>
      <a href="{{ route('admin.templates') }}" class="back-link">
        <span class="material-symbols-rounded">arrow_back</span>
        Zurück zu Templates
      </a>
      <h1>Sektion bearbeiten: <em>{{ $section->section_key }}</em></h1>
    </div>
  </div>

  <div class="table-card">
    <form method="POST" action="{{ route('admin.templates.sections.update', [$template, $section->section_key]) }}">
      @csrf
      @method('PUT')

      {{-- ===== HERO ===== --}}
      @if ($section->section_key === 'hero')
        <div class="section-edit-form">
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text <span class="form-field__hint">(kleiner Text über der Überschrift)</span></label>
            <input type="text" id="eyebrow" name="fields[eyebrow]"
              value="{{ $section->field('eyebrow', 'Willkommen') }}" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="title">Hauptüberschrift</label>
            <input type="text" id="title" name="fields[title]"
              value="{{ $section->field('title', 'Ihr Urlaub auf Rügen') }}" maxlength="150" />
          </div>
          <div class="form-field">
            <label for="subtitle">Untertitel</label>
            <textarea id="subtitle" name="fields[subtitle]" rows="3" maxlength="300">{{ $section->field('subtitle') }}</textarea>
          </div>
        </div>
      @endif

      {{-- ===== DIE WOHNUNG ===== --}}
      @if ($section->section_key === 'about')
        <div class="section-edit-form">

          {{-- Abschnitt: Kopf --}}
          <h2 class="section-edit-form__heading">Sektionskopf</h2>
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text <span class="form-field__hint">(kleiner Text über der Überschrift)</span></label>
            <input type="text" id="eyebrow" name="fields[eyebrow]"
              value="{{ $section->field('eyebrow', 'Willkommen') }}" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="title">Überschrift</label>
            <input type="text" id="title" name="fields[title]"
              value="{{ $section->field('title', 'Ferienwohnung Heider') }}" maxlength="150" />
          </div>

          {{-- Abschnitt: Texte --}}
          <h2 class="section-edit-form__heading">Beschreibungstexte</h2>
          <div class="form-field">
            <label for="text_1">Absatz 1 <span class="form-field__hint">(Intro-Text)</span></label>
            <textarea id="text_1" name="fields[text_1]" rows="3" maxlength="600">{{ $section->field('text_1') }}</textarea>
          </div>
          <div class="form-field">
            <label for="text_2">Absatz 2</label>
            <textarea id="text_2" name="fields[text_2]" rows="3" maxlength="600">{{ $section->field('text_2') }}</textarea>
          </div>
          <div class="form-field">
            <label for="text_3">Absatz 3</label>
            <textarea id="text_3" name="fields[text_3]" rows="3" maxlength="600">{{ $section->field('text_3') }}</textarea>
          </div>

          {{-- Abschnitt: Highlight-Cards --}}
          <h2 class="section-edit-form__heading">Highlight-Cards <span class="form-field__hint">(leer lassen = nicht anzeigen)</span></h2>
          @for ($i = 1; $i <= 6; $i++)
            <div class="highlight-card-editor">
              <div class="highlight-card-editor__label">Card {{ $i }}</div>
              <div class="highlight-card-editor__fields">
                <div class="form-field">
                  <label>Icon</label>
                  <div class="icon-select-wrap">
                    <select name="fields[card_{{ $i }}_icon]" onchange="updateIconPreview(this, 'card-icon-{{ $i }}')">
                      @foreach ($highlightIcons as $value => $label)
                        <option value="{{ $value }}" {{ $section->field("card_{$i}_icon") === $value ? 'selected' : '' }}>
                          {{ $label }}
                        </option>
                      @endforeach
                    </select>
                    <span class="material-symbols-rounded icon-preview" id="card-icon-{{ $i }}">
                      {{ $section->field("card_{$i}_icon") }}
                    </span>
                  </div>
                </div>
                <div class="form-field">
                  <label>Überschrift</label>
                  <input type="text" name="fields[card_{{ $i }}_heading]"
                    value="{{ $section->field("card_{$i}_heading") }}" maxlength="80" />
                </div>
                <div class="form-field">
                  <label>Text</label>
                  <input type="text" name="fields[card_{{ $i }}_text]"
                    value="{{ $section->field("card_{$i}_text") }}" maxlength="120" />
                </div>
              </div>
            </div>
          @endfor

        </div>
      @endif

      {{-- ===== AUSSTATTUNG ===== --}}
      @if ($section->section_key === 'amenities')
        <div class="section-edit-form">

          <h2 class="section-edit-form__heading">Sektionskopf</h2>
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text</label>
            <input type="text" id="eyebrow" name="fields[eyebrow]"
              value="{{ $section->field('eyebrow', 'Was wir bieten') }}" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="title">Überschrift</label>
            <input type="text" id="title" name="fields[title]"
              value="{{ $section->field('title', 'Ausstattung') }}" maxlength="150" />
          </div>

          <h2 class="section-edit-form__heading">
            Ausstattungs-Items
            <span class="form-field__hint">(leer lassen = nicht anzeigen)</span>
          </h2>

          <div id="amenity-list">
            @php $existingItems = []; @endphp
            @for ($i = 1; $i <= 50; $i++)
              @php
                $icon  = $section->field("amenity_{$i}_icon");
                $label = $section->field("amenity_{$i}_label");
              @endphp
              @if ($icon || $label)
                @php $existingItems[] = ['icon' => $icon, 'label' => $label, 'index' => $i]; @endphp
              @else
                @break
              @endif
            @endfor

            @foreach ($existingItems as $item)
              <div class="amenity-editor" data-index="{{ $item['index'] }}">
                <div class="amenity-editor__fields">
                  <div class="form-field">
                    <label>Icon</label>
                    <div class="icon-select-wrap">
                      <select name="fields[amenity_{{ $item['index'] }}_icon]"
                              onchange="updateIconPreview(this, 'amenity-icon-{{ $item['index'] }}')">
                        @foreach ($highlightIcons as $value => $label)
                          <option value="{{ $value }}" {{ $item['icon'] === $value ? 'selected' : '' }}>
                            {{ $label }}
                          </option>
                        @endforeach
                      </select>
                      <span class="material-symbols-rounded icon-preview" id="amenity-icon-{{ $item['index'] }}">
                        {{ $item['icon'] }}
                      </span>
                    </div>
                  </div>
                  <div class="form-field">
                    <label>Bezeichnung</label>
                    <input type="text" name="fields[amenity_{{ $item['index'] }}_label]"
                      value="{{ $item['label'] }}" maxlength="100" />
                  </div>
                </div>
                <button type="button" class="btn btn-delete amenity-editor__remove" onclick="removeAmenity(this)">
                  <span class="material-symbols-rounded">delete</span>
                </button>
              </div>
            @endforeach
          </div>

          <div>
            <button type="button" class="btn btn-add" onclick="addAmenity()">
              <span class="material-symbols-rounded">add</span> Item hinzufügen
            </button>
          </div>

        </div>
      @endif

      {{-- ===== GALERIE (Sektionskopf im PUT-Formular) ===== --}}
      @if ($section->section_key === 'gallery')
        <div class="section-edit-form">
          <h2 class="section-edit-form__heading">Sektionskopf</h2>
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text</label>
            <input type="text" id="eyebrow" name="fields[eyebrow]"
              value="{{ $section->field('eyebrow', 'Eindrücke') }}" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="title">Überschrift</label>
            <input type="text" id="title" name="fields[title]"
              value="{{ $section->field('title', 'Galerie') }}" maxlength="150" />
          </div>
        </div>
      @endif

      <div class="section-edit-form__actions">
        <a href="{{ route('admin.templates') }}" class="btn btn-cancel">Abbrechen</a>
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </div>

  {{-- ===== GALERIE: Upload + Bild-Tabelle (außerhalb des PUT-Formulars) ===== --}}
  @if ($section->section_key === 'gallery')
    <div class="table-card" style="margin-top:1.5rem">
      <div class="table-card__header">
        <h2>Bilder hochladen</h2>
      </div>
      <div style="padding:1.75rem">
        <form method="POST"
              action="{{ route('admin.gallery.store', [$template, $section->section_key]) }}"
              enctype="multipart/form-data">
          @csrf
          <div class="form-field">
            <label for="images">
              Bilder auswählen
              <span class="form-field__hint">(JPG, PNG, WebP – max. 5 MB pro Bild, mehrere gleichzeitig möglich)</span>
            </label>
            <input type="file" id="images" name="images[]"
                   multiple accept="image/jpeg,image/png,image/webp" />
            @error('images.*')
              <span style="color:red;font-size:0.85rem">{{ $message }}</span>
            @enderror
          </div>
          <div style="margin-top:1rem">
            <button type="submit" class="btn btn-save">
              <span class="material-symbols-rounded">upload</span>
              Hochladen
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="table-card" style="margin-top:1.5rem">
      <div class="table-card__header">
        <h2>Vorhandene Bilder ({{ $section->galleryImages->count() }})</h2>
      </div>
      @if ($section->galleryImages->isEmpty())
        <p style="padding:1.75rem;color:#aaa">Noch keine Bilder hochgeladen.</p>
      @else
        <div class="gallery-admin-grid">
          @foreach ($section->galleryImages as $img)
            <div class="gallery-admin-card">
              <img src="{{ $img->url() }}" alt="{{ $img->caption }}" class="gallery-admin-card__preview" />

              <div class="gallery-admin-card__body">
                <form method="POST"
                      action="{{ route('admin.gallery.update', [$template, $section->section_key, $img]) }}">
                  @csrf @method('PUT')

                  {{-- Zeile 1: Caption --}}
                  <input type="text" name="caption" value="{{ $img->caption }}"
                         maxlength="200" placeholder="Bildbeschriftung" class="gallery-admin-card__caption" />

                  {{-- Zeile 2: Sort + Speichern --}}
                  <div class="gallery-admin-card__row gallery-admin-card__row--actions">
                    <span class="material-symbols-rounded gallery-admin-card__sort-icon" title="Reihenfolge">swap_vert</span>
                    <input type="number" name="sort_order" value="{{ $img->sort_order }}"
                           min="0" class="gallery-admin-card__sort" />
                    <button type="submit" class="btn btn-save btn-save--sm" title="Speichern">
                      <span class="material-symbols-rounded">save</span>
                    </button>

                    {{-- Löschen (eigenes Form, aber in derselben Zeile) --}}
                    <button type="submit" form="delete-{{ $img->id }}"
                            class="btn btn-delete btn-delete--sm" title="Löschen">
                      <span class="material-symbols-rounded">delete</span>
                    </button>
                  </div>
                </form>

                <form id="delete-{{ $img->id }}" method="POST"
                      action="{{ route('admin.gallery.destroy', [$template, $section->section_key, $img]) }}"
                      onsubmit="return confirm('Bild wirklich löschen?')" style="display:none">
                  @csrf @method('DELETE')
                </form>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  @endif

  {{-- Icon-Optionen als JSON für JS --}}
  @if ($section->section_key === 'amenities')
    <script id="icon-options-data" type="application/json">
      @json(array_keys($highlightIcons))
    </script>
    <script id="icon-labels-data" type="application/json">
      @json($highlightIcons)
    </script>
  @endif
@endsection

@push('scripts')
<script>
  function updateIconPreview(select, previewId) {
    const preview = document.getElementById(previewId);
    if (preview) preview.textContent = select.value;
  }

  // Ausstattungs-Items dynamisch hinzufügen/entfernen
  function getNextAmenityIndex() {
    const items = document.querySelectorAll('.amenity-editor');
    let max = 0;
    items.forEach(el => {
      const idx = parseInt(el.dataset.index, 10);
      if (idx > max) max = idx;
    });
    return max + 1;
  }

  function buildIconSelect(name, previewId) {
    const labels = JSON.parse(document.getElementById('icon-labels-data')?.textContent || '{}');
    let select = `<select name="${name}" onchange="updateIconPreview(this, '${previewId}')">`;
    for (const [value, label] of Object.entries(labels)) {
      select += `<option value="${value}">${label}</option>`;
    }
    select += '</select>';
    return select;
  }

  function addAmenity() {
    const idx   = getNextAmenityIndex();
    const list  = document.getElementById('amenity-list');
    const div   = document.createElement('div');
    div.className   = 'amenity-editor';
    div.dataset.index = idx;
    div.innerHTML = `
      <div class="amenity-editor__fields">
        <div class="form-field">
          <label>Icon</label>
          <div class="icon-select-wrap">
            ${buildIconSelect('fields[amenity_' + idx + '_icon]', 'amenity-icon-' + idx)}
            <span class="material-symbols-rounded icon-preview" id="amenity-icon-${idx}"></span>
          </div>
        </div>
        <div class="form-field">
          <label>Bezeichnung</label>
          <input type="text" name="fields[amenity_${idx}_label]" maxlength="100" />
        </div>
      </div>
      <button type="button" class="btn btn-delete amenity-editor__remove" onclick="removeAmenity(this)">
        <span class="material-symbols-rounded">delete</span>
      </button>
    `;
    list.appendChild(div);
  }

  function removeAmenity(btn) {
    const editor = btn.closest('.amenity-editor');
    const idx    = editor.dataset.index;
    // Leere Werte senden damit der Controller die Felder auf '' setzt
    const iconInput  = document.createElement('input');
    iconInput.type   = 'hidden';
    iconInput.name   = `fields[amenity_${idx}_icon]`;
    iconInput.value  = '';
    const labelInput  = document.createElement('input');
    labelInput.type   = 'hidden';
    labelInput.name   = `fields[amenity_${idx}_label]`;
    labelInput.value  = '';
    editor.replaceWith(iconInput, labelInput);
  }
</script>
@endpush
