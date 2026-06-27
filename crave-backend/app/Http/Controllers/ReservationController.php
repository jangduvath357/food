<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // MAKE RESERVATION
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string',
            'phone'  => 'required|string',
            'date'   => 'required|date',
            'time'   => 'required',
            'guests' => 'required|integer|min:1'
        ]);

        $reservation = Reservation::create([
            'user_id' => $request->user()->id ?? null,
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'date'    => $request->date,
            'time'    => $request->time,
            'guests'  => $request->guests,
            'note'    => $request->note,
            'status'  => 'pending'
        ]);

        return response()->json([
            'message'     => 'Reservation made successfully',
            'reservation' => $reservation
        ], 201);
    }

    // GET USER RESERVATIONS
    public function userReservations(Request $request)
    {
        $reservations = Reservation::where('user_id', $request->user()->id)
                                   ->latest()
                                   ->get();
        return response()->json($reservations);
    }

    // GET ALL RESERVATIONS (admin)
    public function index()
    {
        $reservations = Reservation::with('user')->latest()->get();
        return response()->json($reservations);
    }

    // UPDATE STATUS (admin)
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);
        return response()->json([
            'message'     => 'Reservation status updated',
            'reservation' => $reservation
        ]);
    }
}