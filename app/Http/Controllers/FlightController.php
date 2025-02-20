<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightRequestStore;
use App\Mail\ReservationMailSend;
use App\Mail\ReservationStatusChangeMail;
use App\Models\Customer;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlightController extends Controller
{
    public function index()
    {
        $flights = Flight::with(['customer', 'origin', 'destination'])->get();
        return response()->json($flights);
    }

    public function store(FlightRequestStore $request)
    {
        Log::info('Flight Request Data: ', $request->all());
        try {
            $customer = Customer::where('email', $request->email)->first();
    
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }
    
            $flight = Flight::create([
                'customer_id' => $customer->id,
                'flight_type' => $request->flight_type,
                'departure_city_id' => $request->departure_city_id,
                'destination_city_id' => $request->destination_city_id,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'passengers' => $request->passengers,
                'flight_class' => $request->flight_class,
                'status' => 'pending',
            ]);
    
            // Envoi d'un e-mail à l'admin
            $admin = User::where('role', 'admin')->first();
            Mail::to($admin->email)->send(new ReservationMailSend($flight, 'VOL'));
    
            return response()->json(['message' => 'Réservation de vol soumise avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }
    
    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'newStatus' => 'required|string|in:pending,approved,rejected',
            'note' => 'nullable|string',
            'departureDate' => 'required_if:newStatus,rejected|date',
            'price' => 'nullable',
        ]);

        $flight = Flight::findOrFail($id);

        if (!$flight) {
            return response()->json(['error' => 'Vol non trouvé'], 404);
        }

        
        $flight->note = $request->note;
        $flight->departure_date = $request->departureDate;
        $flight->price = $request->price;
        $flight->status = $request->newStatus;

        $flight->save();

        // Envoi d'un e-mail au client
        Mail::to($flight->customer->email)->send(new ReservationStatusChangeMail($flight, 'VOL'));

        return response()->json(['message' => 'Statut du vol mis à jour']);
    }

    public function show($id)
    {
        $flight = Flight::with(['customer', 'origin', 'destination'])->findOrFail($id);
        return response()->json($flight);
    }

    public function update(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);
        $flight->update($request->all());
        return response()->json($flight);
    }

    public function destroy($id)
    {
        Flight::destroy($id);
        return response()->json(null, 204);
    }
}

