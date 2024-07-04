import { useEffect } from "react";

function Cookiebot() {
  useEffect(() => {
    const script = document.createElement("script");
    script.id = "Cookiebot";
    script.src = "https://consent.cookiebot.com/uc.js";
    script.setAttribute("data-cbid", "ae793493-8dc7-437e-a45c-662b2c9c0dcb");
    script.type = "text/javascript";
    script.async = true;
    document.body.appendChild(script);
  }, []);

  return null;
}

export default Cookiebot;
