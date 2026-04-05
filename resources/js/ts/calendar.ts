// ============================================================
// BELEGUNGSKALENDER – Daten werden dynamisch per API geladen
// ============================================================

interface BookedRange {
  from: Date;
  to: Date;
}

const WEEKDAY_LABELS = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
const MONTH_NAMES = [
  'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
  'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember',
];

async function loadBookings(): Promise<BookedRange[]> {
  const res = await fetch('/api/bookings');
  const data: { from: string; to: string }[] = await res.json();
  return data.map((b) => ({
    from: new Date(b.from),
    to:   new Date(b.to),
  }));
}

function isBooked(date: Date, ranges: BookedRange[]): boolean {
  return ranges.some(({ from, to }) => {
    const d = date.getTime();
    return d >= from.getTime() && d <= to.getTime();
  });
}

function isSameDay(a: Date, b: Date): boolean {
  return a.getFullYear() === b.getFullYear()
    && a.getMonth() === b.getMonth()
    && a.getDate() === b.getDate();
}

function isCheckIn(date: Date, ranges: BookedRange[]): boolean {
  return ranges.some(({ from }) => isSameDay(date, from));
}

function isCheckOut(date: Date, ranges: BookedRange[]): boolean {
  return ranges.some(({ to }) => isSameDay(date, to));
}

function renderMonth(year: number, month: number, ranges: BookedRange[]): HTMLElement {
  const today = new Date();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);

  let startDow = firstDay.getDay();
  startDow = (startDow + 6) % 7;

  const monthEl = document.createElement('div');
  monthEl.className = 'cal-month';

  const header = document.createElement('div');
  header.className = 'cal-month__header';
  header.textContent = `${MONTH_NAMES[month]} ${year}`;
  monthEl.appendChild(header);

  const weekdays = document.createElement('div');
  weekdays.className = 'cal-month__weekdays';
  WEEKDAY_LABELS.forEach((label) => {
    const span = document.createElement('span');
    span.textContent = label;
    weekdays.appendChild(span);
  });
  monthEl.appendChild(weekdays);

  const daysGrid = document.createElement('div');
  daysGrid.className = 'cal-month__days';

  for (let i = 0; i < startDow; i++) {
    const empty = document.createElement('div');
    empty.className = 'cal-day cal-day--empty';
    daysGrid.appendChild(empty);
  }

  for (let day = 1; day <= lastDay.getDate(); day++) {
    const date = new Date(year, month, day);
    const dayEl = document.createElement('div');
    const classes = ['cal-day'];

    if (isSameDay(date, today)) {
      classes.push('cal-day--today');
    }

    if (date < today && !isSameDay(date, today)) {
      classes.push('cal-day--past');
    } else {
      const checkIn  = isCheckIn(date, ranges);
      const checkOut = isCheckOut(date, ranges);

      if (checkIn && checkOut) {
        classes.push('cal-day--booked');
      } else if (checkIn) {
        classes.push('cal-day--booked', 'cal-day--check-in');
      } else if (checkOut) {
        classes.push('cal-day--booked', 'cal-day--check-out');
      } else if (isBooked(date, ranges)) {
        classes.push('cal-day--booked');
      } else {
        classes.push('cal-day--free');
      }
    }

    dayEl.className = classes.join(' ');

    const checkIn  = isCheckIn(date, ranges);
    const checkOut = isCheckOut(date, ranges);

    if ((checkIn || checkOut) && !(checkIn && checkOut) && !(date < today && !isSameDay(date, today))) {
      const numMain = document.createElement('span');
      numMain.className = 'cal-day__num cal-day__num--main';
      numMain.textContent = String(day);

      const numAlt = document.createElement('span');
      numAlt.className = 'cal-day__num cal-day__num--alt';
      numAlt.textContent = String(day);

      dayEl.appendChild(numMain);
      dayEl.appendChild(numAlt);
    } else {
      dayEl.textContent = String(day);
    }

    if (isBooked(date, ranges)) {
      dayEl.setAttribute('title', 'Belegt');
    } else if (date >= today) {
      dayEl.setAttribute('title', 'Verfügbar');
    }

    daysGrid.appendChild(dayEl);
  }

  monthEl.appendChild(daysGrid);
  return monthEl;
}

export async function initCalendar(): Promise<void> {
  const container = document.getElementById('booking-calendar');
  const prevBtn = document.getElementById('cal-prev') as HTMLButtonElement | null;
  const nextBtn = document.getElementById('cal-next') as HTMLButtonElement | null;
  if (!container) return;

  const ranges = await loadBookings();

  const now = new Date();
  let displayYear = now.getFullYear();
  let displayMonth = now.getMonth();

  function render() {
    container!.innerHTML = '';
    container!.appendChild(renderMonth(displayYear, displayMonth, ranges));
    if (prevBtn) {
      prevBtn.disabled =
        displayYear === now.getFullYear() && displayMonth === now.getMonth();
    }
  }

  prevBtn?.addEventListener('click', () => {
    if (displayMonth === 0) { displayMonth = 11; displayYear--; }
    else { displayMonth--; }
    render();
  });

  nextBtn?.addEventListener('click', () => {
    if (displayMonth === 11) { displayMonth = 0; displayYear++; }
    else { displayMonth++; }
    render();
  });

  render();
}
