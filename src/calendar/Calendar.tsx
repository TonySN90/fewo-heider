import { Calendar } from "@demark-pro/react-booking-calendar";

import "@demark-pro/react-booking-calendar/dist/react-booking-calendar.css";
import { useEffect } from "react";

function BookingCalendar() {
  const bookings = [
    {
      startDate: new Date("2024-06-10"),
      endDate: new Date("2024-06-17"),
    },
    {
      startDate: new Date("2024-06-01"),
      endDate: new Date("2024-06-05"),
    },
    {
      startDate: new Date("2024-06-28"),
      endDate: new Date("2024-07-06"),
    },
    {
      startDate: new Date("2024-07-15"),
      endDate: new Date("2024-07-20"),
    },
  ];

  useEffect(() => {
    document.querySelectorAll(".calendar__day-reservation").forEach((child) => {
      child.parentElement?.classList.add("reservation_style");
    });
  }, []);

  return (
    <div className="w-[100%] sm:w-[60%] md:w-[50%] lg:w-[30%]">
      <Calendar
        reserved={bookings}
        range={true}
        protection={true}
        options={{ locale: "de", weekStartsOn: 0, useAttributes: true }}
      />
    </div>
  );
}

export default BookingCalendar;
