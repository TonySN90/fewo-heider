export interface IFewoContextTypes {
  loadingStatus: boolean;
  bookings: { startDate: string; endDate: string }[];
}

export type TBookings = { startDate: string; endDate: string }[];
