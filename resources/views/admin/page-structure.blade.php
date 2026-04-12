@extends('layouts.admin')

@section('title', 'Seitenstruktur')

@section('content')
  <div class="page-header">
    <h1>Seitenstruktur</h1>
  </div>

  {{-- ===== STARTSEITE ===== --}}
  <details class="table-card table-card--collapsible" style="margin-bottom:1.5rem">
    <summary class="table-card__header table-card__header--summary">
      <span class="material-symbols-rounded table-card__chevron">expand_more</span>
      <h2>Hauptseite</h2>
    </summary>
    <form method="POST" action="{{ route('admin.page-structure.sections') }}">
      @csrf
      @method('PUT')
      <table>
        <thead>
          <tr>
            <th style="width:2.5rem"></th>
            <th>Sektion</th>
            <th>Sichtbar</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="sections-tbody">
          @foreach ($sections as $section)
            @php
              $isFixed  = in_array($section->section_key, ['header', 'hero', 'footer']);
              $isLocked = in_array($section->section_key, $lockedSections);
            @endphp
            <tr data-id="{{ $section->id }}" {{ $isFixed ? 'data-fixed="true"' : '' }} {{ $isLocked ? 'data-fixed="true"' : '' }}>
              <td>
                @if (!$isFixed && !$isLocked)
                  <span class="drag-handle" title="Verschieben">
                    <span class="material-symbols-rounded">drag_indicator</span>
                  </span>
                @endif
              </td>
              <td>
                {{ $sectionLabels[$section->section_key] ?? $section->section_key }}
                @if ($isLocked)
                  <span class="season-badge season-badge--gray" style="margin-left:.5rem" title="Vom Plattform-Administrator deaktiviert">
                    <span class="material-symbols-rounded" style="font-size:1rem;vertical-align:middle">lock</span>
                    gesperrt
                  </span>
                @endif
              </td>
              <td>
                @if ($isLocked)
                  <label class="toggle toggle--disabled" title="Vom Plattform-Administrator deaktiviert">
                    <input type="checkbox" disabled />
                    <span class="toggle__slider"></span>
                  </label>
                @else
                  <input type="hidden" name="sections[{{ $section->section_key }}]" value="0" />
                  <label class="toggle">
                    <input
                      type="checkbox"
                      name="sections[{{ $section->section_key }}]"
                      value="1"
                      {{ $section->is_visible ? 'checked' : '' }}
                    />
                    <span class="toggle__slider"></span>
                  </label>
                @endif
              </td>
              <td>
                @if (!$isLocked && in_array($section->section_key, $editableSections))
                  <a href="{{ route('admin.page-structure.edit', $section->section_key) }}" class="btn btn-edit">
                    <span class="material-symbols-rounded">edit</span>
                  </a>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div style="padding:1rem 1.5rem">
        <button type="submit" class="btn btn-save">Sektionen speichern</button>
      </div>
    </form>

    <script>
    (function () {
      const tbody = document.getElementById('sections-tbody');
      const reorderUrl = '{{ route('admin.page-structure.sections.reorder') }}';
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
      let dragRow = null;

      tbody.querySelectorAll('tr[data-id]').forEach(row => {
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
          saveSectionOrder();
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
          // Linie unterhalb der Zeile VOR dem Ziel anzeigen
          const prev = target.previousElementSibling;
          if (prev && prev !== dragRow) prev.classList.add('row--drop-after');
          else target.classList.add('row--drop-before');
          tbody.insertBefore(dragRow, target);
        } else {
          target.classList.add('row--drop-after');
          target.after(dragRow);
        }
      });

      tbody.addEventListener('dragleave', e => {
        if (!tbody.contains(e.relatedTarget)) clearDropIndicators();
      });

      async function saveSectionOrder() {
        const ids = [...tbody.querySelectorAll('tr[data-id]')].map(r => r.dataset.id);
        const body = new FormData();
        body.append('_token', csrfToken);
        ids.forEach(id => body.append('ids[]', id));
        await fetch(reorderUrl, { method: 'POST', body });
      }
    })();
    </script>
  </details>

  {{-- ===== SEITENGRUPPEN ===== --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Unterseiten</h2>
      <button class="btn btn-add" onclick="openModal('Neue Gruppe', 'new-group-tpl')">
        <span class="material-symbols-rounded">add</span>
        Neue Unterseite
      </button>
    </div>

    @if ($groups->isEmpty())
      <p style="padding:1.75rem;color:#aaa">Noch keine Unterseite angelegt.</p>
    @else
      @foreach ($groups as $group)
        <details class="page-group">
          <summary class="page-group__summary">
            <span class="material-symbols-rounded page-group__chevron">expand_more</span>
            <strong>{{ $group->title }}</strong>
            <span class="page-group__meta">
              Nav: „{{ $group->nav_label }}" &nbsp;·&nbsp; /{{ $group->slug }}
            </span>
            <span class="season-badge season-badge--{{ $group->is_visible ? 'green' : 'gray' }}" style="margin-left:auto">
              {{ $group->is_visible ? 'sichtbar' : 'versteckt' }}
            </span>
            <div class="table__actions" style="margin-left:1rem" onclick="event.preventDefault()">
              <button class="btn btn-edit"
                      onclick="openModal('Unterseite bearbeiten', 'edit-group-tpl-{{ $group->id }}')"
                      title="Bearbeiten">
                <span class="material-symbols-rounded">edit</span>
              </button>
              <button class="btn btn-delete"
                      onclick="openModal('Unterseite löschen', 'delete-group-tpl-{{ $group->id }}')"
                      title="Löschen">
                <span class="material-symbols-rounded">delete</span>
              </button>
            </div>
          </summary>

          <div class="page-group__body">
            <div style="padding:.75rem 1.5rem;display:flex;justify-content:flex-end">
              <button class="btn btn-add btn--sm"
                      onclick="openModal('Neue Kategorie', 'new-page-tpl-{{ $group->id }}')">
                <span class="material-symbols-rounded">add</span>
                Neue Kategorie
              </button>
            </div>

            @if ($group->pages->isEmpty())
              <p style="padding:.5rem 1.5rem 1.5rem;color:#aaa">Noch keine Kategorien vorhanden.</p>
            @else
              <table>
                <thead>
                  <tr>
                    <th>Titel</th>
                    <th>URL</th>
                    <th>Sichtbar</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($group->pages as $page)
                    <tr>
                      <td>{{ $page->title }}</td>
                      <td><code>/{{ $group->slug }}/{{ $page->slug }}</code></td>
                      <td>
                        <span class="season-badge season-badge--{{ $page->is_visible ? 'green' : 'gray' }}">
                          {{ $page->is_visible ? 'sichtbar' : 'versteckt' }}
                        </span>
                      </td>
                      <td class="table__actions">
                        <a href="{{ route('admin.pages.entries', $page) }}" class="btn btn-edit" title="Einträge verwalten">
                          <span class="material-symbols-rounded">article</span>
                        </a>
                        <button class="btn btn-edit"
                                onclick="openModal('Kategorie bearbeiten', 'edit-page-tpl-{{ $page->id }}')"
                                title="Bearbeiten">
                          <span class="material-symbols-rounded">edit</span>
                        </button>
                        <button class="btn btn-delete"
                                onclick="openModal('Kategorie löschen', 'delete-page-tpl-{{ $page->id }}')"
                                title="Löschen">
                          <span class="material-symbols-rounded">delete</span>
                        </button>
                      </td>
                    </tr>

                    {{-- Modal: Kategorie bearbeiten --}}
                    <template id="edit-page-tpl-{{ $page->id }}">
                      @php
                        $introEntry   = $page->entries->first();
                        $introHeading = $introEntry?->blocks->firstWhere('type', 'heading')?->content ?? '';
                        $introText    = $introEntry?->blocks->firstWhere('type', 'text')?->content ?? '';
                        $updateUrl    = route('admin.pages.update', $page);
                      @endphp

                      {{-- ── Hero: direkt editierbar ── --}}
                      <div class="modal-page-hero"
                           @if($page->cover_image) style="background-image:url('{{ Storage::url($page->cover_image) }}')" @endif
                           title="Klicken um Bild zu wechseln">
                        <div class="modal-page-hero__overlay"></div>
                        <span class="modal-page-hero__upload-hint">
                          <span class="material-symbols-rounded">photo_camera</span> Bild wechseln
                        </span>
                        <div class="modal-page-hero__content">
                          <h2 class="modal-page-hero__title"
                              contenteditable="true"
                              data-field="title"
                              data-url="{{ $updateUrl }}">{{ $page->title }}</h2>
                          <p class="modal-page-hero__desc"
                             contenteditable="true"
                             data-field="description"
                             data-url="{{ $updateUrl }}"
                             data-placeholder="Untertitel eingeben …">{{ $page->description }}</p>
                        </div>
                        <input type="file" class="modal-page-hero__upload" accept="image/*"
                               style="display:none" data-url="{{ $updateUrl }}" />
                      </div>

                      {{-- ── Intro: direkt editierbar ── --}}
                      <div class="modal-intro-preview">
                        <p class="modal-intro-preview__label">Intro-Bereich</p>
                        <h3 class="modal-intro-preview__heading"
                            contenteditable="true"
                            data-field="intro_heading"
                            data-url="{{ $updateUrl }}"
                            data-placeholder="Überschrift eingeben …">{{ $introHeading }}</h3>
                        <div class="modal-intro-preview__divider"></div>
                        <p class="modal-intro-preview__text"
                           contenteditable="true"
                           data-field="intro_text"
                           data-multiline="true"
                           data-url="{{ $updateUrl }}"
                           data-placeholder="Einleitungstext eingeben …">{{ $introText }}</p>
                      </div>

                      {{-- ── Einstellungen (AJAX on change) ── --}}
                      <div class="modal-page-settings" data-url="{{ $updateUrl }}">
                        <div class="modal-page-settings__group">
                          <label>Layout</label>
                          <select data-field="layout">
                            <option value="cards"        {{ $page->layout === 'cards'        ? 'selected' : '' }}>Karten-Grid</option>
                            <option value="place-list"   {{ $page->layout === 'place-list'   ? 'selected' : '' }}>Orte / alternierend</option>
                            <option value="feature"      {{ $page->layout === 'feature'      ? 'selected' : '' }}>Feature-Blöcke</option>
                            <option value="route"        {{ $page->layout === 'route'        ? 'selected' : '' }}>Routen-Liste</option>
                            <option value="hero-feature" {{ $page->layout === 'hero-feature' ? 'selected' : '' }}>Hero + Karten-Grid</option>
                          </select>
                        </div>
                        <div class="modal-page-settings__group">
                          <label>&nbsp;</label>
                          <div class="modal-page-settings__toggle">
                            <label class="toggle">
                              <input type="checkbox" data-field="is_visible" {{ $page->is_visible ? 'checked' : '' }} />
                              <span class="toggle__slider"></span>
                            </label>
                            <span>Sichtbar</span>
                          </div>
                        </div>
                      </div>

                      {{-- ── SEO ── --}}
                      <form method="POST"
                            action="{{ route('admin.pages.seo.update', $page) }}"
                            style="padding:0 1.5rem 1.5rem"
                            data-ajax-seo>
                        @csrf @method('PUT')
                        <div class="modal-page-settings__label" style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem">SEO (optional)</div>
                        <div class="modal-form-grid">
                          <div class="modal-form-grid__full">
                            <label>SEO-Titel <span class="form-field__hint" style="font-size:.75rem;color:#aaa">(leer = Seitenname, max. 70 Zeichen)</span></label>
                            <input type="text" name="seo_title"
                                   value="{{ $page->seo?->title ?? '' }}"
                                   maxlength="70"
                                   placeholder="{{ $page->title }}" />
                          </div>
                          <div class="modal-form-grid__full">
                            <label>SEO-Beschreibung <span class="form-field__hint" style="font-size:.75rem;color:#aaa">(max. 160 Zeichen)</span></label>
                            <textarea name="seo_description" rows="2" maxlength="160"
                                      placeholder="{{ $page->description ?? '' }}">{{ $page->seo?->description ?? '' }}</textarea>
                          </div>
                        </div>
                        <div style="margin-top:.75rem">
                          <button type="submit" class="btn btn-save btn--sm">SEO speichern</button>
                        </div>
                      </form>
                    </template>

                    {{-- Modal: Kategorie löschen --}}
                    <template id="delete-page-tpl-{{ $page->id }}">
                      <p>Möchtest du die Kategorie <strong>{{ $page->title }}</strong> wirklich löschen? Alle Einträge werden ebenfalls gelöscht.</p>
                      <form method="POST" action="{{ route('admin.pages.destroy', $page) }}">
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
            @endif
          </div>
        </details>

        {{-- Modal: Gruppe bearbeiten --}}
        <template id="edit-group-tpl-{{ $group->id }}">
          @php $groupUpdateUrl = route('admin.pages.groups.update', $group); @endphp

          {{-- ── Hero: nav_label + description direkt editierbar ── --}}
          <div class="modal-page-hero"
               data-url="{{ $groupUpdateUrl }}">
            <div class="modal-page-hero__overlay"></div>
            <div class="modal-page-hero__content">
              <h2 class="modal-page-hero__title"
                  contenteditable="true"
                  data-field="title"
                  data-url="{{ $groupUpdateUrl }}">{{ $group->title }}</h2>
              <p class="modal-page-hero__desc"
                 contenteditable="true"
                 data-field="description"
                 data-url="{{ $groupUpdateUrl }}"
                 data-placeholder="Untertitel eingeben …">{{ $group->description }}</p>
            </div>
          </div>

          {{-- ── Einstellungen ── --}}
          <div class="modal-page-settings" data-url="{{ $groupUpdateUrl }}">
            <div class="modal-page-settings__group">
              <label>Navigation</label>
              <input type="text"
                     data-field="nav_label"
                     data-url="{{ $groupUpdateUrl }}"
                     value="{{ $group->nav_label }}"
                     maxlength="150"
                     placeholder="Navigationsbezeichnung" />
            </div>
            <div class="modal-page-settings__group">
              <label>&nbsp;</label>
              <div class="modal-page-settings__toggle">
                <label class="toggle">
                  <input type="checkbox" data-field="is_visible" {{ $group->is_visible ? 'checked' : '' }} />
                  <span class="toggle__slider"></span>
                </label>
                <span>Sichtbar</span>
              </div>
            </div>
          </div>

          {{-- ── SEO ── --}}
          <form method="POST"
                action="{{ route('admin.pages.groups.seo.update', $group) }}"
                style="padding:0 1.5rem 1.5rem"
                data-ajax-seo>
            @csrf @method('PUT')
            <div class="modal-page-settings__label" style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem">SEO (optional)</div>
            <div class="modal-form-grid">
              <div class="modal-form-grid__full">
                <label>SEO-Titel <span class="form-field__hint" style="font-size:.75rem;color:#aaa">(leer = Seitenname, max. 70 Zeichen)</span></label>
                <input type="text" name="seo_title"
                       value="{{ $group->seo?->title ?? '' }}"
                       maxlength="70"
                       placeholder="{{ $group->title }}" />
              </div>
              <div class="modal-form-grid__full">
                <label>SEO-Beschreibung <span class="form-field__hint" style="font-size:.75rem;color:#aaa">(max. 160 Zeichen)</span></label>
                <textarea name="seo_description" rows="2" maxlength="160"
                          placeholder="{{ $group->description ?? '' }}">{{ $group->seo?->description ?? '' }}</textarea>
              </div>
            </div>
            <div style="margin-top:.75rem">
              <button type="submit" class="btn btn-save btn--sm">SEO speichern</button>
            </div>
          </form>
        </template>

        {{-- Modal: Gruppe löschen --}}
        <template id="delete-group-tpl-{{ $group->id }}">
          <p>Möchtest du die Gruppe <strong>{{ $group->title }}</strong> wirklich löschen? Alle Kategorien und Einträge werden ebenfalls gelöscht.</p>
          <form method="POST" action="{{ route('admin.pages.groups.destroy', $group) }}">
            @csrf
            @method('DELETE')
            <div class="modal__actions">
              <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
              <button type="submit" class="btn btn-delete">Löschen</button>
            </div>
          </form>
        </template>

        {{-- Modal: Neue Kategorie in dieser Gruppe --}}
        <template id="new-page-tpl-{{ $group->id }}">
          <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="page_group_id" value="{{ $group->id }}" />
            <div class="modal-form-grid">
              <div class="modal-form-grid__full">
                <label>Titel</label>
                <input type="text" name="title" maxlength="150" required autofocus />
              </div>
              <div class="modal-form-grid__full">
                <label>Beschreibung <span class="form-field__hint">(optional)</span></label>
                <textarea name="description" rows="3" maxlength="500"></textarea>
              </div>
              <div class="modal-form-grid__full">
                <label>Titelbild <span class="form-field__hint">(optional)</span></label>
                <input type="file" name="cover_image" accept="image/*" />
              </div>
              <div class="modal-form-grid__full">
                <label>Layout</label>
                <select name="layout">
                  <option value="cards">Karten-Grid</option>
                  <option value="place-list">Orte / alternierend</option>
                  <option value="feature">Feature-Blöcke</option>
                  <option value="route">Routen-Liste</option>
                  <option value="hero-feature">Hero + Karten-Grid</option>
                </select>
              </div>
            </div>
            <div class="modal__actions">
              <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
              <button type="submit" class="btn btn-save">Anlegen</button>
            </div>
          </form>
        </template>
      @endforeach
    @endif
  </div>

  {{-- Modal: Neue Gruppe --}}
  <template id="new-group-tpl">
    <form method="POST" action="{{ route('admin.pages.groups.store') }}">
      @csrf
      <div class="modal-form-grid">
        <div class="modal-form-grid__full">
          <label>Interner Name</label>
          <input type="text" name="title" maxlength="150" required autofocus />
        </div>
        <div class="modal-form-grid__full">
          <label>Nav-Label <span class="form-field__hint">(Text im Menü, z.B. „Rügen erleben")</span></label>
          <input type="text" name="nav_label" maxlength="150" />
        </div>
        <div class="modal-form-grid__full">
          <label>Beschreibung <span class="form-field__hint">(optional)</span></label>
          <textarea name="description" rows="3" maxlength="500"></textarea>
        </div>
      </div>
      <div class="modal__actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
        <button type="submit" class="btn btn-save">Anlegen</button>
      </div>
    </form>
  </template>
@endsection
