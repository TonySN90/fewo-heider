export function initThemeToggle(): void {
  const toggle = (): void => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const next = isDark ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
  };

  document.querySelectorAll<HTMLButtonElement>('.header__theme-toggle').forEach(btn => {
    btn.addEventListener('click', toggle);
  });
}
