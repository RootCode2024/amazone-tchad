<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightHotelRequestStore;
use App\Mail\ReservationMailSend;
use App\Mail\ReservationStatusChangeMail;
use App\Models\Customer;
use App\Models\FlightHotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlightHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flights_hotels = FlightHotel::with(['customer', 'origin', 'destination'])->get();
        return response()->json($flights_hotels);
    }

    public function store(FlightHotelRequestStore $request)
    {
        Log::info('Flight+Hotel Request Data: ', $request->all());
        try {
            $customer = Customer::where('email', $request->email)->first();
    
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }
    
            $flighthotel = FlightHotel::create([
                'customer_id' => $customer->id,
                'flight_type' => $request->flight_type,
                'departure_city_id' => $request->departure_city_id,
                'destination_city_id' => $request->destination_city_id,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'passengers' => $request->passengers,
                'flight_class' => $request->flight_class,
                'number_of_room' => $request->number_of_room,
                'status' => 'pending',
            ]);
    
            // Envoi d'un e-mail à l'admin
            $admin = User::where('role', 'admin')->first();
            Mail::to($admin->email)->send(new ReservationMailSend($flighthotel, 'VOL + HOTEL'));
    
            return response()->json(['message' => 'Réservation de vol + hotel soumise avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }
    

    // Mettre à jour le statut d'un vol
    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'newStatus' => 'required|string|in:pending,approved,rejected',
            'note' => 'nullable|string',
            'departureDate' => 'required_if:newStatus,rejected|date',
            'price' => 'nullable',
        ]);

        $flighthotel = FlightHotel::findOrFail($id);

        if (!$flighthotel) {
            return response()->json(['error' => 'Vol + Hotel non trouvé'], 404);
        }

        $flighthotel->note = $request->note;
        $flighthotel->departure_date = $request->departureDate;
        $flighthotel->price = $request->price;
        $flighthotel->status = $request->newStatus;

        $flighthotel->save();

        // Envoi d'un e-mail au client
        Mail::to($flighthotel->customer->email)->send(new ReservationStatusChangeMail($flighthotel, 'VOL + HOTEL'));

        return response()->json(['message' => 'Statut du vol + hotel mis à jour']);
    }

    public function show($id)
    {
        $flightHotel = FlightHotel::with(['customer', 'origin', 'destination'])->findOrFail($id);
        return response()->json($flightHotel);
    }

    public function update(Request $request, $id)
    {
        $flight_hotel = FlightHotel::findOrFail($id);
        $flight_hotel->update($request->all());
        return response()->json($flight_hotel);
    }

    public function destroy($id)
    {
        FlightHotel::destroy($id);
        return response()->json(null, 204);
    }
}
