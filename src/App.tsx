import NavbarDesktop from "./components/NavbarDesktop";
import { FewoProvider } from "./contexts/FewoContext";
import CalendarPrices from "./features/calendar/CalendarPrices";
import Contact from "./features/contact/Contact";
import Footer from "./features/footer/Footer";
import Header from "./features/header/Header";
import Insights from "./features/insights/Insights";

export default function App() {
  return (
    <FewoProvider>
      <Header />
      <NavbarDesktop />
      <Insights />
      <CalendarPrices />
      <Contact />
      <Footer />
    </FewoProvider>
  );
}
