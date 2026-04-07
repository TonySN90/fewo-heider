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
            <b>Badge-Blöcke</b> = farbige Labels (Schwierigkeit, Distanz, etc.) — Farbe frei wählbar
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
          <input type="text" name="title" value="{{ $entry->title }}" maxlength="200" required
                 oninput="updatePreviewTitle(this.value)" />
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
          <input type="file" name="cover_image" accept="image/*"
                 onchange="updatePreviewImage(this)" />
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
                @if($block->type === 'heading') title
                @elseif($block->type === 'image') image
                @elseif($block->type === 'badge') sell
                @else notes
                @endif
              </span>
              {{ $block->type === 'badge' ? 'Badge' : ucfirst($block->type) }}
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
              @elseif ($block->type === 'badge')
                <div class="block-editor__badge-preview">
                  <span class="badge badge--{{ $block->color ?? 'gray' }}" id="badge-preview-{{ $block->id }}">{{ $block->content ?: 'Vorschau' }}</span>
                </div>
                <div class="block-editor__badge-controls">
                  <input type="text" name="content" value="{{ $block->content }}"
                         placeholder="Badge-Text" class="block-editor__input"
                         data-block-id="{{ $block->id }}" data-block-type="badge"
                         oninput="document.getElementById('badge-preview-{{ $block->id }}').textContent = this.value || 'Vorschau'; updatePreviewBadge({{ $block->id }}, this.value, null)" />
                  <select name="color" class="block-editor__color-select"
                          data-block-id="{{ $block->id }}" data-block-type="badge-color"
                          onchange="document.getElementById('badge-preview-{{ $block->id }}').className = 'badge badge--' + this.value; updatePreviewBadge({{ $block->id }}, null, this.value)">
                    <option value="green"  @selected(($block->color ?? 'gray') === 'green')>Grün</option>
                    <option value="blue"   @selected(($block->color ?? 'gray') === 'blue')>Blau</option>
                    <option value="orange" @selected(($block->color ?? 'gray') === 'orange')>Orange</option>
                    <option value="gray"   @selected(($block->color ?? 'gray') === 'gray')>Grau</option>
                  </select>
                </div>
              @else
                <textarea name="content" rows="4" class="block-editor__textarea"
                          placeholder="Text eingeben..."
                          data-block-id="{{ $block->id }}" data-block-sort="{{ $block->sort_order }}"
                          oninput="updatePreviewText({{ $block->sort_order }}, this.value)">{{ $block->content }}</textarea>
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

  {{-- Card-Vorschau (nur bei cards-Layout) --}}
  @if ($page->layout === 'cards')
  <div class="table-card" style="margin-top:1.5rem" id="card-preview-wrap">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">Aktualisiert sich live beim Bearbeiten</span>
    </div>
    <div class="entry-preview">
      <div class="card" id="card-preview">
        @if ($entry->cover_image)
          <div class="card__img">
            <img id="preview-img" src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" />
          </div>
        @else
          <div class="card__img" id="preview-img-wrap" style="display:none">
            <img id="preview-img" src="" alt="" />
          </div>
        @endif
        <div class="card__body">
          <div class="card__meta" id="preview-badges">
            @foreach ($entry->blocks->where('type', 'badge') as $b)
              <span class="badge badge--{{ $b->color ?? 'gray' }}" data-block-id="{{ $b->id }}">{{ $b->content }}</span>
            @endforeach
          </div>
          <h3 class="card__title" id="preview-title">{{ $entry->title }}</h3>
          @php
            $previewTextBlocks = $entry->blocks->where('type', 'text')->values();
            $previewDesc       = $previewTextBlocks->first()?->content;
            $previewHighlights = $previewTextBlocks->skip(1)->first()?->content;
          @endphp
          <p class="card__text" id="preview-desc">{{ $previewDesc }}</p>
          <div class="card__highlights" id="preview-highlights"
               style="{{ ($previewHighlights && str_contains($previewHighlights, '•')) ? '' : 'display:none' }}">
            <h4>Highlights</h4>
            <ul id="preview-highlights-list">
              @if ($previewHighlights)
                @foreach (array_filter(array_map('trim', explode('•', $previewHighlights))) as $item)
                  <li>{{ $item }}</li>
                @endforeach
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

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
            <option value="badge">Badge</option>
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
// ── Modal: Block-Typ wechseln ─────────────────────────────────────────────
function updateBlockContentField(type) {
  const wrap = document.getElementById('block-content-field');
  if (type === 'text') {
    wrap.innerHTML = '<label>Inhalt</label><textarea name="content" rows="4"></textarea>';
  } else if (type === 'heading') {
    wrap.innerHTML = '<label>Überschrift</label><input type="text" name="content" maxlength="200" />';
  } else if (type === 'badge') {
    wrap.innerHTML = `
      <div style="margin-bottom:.75rem">
        <label>Badge-Text</label>
        <input type="text" name="content" maxlength="200" placeholder="z.B. Leicht – Moderat"
               oninput="document.getElementById('modal-badge-preview').textContent = this.value || 'Vorschau'" />
      </div>
      <div style="margin-bottom:.75rem">
        <label>Farbe</label>
        <select name="color" onchange="document.getElementById('modal-badge-preview').className = 'badge badge--' + this.value">
          <option value="green">Grün</option>
          <option value="blue">Blau</option>
          <option value="orange">Orange</option>
          <option value="gray">Grau</option>
        </select>
      </div>
      <div>
        <label>Vorschau</label>
        <div style="padding:.5rem 0"><span id="modal-badge-preview" class="badge badge--green">Vorschau</span></div>
      </div>`;
  } else {
    wrap.innerHTML = '<label>Bild-URL oder Pfad</label><input type="text" name="content" />';
  }
}

