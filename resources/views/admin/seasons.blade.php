@extends('layouts.admin')

@section('title', 'Preise')

@section('content')
  <h1>Preise verwalten</h1>

  <!-- Neue Saison -->
  <div class="card">
    <h2>Neue Saison anlegen</h2>
    <form method="POST" action="{{ route('admin.seasons.store') }}">
      @csrf
      <div class="form-grid">
        <div>
          <label for="name">Saisonname</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="z.B. Hauptsaison" maxlength="100" required />
          @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="from">Von</label>
          <input type="date" id="from" name="from" value="{{ old('from') }}" required />
          @error('from') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="to">Bis</label>
          <input type="date" id="to" name="to" value="{{ old('to') }}" required />
          @error('to') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="price_per_night">€ / Nacht</label>
          <input type="number" id="price_per_night" name="price_per_night" value="{{ old('price_per_night') }}" min="1" required />
          @error('price_per_night') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="min_nights">Mindestaufenthalt</label>
          <input type="number" id="min_nights" name="min_nights" value="{{ old('min_nights', 3) }}" min="1" required />
          @error('min_nights') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="sort_order">Reihenfolge</label>
          <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required />
        </div>
        <div>
          <label for="badge_color">Badge-Farbe</label>
          <select id="badge_color" name="badge_color">
            <option value="">– keine –</option>
            <option value="blue"   {{ old('badge_color') === 'blue'   ? 'selected' : '' }}>blue (blaugrau)</option>
            <option value="green"  {{ old('badge_color') === 'green'  ? 'selected' : '' }}>green (grün)</option>
            <option value="orange" {{ old('badge_color') === 'orange' ? 'selected' : '' }}>orange</option>
            <option value="gold"   {{ old('badge_color') === 'gold'   ? 'selected' : '' }}>gold</option>
          </select>
        </div>
        <div>
          <button type="submit" class="btn btn-add">Speichern</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Saisonliste -->
  <div class="table-card">
    <h2>Alle Saisonen ({{ $seasons->count() }})</h2>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Saison</th>
          <th>Von</th>
          <th>Bis</th>
          <th>€ / Nacht</th>
          <th>Mindestaufenthalt</th>
          <th>Badge</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($seasons as $season)
          <tr class="row-display">
            <td>{{ $season->sort_order }}</td>
            <td>{{ $season->name }}</td>
            <td>{{ $season->from->format('d.m.Y') }}</td>
            <td>{{ $season->to->format('d.m.Y') }}</td>
            <td>{{ $season->price_per_night }} €</td>
            <td>{{ $season->min_nights }} {{ $season->min_nights === 1 ? 'Nacht' : 'Nächte' }}</td>
            <td>
              @if ($season->badge_color)
                <span class="season-badge season-badge--{{ $season->badge_color }}">{{ $season->badge_color }}</span>
              @else
                <span style="color:#aaa">–</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <button class="btn btn-edit" onclick="openModal('Saison bearbeiten', 'edit-tpl-{{ $season->id }}')">Bearbeiten</button>
                <form method="POST" action="{{ route('admin.seasons.destroy', $season) }}" onsubmit="return confirm('Saison wirklich löschen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete">Löschen</button>
                </form>
              </div>
            </td>
          </tr>

          {{-- Edit-Template (versteckt, wird ins Modal kopiert) --}}
          <template id="edit-tpl-{{ $season->id }}">
            <form method="POST" action="{{ route('admin.seasons.update', $season) }}">
              @csrf
              @method('PUT')
              <div class="modal-form-grid">
                <div>
                  <label>Saisonname</label>
                  <input type="text" name="name" value="{{ $season->name }}" maxlength="100" required />
                </div>
                <div>
                  <label>Von</label>
                  <input type="date" name="from" value="{{ $season->from->format('Y-m-d') }}" required />
                </div>
                <div>
                  <label>Bis</label>
                  <input type="date" name="to" value="{{ $season->to->format('Y-m-d') }}" required />
                </div>
                <div>
                  <label>€ / Nacht</label>
                  <input type="number" name="price_per_night" value="{{ $season->price_per_night }}" min="1" required />
                </div>
                <div>
                  <label>Mindestaufenthalt</label>
                  <input type="number" name="min_nights" value="{{ $season->min_nights }}" min="1" required />
                </div>
                <div>
                  <label>Reihenfolge</label>
                  <input type="number" name="sort_order" value="{{ $season->sort_order }}" min="0" required />
                </div>
                <div class="modal-form-grid__full">
                  <label>Badge-Farbe</label>
                  <select name="badge_color">
                    <option value="">– keine –</option>
                    <option value="blue"   {{ $season->badge_color === 'blue'   ? 'selected' : '' }}>blue (blaugrau)</option>
                    <option value="green"  {{ $season->badge_color === 'green'  ? 'selected' : '' }}>green (grün)</option>
                    <option value="orange" {{ $season->badge_color === 'orange' ? 'selected' : '' }}>orange</option>
                    <option value="gold"   {{ $season->badge_color === 'gold'   ? 'selected' : '' }}>gold</option>
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
            <td colspan="8">Noch keine Saisonen vorhanden.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection