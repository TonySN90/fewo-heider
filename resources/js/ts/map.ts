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

const ZOOM = 13;

export function initMap(): void {
  const mapEl = document.getElementById('map');
  if (!mapEl) return;

  const LAT = parseFloat(mapEl.dataset.lat ?? '53.618577') || 53.618577;
  const LNG = parseFloat(mapEl.dataset.lng ?? '11.469308') || 11.469308;

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
  const popupName    = mapEl.dataset.name    ?? 'Musterferienwohnung';
  const popupStreet  = mapEl.dataset.street  ?? 'Musterstraße 1';
  const popupCity    = mapEl.dataset.city    ?? '12345 Musterstadt';
  const popupPhone   = mapEl.dataset.phone   ?? '01234 56789';
  const popupPhoneHref = mapEl.dataset.phoneHref ?? '+4912345678';
  const popupEmail   = mapEl.dataset.email   ?? 'info@mustermann-fewo.de';

  marker.bindPopup(`
    <div class="map-popup">
      <h4>🏠 ${popupName}</h4>
      <p>${popupStreet}</p>
      <p>${popupCity}</p>
      <p><a href="tel:${popupPhoneHref}">📞 ${popupPhone}</a></p>
      <p><a href="mailto:${popupEmail}">✉️ ${popupEmail}</a></p>
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
