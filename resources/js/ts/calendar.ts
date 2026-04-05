// ============================================================
// BELEGUNGSKALENDER
// Belegte Zeiträume hier manuell eintragen:
// ============================================================

interface BookedRange {
  from: Date;
  to: Date;
}

// Hilfsfunktion: Monat 1-basiert (1=Jan, 12=Dez)
const d = (year: number, month: number, day: number): Date =>
  new Date(year, month - 1, day);

// ── BELEGUNGEN HIER EINTRAGEN ──────────────────────────────
// Format: d(Jahr, Monat (1=Jan), Tag)
const BOOKED_RANGES: BookedRange[] = [
  { from: d(2026, 5, 14), to: d(2026, 5, 17) },
  { from: d(2026, 6, 21), to: d(2026, 7,  3) },
  { from: d(2026, 7, 11), to: d(2026, 7, 15) },
  { from: d(2026, 7, 22), to: d(2026, 8, 10) },
  { from: d(2026, 8, 15), to: d(2026, 8, 25) },
];
// ──────────────────────────────────────────────────────────

const WEEKDAY_LABELS = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
const MONTH_NAMES = [
  'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
  'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember',
];

function isBooked(date: Date): boolean {
  return BOOKED_RANGES.some(({ from, to }) => {
    const d = date.getTime();
    return d >= from.getTime() && d <= to.getTime();
  });
}

function isSameDay(a: Date, b: Date): boolean {
  return a.getFullYear() === b.getFullYear()
    && a.getMonth() === b.getMonth()
    && a.getDate() === b.getDate();
}

function isCheckIn(date: Date): boolean {
  return BOOKED_RANGES.some(({ from }) => isSameDay(date, from));
}

function isCheckOut(date: Date): boolean {
  return BOOKED_RANGES.some(({ to }) => isSameDay(date, to));
}

function renderMonth(year: number, month: number): HTMLElement {
  const today = new Date();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);

  // Wochentag des Monatsbeginns (0=So → in 1=Mo umrechnen)
  let startDow = firstDay.getDay(); // 0=So
  startDow = (startDow + 6) % 7;   // → 0=Mo

  const monthEl = document.createElement('div');
  monthEl.className = 'cal-month';

  // Header
  const header = document.createElement('div');
  header.className = 'cal-month__header';
  header.textContent = `${MONTH_NAMES[month]} ${year}`;
  monthEl.appendChild(header);

  // Wochentag-Labels
  const weekdays = document.createElement('div');
  weekdays.className = 'cal-month__weekdays';
  WEEKDAY_LABELS.forEach((label) => {
    const span = document.createElement('span');
    span.textContent = label;
    weekdays.appendChild(span);
  });
  monthEl.appendChild(weekdays);

  // Tage
  const daysGrid = document.createElement('div');
  daysGrid.className = 'cal-month__days';

  // Leere Zellen am Anfang
  for (let i = 0; i < startDow; i++) {
    const empty = document.createElement('div');
    empty.className = 'cal-day cal-day--empty';
    daysGrid.appendChild(empty);
  }

  // Tage befüllen
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
      const checkIn  = isCheckIn(date);
      const checkOut = isCheckOut(date);

      if (checkIn && checkOut) {
        // Abreise einer Buchung + Anreise einer anderen → voll belegt
        classes.push('cal-day--booked');
      } else if (checkIn) {
        classes.push('cal-day--booked', 'cal-day--check-in');
      } else if (checkOut) {
        classes.push('cal-day--booked', 'cal-day--check-out');
      } else if (isBooked(date)) {
        classes.push('cal-day--booked');
      } else {
        classes.push('cal-day--free');
      }
    }

    dayEl.className = classes.join(' ');

    const checkIn  = isCheckIn(date);
    const checkOut = isCheckOut(date);

    if ((checkIn || checkOut) && !(checkIn && checkOut) && !(date < today && !isSameDay(date, today))) {
      // Hauptzahl (weiß auf grünem Bereich)
      const numMain = document.createElement('span');
      numMain.className = 'cal-day__num cal-day__num--main';
      numMain.textContent = String(day);

      // Zweite Zahl (dunkel auf grauem Bereich)
      const numAlt = document.createElement('span');
      numAlt.className = 'cal-day__num cal-day__num--alt';
      numAlt.textContent = String(day);

      dayEl.appendChild(numMain);
      dayEl.appendChild(numAlt);
    } else {
      dayEl.textContent = String(day);
    }

    if (isBooked(date)) {
      dayEl.setAttribute('title', 'Belegt');
    } else if (date >= today) {
      dayEl.setAttribute('title', 'Verfügbar');
    }

    daysGrid.appendChild(dayEl);
  }

  monthEl.appendChild(daysGrid);
  return monthEl;
}

export function initCalendar(): void {
  const container = document.getElementById('booking-calendar');
  const prevBtn = document.getElementById('cal-prev') as HTMLButtonElement | null;
  const nextBtn = document.getElementById('cal-next') as HTMLButtonElement | null;
  if (!container) return;

  const now = new Date();
  let displayYear = now.getFullYear();
  let displayMonth = now.getMonth();

  function render() {
    container!.innerHTML = '';
    container!.appendChild(renderMonth(displayYear, displayMonth));
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
