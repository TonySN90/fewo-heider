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
              value="{{ $section->field('title', 'Musterferienwohnung') }}" maxlength="150" />
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

      {{-- ===== HEADER ===== --}}
      @if ($section->section_key === 'header')
        <div class="section-edit-form">

          <div class="form-field">
            <label for="header_brand_type">Darstellung</label>
            <select id="header_brand_type" name="fields[brand_type]">
              <option value="text" {{ $section->field('brand_type', 'text') !== 'logo' ? 'selected' : '' }}>Name (Text)</option>
              <option value="logo" {{ $section->field('brand_type') === 'logo' ? 'selected' : '' }}>Logo (Bild)</option>
            </select>
          </div>

          <div class="form-field">
            <label for="header_brand_name">Name <span class="form-field__hint">(wird angezeigt wenn „Name (Text)" gewählt)</span></label>
            <input type="text" id="header_brand_name" name="fields[brand_name]"
              value="{{ $section->field('brand_name', 'Musterferienwohnung') }}" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="header_brand_sub">Unterzeile <span class="form-field__hint">(z.B. „Ort · Region")</span></label>
            <input type="text" id="header_brand_sub" name="fields[brand_sub]"
              value="{{ $section->field('brand_sub') }}" maxlength="100" />
          </div>

        </div>
      @endif

      {{-- ===== ANREISE ===== --}}
      @if ($section->section_key === 'arrival')
        <div class="section-edit-form">

          <h2 class="section-edit-form__heading">Sektionskopf</h2>
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text <span class="form-field__hint">(kleiner Text über der Überschrift)</span></label>
            <input type="text" id="eyebrow" name="fields[eyebrow]"
              value="{{ $section->field('eyebrow') }}" placeholder="So finden Sie uns" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="title">Überschrift</label>
            <input type="text" id="title" name="fields[title]"
              value="{{ $section->field('title') }}" placeholder="Anreise" maxlength="150" />
          </div>

          <h2 class="section-edit-form__heading">Adresse</h2>
          <div class="form-field">
            <label for="address_name">Name</label>
            <input type="text" id="address_name" name="fields[address_name]"
              value="{{ $section->field('address_name') }}" placeholder="Max Mustermann" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="address_street">Straße &amp; Hausnummer</label>
            <input type="text" id="address_street" name="fields[address_street]"
              value="{{ $section->field('address_street') }}" placeholder="Musterstraße 1" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="address_city">PLZ &amp; Ort</label>
            <input type="text" id="address_city" name="fields[address_city]"
              value="{{ $section->field('address_city') }}" placeholder="12345 Musterstadt" maxlength="100" />
          </div>

          <h2 class="section-edit-form__heading">Kontakt</h2>
          <div class="form-field">
            <label for="phone">Telefon <span class="form-field__hint">(z.B. 01234 56789)</span></label>
            <input type="text" id="phone" name="fields[phone]"
              value="{{ $section->field('phone') }}" placeholder="01234 56789" maxlength="50" />
          </div>
          <div class="form-field">
            <label for="phone_href">Telefon (tel:-Link) <span class="form-field__hint">(z.B. +4912345678)</span></label>
            <input type="text" id="phone_href" name="fields[phone_href]"
              value="{{ $section->field('phone_href') }}" placeholder="+4912345678" maxlength="50" />
          </div>
          <div class="form-field">
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="fields[email]"
              value="{{ $section->field('email') }}" placeholder="info@mustermann-fewo.de" maxlength="150" />
          </div>

          <h2 class="section-edit-form__heading">Kartenposition</h2>
          <div class="form-field">
            <label for="map_lat">Breitengrad (Lat)</label>
            <input type="text" id="map_lat" name="fields[map_lat]"
              value="{{ $section->field('map_lat') }}" placeholder="54.3835" maxlength="20" />
          </div>
          <div class="form-field">
            <label for="map_lng">Längengrad (Lng)</label>
            <input type="text" id="map_lng" name="fields[map_lng]"
              value="{{ $section->field('map_lng') }}" placeholder="13.5632" maxlength="20" />
          </div>

        </div>
      @endif

      {{-- ===== KONTAKT ===== --}}
      @if ($section->section_key === 'contact')
        <div class="section-edit-form">

          <h2 class="section-edit-form__heading">Sektionskopf</h2>
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text <span class="form-field__hint">(kleiner Text über der Überschrift)</span></label>
            <input type="text" id="eyebrow" name="fields[eyebrow]"
              value="{{ $section->field('eyebrow') }}" placeholder="Wir freuen uns auf Sie" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="title">Überschrift</label>
            <input type="text" id="title" name="fields[title]"
              value="{{ $section->field('title') }}" placeholder="Kontakt & Anfrage" maxlength="150" />
          </div>

          <h2 class="section-edit-form__heading">Texte</h2>
          <div class="form-field">
            <label for="text_1">Absatz 1</label>
            <textarea id="text_1" name="fields[text_1]" rows="3" maxlength="600">{{ $section->field('text_1') }}</textarea>
          </div>
          <div class="form-field">
            <label for="text_2">Absatz 2 <span class="form-field__hint">(optional)</span></label>
            <textarea id="text_2" name="fields[text_2]" rows="3" maxlength="600">{{ $section->field('text_2') }}</textarea>
          </div>
          <div class="form-field">
            <label for="btn_label">Button-Beschriftung</label>
            <input type="text" id="btn_label" name="fields[btn_label]"
              value="{{ $section->field('btn_label') }}" placeholder="E-Mail schreiben" maxlength="80" />
          </div>

          <h2 class="section-edit-form__heading">Kontaktkarte</h2>
          <div class="form-field">
            <label for="card_name">Name</label>
            <input type="text" id="card_name" name="fields[card_name]"
              value="{{ $section->field('card_name') }}" placeholder="Max Mustermann" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="card_address">Adresse <span class="form-field__hint">(einzeilig)</span></label>
            <input type="text" id="card_address" name="fields[card_address]"
              value="{{ $section->field('card_address') }}" placeholder="Musterstraße 1, 12345 Musterstadt" maxlength="200" />
          </div>
          <div class="form-field">
            <label for="phone">Telefon <span class="form-field__hint">(Anzeige, z.B. 01234 56789)</span></label>
            <input type="text" id="phone" name="fields[phone]"
              value="{{ $section->field('phone') }}" placeholder="01234 56789" maxlength="50" />
          </div>
          <div class="form-field">
            <label for="phone_href">Telefon (tel:-Link) <span class="form-field__hint">(z.B. +4912345678)</span></label>
            <input type="text" id="phone_href" name="fields[phone_href]"
              value="{{ $section->field('phone_href') }}" placeholder="+4912345678" maxlength="50" />
          </div>
          <div class="form-field">
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="fields[email]"
              value="{{ $section->field('email') }}" placeholder="info@mustermann-fewo.de" maxlength="150" />
          </div>

        </div>
      @endif

      {{-- ===== FOOTER ===== --}}
      @if ($section->section_key === 'footer')
        <div class="section-edit-form">

          <h2 class="section-edit-form__heading">Brand</h2>
          <div class="form-field">
            <label for="brand_name">Name <span class="form-field__hint">(Text im Footer)</span></label>
            <input type="text" id="brand_name" name="fields[brand_name]"
              value="{{ $section->field('brand_name', 'Musterferienwohnung') }}" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="brand_sub">Subzeile <span class="form-field__hint">(z.B. „Ort · Region")</span></label>
            <input type="text" id="brand_sub" name="fields[brand_sub]"
              value="{{ $section->field('brand_sub') }}" maxlength="150" />
          </div>

          <h2 class="section-edit-form__heading">Kontaktdaten</h2>
          <div class="form-field">
            <label for="contact_name">Name</label>
            <input type="text" id="contact_name" name="fields[contact_name]"
              value="{{ $section->field('contact_name') }}" placeholder="Max Mustermann" maxlength="100" />
          </div>
          <div class="form-field">
            <label for="contact_street">Adresse</label>
            <input type="text" id="contact_street" name="fields[contact_street]"
              value="{{ $section->field('contact_street') }}" placeholder="Musterstraße 1, 12345 Musterstadt" maxlength="200" />
          </div>
          <div class="form-field">
            <label for="contact_phone">Telefon <span class="form-field__hint">(Anzeige)</span></label>
            <input type="text" id="contact_phone" name="fields[contact_phone]"
              value="{{ $section->field('contact_phone') }}" placeholder="01234 56789" maxlength="50" />
          </div>
          <div class="form-field">
            <label for="contact_phone_href">Telefon (tel:-Link) <span class="form-field__hint">(z.B. +4912345678)</span></label>
            <input type="text" id="contact_phone_href" name="fields[contact_phone_href]"
              value="{{ $section->field('contact_phone_href') }}" placeholder="+4912345678" maxlength="50" />
          </div>
          <div class="form-field">
            <label for="contact_email">E-Mail</label>
            <input type="email" id="contact_email" name="fields[contact_email]"
              value="{{ $section->field('contact_email') }}" placeholder="info@mustermann-fewo.de" maxlength="150" />
          </div>

          <h2 class="section-edit-form__heading">Footer unten</h2>
          <div class="form-field">
            <label for="copyright">Copyright-Text</label>
            <input type="text" id="copyright" name="fields[copyright]"
              value="{{ $section->field('copyright', '© ' . date('Y') . ' – Alle Rechte vorbehalten') }}" maxlength="200" />
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
