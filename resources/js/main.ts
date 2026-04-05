// ============================================================
// MAIN.TS – Einstiegspunkt
// ============================================================

import '../css/main.scss';

import { initNavigation } from './ts/navigation';
import { initGallery } from './ts/gallery';
import { initCalendar } from './ts/calendar';
import { initMap } from './ts/map';
import { initCookieBanner } from './ts/cookie';

document.addEventListener('DOMContentLoaded', () => {
  initNavigation();
  initGallery();
  initCalendar();
  initMap();
  initCookieBanner();
});
