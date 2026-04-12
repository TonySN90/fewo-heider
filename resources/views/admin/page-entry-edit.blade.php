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

  @if ($page->layout === 'cards')
  <div class="alert alert--cards">
    <span class="material-symbols-rounded alert__icon--cards">tips_and_updates</span>
    <div>
      <strong>Karte bearbeiten</strong>
      <ul class="alert__list">
        <li><b>Bild</b> — Klick auf das Vorschaubild</li>
        <li><b>Badges</b> — Farbe wählbar, verschiebbar, löschbar</li>
        <li><b>Titel &amp; Texte</b> — direkt in der Karte anklicken</li>
        <li><b>Highlights</b> — <code>- Text</code> = Listenpunkt, 1. Zeile = Überschrift</li>
      </ul>
    </div>
  </div>
  @endif

  {{-- URL-Info --}}
  @php
    $group = $page->group;
    $urlPath = $group
      ? '/' . $group->slug . '/' . $page->slug . '/' . $entry->slug
      : '/' . $page->slug . '/' . $entry->slug;
  @endphp
  <p style="font-size:.8rem;color:#aaa;margin-bottom:1.5rem">
    <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">link</span>
    {{ $urlPath }}
  </p>

  {{-- Card-Vorschau mit Inline-Editing (nur bei cards-Layout) --}}
  @if ($page->layout === 'cards')
  @php
    $previewTextBlocks  = $entry->blocks->where('type', 'text')->values();
    $previewDesc        = $previewTextBlocks->first()?->content;
    $previewHighlights  = $previewTextBlocks->skip(1)->first()?->content;
    $previewDescBlock   = $previewTextBlocks->first();
    $previewHlBlock     = $previewTextBlocks->skip(1)->first();
  @endphp
  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">
        <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">edit</span>
        Klicke direkt in die Vorschau zum Bearbeiten
      </span>
    </div>
    <div class="entry-preview">
      <div class="card">
        <div class="card__img card__img--clickable" id="preview-img-wrap"
             onclick="document.getElementById('preview-img-upload').click()"
             title="Klicken um Bild zu ändern">
          @if ($entry->cover_image)
            <img id="preview-img" src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" />
          @else
            <div id="preview-img-placeholder" class="card__img-placeholder">
              <span class="material-symbols-rounded">add_photo_alternate</span>
              <span>Bild hochladen</span>
            </div>
          @endif
          <div class="card__img-overlay">
            <span class="material-symbols-rounded">photo_camera</span>
          </div>
        </div>
        <input type="file" id="preview-img-upload"
               accept="image/*" style="display:none"
               data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}" />
        <div class="card__body">

          {{-- Badges: Text + Farbe inline editierbar --}}
          <div class="card__meta" id="preview-badges">
            @foreach ($entry->blocks->where('type', 'badge') as $b)
              <span class="badge-wrap">
                <span class="badge-drag-handle" title="Verschieben">⠿</span>
                <span class="badge badge--{{ $b->color ?? 'gray' }} preview-editable"
                      contenteditable="true"
                      draggable="false"
                      data-type="block"
                      data-block-id="{{ $b->id }}"
                      data-color="{{ $b->color ?? 'gray' }}"
                      data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $b]) }}">{{ $b->content }}</span>
                <button type="button" class="badge-delete-btn"
                        data-delete-url="{{ route('admin.pages.blocks.destroy', [$page, $entry, $b]) }}"
                        title="Badge löschen">×</button>
              </span>
            @endforeach
            <button type="button" id="badge-add-btn" class="badge-add-btn" title="Badge hinzufügen">
              <span class="material-symbols-rounded">add</span>
            </button>
          </div>

          {{-- Farb-Picker + Neu-Badge Popup --}}
          <div id="badge-color-picker" class="badge-color-picker" style="display:none">
            <button type="button" data-color="green"  class="badge badge--green">Grün</button>
            <button type="button" data-color="blue"   class="badge badge--blue">Blau</button>
            <button type="button" data-color="orange" class="badge badge--orange">Orange</button>
            <button type="button" data-color="gray"   class="badge badge--gray">Grau</button>
          </div>

          {{-- Neuer Badge: Inline-Input (erscheint beim Klick auf +) --}}
          <div id="badge-new-form" class="badge-new-form" style="display:none">
            <input type="text" id="badge-new-input" placeholder="Badge-Text" maxlength="60" />
            <div class="badge-new-form__colors">
              <button type="button" data-color="green"  class="badge badge--green">Grün</button>
              <button type="button" data-color="blue"   class="badge badge--blue">Blau</button>
              <button type="button" data-color="orange" class="badge badge--orange">Orange</button>
              <button type="button" data-color="gray"   class="badge badge--gray">Grau</button>
            </div>
            <div class="badge-new-form__actions">
              <button type="button" id="badge-new-save" class="btn btn-save btn-save--sm">Hinzufügen</button>
              <button type="button" id="badge-new-cancel" class="btn btn-cancel">Abbrechen</button>
            </div>
          </div>

          {{-- Titel: inline editierbar --}}
          <h3 class="card__title preview-editable"
              contenteditable="true"
              data-type="entry-title"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title }}</h3>

          {{-- Beschreibung: inline editierbar --}}
          @if ($previewDescBlock)
            <p class="card__text preview-editable preview-editable--multiline"
               contenteditable="true"
               data-type="block"
               data-block-id="{{ $previewDescBlock->id }}"
               data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $previewDescBlock]) }}">{{ $previewDesc }}</p>
          @endif

          {{-- Highlights: Titel + Body jeweils eigenes contenteditable --}}
          @if ($previewHlBlock)
            @php
              $hlLines   = explode("\n", $previewHlBlock->content);
              $firstLine = trim($hlLines[0] ?? '');
              $hlHeading = (!str_starts_with($firstLine, '- ') && $firstLine !== '') ? $firstLine : 'Highlights';
              $hlBody    = implode("\n", array_slice($hlLines, $hlHeading !== 'Highlights' || count($hlLines) > 1 ? 1 : 0));
            @endphp
            <div class="card__highlights">
              <h4 class="preview-editable"
                  contenteditable="true"
                  data-type="hl-title"
                  data-block-id="{{ $previewHlBlock->id }}"
                  data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $previewHlBlock]) }}">{{ $hlHeading }}</h4>
              <pre class="preview-editable preview-editable--multiline"
                   contenteditable="true"
                   data-type="hl-body"
                   data-block-id="{{ $previewHlBlock->id }}"
                   data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $previewHlBlock]) }}">{{ $hlBody }}</pre>
            </div>
          @endif

        </div>
      </div>
    </div>

  </div>
  @endif

  {{-- SEO --}}
  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header"><h2>SEO</h2></div>
    <form method="POST"
          action="{{ route('admin.pages.entries.seo.update', [$page, $entry]) }}"
          style="padding:1.5rem">
      @csrf @method('PUT')
      @if (session('success') && str_contains(session('success'), 'SEO'))
        <div class="alert alert--success" style="margin-bottom:1rem">{{ session('success') }}</div>
      @endif
      <div class="modal-form-grid">
        <div class="modal-form-grid__full">
          <label>SEO-Titel <span class="form-field__hint" style="font-size:.75rem;color:#aaa">(leer = Eintragstitel, max. 70 Zeichen)</span></label>
          <input type="text" name="seo_title"
                 value="{{ old('seo_title', $entry->seo?->title ?? '') }}"
                 maxlength="70"
                 placeholder="{{ $entry->title }}" />
        </div>
        <div class="modal-form-grid__full">
          <label>SEO-Beschreibung <span class="form-field__hint" style="font-size:.75rem;color:#aaa">(leer = erster Textblock, max. 160 Zeichen)</span></label>
          <textarea name="seo_description" rows="3" maxlength="160"
                    placeholder="Automatisch aus erstem Textblock">{{ old('seo_description', $entry->seo?->description ?? '') }}</textarea>
        </div>
      </div>
      <div style="margin-top:1rem">
        <button type="submit" class="btn btn-save">SEO speichern</button>
      </div>
    </form>
  </div>

