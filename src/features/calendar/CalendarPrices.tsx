import Title from "../../components/Title";
import Calendar from "./Calendar";
import Prices from "./Prices";

function CalendarPrices() {
  return (
    <section
      className="w-[100%] mx-auto py-16"
      style={{
        backgroundImage: "url(../images/calendar/bg_preise_edit.webp)",
        backgroundPosition: "center",
        backgroundRepeat: "no-repeat",
      }}
    >
      <div className="px-4 max-w-[1100px] sm:w-[70%] md:w-[80%] mx-auto">
        <Title title="Preise & Belegung" />
        <div className="flex flex-col lg:flex-row justify-center gap-2 mt-8">
          <Prices />
          <Calendar />
        </div>
      </div>
    </section>
  );
}

export default CalendarPrices;
