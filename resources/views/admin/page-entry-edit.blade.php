@extends('layouts.admin')

@section('title', $entry->title . ' bearbeiten')

@section('content')
  <div class="page-header">
    <div>
      <a href="{{ route('admin.pages.entries', $page) }}" class="back-link">
        <span class="material-symbols-rounded">arrow_back</span>
        Zurück zu {{ $page->title }}
      </a>
      <h1>{{ $entry->title }}</h1>
    </div>
  </div>

  {{-- Layout-Hinweis --}}
  @php
    $layoutLabels = [
      'cards'        => ['label' => 'Karten-Grid',       'icon' => 'grid_view',       'color' => '#3d7a6e'],
      'place-list'   => ['label' => 'Orte / alternierend','icon' => 'location_on',     'color' => '#5a6e9a'],
      'feature'      => ['label' => 'Feature-Blöcke',    'icon' => 'view_agenda',     'color' => '#7a5e18'],
      'route'        => ['label' => 'Routen-Liste',       'icon' => 'directions_bike', 'color' => '#2e6644'],
      'hero-feature' => ['label' => 'Hero + Karten-Grid', 'icon' => 'star',            'color' => '#8b3a3a'],
    ];
    $lInfo = $layoutLabels[$page->layout] ?? $layoutLabels['cards'];
  @endphp
  <div class="alert" style="border-left:4px solid {{ $lInfo['color'] }};margin-bottom:1.5rem">
    <span class="material-symbols-rounded" style="color:{{ $lInfo['color'] }}">{{ $lInfo['icon'] }}</span>
    <div>
      <strong>Layout der Kategorie: {{ $lInfo['label'] }}</strong>
      <p style="margin-top:.25rem;font-size:.875rem;color:#666">
        @switch($page->layout)
          @case('cards')
            <b>Heading</b> (optional, nur 1. Eintrag) = Seiten-Intro ·
            <b>1. Text</b> = Beschreibung der Karte ·
            <b>2. Text</b> = Highlight-Liste (Punkte mit •) ·
            <b>Letzter Text</b> = Metazeile mit Schwierigkeit &amp; Distanz, getrennt mit ·
            @break
          @case('place-list')
            <b>1. Text</b> = Beschreibung des Ortes ·
            <b>Letzter Text</b> = „Entfernung: ca. X km" (wird als Distanz-Label angezeigt)
            @break
          @case('feature')
            <b>1. Heading</b> = Kategorie-Label (klein, farbig über dem Titel) ·
            <b>1. Text</b> = Hauptbeschreibung ·
            <b>2. Text</b> = zweiter Absatz ·
            <b>Letzter Text</b> = Info-Zeilen getrennt mit · (z.B. „Öffnungszeiten: tägl. 10–17 Uhr · Eintritt: ab 12 €")
            @break
          @case('route')
            <b>1. Heading</b> = Routen-Label (z.B. „Mehrtages-Tour", „Tagesausflug") ·
            <b>1. Text</b> = Streckenbeschreibung ·
            <b>Letzter Text</b> = Stats getrennt mit · (z.B. „Länge: 275 km · Etappen: 5 · Schwierigkeit: Leicht")
            @break
          @case('hero-feature')
            @if ($entry->sort_order === 1)
              Dieser Eintrag wird als großer <b>Hero-Block</b> dargestellt. ·
              <b>1. Text</b> = Haupttext · <b>2. Text</b> = zweiter Absatz ·
              <b>Letzter Text</b> = Fakten getrennt mit · (z.B. „Entfernung: 10 km · Höhe: 38 m · Stil: Renaissance")
            @else
              Dieser Eintrag erscheint im <b>3-spaltigen Karten-Grid</b> (nach dem Hero). ·
              <b>1. Text</b> = Beschreibung · <b>Letzter Text</b> = Jahreszahl oder kurzer Hinweis
            @endif
            @break
        @endswitch
      </p>
    </div>
  </div>

  {{-- Titel bearbeiten --}}
  <div class="table-card" style="margin-bottom:1.5rem">
    <div class="table-card__header"><h2>Eintrag</h2></div>
    <form method="POST" action="{{ route('admin.pages.entries.update', [$page, $entry]) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="section-edit-form">
        <div class="form-field">
          <label>Titel</label>
          <input type="text" name="title" value="{{ $entry->title }}" maxlength="200" required />
        </div>
        <div class="form-field">
          <label>URL</label>
          @php
            $group = $page->group;
            $urlPath = $group
              ? '/' . $group->slug . '/' . $page->slug . '/' . $entry->slug
              : '/' . $page->slug . '/' . $entry->slug;
          @endphp
          <input type="text" value="{{ $urlPath }}" disabled style="color:#888" />
        </div>
        <div class="form-field">
          <label>Titelbild <span class="form-field__hint">(optional, wird als Hero-Bild gezeigt)</span></label>
          @if ($entry->cover_image)
            <img src="{{ Storage::url($entry->cover_image) }}" alt="" style="max-height:120px;border-radius:6px;margin-bottom:.5rem;display:block" />
          @endif
          <input type="file" name="cover_image" accept="image/*" />
        </div>
      </div>
      <div class="section-edit-form__actions">
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </div>

  {{-- Blöcke --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Inhaltsblöcke</h2>
      <button class="btn btn-add" onclick="openModal('Block hinzufügen', 'new-block-tpl')">
        <span class="material-symbols-rounded">add</span>
        Block hinzufügen
      </button>
    </div>

    @if ($entry->blocks->isEmpty())
      <p style="padding:1.75rem;color:#aaa">Noch keine Blöcke vorhanden. Füge deinen ersten Block hinzu.</p>
    @else
      <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
        @foreach ($entry->blocks as $block)
          <div class="block-editor">
            <div class="block-editor__type">
              <span class="material-symbols-rounded">
                {{ $block->type === 'heading' ? 'title' : ($block->type === 'image' ? 'image' : 'notes') }}
              </span>
              {{ ucfirst($block->type) }}
            </div>

            <form method="POST"
                  action="{{ route('admin.pages.blocks.update', [$page, $entry, $block]) }}"
                  class="block-editor__form"
                  @if($block->type === 'image') enctype="multipart/form-data" @endif>
              @csrf
              @method('PUT')

              @if ($block->type === 'heading')
                <input type="text" name="content" value="{{ $block->content }}"
                       placeholder="Überschrift" class="block-editor__input" />
              @elseif ($block->type === 'image')
                @if ($block->content)
                  <img src="{{ Storage::url($block->content) }}" alt="" style="max-height:120px;border-radius:4px;margin-bottom:.5rem" />
                @endif
                <input type="text" name="content" value="{{ $block->content }}"
                       placeholder="Bildpfad oder URL" class="block-editor__input" />
              @else
                <textarea name="content" rows="4" class="block-editor__textarea"
                          placeholder="Text eingeben...">{{ $block->content }}</textarea>
              @endif

              <div class="block-editor__actions">
                <button type="submit" class="btn btn-save btn-save--sm">
                  <span class="material-symbols-rounded">save</span>
                </button>
                <button type="submit" form="delete-block-{{ $block->id }}"
                        class="btn btn-delete btn-delete--sm">
                  <span class="material-symbols-rounded">delete</span>
                </button>
              </div>
            </form>

            <form id="delete-block-{{ $block->id }}" method="POST"
                  action="{{ route('admin.pages.blocks.destroy', [$page, $entry, $block]) }}"
                  onsubmit="return confirm('Block löschen?')" style="display:none">
              @csrf
              @method('DELETE')
            </form>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Modal: Neuer Block --}}
  <template id="new-block-tpl">
    <form method="POST" action="{{ route('admin.pages.blocks.store', [$page, $entry]) }}">
      @csrf
      <div class="modal-form-grid">
        <div class="modal-form-grid__full">
          <label>Typ</label>
          <select name="type" id="block-type-select" onchange="updateBlockContentField(this.value)">
            <option value="text">Text</option>
            <option value="heading">Überschrift</option>
            <option value="image">Bild (URL/Pfad)</option>
          </select>
        </div>
        <div class="modal-form-grid__full" id="block-content-field">
          <label>Inhalt</label>
          <textarea name="content" rows="4"></textarea>
        </div>
      </div>
      <div class="modal__actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
        <button type="submit" class="btn btn-save">Hinzufügen</button>
      </div>
    </form>
  </template>
@endsection

@push('scripts')
<script>
function updateBlockContentField(type) {
  const wrap = document.getElementById('block-content-field');
  if (type === 'text') {
    wrap.innerHTML = '<label>Inhalt</label><textarea name="content" rows="4"></textarea>';
  } else if (type === 'heading') {
    wrap.innerHTML = '<label>Überschrift</label><input type="text" name="content" maxlength="200" />';
  } else {
    wrap.innerHTML = '<label>Bild-URL oder Pfad</label><input type="text" name="content" />';
  }
}
</script>
@endpush
