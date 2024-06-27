import HeaderCarousel from "./Carousel";

function hero() {
  return (
    <div className="relative">
      <div className="h-[500px] clippath-header">
        <HeaderCarousel />
      </div>
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center text-white">
        <h2 className="text-[2.6rem]">Willkommen auf der</h2>
        <h1 className="text-[6rem]">Insel Rügen</h1>
      </div>
    </div>
  );
}

export default hero;
