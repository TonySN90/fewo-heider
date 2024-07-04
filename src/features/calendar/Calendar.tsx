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
  subDays,
  addDays,
} from "date-fns";
import { MdKeyboardArrowLeft, MdKeyboardArrowRight } from "react-icons/md";
import { TBookings } from "../../types/fewoTypes";

function BookingCalendar() {
  const { bookings } = useFewoContext();
  const sortedBookings = bookings.sort(
    (a, b) => new Date(b.startDate).getTime() - new Date(a.startDate).getTime()
  );

  return (
    <div className="w-full lg:w-1/3">
      <Calendar bookings={sortedBookings} />
    </div>
  );
}

export default BookingCalendar;

function getPreviousMonthDays(
  startDayOfWeek: number,
  startOfCurrentMonth: Date
) {
  const days = [];
  for (let i = 0; i < startDayOfWeek; i++) {
    days.unshift(subDays(startOfCurrentMonth, i + 1));
  }
  return days;
}

function getNextMonthDays(endDayOfWeek: number, endOfCurrentMonth: Date) {
  const days = [];
  for (let i = 0; i < 6 - endDayOfWeek; i++) {
    days.push(addDays(endOfCurrentMonth, i + 1));
  }
  return days;
}

function Calendar({ bookings }: { bookings: TBookings }) {
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
        return "text-white start-date";
      }
      if (isSameDay(day, booking.endDate)) {
        return "text-white end-date";
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
  const startDayOfWeek = getDay(start);
  const endDayOfWeek = getDay(end);

  const previousMonthDays = getPreviousMonthDays(startDayOfWeek, start);
  const nextMonthDays = getNextMonthDays(endDayOfWeek, end);

  function colorWeekend(index: number) {
    if (index === 0 || index === 6) {
      return "text-color_red";
    }
  }

  return (
    <div className="bg-color_bg_darkgray rounded-md overflow-hidden text-white">
      <div className="bg-color_bg_darkgray px-4 py-[2.5px]">
        <div className="flex justify-between items-center mb-1">
          <button onClick={handlePrevMonth} className="py-2 pr-4 rounded">
            <MdKeyboardArrowLeft className="text-2xl" />
          </button>
          <h2 className="font-sans text-center">
            {format(currentMonth, "MMMM yyyy")}
          </h2>
          <button onClick={handleNextMonth} className="py-2 pl-4">
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
        {previousMonthDays.map((day, index) => (
          <div
            key={`prev-${index}`}
            className={`mb-2 flex items-center justify-center relative h-8 text-sm text-gray-400 ${getBookingClass(
              day
            )}`}
          >
            {format(day, "d")}
          </div>
        ))}
        {days.map((day) => (
          <div
            key={day.toISOString()}
            className={`mb-2 flex items-center justify-center relative h-8 text-sm ${getBookingClass(
              day
            )}`}
          >
            {format(day, "d")}
          </div>
        ))}
        {nextMonthDays.map((day, index) => (
          <div
            key={`next-${index}`}
            className={`mb-2 flex items-center justify-center relative h-8 text-sm text-gray-400 ${getBookingClass(
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
