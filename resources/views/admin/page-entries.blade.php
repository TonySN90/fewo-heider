@extends('layouts.admin')

@section('title', $page->title . ' – Einträge')

@section('content')
  <div class="page-header">
    <div>
      <a href="{{ route('admin.page-structure') }}" class="back-link">
        <span class="material-symbols-rounded">arrow_back</span>
        Zurück zur Seitenstruktur
      </a>
      <h1>{{ $page->title }} <span style="font-weight:400;color:#888">– Einträge</span></h1>
    </div>
    <button class="btn btn-add" onclick="openModal('Neuer Eintrag', 'new-entry-tpl')">
      <span class="material-symbols-rounded">add</span>
      Neuer Eintrag
    </button>
  </div>


  <div class="table-card">
    @if ($page->entries->isEmpty())
      <p style="padding:1.75rem;color:#aaa">Noch keine Einträge vorhanden.</p>
    @else
      <table>
        <thead>
          <tr>
            @if ($page->layout === 'hero-feature') <th></th> @endif
            <th></th>
            <th>#</th>
            <th>Titel</th>
            <th>Slug</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="entries-tbody"
               @if ($page->layout === 'hero-feature')
                 data-reorder-url="{{ route('admin.pages.entries.reorder', $page) }}"
               @endif>
          @foreach ($page->entries as $entry)
            @php $isHero = $page->layout === 'hero-feature' && $loop->first; @endphp
            <tr data-id="{{ $entry->id }}" @if ($isHero) data-fixed="true" @endif>
              @if ($page->layout === 'hero-feature')
                <td style="width:2rem;padding:0 .5rem">
                  @if (!$isHero)
                    <span class="drag-handle" title="Verschieben">
                      <span class="material-symbols-rounded">drag_indicator</span>
                    </span>
                  @else
                    <span style="color:#ccc;font-size:.7rem;line-height:1;display:block;text-align:center" title="Hero – fest">★</span>
                  @endif
                </td>
              @endif
              <td style="width:56px;padding:0.25rem 0.5rem">
                @if ($entry->cover_image)
                  <img src="{{ Storage::url($entry->cover_image) }}" alt=""
                       style="width:48px;height:36px;object-fit:cover;border-radius:4px;display:block" />
                @else
                  <div style="width:48px;height:36px;border-radius:4px;background:#eee;display:flex;align-items:center;justify-content:center">
                    <span class="material-symbols-rounded" style="font-size:1rem;color:#bbb">image</span>
                  </div>
                @endif
              </td>
              <td>{{ $entry->sort_order }}</td>
              <td>
                {{ $entry->title }}
                @if ($isHero) <span style="font-size:.7rem;color:#8b3a3a;font-weight:700;margin-left:.4rem">Hero</span> @endif
              </td>
              <td><code>/ruegen/{{ $page->slug }}/{{ $entry->slug }}</code></td>
              <td class="table__actions">
                <a href="{{ route('admin.pages.entry.edit', [$page, $entry]) }}"
                   class="btn btn-edit" title="Inhalt bearbeiten">
                  <span class="material-symbols-rounded">edit</span>
                </a>
                <button class="btn btn-delete"
                        onclick="openModal('Eintrag löschen', 'delete-entry-tpl-{{ $entry->id }}')"
                        title="Löschen">
                  <span class="material-symbols-rounded">delete</span>
                </button>
              </td>
            </tr>

            <template id="delete-entry-tpl-{{ $entry->id }}">
              <p>Möchtest du den Eintrag <strong>{{ $entry->title }}</strong> wirklich löschen?</p>
              <form method="POST" action="{{ route('admin.pages.entries.destroy', [$page, $entry]) }}">
                @csrf
                @method('DELETE')
                <div class="modal__actions">
                  <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                  <button type="submit" class="btn btn-delete">Löschen</button>
                </div>
              </form>
            </template>
          @endforeach
        </tbody>
      </table>

      @if ($page->layout === 'hero-feature')
      <script>
      (function () {
        const tbody = document.getElementById('entries-tbody');
        const reorderUrl = tbody.dataset.reorderUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        let dragRow = null;

        tbody.querySelectorAll('tr[data-id]').forEach(row => {
          if (row.dataset.fixed) return;
          const handle = row.querySelector('.drag-handle');
          if (!handle) return;

          handle.addEventListener('mousedown', () => row.setAttribute('draggable', 'true'));
          handle.addEventListener('mouseup',   () => row.setAttribute('draggable', 'false'));

          row.addEventListener('dragstart', e => {
            dragRow = row;
            row.classList.add('row--dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', '');
          });

          row.addEventListener('dragend', () => {
            row.setAttribute('draggable', 'false');
            row.classList.remove('row--dragging');
            clearDropIndicators();
            dragRow = null;
            saveOrder();
          });
        });

        function clearDropIndicators() {
          tbody.querySelectorAll('.row--drop-before, .row--drop-after').forEach(r => {
            r.classList.remove('row--drop-before', 'row--drop-after');
          });
        }

        tbody.addEventListener('dragover', e => {
          e.preventDefault();
          const target = e.target.closest('tr[data-id]');
          if (!target || target === dragRow || target.dataset.fixed) return;
          const rect = target.getBoundingClientRect();
          const before = e.clientY < rect.top + rect.height / 2;
          clearDropIndicators();
          if (before) {
            const prev = target.previousElementSibling;
            if (prev && prev !== dragRow && !prev.dataset.fixed) prev.classList.add('row--drop-after');
            else target.classList.add('row--drop-before');
            // Nicht vor den Hero-Eintrag schieben
            const heroRow = tbody.querySelector('tr[data-fixed]');
            if (heroRow && target.previousElementSibling === heroRow) return;
            tbody.insertBefore(dragRow, target);
          } else {
            target.classList.add('row--drop-after');
            target.after(dragRow);
          }
        });

        tbody.addEventListener('dragleave', e => {
          if (!tbody.contains(e.relatedTarget)) clearDropIndicators();
        });

        async function saveOrder() {
          const ids = [...tbody.querySelectorAll('tr[data-id]')].map(r => r.dataset.id);
          const body = new FormData();
          body.append('_token', csrfToken);
          ids.forEach(id => body.append('ids[]', id));
          await fetch(reorderUrl, { method: 'POST', body });
        }
      })();
      </script>
      @endif
    @endif
  </div>

  {{-- Modal: Neuer Eintrag --}}
  <template id="new-entry-tpl">
    <form method="POST" action="{{ route('admin.pages.entries.store', $page) }}">
      @csrf
      <div class="modal-form-grid">
        <div class="modal-form-grid__full">
          <label>Titel</label>
          <input type="text" name="title" maxlength="200" required autofocus />
        </div>
      </div>
      <div class="modal__actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
        <button type="submit" class="btn btn-save">Anlegen & bearbeiten</button>
      </div>
    </form>
  </template>
@endsection
