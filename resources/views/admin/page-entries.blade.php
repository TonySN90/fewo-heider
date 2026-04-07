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

  @php
    $layoutLabels = [
      'cards'        => ['label' => 'Karten-Grid', 'icon' => 'grid_view',    'color' => '#3d7a6e'],
      'place-list'   => ['label' => 'Orte / alternierend', 'icon' => 'location_on', 'color' => '#5a6e9a'],
      'feature'      => ['label' => 'Feature-Blöcke', 'icon' => 'view_agenda', 'color' => '#7a5e18'],
      'route'        => ['label' => 'Routen-Liste', 'icon' => 'directions_bike', 'color' => '#2e6644'],
      'hero-feature' => ['label' => 'Hero + Karten-Grid', 'icon' => 'star', 'color' => '#8b3a3a'],
    ];
    $lInfo = $layoutLabels[$page->layout] ?? $layoutLabels['cards'];
  @endphp
  <div class="alert" style="border-left:4px solid {{ $lInfo['color'] }};margin-bottom:1.5rem">
    <span class="material-symbols-rounded" style="color:{{ $lInfo['color'] }}">{{ $lInfo['icon'] }}</span>
    <div>
      <strong>Layout: {{ $lInfo['label'] }}</strong>
      <p style="margin-top:.25rem;font-size:.875rem;color:#666">
        @switch($page->layout)
          @case('cards') Jeder Eintrag wird als Karte gerendert. Blöcke: <b>1. Text</b> = Beschreibung · <b>Letzter Text</b> = Metazeile mit Schwierigkeit &amp; km (Trenner: ·) · <b>Zweiter Text</b> = Highlights (Aufzählung mit •) @break
          @case('place-list') Jeder Eintrag = Ort (Bild abwechselnd links/rechts). Blöcke: <b>1. Text</b> = Beschreibung · <b>Letzter Text</b> sollte „Entfernung: ca. X km" enthalten @break
          @case('feature') Jeder Eintrag = Feature-Block (abwechselnd gespiegelt). Blöcke: <b>Heading</b> = Kategorie-Label · <b>1. Text</b> = Haupttext · <b>Letzter Text</b> = Info-Zeilen (Trenner: ·) @break
          @case('route') Jeder Eintrag = Route. Blöcke: <b>Heading</b> = Routen-Label (z.B. „Mehrtages-Tour") · <b>1. Text</b> = Beschreibung · <b>Letzter Text</b> = Stats (z.B. „Länge: 275 km · Etappen: 5 · Schwierigkeit: Leicht") @break
          @case('hero-feature') Erster Eintrag = großer Hero-Block. Blöcke: <b>1. Text</b> = Haupttext · <b>Letzter Text</b> = Fakten (z.B. „Entfernung: 10 km · Höhe: 38 m"). Weitere Einträge = 3-spaltiges Karten-Grid @break
          @default Jeder Eintrag wird als Karte gerendert. @break
        @endswitch
      </p>
    </div>
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
