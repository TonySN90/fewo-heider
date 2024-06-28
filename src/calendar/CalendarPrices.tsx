import Calendar from "./Calendar";
import Prices from "./Prices";

function CalendarPrices() {
  return (
    <section
      className="w-[100%] mx-auto"
      style={{
        backgroundImage: "url(../images/calendar/bg_preise_edit.webp)",
        backgroundPosition: "center",
        backgroundRepeat: "no-repeat",
      }}
    >
      <div className="px-4 py-6 max-w-[1100px] mx-auto">
        <div className="text-center py-10">
          <h2 className="text-3xl mb-2">Preise & Belegung</h2>
          <hr className="w-[3rem] h-[3px] bg-black m-auto" />
        </div>
        <div className="flex flex-col lg:flex-row justify-center items-center lg:items-stretch gap-2 py-2">
          <Prices />
          <Calendar />
        </div>
      </div>
    </section>
  );
}

export default CalendarPrices;
