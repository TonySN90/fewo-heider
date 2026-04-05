<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buchungen verwalten – Ferienwohnung Heider</title>
  @vite(['resources/css/admin.scss'])
</head>
<body>

<div class="topbar">
  <span class="topbar__brand">Ferienwohnung <span>Heider</span> – Admin</span>
  <div class="topbar__actions">
    <a href="{{ url('/') }}" target="_blank">Website ansehen ↗</a>
    <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit">Ausloggen</button>
    </form>
  </div>
</div>

<div class="main">
  <h1>Buchungen verwalten</h1>

  @if (session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  <!-- Neue Buchung -->
  <div class="card">
    <h2>Neue Buchung anlegen</h2>
    <form method="POST" action="{{ route('admin.bookings.store') }}">
      @csrf
      <div class="form-grid">
        <div>
          <label for="from">Anreise</label>
          <input type="date" id="from" name="from" value="{{ old('from') }}" required />
          @error('from') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="to">Abreise</label>
          <input type="date" id="to" name="to" value="{{ old('to') }}" required />
          @error('to') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="guest_name">Gastname (optional)</label>
          <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" placeholder="z.B. Familie Müller" maxlength="100" />
        </div>
        <div>
          <button type="submit" class="btn btn-add">Speichern</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Buchungsliste -->
  <div class="table-card">
    <h2>Alle Buchungen ({{ $bookings->count() }})</h2>
    <table>
      <thead>
        <tr>
          <th>Anreise</th>
          <th>Abreise</th>
          <th>Gastname</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $booking)
          {{-- Anzeigezeile --}}
          <tr class="row-display" id="row-{{ $booking->id }}">
            <td>{{ $booking->from->format('d.m.Y') }}</td>
            <td>{{ $booking->to->format('d.m.Y') }}</td>
            <td class="guest-name">{{ $booking->guest_name ?? '–' }}</td>
            <td>
              <div class="actions">
                <button class="btn btn-edit" onclick="openEdit({{ $booking->id }})">Bearbeiten</button>
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Buchung wirklich löschen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete">Löschen</button>
                </form>
              </div>
            </td>
          </tr>

          {{-- Editierzeile --}}
          <tr class="row-edit" id="edit-{{ $booking->id }}">
            <td colspan="4">
              <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                @csrf
                @method('PUT')
                <div class="edit-grid">
                  <div>
                    <label>Anreise</label>
                    <input type="date" name="from" value="{{ $booking->from->format('Y-m-d') }}" required />
                  </div>
                  <div>
                    <label>Abreise</label>
                    <input type="date" name="to" value="{{ $booking->to->format('Y-m-d') }}" required />
                  </div>
                  <div>
                    <label>Gastname</label>
                    <input type="text" name="guest_name" value="{{ $booking->guest_name }}" placeholder="optional" maxlength="100" />
                  </div>
                  <div>
                    <button type="submit" class="btn btn-save">Speichern</button>
                  </div>
                  <div>
                    <button type="button" class="btn btn-cancel" onclick="closeEdit({{ $booking->id }})">Abbrechen</button>
                  </div>
                </div>
              </form>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="4">Noch keine Buchungen vorhanden.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
  function openEdit(id) {
    document.querySelectorAll('.row-edit.active').forEach(el => {
      if (el.id !== 'edit-' + id) el.classList.remove('active');
    });
    document.getElementById('edit-' + id).classList.add('active');
    document.getElementById('edit-' + id).querySelector('input').focus();
  }

  function closeEdit(id) {
    document.getElementById('edit-' + id).classList.remove('active');
  }
</script>

</body>
</html>
