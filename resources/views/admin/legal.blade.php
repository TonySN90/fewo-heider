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
    <div class="legal-lang-tabs">
      <div class="legal-lang-tabs__bar">
        <button class="legal-lang-tabs__tab is-active" data-lang-tab="datenschutz-de">DE</button>
        <button class="legal-lang-tabs__tab" data-lang-tab="datenschutz-en">EN</button>
      </div>

      <div class="legal-lang-tabs__panel is-active" id="datenschutz-de">
        <div class="legal-tabs__panel-inner">
          <p class="legal-hint">
            Dieser Text wird auf <code>/datenschutz</code> für deutschsprachige Besucher angezeigt.
          </p>
          <form method="POST" action="{{ route('admin.legal.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="datenschutz" />
            <input type="hidden" name="locale" value="de" />
            <div id="editor-datenschutz-de" class="legal-editor" data-content="{{ old('content', $datenschutz_de?->content) }}"></div>
            <input type="hidden" id="content-datenschutz-de" name="content" />
            <div class="form-actions">
              <button type="submit" class="btn btn-save">
                <span class="material-symbols-rounded">save</span>
                Datenschutz (DE) speichern
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="legal-lang-tabs__panel" id="datenschutz-en">
        <div class="legal-tabs__panel-inner">
          <p class="legal-hint">
            This text is shown on <code>/datenschutz</code> for English-speaking visitors.
          </p>
          <form method="POST" action="{{ route('admin.legal.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="datenschutz" />
            <input type="hidden" name="locale" value="en" />
            <div id="editor-datenschutz-en" class="legal-editor" data-content="{{ old('content', $datenschutz_en?->content) }}"></div>
            <input type="hidden" id="content-datenschutz-en" name="content" />
            <div class="form-actions">
              <button type="submit" class="btn btn-save">
                <span class="material-symbols-rounded">save</span>
                Save Privacy Policy (EN)
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Panel: Impressum --}}
  <div class="legal-tabs__panel" id="tab-impressum" role="tabpanel">
    <div class="legal-lang-tabs">
      <div class="legal-lang-tabs__bar">
        <button class="legal-lang-tabs__tab is-active" data-lang-tab="impressum-de">DE</button>
        <button class="legal-lang-tabs__tab" data-lang-tab="impressum-en">EN</button>
      </div>

      <div class="legal-lang-tabs__panel is-active" id="impressum-de">
        <div class="legal-tabs__panel-inner">
          <p class="legal-hint">
            Dieser Text wird auf <code>/impressum</code> für deutschsprachige Besucher angezeigt.
          </p>
          <form method="POST" action="{{ route('admin.legal.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="impressum" />
            <input type="hidden" name="locale" value="de" />
            <div id="editor-impressum-de" class="legal-editor" data-content="{{ old('content', $impressum_de?->content) }}"></div>
            <input type="hidden" id="content-impressum-de" name="content" />
            <div class="form-actions">
              <button type="submit" class="btn btn-save">
                <span class="material-symbols-rounded">save</span>
                Impressum (DE) speichern
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="legal-lang-tabs__panel" id="impressum-en">
        <div class="legal-tabs__panel-inner">
          <p class="legal-hint">
            This text is shown on <code>/impressum</code> for English-speaking visitors.
          </p>
          <form method="POST" action="{{ route('admin.legal.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="impressum" />
            <input type="hidden" name="locale" value="en" />
            <div id="editor-impressum-en" class="legal-editor" data-content="{{ old('content', $impressum_en?->content) }}"></div>
            <input type="hidden" id="content-impressum-en" name="content" />
            <div class="form-actions">
              <button type="submit" class="btn btn-save">
                <span class="material-symbols-rounded">save</span>
                Save Legal Notice (EN)
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('head')
  @vite(['resources/js/admin-legal.ts'])
@endpush