import { useEffect, useState } from "react";

function BurgerMenu() {
  const [isOpen, setIsOpen] = useState(false);

  const barStyles =
    "w-[28px] h-[4px] bg-color_bg_darkgray m-[4px] transition-all duration-300 ease-in-out";

  const handleClick = () => {
    setIsOpen(!isOpen);
  };

  useEffect(() => {
    console.log(isOpen);
  }, [isOpen]);

  return (
    <div
      onClick={() => handleClick()}
      className="fixed md:hidden top-3 right-4 bg-red-300 p-1 rounded-md z-[1000] cursor-pointer"
    >
      <div
        className={`bar1 ${
          isOpen ? "rotate-[-45deg] translate-y-[8px]" : ""
        } ${barStyles}`}
      ></div>
      <div className={`bar2 ${isOpen ? "opacity-0" : ""} ${barStyles}`}></div>
      <div
        className={`bar3 ${
          isOpen ? "rotate-[45deg] translate-y-[-8px]" : ""
        } ${barStyles} ${barStyles}`}
      ></div>
    </div>
  );
}

export default BurgerMenu;
