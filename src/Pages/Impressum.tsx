import Footer from "../features/footer/Footer";
import HeaderBar from "../features/header/HeaderBar";

function Impressum() {
  return (
    <>
      <HeaderBar />
      <section>
        <div className="max-w-[1000px] m-auto px-4">
          <h1 className="text-[3rem] font-bold font-sans mb-4">Impressum</h1>
          <h2 className="text-[1.6rem] font-sans mb-2">
            Angaben gemäß § 5 DDG
          </h2>
          <div className="mb-4">
            <p>Lolita Heider </p>
            <p>Serams 8A</p>
            <p>18528 Zirkow</p>
          </div>

          <h2 className="text-[1.6rem] font-sans mb-2">Kontakt</h2>
          <p className="mb-4">E-Mail: fewo.heider@gmail.com</p>
        </div>
      </section>
      <div className="absolute bottom-0 w-full">
        <Footer />
      </div>
    </>
  );
}

export default Impressum;
