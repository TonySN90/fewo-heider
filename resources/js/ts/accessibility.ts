// ============================================================
// ACCESSIBILITY: Schriftgröße, Kontrast, Zeilenabstand
// ============================================================

export function initAccessibility(): void {
  // ── Schriftgröße ───────────────────────────────────────────
  const STEPS = [-2, -1, 0, 1, 2];
  const SIZES = [12, 14, 16, 18, 20]; // px
  const FONT_KEY = 'a11y-font-size-step';

  let currentStep = parseInt(localStorage.getItem(FONT_KEY) ?? '0', 10);
  if (!STEPS.includes(currentStep)) currentStep = 0;

  function applyFontSize(step: number): void {
    document.documentElement.style.fontSize = SIZES[step + 2] + 'px';
    localStorage.setItem(FONT_KEY, String(step));
    currentStep = step;
    updateButtons();
  }

  // ── Hoher Kontrast ─────────────────────────────────────────
  // Custom Properties werden direkt per style.setProperty() gesetzt,
  // damit sie Tenant-Inline-Styles (höhere Spezifität) überschreiben.
  const CONTRAST_KEY = 'a11y-high-contrast';
  let highContrast = localStorage.getItem(CONTRAST_KEY) === '1';

  const CONTRAST_PROPS: Record<string, string> = {
    '--color-bg':           '#ffffff',
    '--color-bg-alt':       '#f2f2f2',
    '--color-surface':      '#ffffff',
    '--color-text':         '#000000',
    '--color-text-light':   '#000000',
    '--color-dark':         '#000000',
    '--color-border':       '#000000',
    '--color-primary':      '#004d42',
    '--color-primary-dark': '#003830',
    '--color-secondary':    '#004d42',
    '--color-footer-top':   '#000000',
    '--color-footer-bot':   '#000000',
  };

  function applyContrast(active: boolean): void {
    const html = document.documentElement;
    html.toggleAttribute('data-a11y-contrast', active);
    if (active) {
      Object.entries(CONTRAST_PROPS).forEach(([prop, val]) => {
        html.style.setProperty(prop, val);
      });
    } else {
      Object.keys(CONTRAST_PROPS).forEach((prop) => {
        html.style.removeProperty(prop);
      });
    }
    localStorage.setItem(CONTRAST_KEY, active ? '1' : '0');
    highContrast = active;
    updateButtons();
  }

  // ── Zeilenabstand ──────────────────────────────────────────
  const SPACING_KEY = 'a11y-line-spacing';
  let wideSpacing = localStorage.getItem(SPACING_KEY) === '1';

  function applySpacing(active: boolean): void {
    document.documentElement.toggleAttribute('data-a11y-spacing', active);
    localStorage.setItem(SPACING_KEY, active ? '1' : '0');
    wideSpacing = active;
    updateButtons();
  }

  // ── Links hervorheben ──────────────────────────────────────
  const LINKS_KEY = 'a11y-highlight-links';
  let highlightLinks = localStorage.getItem(LINKS_KEY) === '1';

  function applyLinks(active: boolean): void {
    document.documentElement.toggleAttribute('data-a11y-links', active);
    localStorage.setItem(LINKS_KEY, active ? '1' : '0');
    highlightLinks = active;
    updateButtons();
  }

  // ── Animationen reduzieren ─────────────────────────────────
  const MOTION_KEY = 'a11y-reduce-motion';
  let reduceMotion = localStorage.getItem(MOTION_KEY) === '1';

  function applyMotion(active: boolean): void {
    document.documentElement.toggleAttribute('data-a11y-motion', active);
    localStorage.setItem(MOTION_KEY, active ? '1' : '0');
    reduceMotion = active;
    updateButtons();
  }

  // ── Buttons aktualisieren ──────────────────────────────────
  function updateButtons(): void {
    document.querySelectorAll<HTMLButtonElement>('.a11y__font-decrease').forEach((btn) => {
      btn.disabled = currentStep <= -2;
    });
    document.querySelectorAll<HTMLButtonElement>('.a11y__font-increase').forEach((btn) => {
      btn.disabled = currentStep >= 2;
    });
    document.querySelectorAll<HTMLButtonElement>('.a11y__font-reset').forEach((btn) => {
      btn.disabled = currentStep === 0;
    });
    document.querySelectorAll<HTMLButtonElement>('.a11y__contrast-toggle').forEach((btn) => {
      btn.classList.toggle('active', highContrast);
      btn.setAttribute('aria-pressed', String(highContrast));
    });
    document.querySelectorAll<HTMLButtonElement>('.a11y__spacing-toggle').forEach((btn) => {
      btn.classList.toggle('active', wideSpacing);
      btn.setAttribute('aria-pressed', String(wideSpacing));
    });
    document.querySelectorAll<HTMLButtonElement>('.a11y__links-toggle').forEach((btn) => {
      btn.classList.toggle('active', highlightLinks);
      btn.setAttribute('aria-pressed', String(highlightLinks));
    });
    document.querySelectorAll<HTMLButtonElement>('.a11y__motion-toggle').forEach((btn) => {
      btn.classList.toggle('active', reduceMotion);
      btn.setAttribute('aria-pressed', String(reduceMotion));
    });
  }

  // ── Initial anwenden ───────────────────────────────────────
  applyFontSize(currentStep);
  applyContrast(highContrast);
  applySpacing(wideSpacing);
  applyLinks(highlightLinks);
  applyMotion(reduceMotion);

  // ── Toggle-Button für Dropdown ─────────────────────────────
  document.querySelectorAll<HTMLButtonElement>('.header__a11y-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const dropdown = btn.closest('.header__a11y')?.querySelector('.header__a11y-dropdown');
      const isOpen = dropdown?.classList.toggle('open');
      btn.setAttribute('aria-expanded', String(isOpen ?? false));
    });
  });

  // Dropdown schließen bei Klick außerhalb
  document.addEventListener('click', () => {
    document.querySelectorAll('.header__a11y-dropdown.open').forEach((d) => {
      d.classList.remove('open');
      d.closest('.header__a11y')
        ?.querySelector('.header__a11y-toggle')
        ?.setAttribute('aria-expanded', 'false');
    });
  });

  // ── Schriftgröße Buttons ───────────────────────────────────
  document.querySelectorAll<HTMLButtonElement>('.a11y__font-reset').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      applyFontSize(0);
    });
  });

  document.querySelectorAll<HTMLButtonElement>('.a11y__font-decrease').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      if (currentStep > -2) applyFontSize(currentStep - 1);
    });
  });

  document.querySelectorAll<HTMLButtonElement>('.a11y__font-increase').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      if (currentStep < 2) applyFontSize(currentStep + 1);
    });
  });

  // ── Kontrast Button ────────────────────────────────────────
  document.querySelectorAll<HTMLButtonElement>('.a11y__contrast-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      applyContrast(!highContrast);
    });
  });

  // ── Zeilenabstand Button ───────────────────────────────────
  document.querySelectorAll<HTMLButtonElement>('.a11y__spacing-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      applySpacing(!wideSpacing);
    });
  });

  // ── Links hervorheben Button ───────────────────────────────
  document.querySelectorAll<HTMLButtonElement>('.a11y__links-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      applyLinks(!highlightLinks);
    });
  });

  // ── Animationen reduzieren Button ─────────────────────────
  document.querySelectorAll<HTMLButtonElement>('.a11y__motion-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      applyMotion(!reduceMotion);
    });
  });
}