// ── Card-Vorschau: Live-Updates ───────────────────────────────────────────
function updatePreviewTitle(val) {
  const el = document.getElementById('preview-title');
  if (el) el.textContent = val || '';
}

function updatePreviewImage(input) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('preview-img');
    const wrap = document.getElementById('preview-img-wrap');
    if (img) {
      img.src = e.target.result;
      if (wrap) wrap.style.display = '';
    }
  };
  reader.readAsDataURL(input.files[0]);
}

// Sammelt alle Text-Blöcke nach sort_order und aktualisiert desc + highlights
const _textContents = {};
function updatePreviewText(sortOrder, val) {
  _textContents[sortOrder] = val;
  const sorted = Object.entries(_textContents).sort((a,b) => a[0]-b[0]).map(e => e[1]);
  const desc = sorted[0] || '';
  const highlights = sorted[1] || '';

  const descEl = document.getElementById('preview-desc');
  if (descEl) descEl.textContent = desc;

  const hlWrap = document.getElementById('preview-highlights');
  const hlList = document.getElementById('preview-highlights-list');
  if (hlWrap && hlList) {
    const items = highlights.split('•').map(s => s.trim()).filter(Boolean);
    if (items.length > 0) {
      hlList.innerHTML = items.map(i => `<li>${i}</li>`).join('');
      hlWrap.style.display = '';
    } else {
      hlWrap.style.display = 'none';
    }
  }
}

// Badge in der Vorschau aktualisieren (text oder farbe)
function updatePreviewBadge(blockId, text, color) {
  const el = document.querySelector(`#preview-badges [data-block-id="${blockId}"]`);
  if (!el) return;
  if (text !== null) el.textContent = text;
  if (color !== null) el.className = 'badge badge--' + color;
}

// Beim Laden: Text-Block-Inhalte initialisieren damit updatePreviewText korrekt startet
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('textarea[data-block-sort]').forEach(ta => {
    _textContents[ta.dataset.blockSort] = ta.value;
  });
});
</script>
@endpush
