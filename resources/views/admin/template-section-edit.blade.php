@extends('layouts.admin')

@section('title', 'Sektion bearbeiten')

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

      @if ($section->section_key === 'hero')
        <div class="section-edit-form">
          <div class="form-field">
            <label for="eyebrow">Eyebrow-Text <span class="form-field__hint">(kleiner Text über der Überschrift)</span></label>
            <input
              type="text"
              id="eyebrow"
              name="fields[eyebrow]"
              value="{{ $section->field('eyebrow', 'Willkommen') }}"
              maxlength="100"
            />
          </div>

          <div class="form-field">
            <label for="title">Hauptüberschrift</label>
            <input
              type="text"
              id="title"
              name="fields[title]"
              value="{{ $section->field('title', 'Ihr Urlaub auf Rügen') }}"
              maxlength="150"
            />
          </div>

          <div class="form-field">
            <label for="subtitle">Untertitel</label>
            <textarea
              id="subtitle"
              name="fields[subtitle]"
              rows="3"
              maxlength="300"
            >{{ $section->field('subtitle', 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.') }}</textarea>
          </div>
        </div>
      @endif

      <div class="section-edit-form__actions">
        <a href="{{ route('admin.templates') }}" class="btn btn-cancel">Abbrechen</a>
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </div>
@endsection