import { Carousel } from "flowbite-react";

const theme = {
  root: {
    base: "relative h-full w-full",
    leftControl:
      "absolute left-0 top-0 flex h-full items-center justify-center px-4 focus:outline-none",
    rightControl:
      "absolute right-0 top-0 flex h-full items-center justify-center px-4 focus:outline-none",
  },
  indicators: {
    active: {
      off: "bg-white/50 hover:bg-white dark:bg-gray-800/50 dark:hover:bg-gray-800",
      on: "bg-white dark:bg-gray-800",
    },
    base: "h-3 w-3 rounded-full",
    wrapper: "absolute bottom-5 left-1/2 flex -translate-x-1/2 space-x-3",
  },
  item: {
    base: "object-cover w-full h-full",
    wrapper: {
      off: "w-full flex-shrink-0 transform cursor-default snap-center",
      on: "w-full flex-shrink-0 transform cursor-grab snap-center",
    },
  },
  control: {
    base: "inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/30 group-hover:bg-white/50 group-focus:outline-none  dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60 dark:group-focus:ring-gray-800/70 sm:h-10 sm:w-10",
    icon: "h-5 w-5 text-white dark:text-gray-800 sm:h-6 sm:w-6",
  },
  scrollContainer: {
    base: "flex h-full snap-mandatory overflow-y-hidden overflow-x-scroll scroll-smooth rounded-none",
    snap: "snap-x",
  },
};

export function HeaderCarousel() {
  return (
    <div className="h-[500px]">
      <Carousel
        indicators={false}
        pauseOnHover
        slideInterval={7000}
        theme={theme}
      >
        <img src="images/bg_header/bg_01.webp" alt="..." />
        <img src="images/bg_header/bg_02.webp" alt="..." />
        <img src="images/bg_header/bg_03.webp" alt="..." />
        <img src="images/bg_header/bg_04.webp" alt="..." />
        <img src="images/bg_header/bg_05.webp" alt="..." />
      </Carousel>
    </div>
  );
}

export default HeaderCarousel;
