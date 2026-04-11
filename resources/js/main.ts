// ============================================================
// MAIN.TS – Einstiegspunkt
// ============================================================

import '../css/main.scss';

import { initNavigation } from './ts/navigation';
import { initThemeToggle } from './ts/theme';
import { initGallery } from './ts/gallery';
import { initCalendar } from './ts/calendar';
import { initMap } from './ts/map';
import { initCookieBanner } from './ts/cookie';
import { initSeasons } from './ts/seasons';

document.addEventListener('DOMContentLoaded', () => {
  initThemeToggle();
  initNavigation();
  initGallery();
  initCalendar();
  initSeasons();
  initMap();
  initCookieBanner();
});
