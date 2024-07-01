import Footer from "../features/footer/Footer";
import HeaderBar from "../features/header/HeaderBar";

function Datenschutz() {
  return (
    <>
      <HeaderBar />
      <section className="max-w-[1200px] m-auto">
        <h1 className="text-[3rem] font-bold">Datenschutz</h1>
      </section>
      <div className="absolute bottom-0">
        <Footer />
      </div>
    </>
  );
}

export default Datenschutz;
