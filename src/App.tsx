// import Background from "./components/Background";
import CalendarPrices from "./calendar/CalendarPrices";
import NavbarDesktop from "./components/NavbarDesktop";
import Contact from "./contact/Contact";
import Footer from "./footer/Footer";
import Header from "./header/Header";
import Insights from "./insights/Insights";

export default function App() {
  return (
    <>
      <Header />
      <NavbarDesktop />
      <Insights />
      <CalendarPrices />
      <Contact />
      <Footer />
    </>
  );
}
