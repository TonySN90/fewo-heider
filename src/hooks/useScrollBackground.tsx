import { useEffect, useRef, useState } from "react";

function useScrollBackground() {
  const ref = useRef(null);
  const [backgroundPositionY, newBackgroundPositionY] = useState("0px");

  useEffect(() => {
    const handleScroll = () => {
      if (ref.current) {
        const scrollPosition = window.scrollY;
        newBackgroundPositionY(scrollPosition * -0.1 + "px");
      }
    };
    window.addEventListener("scroll", handleScroll);

    return () => {
      window.removeEventListener("scroll", handleScroll);
    };
  }, [backgroundPositionY]);

  const sectionStyle = {
    backgroundPosition: "center",
    backgroundImage: "url('/images/bg.webp')",
    backgroundPositionY: backgroundPositionY,
    backgroundRepeat: "repeat",
    backgroundAttachment: "fixed",
  };

  return {
    ref,
    sectionStyle,
  };
}

export default useScrollBackground;
