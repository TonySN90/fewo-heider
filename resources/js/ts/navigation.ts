// ============================================================
// NAVIGATION: Sticky Header, Hamburger, Smooth Scroll, Active State
// ============================================================

export function initNavigation(): void {
  const header = document.getElementById('header');
  const hamburger = document.getElementById('hamburger');
  const nav = document.getElementById('main-nav');
  const navLinks = document.querySelectorAll<HTMLAnchorElement>('.header__nav a[href^="#"]');
  const scrollTopBtn = document.getElementById('scroll-top');

  if (!header || !hamburger || !nav) return;

  // ── Sticky Header ──────────────────────────────────────────
  function updateHeader(): void {
    if (window.scrollY > 50) {
      header!.classList.add('scrolled');
    } else {
      header!.classList.remove('scrolled');
    }

    if (scrollTopBtn) {
      if (window.scrollY > 400) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    }
  }

  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();

  // ── Hamburger Menu ─────────────────────────────────────────
  hamburger.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    hamburger.setAttribute('aria-expanded', String(isOpen));
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  // Nav schließen bei Klick außerhalb
  document.addEventListener('click', (e) => {
    const target = e.target as Node;
    if (!nav.contains(target) && !hamburger.contains(target)) {
      closeNav();
    }
  });

  // Nav schließen bei Linkklick
  navLinks.forEach((link) => {
    link.addEventListener('click', () => {
      closeNav();
    });
  });

  function closeNav(): void {
    nav!.classList.remove('open');
    hamburger!.classList.remove('open');
    hamburger!.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  // ── Smooth Scroll ──────────────────────────────────────────
  document.querySelectorAll<HTMLAnchorElement>('a[href^="#"]').forEach((link) => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (!href || href === '#') return;
      if (link.classList.contains('skip-link')) return;

      const target = document.querySelector<HTMLElement>(href);
      if (!target) return;

      e.preventDefault();

      const headerHeight = header.offsetHeight;
      const targetTop = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;

      window.scrollTo({ top: targetTop, behavior: 'smooth' });
    });
  });

  // ── Active Nav Link via IntersectionObserver ───────────────
  const sections = document.querySelectorAll<HTMLElement>('section[id]');

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          navLinks.forEach((link) => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${entry.target.id}`) {
              link.classList.add('active');
            }
          });
        }
      });
    },
    {
      rootMargin: '-20% 0px -60% 0px',
      threshold: 0,
    }
  );

  sections.forEach((section) => observer.observe(section));

  // ── Scroll-to-Top Button ───────────────────────────────────
  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
}
