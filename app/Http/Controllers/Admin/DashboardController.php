<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Season;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $year  = $today->year;
        $month = $today->month;

        $bookingsThisYear  = Booking::whereYear('from', $year)->count();
        $bookingsThisMonth = Booking::whereYear('from', $year)->whereMonth('from', $month)->count();

        $nextBooking = Booking::where('from', '>=', $today)
            ->orderBy('from')
            ->first();

        $daysInMonth   = $today->daysInMonth;
        $bookedDays    = $this->bookedDaysInMonth($today);
        $occupancyRate = $daysInMonth > 0
            ? round(($bookedDays / $daysInMonth) * 100)
            : 0;

        $upcomingBookings = Booking::where('from', '>=', $today)
            ->orderBy('from')
            ->limit(5)
            ->get();

        $activeSeason = Season::where('is_active', true)->with(['prices' => fn ($q) => $q->orderBy('sort_order')->orderBy('from')])->first();
        $seasons = $activeSeason !== null ? $activeSeason->prices : collect();

        return view('admin.dashboard', compact(
            'bookingsThisYear',
            'bookingsThisMonth',
            'nextBooking',
            'occupancyRate',
            'daysInMonth',
            'bookedDays',
            'upcomingBookings',
            'seasons',
        ));
    }

    private function bookedDaysInMonth(Carbon $today): int
    {
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd   = $today->copy()->endOfMonth();

        $bookings = Booking::where('to', '>=', $monthStart)
            ->where('from', '<=', $monthEnd)
            ->get(['from', 'to']);

        $days = [];
        foreach ($bookings as $booking) {
            $cursor = $booking->from->max($monthStart)->copy();
            $end    = $booking->to->min($monthEnd);
            while ($cursor->lte($end)) {
                $days[$cursor->format('Y-m-d')] = true;
                $cursor->addDay();
            }
        }

        return count($days);
    }
}