function HouseImages() {
  return (
    <div className="flex justify-center">
      <img
        className="w-[35%] rotate-[-5deg] border-[6px] border-white rounded-lg shadow-2xl z-10"
        src="images/house/front.webp"
        alt="front view"
      />
      <img
        className="w-[35%] rotate-[5deg] border-[6px] border-white rounded-lg shadow-2xl"
        src="images/house/side.webp"
        alt="front view"
      />
    </div>
  );
}

export default HouseImages;
