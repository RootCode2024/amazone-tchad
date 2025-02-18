<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelRequestStore;
use App\Mail\ReservationMailSend;
use App\Mail\ReservationStatusChangeMail;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::with(['customer', 'country'])->get();
        return response()->json($hotels);
    }

    public function store(HotelRequestStore $request)
    {
        Log::info('Hotel Request Data: ', $request->all());
        
        try {
            $customer = Customer::where('email', $request->email)->first();
    
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }

            $hotel = Hotel::create([
                'customer_id' => $customer->id,
                'city_id' => $request->city_id,
                'arrival_date' => $request->arrival_date,
                'return_date' => $request->return_date,
                'number_of_room' => $request->number_of_room,
                'status' => 'pending',
            ]);

        // Envoi d'un e-mail à l'admin
        $admin = User::where('role', 'admin')->first();
        Mail::to($admin->email)->send(new ReservationMailSend($hotel, 'HOTEL'));

        return response()->json(['message' => 'Réservation de l\'hotel soumise avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }

    // Mettre à jour le statut d'un hotel
    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'newStatus' => 'required|string|in:pending,approved,rejected',
            'note' => 'nullable|string',
            'arrivalDate' => 'required_if:newStatus,rejected|date',
            'price' => 'required_if:newStatus,rejected|numeric',
        ]);

        $hotel = Hotel::findOrFail($id);

        if (!$hotel) {
            return response()->json(['error' => 'Reservation hotel non trouvé'], 404);
        }

        if ($request->newStatus === 'rejected')
        {
            $hotel->note = $request->note;
            $hotel->arrival_date = $request->arrivalDate;
            $hotel->price = $request->price;
        }

        $hotel->status = $request->newStatus;

        $hotel->save();

        // Envoi d'un e-mail au client
        Mail::to($hotel->customer->email)->send(new ReservationStatusChangeMail($hotel, 'VOL'));

        return response()->json(['message' => 'Statut du vol mis à jour']);
    }

    public function show($id)
    {
        $hotel = Hotel::with(['customer', 'country'])->findOrFail($id);
        return response()->json($hotel);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->update($request->all());
        return response()->json($hotel);
    }

    public function destroy($id)
    {
        Hotel::destroy($id);
        return response()->json(null, 204);
    }
}
