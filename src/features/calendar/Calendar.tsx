import { Calendar } from "@demark-pro/react-booking-calendar";

import "@demark-pro/react-booking-calendar/dist/react-booking-calendar.css";
import { useFewoContext } from "../../contexts/FewoContext";

function BookingCalendar() {
  const { bookings } = useFewoContext();

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
