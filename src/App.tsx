// import Background from "./components/Background";
import CalendarPrices from "./calendar/CalendarPrices";
import NavbarDesktop from "./components/NavbarDesktop";
import Header from "./header/Header";
import Insights from "./insights/Insights";

export default function App() {
  return (
    <>
      <Header />
      <NavbarDesktop />
      <Insights />
      <CalendarPrices />
    </>
  );
}
