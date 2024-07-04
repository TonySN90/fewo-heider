import Map from "./Map";
import Address from "./Address";
import useScrollBackground from "../../hooks/useScrollBackground";
import Title from "../../components/Title";

function Contact() {
  const { ref, sectionStyle } = useScrollBackground();

  return (
    <section id="kontakt" ref={ref} style={sectionStyle} className=" py-10">
      <Title title="Kontakt" />
      <div className="w-[95%] max-w-[1250px] py-10 mx-auto px-2">
        <div className="w-full md:w-[80%] lg:w-[70%] flex flex-col sm:flex-row justify-center gap-6 mx-auto">
          <Map />
          <Address />
        </div>
      </div>
    </section>
  );
}

export default Contact;
