<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::orderBy('from')->get();

        return view('admin.bookings', compact('bookings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from'       => ['required', 'date'],
            'to'         => ['required', 'date', 'after_or_equal:from'],
            'guest_name' => ['nullable', 'string', 'max:100'],
        ]);

        Booking::create($data);

        return back()->with('success', 'Buchung wurde gespeichert.');
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'from'       => ['required', 'date'],
            'to'         => ['required', 'date', 'after_or_equal:from'],
            'guest_name' => ['nullable', 'string', 'max:100'],
        ]);

        $booking->update($data);

        return back()->with('success', 'Buchung wurde aktualisiert.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return back()->with('success', 'Buchung wurde gelöscht.');
    }
}
