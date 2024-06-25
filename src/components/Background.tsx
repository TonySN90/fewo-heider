import { useRef } from "react";

function Background() {
  return (
    <div className="absolute top-0 left-0 w-full h-full">
      <img className="object-cover w-full h-full" src="images/bg.webp" alt="" />
    </div>
  );
}

export default Background;
