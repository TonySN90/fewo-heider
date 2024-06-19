function Insights() {
  return (
    <section className="mx-auto bg-red-50">
      <div className="w-[95%] max-w-[1250px] mx-auto py-[5rem]">
        <h1 className="text-3xl text-center">Ferienwohnung Heider</h1>
        <hr className="w-[4rem] bg-black h-[3px] mx-auto my-[4rem]" />
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
      </div>
    </section>
  );
}

export default Insights;
