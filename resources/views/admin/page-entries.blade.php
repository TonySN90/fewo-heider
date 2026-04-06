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
            <th>#</th>
            <th>Titel</th>
            <th>Slug</th>
            <th>Blöcke</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($page->entries as $entry)
            <tr>
              <td>{{ $entry->sort_order }}</td>
              <td>{{ $entry->title }}</td>
              <td><code>/ruegen/{{ $page->slug }}/{{ $entry->slug }}</code></td>
              <td>{{ $entry->blocks->count() }}</td>
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
