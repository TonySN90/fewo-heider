// ============================================================
// PREISTABELLE – Daten werden dynamisch per API geladen
// ============================================================

interface Season {
  name: string;
  from: string;
  to: string;
  price_per_night: number;
  min_nights: number;
  badge_color: string | null;
}

function formatDate(dateStr: string): string {
  const [year, month, day] = dateStr.split('-');
  return `${day}.${month}.${year.slice(2)}`;
}

async function loadSeasons(): Promise<void> {
  const tbody = document.getElementById('seasons-tbody');
  if (!tbody) return;

  try {
    const res = await fetch('/api/seasons');
    const data: { year: number | null; prices: Season[] } = await res.json();

    const yearEl = document.getElementById('pricing-year');
    if (yearEl && data.year) yearEl.textContent = String(data.year);

    if (data.prices.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#aaa;">Keine Preise hinterlegt.</td></tr>';
      return;
    }

    tbody.innerHTML = data.prices
      .map((s) => {
        const badgeClass = s.badge_color ? `season-badge season-badge--${s.badge_color}` : 'season-badge';
        return `
        <tr>
          <td><span class="${badgeClass}">${s.name}</span></td>
          <td>${formatDate(s.from)} – ${formatDate(s.to)}</td>
          <td class="price">${s.price_per_night} €</td>
          <td>${s.min_nights} ${s.min_nights === 1 ? 'Nacht' : 'Nächte'}</td>
        </tr>`;
      })
      .join('');
  } catch {
    tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#aaa;">Preise konnten nicht geladen werden.</td></tr>';
  }
}

async function loadPricingNotes(): Promise<void> {
  const container = document.getElementById('pricing-notes-list');
  if (!container) return;

  try {
    const res = await fetch('/api/pricing-notes');
    const notes: { text: string; icon: string | null }[] = await res.json();

    if (notes.length === 0) {
      container.innerHTML = '';
      return;
    }

    container.innerHTML = notes
      .map((n) => {
        const icon = n.icon
          ? `<span class="material-symbols-rounded">${n.icon}</span> `
          : '';
        return `<p>${icon}${n.text}</p>`;
      })
      .join('');
  } catch {
    container.innerHTML = '';
  }
}

export function initSeasons(): void {
  loadSeasons();
  loadPricingNotes();
}
