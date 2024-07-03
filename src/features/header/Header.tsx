import BurgerButton from "../../components/BurgerButton";
import HeaderLayout from "./HeaderLayout";

function Header() {
  return (
    <header className="mx-auto bg-white ">
      <BurgerButton />
      <HeaderLayout />
    </header>
  );
}

export default Header;
