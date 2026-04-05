@extends('layouts.admin')

@section('title', 'Preise')

@php
use App\Models\Icon;
$badgeColors = [
  ''       => '– keine –',
  'blue'   => 'blue (blaugrau)',
  'green'  => 'green (grün)',
  'orange' => 'orange',
  'gold'   => 'gold',
];
$icons = array_merge(['' => '– kein Icon –'], Icon::forSelect());
@endphp

@section('content')
  <div class="page-header">
    <h1>Saisons und Preise</h1>
    <button class="btn btn-add" onclick="openModal('Neue Saison', 'add-season-tpl')">Neue Saison</button>
  </div>

  {{-- Template: Neue Saison (Modal) --}}
  <template id="add-season-tpl">
    <form method="POST" action="{{ route('admin.seasons.store') }}">
      @csrf
      <div class="modal-form-grid">
        <div>
          <label>Jahr</label>
          <input type="number" name="year" value="{{ old('year', now()->year + 1) }}" min="2020" max="2099" required />
        </div>
        <div>
          <label>Bezeichnung</label>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="z.B. Saison 2027" maxlength="100" required />
        </div>
        <div>
          <label>Reihenfolge</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required />
        </div>
      </div>
      <div class="modal__actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
        <button type="submit" class="btn btn-save">Anlegen</button>
      </div>
    </form>
  </template>

  @if ($seasons->isEmpty())
    <div class="card">
      <p style="color:#aaa;text-align:center">Noch keine Saisonen vorhanden. Lege die erste Saison an.</p>
    </div>
  @else
    {{-- ── Tab-Navigation ── --}}
    <div class="seasons-tabs">
      <div class="seasons-tabs__bar" role="tablist">
        @foreach ($seasons as $season)
          <button
            class="seasons-tabs__tab {{ $loop->first ? 'is-active' : '' }}"
            role="tab"
            data-tab="season-{{ $season->id }}"
            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
          >
            {{ $season->year }}
            @if ($season->is_active)
              <span class="seasons-tabs__dot" title="Aktive Saison"></span>
            @endif
          </button>
        @endforeach
      </div>

      {{-- ── Panels ── --}}
      @foreach ($seasons as $season)
        <div class="seasons-tabs__panel {{ $loop->first ? 'is-active' : '' }}" id="season-{{ $season->id }}" role="tabpanel">

          {{-- Panel-Header --}}
          <div class="season-panel__header">
            <div class="season-panel__meta">
              <span class="season-panel__name">{{ $season->name }}</span>
              @if ($season->is_active)
                <span class="season-badge season-badge--green">aktiv</span>
              @endif
            </div>
            <div class="actions">
              @unless ($season->is_active)
                <form method="POST" action="{{ route('admin.seasons.activate', $season) }}">
                  @csrf
                  <button type="submit" class="btn btn-activate">Aktiv setzen</button>
                </form>
              @endunless
              <button class="btn btn-edit" onclick="openModal('Saison bearbeiten', 'edit-season-tpl-{{ $season->id }}')"><span class="material-symbols-rounded">edit</span></button>
              <form method="POST" action="{{ route('admin.seasons.destroy', $season) }}" onsubmit="return confirm('Saison und alle zugehörigen Preise wirklich löschen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete"><span class="material-symbols-rounded">delete</span></button>
              </form>
            </div>
          </div>

          {{-- Preistabelle --}}
          <div class="table-card" style="box-shadow:none;margin-bottom:0;border-radius:0">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Preisperiode</th>
                  <th>Von</th>
                  <th>Bis</th>
                  <th>€ / Nacht</th>
                  <th>Mindestaufenthalt</th>
                  <th>Badge</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @forelse ($season->prices as $price)
                  <tr>
                    <td>{{ $price->sort_order }}</td>
                    <td>{{ $price->name }}</td>
                    <td>{{ $price->from->format('d.m.Y') }}</td>
                    <td>{{ $price->to->format('d.m.Y') }}</td>
                    <td>{{ $price->price_per_night }} €</td>
                    <td>{{ $price->min_nights }} {{ $price->min_nights === 1 ? 'Nacht' : 'Nächte' }}</td>
                    <td>
                      @if ($price->badge_color)
                        <span class="season-badge season-badge--{{ $price->badge_color }}">{{ $price->badge_color }}</span>
                      @else
                        <span style="color:#aaa">–</span>
                      @endif
                    </td>
                    <td>
                      <div class="actions">
                        <button class="btn btn-edit" onclick="openModal('Preis bearbeiten', 'edit-price-tpl-{{ $price->id }}')"><span class="material-symbols-rounded">edit</span></button>
                        <form method="POST" action="{{ route('admin.season-prices.destroy', $price) }}" onsubmit="return confirm('Preis wirklich löschen?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-delete"><span class="material-symbols-rounded">delete</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  <template id="edit-price-tpl-{{ $price->id }}">
                    <form method="POST" action="{{ route('admin.season-prices.update', $price) }}">
                      @csrf
                      @method('PUT')
                      <div class="modal-form-grid">
                        <div>
                          <label>Preisperiode</label>
                          <input type="text" name="name" value="{{ $price->name }}" maxlength="100" required />
                        </div>
                        <div>
                          <label>Von</label>
                          <input type="date" name="from" value="{{ $price->from->format('Y-m-d') }}" required />
                        </div>
                        <div>
                          <label>Bis</label>
                          <input type="date" name="to" value="{{ $price->to->format('Y-m-d') }}" required />
                        </div>
                        <div>
                          <label>€ / Nacht</label>
                          <input type="number" name="price_per_night" value="{{ $price->price_per_night }}" min="1" required />
                        </div>
                        <div>
                          <label>Mindestaufenthalt</label>
                          <input type="number" name="min_nights" value="{{ $price->min_nights }}" min="1" required />
                        </div>
                        <div>
                          <label>Reihenfolge</label>
                          <input type="number" name="sort_order" value="{{ $price->sort_order }}" min="0" required />
                        </div>
                        <div class="modal-form-grid__full">
                          <label>Badge-Farbe</label>
                          <select name="badge_color">
                            @foreach ($badgeColors as $value => $label)
                              <option value="{{ $value }}" {{ $price->badge_color === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="modal__actions">
                        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                        <button type="submit" class="btn btn-save">Speichern</button>
                      </div>
                    </form>
                  </template>
                @empty
                  <tr class="empty-row">
                    <td colspan="8">Noch keine Preise für diese Saison.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Neuen Preis anlegen --}}
          <details class="add-price-details">
            <summary>+ Neuen Preis anlegen</summary>
            <form method="POST" action="{{ route('admin.season-prices.store', $season) }}" class="add-price-form">
              @csrf
              <div class="form-grid">
                <div>
                  <label>Preisperiode</label>
                  <input type="text" name="name" placeholder="z.B. Hauptsaison" maxlength="100" required />
                </div>
                <div>
                  <label>Von</label>
                  <input type="date" name="from" required />
                </div>
                <div>
                  <label>Bis</label>
                  <input type="date" name="to" required />
                </div>
                <div>
                  <label>€ / Nacht</label>
                  <input type="number" name="price_per_night" min="1" required />
                </div>
                <div>
                  <label>Mindestaufenthalt</label>
                  <input type="number" name="min_nights" value="3" min="1" required />
                </div>
                <div>
                  <label>Reihenfolge</label>
                  <input type="number" name="sort_order" value="0" min="0" required />
                </div>
                <div>
                  <label>Badge-Farbe</label>
                  <select name="badge_color">
                    @foreach ($badgeColors as $value => $label)
                      <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <button type="submit" class="btn btn-add">Speichern</button>
                </div>
              </div>
            </form>
          </details>
        </div>

        {{-- Edit-Template Saison --}}
        <template id="edit-season-tpl-{{ $season->id }}">
          <form method="POST" action="{{ route('admin.seasons.update', $season) }}">
            @csrf
            @method('PUT')
            <div class="modal-form-grid">
              <div class="modal-form-grid__full">
                <label>Bezeichnung</label>
                <input type="text" name="name" value="{{ $season->name }}" maxlength="100" required />
              </div>
              <div>
                <label>Reihenfolge</label>
                <input type="number" name="sort_order" value="{{ $season->sort_order }}" min="0" required />
              </div>
            </div>
            <div class="modal__actions">
              <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
              <button type="submit" class="btn btn-save">Speichern</button>
            </div>
          </form>
        </template>
      @endforeach

    </div>
  @endif

  {{-- ── Hinweise ── --}}
  <div class="table-card" style="margin-top:2rem">
    <div class="table-card__header">
      <h2>Alle Hinweise ({{ $notes->count() }})</h2>
      <button class="btn btn-add" onclick="openModal('Neuer Hinweis', 'add-note-tpl')">Neuer Hinweis</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Icon</th>
          <th>Text</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($notes as $note)
          <tr>
            <td>{{ $note->sort_order }}</td>
            <td>
              @if ($note->icon)
                <span class="material-symbols-rounded" style="font-size:1.1rem;vertical-align:middle;color:#555">{{ $note->icon }}</span>
                <span style="color:#aaa;font-size:0.8rem;margin-left:4px">{{ $note->icon }}</span>
              @else
                <span style="color:#aaa">–</span>
              @endif
            </td>
            <td>{{ $note->text }}</td>
            <td>
              <div class="actions">
                <button class="btn btn-edit" onclick="openModal('Hinweis bearbeiten', 'edit-note-tpl-{{ $note->id }}')"><span class="material-symbols-rounded">edit</span></button>
                <form method="POST" action="{{ route('admin.pricing-notes.destroy', $note) }}" onsubmit="return confirm('Hinweis wirklich löschen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete"><span class="material-symbols-rounded">delete</span></button>
                </form>
              </div>
            </td>
          </tr>

          <template id="edit-note-tpl-{{ $note->id }}">
            <form method="POST" action="{{ route('admin.pricing-notes.update', $note) }}">
              @csrf
              @method('PUT')
              <div class="modal-form-grid">
                <div class="modal-form-grid__full">
                  <label>Hinweistext</label>
                  <input type="text" name="text" value="{{ $note->text }}" maxlength="255" required />
                </div>
                <div class="modal-form-grid__full">
                  <label>Icon</label>
                  <div class="icon-select-wrap">
                    <select name="icon" onchange="updateIconPreview(this, 'modal-icon-preview')">
                      @foreach ($icons as $value => $label)
                        <option value="{{ $value }}" {{ $note->icon === $value ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                    </select>
                    <span class="material-symbols-rounded icon-preview" id="modal-icon-preview">{{ $note->icon ?: '' }}</span>
                  </div>
                </div>
                <div>
                  <label>Reihenfolge</label>
                  <input type="number" name="sort_order" value="{{ $note->sort_order }}" min="0" required />
                </div>
              </div>
              <div class="modal__actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                <button type="submit" class="btn btn-save">Speichern</button>
              </div>
            </form>
          </template>
        @empty
          <tr class="empty-row">
            <td colspan="4">Noch keine Hinweise vorhanden.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Template: Neuer Hinweis (Modal) --}}
  <template id="add-note-tpl">
    <form method="POST" action="{{ route('admin.pricing-notes.store') }}">
      @csrf
      <div class="modal-form-grid">
        <div class="modal-form-grid__full">
          <label>Hinweistext</label>
          <input type="text" name="text" placeholder="z.B. Endreinigung: 35 €" maxlength="255" required />
        </div>
        <div class="modal-form-grid__full">
          <label>Icon</label>
          <div class="icon-select-wrap">
            <select name="icon" onchange="updateIconPreview(this, 'modal-add-icon-preview')">
              @foreach ($icons as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
            <span class="material-symbols-rounded icon-preview" id="modal-add-icon-preview"></span>
          </div>
        </div>
        <div>
          <label>Reihenfolge</label>
          <input type="number" name="sort_order" value="0" min="0" required />
        </div>
      </div>
      <div class="modal__actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </template>
@endsection

@push('scripts')
<script>
  // Tab-Switching
  document.querySelectorAll('.seasons-tabs__tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.dataset.tab;

      document.querySelectorAll('.seasons-tabs__tab').forEach(t => {
        t.classList.remove('is-active');
        t.setAttribute('aria-selected', 'false');
      });
      document.querySelectorAll('.seasons-tabs__panel').forEach(p => p.classList.remove('is-active'));

      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');
      document.getElementById(targetId)?.classList.add('is-active');
    });
  });

  function updateIconPreview(select, previewId) {
    const preview = document.getElementById(previewId);
    if (preview) preview.textContent = select.value;
  }
</script>
@endpush