// ============================================================
// MAIN.TS – Einstiegspunkt
// ============================================================

import '../css/main.scss';
import 'vanilla-cookieconsent/dist/cookieconsent.css';

import { initCookieConsent } from './ts/cookie';
import { initNavigation } from './ts/navigation';
import { initThemeToggle } from './ts/theme';
import { initGallery } from './ts/gallery';
import { initCalendar } from './ts/calendar';
import { initMap } from './ts/map';
import { initSeasons } from './ts/seasons';
import { initAccessibility } from './ts/accessibility';

document.addEventListener('DOMContentLoaded', () => {
  initAccessibility();
  initCookieConsent();
  initThemeToggle();
  initNavigation();
  initGallery();
  initCalendar();
  initSeasons();
  initMap();
});