@endsection

@push('scripts')
<script>

// ── Inline-Editing: AJAX blur-save ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  // Bild-Upload via Klick auf Vorschau
  const imgUpload = document.getElementById('preview-img-upload');
  if (imgUpload) {
    imgUpload.addEventListener('change', async () => {
      const file = imgUpload.files[0];
      if (!file) return;
      const wrap = document.getElementById('preview-img-wrap');
      wrap?.classList.add('card__img--uploading');

      // Sofort lokal vorschauen
      const reader = new FileReader();
      reader.onload = e => {
        let img = document.getElementById('preview-img');
        const placeholder = document.getElementById('preview-img-placeholder');
        if (placeholder) {
          placeholder.outerHTML = `<img id="preview-img" src="${e.target.result}" alt="" />`;
        } else if (img) {
          img.src = e.target.result;
        }
      };
      reader.readAsDataURL(file);

      // AJAX-Upload
      const formData = new FormData();
      formData.append('_method', 'PUT');
      formData.append('_token', csrfToken);
      formData.append('title', document.querySelector('[data-type="entry-title"]')?.innerText.trim() ?? '{{ $entry->title }}');
      formData.append('cover_image', file);

      try {
        const res = await fetch(imgUpload.dataset.url, { method: 'POST', body: formData });
        wrap?.classList.remove('card__img--uploading');
        if (res.ok) {
          wrap?.classList.add('card__img--saved');
          setTimeout(() => wrap?.classList.remove('card__img--saved'), 1200);
          // Bild-Vorschau im Formular oben auch aktualisieren
          const formImg = document.querySelector('.section-edit-form img');
          if (formImg) { const r2 = new FileReader(); r2.onload = e => formImg.src = e.target.result; r2.readAsDataURL(file); }
        } else {
          wrap?.classList.add('card__img--error');
          setTimeout(() => wrap?.classList.remove('card__img--error'), 2000);
        }
      } catch {
        wrap?.classList.remove('card__img--uploading');
        wrap?.classList.add('card__img--error');
        setTimeout(() => wrap?.classList.remove('card__img--error'), 2000);
      }
    });
  }

  // ── Badge Farb-Picker ─────────────────────────────────────────────────────
  const picker = document.getElementById('badge-color-picker');
  let activeBadge = null;

  function showPicker(badge) {
    if (!picker) return;
    activeBadge = badge;
    const rect = badge.getBoundingClientRect();
    const wrapRect = badge.closest('.entry-preview').getBoundingClientRect();
    picker.style.top  = (rect.bottom - wrapRect.top + 6) + 'px';
    picker.style.left = (rect.left - wrapRect.left) + 'px';
    picker.style.display = 'flex';
    // Aktive Farbe markieren
    picker.querySelectorAll('button').forEach(btn => {
      btn.classList.toggle('badge-color-picker__active', btn.dataset.color === badge.dataset.color);
    });
  }

  function hidePicker() {
    if (picker) picker.style.display = 'none';
    activeBadge = null;
  }

  picker?.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('mousedown', async e => {
      e.preventDefault(); // blur des Badge verhindern
      const color = btn.dataset.color;
      if (!activeBadge) return;

      const url = activeBadge.dataset.url;
      const body = new FormData();
      body.append('_method', 'PUT');
      body.append('_token', csrfToken);
      body.append('content', activeBadge.innerText.trim());
      body.append('color', color);

      // Sofort visuell aktualisieren
      activeBadge.className = `badge badge--${color} preview-editable`;
      activeBadge.dataset.color = color;
      // Block-Editor-Select synchronisieren
      const sel = document.querySelector(`select[data-block-id="${activeBadge.dataset.blockId}"]`);
      if (sel) sel.value = color;

      await fetch(url, { method: 'POST', body });
      hidePicker();
    });
  });

  // Picker schließen bei Klick außerhalb
  document.addEventListener('mousedown', e => {
    if (picker && !picker.contains(e.target) && e.target !== activeBadge) {
      hidePicker();
    }
  });

  // ── Badge löschen ────────────────────────────────────────────────────────
  async function deleteBadge(btn) {
    const url = btn.dataset.deleteUrl;
    if (!url) return;
    const wrap = btn.closest('.badge-wrap');
    wrap.style.opacity = '.4';
    const body = new FormData();
    body.append('_method', 'DELETE');
    body.append('_token', csrfToken);
    try {
      const res = await fetch(url, { method: 'POST', body });
      if (res.ok) {
        wrap.remove();
      } else {
        wrap.style.opacity = '';
      }
    } catch {
      wrap.style.opacity = '';
    }
  }

  document.querySelectorAll('.badge-delete-btn').forEach(btn => {
    btn.addEventListener('click', () => deleteBadge(btn));
  });

  // ── Badge Drag & Drop ────────────────────────────────────────────────────
  const reorderUrl = '{{ route('admin.pages.blocks.reorder', [$page, $entry]) }}';

  function initDraggable(wrap) {
    wrap.setAttribute('draggable', 'false'); // default off
    const handle = wrap.querySelector('.badge-drag-handle');

    // Drag nur erlauben wenn Handle gedrückt wird
    handle?.addEventListener('mousedown', () => wrap.setAttribute('draggable', 'true'));
    handle?.addEventListener('mouseup',   () => wrap.setAttribute('draggable', 'false'));
    wrap.addEventListener('dragend',      () => wrap.setAttribute('draggable', 'false'));

    wrap.addEventListener('dragstart', e => {
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/plain', ''); // Firefox requires this
      wrap.classList.add('badge-wrap--dragging');
      window._dragBadge = wrap;
    });

    wrap.addEventListener('dragend', () => {
      wrap.classList.remove('badge-wrap--dragging');
      document.querySelectorAll('.badge-wrap--over').forEach(el => el.classList.remove('badge-wrap--over'));
      window._dragBadge = null;
      saveBadgeOrder();
    });

    wrap.addEventListener('dragover', e => {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
      const dragging = window._dragBadge;
      if (!dragging || dragging === wrap) return;
      // Einfügeposition bestimmen: vor oder nach dem Ziel
      const rect   = wrap.getBoundingClientRect();
      const midX   = rect.left + rect.width / 2;
      const before = e.clientX < midX;
      wrap.classList.add('badge-wrap--over');
      if (before) {
        badgesWrap.insertBefore(dragging, wrap);
      } else {
        wrap.after(dragging);
      }
    });

    wrap.addEventListener('dragleave', () => {
      wrap.classList.remove('badge-wrap--over');
    });
  }

  async function saveBadgeOrder() {
    const ids = [...badgesWrap.querySelectorAll('.badge-wrap')]
      .map(w => w.querySelector('[data-block-id]')?.dataset.blockId)
      .filter(Boolean)
      .map(Number);
    if (!ids.length) return;
    const body = new FormData();
    body.append('_token', csrfToken);
    ids.forEach(id => body.append('ids[]', id));
    await fetch(reorderUrl, { method: 'POST', body });
  }

  // Bestehende Wrappers initialisieren
  document.querySelectorAll('#preview-badges .badge-wrap').forEach(initDraggable);

  // ── Badge hinzufügen (+) ──────────────────────────────────────────────────
  const addBtn      = document.getElementById('badge-add-btn');
  const newForm     = document.getElementById('badge-new-form');
  const newInput    = document.getElementById('badge-new-input');
  const newSave     = document.getElementById('badge-new-save');
  const newCancel   = document.getElementById('badge-new-cancel');
  const badgesWrap  = document.getElementById('preview-badges');
  let   newColor    = 'green';

  // Farb-Buttons im Neu-Formular
  newForm?.querySelectorAll('[data-color]').forEach(btn => {
    if (btn.dataset.color === newColor) btn.classList.add('is-active');
    btn.addEventListener('click', () => {
      newColor = btn.dataset.color;
      newForm.querySelectorAll('[data-color]').forEach(b => b.classList.toggle('is-active', b === btn));
    });
  });

  addBtn?.addEventListener('click', () => {
    newForm.style.display = 'flex';
    newInput.value = '';
    newInput.focus();
    addBtn.style.display = 'none';
  });

  function hideNewForm() {
    newForm.style.display = 'none';
    addBtn.style.display  = '';
  }

  newCancel?.addEventListener('click', hideNewForm);

  newSave?.addEventListener('click', async () => {
    const text = newInput.value.trim();
    if (!text) { newInput.focus(); return; }

    const storeUrl = '{{ route('admin.pages.blocks.store', [$page, $entry]) }}';
    const body = new FormData();
    body.append('_token', csrfToken);
    body.append('type', 'badge');
    body.append('content', text);
    body.append('color', newColor);

    try {
      const res  = await fetch(storeUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      if (res.ok && data.id) {
        // Badge-Span erstellen
        const span = document.createElement('span');
        span.className        = `badge badge--${newColor} preview-editable`;
        span.contentEditable  = 'true';
        span.draggable        = false;
        span.dataset.type     = 'block';
        span.dataset.blockId  = data.id;
        span.dataset.color    = newColor;
        span.dataset.url      = data.url;
        span.textContent      = text;

        span.addEventListener('focus', () => showPicker(span));
        span.addEventListener('blur',  () => setTimeout(hidePicker, 150));
        span.addEventListener('keydown', e => {
          if (e.key === 'Enter') { e.preventDefault(); span.blur(); }
        });
        span.addEventListener('blur', async () => {
          const body2 = new FormData();
          body2.append('_method', 'PUT');
          body2.append('_token', csrfToken);
          body2.append('content', span.innerText.trim());
          body2.append('color', span.dataset.color);
          const r = await fetch(span.dataset.url, { method: 'POST', body: body2 });
          if (r.ok) {
            span.classList.add('preview-editable--saved');
            setTimeout(() => span.classList.remove('preview-editable--saved'), 1200);
          }
        });

        // Delete-Button erstellen
        const delBtn = document.createElement('button');
        delBtn.type      = 'button';
        delBtn.className = 'badge-delete-btn';
        delBtn.title     = 'Badge löschen';
        delBtn.textContent = '×';
        delBtn.dataset.deleteUrl = data.deleteUrl ?? '';
        delBtn.addEventListener('click', () => deleteBadge(delBtn));

        // Drag-Handle
        const handle = document.createElement('span');
        handle.className = 'badge-drag-handle';
        handle.title     = 'Verschieben';
        handle.textContent = '⠿';

        // Wrapper
        const wrap = document.createElement('span');
        wrap.className = 'badge-wrap';
        wrap.appendChild(handle);
        wrap.appendChild(span);
        wrap.appendChild(delBtn);

        initDraggable(wrap);
        badgesWrap.insertBefore(wrap, addBtn);
        hideNewForm();
      }
    } catch (err) {
      console.error('Badge speichern fehlgeschlagen', err);
    }
  });

  // Enter im Input = Speichern
  newInput?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); newSave.click(); }
    if (e.key === 'Escape') hideNewForm();
  });

  document.querySelectorAll('.preview-editable').forEach(el => {
    // Badge: Picker bei Focus zeigen
    if (el.dataset.type === 'block' && el.classList.contains('badge')) {
      el.addEventListener('focus', () => showPicker(el));
      el.addEventListener('blur',  () => setTimeout(hidePicker, 150));
    }

    // Enter bei einzeiligen Elementen → blur statt Zeilenumbruch
    el.addEventListener('keydown', e => {
      if (e.key === 'Enter' && !el.classList.contains('preview-editable--multiline')) {
        e.preventDefault();
        el.blur();
      }
    });

    el.addEventListener('blur', async () => {
      const text = el.innerText.replace(/\n{3,}/g, '\n\n').trim();
      const url  = el.dataset.url;
      const type = el.dataset.type;

      const body = new FormData();
      body.append('_method', 'PUT');
      body.append('_token', csrfToken);

      if (type === 'entry-title') {
        body.append('title', text);
      } else if (type === 'hl-title' || type === 'hl-body') {
        // Titel + Body zusammensetzen
        const titleEl = document.querySelector('[data-type="hl-title"]');
        const bodyEl  = document.querySelector('[data-type="hl-body"]');
        const title   = titleEl?.innerText.trim() ?? '';
        const hlBody  = bodyEl?.innerText.trim() ?? '';
        body.append('content', title ? title + '\n' + hlBody : hlBody);
      } else {
        body.append('content', text);
        if (el.dataset.color) body.append('color', el.dataset.color);
      }

      try {
        const res = await fetch(url, { method: 'POST', body });
        if (res.ok) {
          // Grüner Flash
          el.classList.add('preview-editable--saved');
          setTimeout(() => el.classList.remove('preview-editable--saved'), 1200);
          // Titel-Input synchronisieren
          if (type === 'entry-title') {
            const titleInput = document.querySelector('input[name="title"]');
            if (titleInput) titleInput.value = text;
          }
        } else {
          el.classList.add('preview-editable--error');
          setTimeout(() => el.classList.remove('preview-editable--error'), 2000);
        }
      } catch {
        el.classList.add('preview-editable--error');
        setTimeout(() => el.classList.remove('preview-editable--error'), 2000);
      }
    });
  });

});
</script>
@endpush
