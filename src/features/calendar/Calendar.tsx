import "@demark-pro/react-booking-calendar/dist/react-booking-calendar.css";
import { useFewoContext } from "../../contexts/FewoContext";
import { useState } from "react";
import {
  format,
  startOfMonth,
  endOfMonth,
  eachDayOfInterval,
  isWithinInterval,
  isSameDay,
  addMonths,
  subMonths,
  getDay,
} from "date-fns";
import { MdKeyboardArrowLeft, MdKeyboardArrowRight } from "react-icons/md";

function BookingCalendar() {
  const { bookings } = useFewoContext();

  return (
    <div className="w-full lg:w-1/3">
      {/* <div className="w-[100%] sm:w-[60%] md:w-[50%] lg:w-[30%]"> */}
      <Calendar bookings={bookings.sort((a, b) => b.startDate - a.startDate)} />
    </div>
  );
}

export default BookingCalendar;

function Calendar({ bookings }) {
  const [currentMonth, setCurrentMonth] = useState(new Date());

  const start = startOfMonth(currentMonth);
  const end = endOfMonth(currentMonth);
  const days = eachDayOfInterval({ start, end });

  const handleNextMonth = () => {
    setCurrentMonth(addMonths(currentMonth, 1));
  };

  const handlePrevMonth = () => {
    setCurrentMonth(subMonths(currentMonth, 1));
  };

  function getBookingClass(day: Date) {
    for (const booking of bookings) {
      if (isSameDay(day, booking.startDate)) {
        for (const booking of bookings) {
          if (isSameDay(day, booking.endDate)) {
            return "bg-color_red text-white";
          }
        }
        return "bg-color_red text-white start-date";
      }
      if (isSameDay(day, booking.endDate)) {
        return "bg-color_red text-white end-date";
      }

      if (
        isWithinInterval(day, {
          start: booking.startDate,
          end: booking.endDate,
        })
      ) {
        return "bg-color_red text-white";
      }
    }
  }

  const dayStrings = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];

  // const weekDays = Array.from({ length: 7 }, (_, i) =>
  //   addDays(startOfWeek(start), i)
  // );

  const startDayOfWeek = getDay(start);

  function colorWeekend(index: number) {
    if (index === 0 || index === 6) {
      return "text-color_red";
    }
  }

  return (
    <div className="bg-color_bg_lightgray rounded-md overflow-hidden">
      <div className="bg-color_bg_darkgray px-4 py-[2.5px] text-white">
        <div className="flex justify-between items-center mb-1">
          <button onClick={handlePrevMonth} className="py-2 px-4 rounded">
            <MdKeyboardArrowLeft className="text-2xl" />
          </button>
          <h2 className=" font-sans text-center">
            {format(currentMonth, "MMMM yyyy")}
          </h2>
          <button onClick={handleNextMonth} className="bg-blue-500py-2 px-4">
            <MdKeyboardArrowRight className="text-2xl" />
          </button>
        </div>
        <div className="grid grid-cols-7 gap-2 mb-2 text-sm">
          {dayStrings.map((day, index) => (
            <div
              key={day}
              className={`flex items-center justify-center ${colorWeekend(
                index
              )}`}
            >
              {day}
            </div>
          ))}
        </div>
      </div>
      <div className="grid grid-cols-7 p-4">
        {Array.from({ length: startDayOfWeek }).map((_, index) => (
          <div key={index}></div>
        ))}
        {days.map((day) => (
          <div
            key={day}
            className={`mb-2 flex items-center justify-center relative h-8 text-sm ${getBookingClass(
              day
            )}`}
          >
            {format(day, "d")}
          </div>
        ))}
      </div>
    </div>
  );
}
