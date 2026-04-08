<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::orderBy('from')
            ->get()
            ->map(fn ($b) => [
                'from' => $b->from->toDateString(),
                'to' => $b->to->toDateString(),
            ]);
    }
}
