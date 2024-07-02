import { Calendar } from "@demark-pro/react-booking-calendar";

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
  startOfWeek,
  endOfWeek,
  eachDayOfWeek,
  addDays,
  getDay,
} from "date-fns";
import { IoChevronForwardSharp } from "react-icons/io5";
import { MdKeyboardArrowLeft, MdKeyboardArrowRight } from "react-icons/md";

function BookingCalendar() {
  // const { bookings } = useFewoContext();

  // console.log(bookings);

  const bookings2 = [
    {
      startDate: new Date("2024-07-01T15:00:00.000Z"),
      endDate: new Date("2024-07-06T10:00:00.000Z"), // Adjusted to include the end of the day
    },
    {
      startDate: new Date("2024-07-07T15:00:00.000Z"), // Start of the next day
      endDate: new Date("2024-07-10T10:00:00.000Z"),
    },
  ];

  const bookings = [
    { start: new Date(2024, 6, 19), end: new Date(2024, 6, 25) },
    { start: new Date(2024, 6, 10), end: new Date(2024, 6, 16) },
    { start: new Date(2024, 6, 8), end: new Date(2024, 6, 10) },
    { start: new Date(2024, 6, 1), end: new Date(2024, 6, 7) },
  ];

  return (
    <div className="w-[100%] sm:w-[60%] md:w-[50%] lg:w-[30%]">
      <Calendar
        reserved={bookings2}
        range={false}
        protection={false}
        options={{ locale: "de", weekStartsOn: 0, useAttributes: true }}
      />

      <Calendarr bookings={bookings} />
    </div>
  );
}

export default BookingCalendar;

function Calendarr({ bookings }) {
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

  // const getBookingClass = (day) => {
  //   for (let booking of bookings) {
  //     if (isWithinInterval(day, { start: booking.start, end: booking.end })) {
  //       if (isSameDay(day, booking.start)) {
  //         return "bg-red-500 text-white half-start";
  //       }
  //       if (isSameDay(day, booking.end)) {
  //         return "bg-red-500 text-white half-end";
  //       }
  //       return "bg-red-500 text-white";
  //     }
  //   }
  //   return "";
  // };

  // function getBookingClass(day) {
  //   for (const booking of bookings) {
  //     if (day.getDate() == booking.start.getDate())
  //       return "bg-red-500 text-white start-date";
  //     if (day.getDate() == booking.end.getDate())
  //       return "bg-red-500 text-white end-date";

  //     // if (isWithinInterval(day, { start: booking.start, end: booking.end })) {
  //     //   return "bg-red-500 text-white";
  //     // }
  //   }
  // }

  function getBookingClass(day) {
    let classes = [];

    for (const booking of bookings) {
      if (booking.start.getDate() === day.getDate()) {
        for (const booking of bookings) {
          if (booking.end.getDate() === day.getDate()) {
            classes.push("bg-red-500 text-white date-both");
          }
        }
        // return "bg-red-500 text-white";
        classes.push("bg-red-500 text-white end-date");
      }
      if (booking.end.getDate() === day.getDate()) {
        // console.log(day, "end");
        // return "bg-red-500 text-white";
        classes.push("bg-red-500 text-white start-date");
      }

      // if (
      //   booking.end.getDate() === day.getDate() &&
      //   booking.start.getDate() === day.getDate()
      // ) {
      //   console.log("both");
      // }
      // if (day >= booking.start && day <= booking.end) {
      //   classes.push("bg-red-500 text-white fuck");
      // }

      // if (
      //   day.getFullYear() === booking.start.getFullYear() &&
      //   day.getMonth() === booking.start.getMonth() &&
      //   day.getDate() === booking.start.getDate()
      // ) {
      //   classes.push("bg-red-500 text-white start-date");
      // }
      // if (
      //   day.getFullYear() === booking.end.getFullYear() &&
      //   day.getMonth() === booking.end.getMonth() &&
      //   day.getDate() === booking.end.getDate()
      // ) {
      //   classes.push("bg-red-500 text-white end-date");
      // }

      // if (day >= booking.start && day <= booking.end) {
      //   classes.push("bg-red-500 text-white");
      // }
    }

    return classes.join(" ");
  }

  const weekDays = Array.from({ length: 7 }, (_, i) =>
    addDays(startOfWeek(start), i)
  );
  const startDayOfWeek = getDay(start);

  return (
    <div className="bg-white p-4">
      <div className="flex justify-between items-center">
        <button onClick={handlePrevMonth} className="py-2 px-4 rounded">
          <MdKeyboardArrowLeft className="text-slate-800 text-3xl" />
        </button>
        <h2 className="font-bold font-sans mb-4 text-center">
          {format(currentMonth, "MMMM yyyy")}
        </h2>
        <button onClick={handleNextMonth} className="bg-blue-500py-2 px-4">
          <MdKeyboardArrowRight className="text-slate-800 text-3xl" />
        </button>
      </div>
      <div className="grid grid-cols-7 gap-2 mb-2">
        {weekDays.map((day) => (
          <div
            key={day}
            className="w-8 h-8 flex items-center justify-center font-semibold"
          >
            {format(day, "EEE")}
          </div>
        ))}
      </div>
      <div className="grid grid-cols-7">
        {Array.from({ length: startDayOfWeek }).map((_, index) => (
          <div key={index} className="w-8 h-8"></div>
        ))}
        {days.map((day) => (
          <div
            key={day}
            className={`h-8 mb-2 flex items-center justify-center border-gray-300 relative ${getBookingClass(
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
