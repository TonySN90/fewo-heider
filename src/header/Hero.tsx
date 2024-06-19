import HeaderCarousel from "./Carousel";

function hero() {
  return (
    <div className="relative">
      <div className="h-[500px] clippath-header">
        <HeaderCarousel />
      </div>
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white">
        <h2 className="text-3xl">Willkommen auf der</h2>
        <h1 className="text-5xl">Insel Rügen</h1>
      </div>
    </div>
  );
}

export default hero;
