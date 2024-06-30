import NavbarDesktop from "../components/NavbarDesktop";
import CalendarPrices from "../features/calendar/CalendarPrices";
import Contact from "../features/contact/Contact";
import Footer from "../features/footer/Footer";
import Header from "../features/header/Header";
import Insights from "../features/insights/Insights";

function Home() {
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

export default Home;
