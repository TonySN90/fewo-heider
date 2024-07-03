import HeaderCarousel from "./Carousel";

function hero() {
  return (
    <div className="relative">
      <div className="clippath-header">
        <HeaderCarousel />
      </div>
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center text-white w-[250px] md:w-auto">
        <h2 className="text-[1.7rem] md:text-[2.6rem]">Willkommen auf der</h2>
        <h1 className="text-[3rem] md:text-[6rem]">Insel Rügen</h1>
      </div>
    </div>
  );
}

export default hero;
