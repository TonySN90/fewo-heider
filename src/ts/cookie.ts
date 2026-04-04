// ============================================================
// COOKIE-HINWEIS
// ============================================================

export function initCookieBanner(): void {
  const banner = document.getElementById('cookie-banner');
  const closeBtn = document.getElementById('cookie-close');
  if (!banner || !closeBtn) return;

  if (localStorage.getItem('cookie-notice-dismissed') === '1') {
    banner.classList.add('hidden');
    return;
  }

  closeBtn.addEventListener('click', () => {
    banner.classList.add('hidden');
    localStorage.setItem('cookie-notice-dismissed', '1');
  });
}
