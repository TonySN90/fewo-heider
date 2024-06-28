import useScrollBackground from "../hooks/useScrollBackground";
import Title from "../components/Title";
import Map from "./Map";
import Address from "./Address";

function Contact() {
  const { ref, sectionStyle } = useScrollBackground();

  return (
    <section ref={ref} style={sectionStyle} className=" py-10">
      <Title title="Kontakt" />
      <div className="flex justify-center gap-6 w-[60%] max-w-[1250px] mx-auto py-10">
        <Map />
        <Address />
      </div>
    </section>
  );
}

export default Contact;
