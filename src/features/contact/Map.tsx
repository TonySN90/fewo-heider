import { Marker, Popup } from "react-leaflet";
import { MapContainer } from "react-leaflet/MapContainer";
import { TileLayer } from "react-leaflet/TileLayer";
import "leaflet/dist/leaflet.css";

function Map() {
  return (
    <MapContainer
      className="w-full md:w-3/4 md:h-auto"
      center={[54.37735, 13.5902]}
      zoom={16}
      scrollWheelZoom={false}
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />
      <Marker position={[54.37735, 13.5902]}>
        <Popup>😀😊😉</Popup>
      </Marker>
    </MapContainer>
  );
}

export default Map;

// <iframe
//   className="w-full md:w-3/4"
//   src="https://www.openstreetmap.org/export/embed.html?bbox=13.588274717330934%2C54.37663002252498%2C13.592158555984499%2C54.37807977875707&amp;layer=mapnik&amp;marker=54.377354907040505%2C13.590216636657715"
// ></iframe>
