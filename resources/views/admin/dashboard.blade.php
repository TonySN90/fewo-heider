@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
  <h1>Dashboard</h1>

  <div class="stat-grid">

    <div class="stat-card">
      <div class="stat-card__label">Buchungen dieses Jahr</div>
      <div class="stat-card__value">{{ $bookingsThisYear }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Buchungen diesen Monat</div>
      <div class="stat-card__value">{{ $bookingsThisMonth }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Nächste Buchung</div>
      @if ($nextBooking)
        <div class="stat-card__value stat-card__value--lg">{{ $nextBooking->from->format('d.m.Y') }}</div>
        <div class="stat-card__sub">
          {{ $nextBooking->guest_name ?? 'Kein Gastname' }}
          &ndash; bis {{ $nextBooking->to->format('d.m.Y') }}
        </div>
      @else
        <div class="stat-card__value stat-card__value--empty">–</div>
        <div class="stat-card__sub">Keine anstehenden Buchungen</div>
      @endif
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Auslastung {{ \Carbon\Carbon::now()->locale('de')->isoFormat('MMMM YYYY') }}</div>
      <div class="stat-card__value">{{ $occupancyRate }}&thinsp;%</div>
      <div class="stat-card__sub">{{ $bookedDays }} von {{ $daysInMonth }} Tagen belegt</div>
      <div class="stat-card__bar" role="progressbar"
           aria-valuenow="{{ $occupancyRate }}" aria-valuemin="0" aria-valuemax="100">
        <div class="stat-card__bar-fill" style="width: {{ $occupancyRate }}%"></div>
      </div>
    </div>

  </div>

  <div class="table-card">
    <h2>Nächste Buchungen</h2>
    <table>
      <thead>
        <tr>
          <th>Anreise</th>
          <th>Abreise</th>
          <th>Nächte</th>
          <th>Gastname</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($upcomingBookings as $booking)
          <tr>
            <td>{{ $booking->from->format('d.m.Y') }}</td>
            <td>{{ $booking->to->format('d.m.Y') }}</td>
            <td>{{ $booking->from->diffInDays($booking->to) }}</td>
            <td class="guest-name">{{ $booking->guest_name ?? '–' }}</td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="4">Keine anstehenden Buchungen.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection