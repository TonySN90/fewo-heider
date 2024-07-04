import { Link } from "react-router-dom";

function Footer() {
  return (
    <section className="flex flex-col justify-center items-center bg-color_bg_darkgray text-white py-10">
      <p>Copyright &copy; 2024 - Ferienwohnung Heider</p>
      <ul className="py-6 text-center">
        <li>
          <Link to="/impressum">Impressum</Link>{" "}
        </li>
        <li>
          {" "}
          <Link to="/datenschutz">Datenschutz</Link>{" "}
        </li>
      </ul>
      <p>
        Erstellt mit <span className="text-color_red">🤍</span> von{" "}
        <a
          className="text-color_red hover:text-yellow-400 transition-all"
          href="https://tonyheider.de"
          target="_blank"
        >
          Tony Heider
        </a>
      </p>
    </section>
  );
}

export default Footer;
