@extends('layouts.admin')

@section('title', 'Buchungen')

@section('content')
  <h1>Buchungen verwalten</h1>

  <div class="table-card">
    <div class="table-card__header">
      <h2>Alle Buchungen ({{ $bookings->count() }})</h2>
      @can('manage bookings')
      <button class="btn btn-add" onclick="openModal('Neue Buchung', 'add-booking-tpl')">Neue Buchung</button>
      @endcan
    </div>
    <table>
      <thead>
        <tr>
          <th>Anreise</th>
          <th>Abreise</th>
          <th>Gastname</th>
          <th>Portal</th>
          <th>Buchungsdatum</th>
          @can('manage bookings')<th></th>@endcan
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $booking)
          <tr class="row-display">
            <td>{{ $booking->from->format('d.m.Y') }}</td>
            <td>{{ $booking->to->format('d.m.Y') }}</td>
            <td class="guest-name">{{ $booking->guest_name ?? '–' }}</td>
            <td>{{ $booking->portal ?? '–' }}</td>
            <td>{{ $booking->booked_at?->format('d.m.Y') ?? '–' }}</td>
            @can('manage bookings')
            <td>
              <div class="actions">
                <button class="btn btn-edit" onclick="openModal('Buchung bearbeiten', 'edit-tpl-{{ $booking->id }}')"><span class="material-symbols-rounded">edit</span></button>
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Buchung wirklich löschen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete"><span class="material-symbols-rounded">delete</span></button>
                </form>
              </div>
            </td>
            @endcan
          </tr>

          <template id="edit-tpl-{{ $booking->id }}">
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
              @csrf
              @method('PUT')
              <div class="modal-form-grid">
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
                  <label>Buchungsportal</label>
                  <input type="text" name="portal" value="{{ $booking->portal }}" placeholder="optional" maxlength="100" />
                </div>
                <div>
                  <label>Buchungsdatum</label>
                  <input type="date" name="booked_at" value="{{ $booking->booked_at?->format('Y-m-d') }}" />
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
            <td colspan="6">Noch keine Buchungen vorhanden.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <template id="add-booking-tpl">
    <form method="POST" action="{{ route('admin.bookings.store') }}">
      @csrf
      <div class="modal-form-grid">
        <div>
          <label>Anreise</label>
          <input type="date" name="from" required />
        </div>
        <div>
          <label>Abreise</label>
          <input type="date" name="to" required />
        </div>
        <div>
          <label>Gastname</label>
          <input type="text" name="guest_name" placeholder="optional" maxlength="100" />
        </div>
        <div>
          <label>Buchungsportal</label>
          <input type="text" name="portal" placeholder="optional" maxlength="100" />
        </div>
        <div>
          <label>Buchungsdatum</label>
          <input type="date" name="booked_at" />
        </div>
      </div>
      <div class="modal__actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </template>
@endsection