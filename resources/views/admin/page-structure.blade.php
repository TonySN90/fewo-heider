@extends('layouts.admin')

@section('title', 'Seitenstruktur')

@section('content')
  <div class="page-header">
    <h1>Seitenstruktur</h1>
  </div>

  {{-- ===== STARTSEITE ===== --}}
  <div class="table-card" style="margin-bottom:1.5rem">
    <div class="table-card__header">
      <h2>Startseite</h2>
    </div>
    <form method="POST" action="{{ route('admin.page-structure.sections') }}">
      @csrf
      @method('PUT')
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Sektion</th>
            <th>Sichtbar</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sections as $section)
            <tr>
              <td>{{ $section->sort_order }}</td>
              <td>{{ $sectionLabels[$section->section_key] ?? $section->section_key }}</td>
              <td>
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
              </td>
              <td>
                @if (in_array($section->section_key, $editableSections))
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
  </div>

  {{-- ===== SEITENGRUPPEN ===== --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Unterseiten</h2>
      <button class="btn btn-add" onclick="openModal('Neue Gruppe', 'new-group-tpl')">
        <span class="material-symbols-rounded">add</span>
        Neue Gruppe
      </button>
    </div>

    @if ($groups->isEmpty())
      <p style="padding:1.75rem;color:#aaa">Noch keine Gruppen angelegt.</p>
    @else
      @foreach ($groups as $group)
        <details class="page-group" open>
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
                      onclick="openModal('Gruppe bearbeiten', 'edit-group-tpl-{{ $group->id }}')"
                      title="Bearbeiten">
                <span class="material-symbols-rounded">edit</span>
              </button>
              <button class="btn btn-delete"
                      onclick="openModal('Gruppe löschen', 'delete-group-tpl-{{ $group->id }}')"
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
                      <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-form-grid">
                          <div class="modal-form-grid__full">
                            <label>Titel</label>
                            <input type="text" name="title" value="{{ $page->title }}" maxlength="150" required />
                          </div>
                          <div class="modal-form-grid__full">
                            <label>Beschreibung <span class="form-field__hint">(optional)</span></label>
                            <textarea name="description" rows="3" maxlength="500">{{ $page->description }}</textarea>
                          </div>
                          <div class="modal-form-grid__full">
                            <label>Titelbild <span class="form-field__hint">(optional)</span></label>
                            @if ($page->cover_image)
                              <img src="{{ Storage::url($page->cover_image) }}" alt="" style="width:100%;max-height:120px;object-fit:cover;border-radius:6px;margin-bottom:.5rem" />
                            @endif
                            <input type="file" name="cover_image" accept="image/*" />
                          </div>
                          <div class="modal-form-grid__full">
                            <label class="toggle" style="gap:.75rem">
                              <input type="hidden" name="is_visible" value="0" />
                              <input type="checkbox" name="is_visible" value="1" {{ $page->is_visible ? 'checked' : '' }} />
                              <span class="toggle__slider"></span>
                              Sichtbar
                            </label>
                          </div>
                        </div>
                        <div class="modal__actions">
                          <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                          <button type="submit" class="btn btn-save">Speichern</button>
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
          <form method="POST" action="{{ route('admin.pages.groups.update', $group) }}">
            @csrf
            @method('PUT')
            <div class="modal-form-grid">
              <div class="modal-form-grid__full">
                <label>Interner Name</label>
                <input type="text" name="title" value="{{ $group->title }}" maxlength="150" required />
              </div>
              <div class="modal-form-grid__full">
                <label>Nav-Label <span class="form-field__hint">(Text im Menü)</span></label>
                <input type="text" name="nav_label" value="{{ $group->nav_label }}" maxlength="150" />
              </div>
              <div class="modal-form-grid__full">
                <label>Beschreibung <span class="form-field__hint">(optional)</span></label>
                <textarea name="description" rows="3" maxlength="500">{{ $group->description }}</textarea>
              </div>
              <div class="modal-form-grid__full">
                <label class="toggle" style="gap:.75rem">
                  <input type="hidden" name="is_visible" value="0" />
                  <input type="checkbox" name="is_visible" value="1" {{ $group->is_visible ? 'checked' : '' }} />
                  <span class="toggle__slider"></span>
                  Sichtbar
                </label>
              </div>
            </div>
            <div class="modal__actions">
              <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
              <button type="submit" class="btn btn-save">Speichern</button>
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
