import { Marker, Popup } from "react-leaflet";
import { MapContainer } from "react-leaflet/MapContainer";
import { TileLayer } from "react-leaflet/TileLayer";
import "leaflet/dist/leaflet.css";

function Map() {
  return (
    <MapContainer
      className="w-full md:w-3/4 h-[170px] sm:h-auto"
      center={[54.37735, 13.5902]}
      zoom={16}
      scrollWheelZoom={true}
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
