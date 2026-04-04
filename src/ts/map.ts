// ============================================================
// LEAFLET KARTE
// ============================================================

import L from 'leaflet';

// Leaflet Icon-Fix für Vite (Standard-Icons werden durch Module-Bundler verschoben)
delete (L.Icon.Default.prototype as unknown as Record<string, unknown>)['_getIconUrl'];
L.Icon.Default.mergeOptions({
  iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).href,
  iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
  shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
});

// Koordinaten: Serams/Zirkow, Rügen
const LAT = 54.3835;
const LNG = 13.5632;
const ZOOM = 13;

export function initMap(): void {
  const mapEl = document.getElementById('map');
  if (!mapEl) return;

  const map = L.map('map', {
    center: [LAT, LNG],
    zoom: ZOOM,
    scrollWheelZoom: false,
    zoomControl: false,
    attributionControl: false,
  });

  // Mapbox Tile-Layer
  const MAPBOX_TOKEN = import.meta.env.VITE_MAPBOX_TOKEN;
  L.tileLayer(
    `https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/{z}/{x}/{y}?access_token=${MAPBOX_TOKEN}`,
    {
      attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      tileSize: 512,
      zoomOffset: -1,
      maxZoom: 20,
    }
  ).addTo(map);

  // Marker
  const marker = L.marker([LAT, LNG]).addTo(map);

  // Popup
  marker.bindPopup(`
    <div class="map-popup">
      <h4>🏠 Ferienwohnung Heider</h4>
      <p>Serams 8A</p>
      <p>18528 Zirkow/Serams</p>
      <p><a href="tel:+493839331283">📞 038393 31283</a></p>
      <p><a href="mailto:fewo.heider@gmail.com">✉️ fewo.heider@gmail.com</a></p>
    </div>
  `, {
    maxWidth: 250,
  });

  // Karte reaktivieren wenn Sektion sichtbar wird (ScrollWheelZoom erst nach Klick)
  mapEl.addEventListener('click', () => {
    map.scrollWheelZoom.enable();
  });

  map.on('blur', () => {
    map.scrollWheelZoom.disable();
  });
}
