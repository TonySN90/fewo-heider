import { Link } from "react-router-dom";

function HeaderBar() {
  return (
    <div className="flex items-center justify-between h-[65px] px-10 border-b-2 ">
      <div>
        <Link to={"/home"}>
          <img className="w-[170px]" src="images/logo.png" alt="logo" />
        </Link>
      </div>
      <div className="hidden md:visible md:flex gap-10">
        <div>Tel: 038393 31283</div>
        <div>E-Mail: fewo.heider@gmail.com</div>
      </div>
    </div>
  );
}

export default HeaderBar;
