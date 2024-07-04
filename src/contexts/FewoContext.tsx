import { createContext, useContext, useEffect, useState } from "react";
import supabase from "../utils/supabase";
import { IFewoContextTypes } from "../types/fewoTypes";

const FewoContext = createContext<IFewoContextTypes | undefined>(undefined);

function FewoProvider({ children }: { children: React.ReactNode }) {
  const [loadingStatus, setLoadingStatus] = useState(false);
  const [bookings, setBookings] = useState<
    { startDate: string; endDate: string }[]
  >([]);

  useEffect(() => {
    async function fetchData() {
      try {
        setLoadingStatus(true);
        const { data: fetchedBookings } = await supabase
          .from("bookings")
          .select();

        const filteredDates: { startDate: string; endDate: string }[] =
          fetchedBookings?.map((booking) => ({
            startDate: booking.startDate || "",
            endDate: booking.endDate || "",
          })) || [];

        setBookings(filteredDates);
      } catch (error) {
        console.error(error);
      } finally {
        setLoadingStatus(false);
      }
      setLoadingStatus;
    }

    fetchData();
  }, []);

  return (
    <FewoContext.Provider value={{ bookings, loadingStatus }}>
      {children}
    </FewoContext.Provider>
  );
}

function useFewoContext() {
  const context = useContext(FewoContext);
  if (context === undefined) {
    throw new Error("useFewoContext must be used within a FewoProvider");
  }
  return context;
}

export { FewoProvider, useFewoContext };
