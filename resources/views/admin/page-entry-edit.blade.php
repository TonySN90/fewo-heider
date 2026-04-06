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

  {{-- Titel bearbeiten --}}
  <div class="table-card" style="margin-bottom:1.5rem">
    <div class="table-card__header"><h2>Eintrag</h2></div>
    <form method="POST" action="{{ route('admin.pages.entries.update', [$page, $entry]) }}">
      @csrf
      @method('PUT')
      <div class="section-edit-form">
        <div class="form-field">
          <label>Titel</label>
          <input type="text" name="title" value="{{ $entry->title }}" maxlength="200" required />
        </div>
        <div class="form-field">
          <label>URL</label>
          <input type="text" value="/ruegen/{{ $page->slug }}/{{ $entry->slug }}" disabled style="color:#888" />
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
