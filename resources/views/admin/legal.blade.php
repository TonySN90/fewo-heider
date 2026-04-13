@extends('layouts.admin')

@section('title', 'Datenschutz / Impressum')

@section('content')
<h1>Datenschutz / Impressum</h1>

<div class="legal-tabs">
  <div class="legal-tabs__bar" role="tablist">
    <button
      class="legal-tabs__tab is-active"
      role="tab"
      data-tab="tab-datenschutz"
      aria-selected="true"
    >
      <span class="material-symbols-rounded">security</span>
      Datenschutz
    </button>
    <button
      class="legal-tabs__tab"
      role="tab"
      data-tab="tab-impressum"
      aria-selected="false"
    >
      <span class="material-symbols-rounded">description</span>
      Impressum
    </button>
  </div>

  {{-- Panel: Datenschutz --}}
  <div class="legal-tabs__panel is-active" id="tab-datenschutz" role="tabpanel">
    <div class="legal-tabs__panel-inner">
      <p class="legal-hint">
        Dieser Text wird auf der öffentlichen Seite <code>/datenschutz</code> angezeigt.
        Texte können direkt aus dem Browser oder Word eingefügt werden.
      </p>
      <form method="POST" action="{{ route('admin.legal.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="datenschutz" />
        <div
          id="editor-datenschutz"
          class="legal-editor"
          data-content="{{ old('content', $datenschutz?->content) }}"
        ></div>
        <input type="hidden" id="content-datenschutz" name="content" />
        <div class="form-actions">
          <button type="submit" class="btn btn-save">
            <span class="material-symbols-rounded">save</span>
            Datenschutz speichern
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Panel: Impressum --}}
  <div class="legal-tabs__panel" id="tab-impressum" role="tabpanel">
    <div class="legal-tabs__panel-inner">
      <p class="legal-hint">
        Dieser Text wird auf der öffentlichen Seite <code>/impressum</code> angezeigt.
        Texte können direkt aus dem Browser oder Word eingefügt werden.
      </p>
      <form method="POST" action="{{ route('admin.legal.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="impressum" />
        <div
          id="editor-impressum"
          class="legal-editor"
          data-content="{{ old('content', $impressum?->content) }}"
        ></div>
        <input type="hidden" id="content-impressum" name="content" />
        <div class="form-actions">
          <button type="submit" class="btn btn-save">
            <span class="material-symbols-rounded">save</span>
            Impressum speichern
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('head')
  @vite(['resources/js/admin-legal.ts'])
@endpush
