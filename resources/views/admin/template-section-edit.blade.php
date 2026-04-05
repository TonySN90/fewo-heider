@extends('layouts.admin')

@section('title', 'Sektion bearbeiten')

@php
$highlightIcons = [
  ''                => '– kein Icon –',
  'beach_access'    => 'beach_access – Strand',
  'park'            => 'park – Natur',
  'directions_bike' => 'directions_bike – Fahrrad',
  'theater_comedy'  => 'theater_comedy – Kultur',
  'restaurant'      => 'restaurant – Restaurant',
  'local_cafe'      => 'local_cafe – Café',
  'waves'           => 'waves – Wellen',
  'hiking'          => 'hiking – Wandern',
  'sailing'         => 'sailing – Segeln',
  'kayaking'        => 'kayaking – Kajak',
  'anchor'          => 'anchor – Anker',
  'forest'          => 'forest – Wald',
  'sunny'           => 'sunny – Sonne',
  'star'            => 'star – Stern',
  'favorite'        => 'favorite – Herz',
  'home'            => 'home – Haus',
  'bed'             => 'bed – Bett',
  'wifi'            => 'wifi – WLAN',
  'local_parking'   => 'local_parking – Parken',
  'pets'            => 'pets – Haustiere',
  'family_restroom' => 'family_restroom – Familie',
  'child_care'      => 'child_care – Kinder',
  'cake'            => 'cake – Feier',
  'spa'             => 'spa – Wellness',
  'fitness_center'  => 'fitness_center – Fitness',
  'sports_tennis'   => 'sports_tennis – Sport',
];
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
      @if ($section->section_key === 'ueber-uns')
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

      <div class="section-edit-form__actions">
        <a href="{{ route('admin.templates') }}" class="btn btn-cancel">Abbrechen</a>
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script>
  function updateIconPreview(select, previewId) {
    const preview = document.getElementById(previewId);
    if (preview) preview.textContent = select.value;
  }
</script>
@endpush
