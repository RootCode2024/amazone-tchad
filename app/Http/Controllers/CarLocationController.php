<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarLocationRequestStore;
use App\Mail\ReservationMailSend;
use App\Mail\ReservationStatusChangeMail;
use App\Models\CarLocation;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CarLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carLocation = CarLocation::with(['customer', 'origin'])->get();
        return response()->json($carLocation);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarLocationRequestStore $request)
    {
        Log::info('Car Location Request Data: ', $request->all());
        try {
            $customer = Customer::where('email', $request->email)->first();
    
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }
    
            $carLocation = CarLocation::create([
                'customer_id' => $customer->id,
                'place_of_location' => $request->place_of_location,
                'started_date' => $request->started_date,
                'ended_date' => $request->ended_date,
                'age' => $request->age,
                'status' => 'pending',
            ]);
    
            // Envoi d'un e-mail à l'admin
            $admin = User::where('role', 'admin')->first();
            Mail::to($admin->email)->send(new ReservationMailSend($carLocation, 'LOCATION'));
    
            return response()->json(['message' => 'Réservation de voiture soumise avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'newStatus' => 'required|string|in:pending,approved,rejected',
            'note' => 'nullable|string',
            'startedDate' => 'required_if:newStatus,rejected|date',
            'price' => 'nullable',
        ]);

        $carLocation = CarLocation::findOrFail($id);

        if (!$carLocation) {
            return response()->json(['error' => 'Location de voiture non trouvée'], 404);
        }

        $carLocation->note = $request->note;
        $carLocation->started_date = $request->startedDate;
        $carLocation->price = $request->price;
        $carLocation->status = $request->newStatus;

        $carLocation->save();

        // Envoi d'un e-mail au client
        Mail::to($carLocation->customer->email)->send(new ReservationStatusChangeMail($carLocation, 'LOCATION'));

        return response()->json(['message' => 'Statut de la location mis à jour.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $carLocation = CarLocation::with(['customer', 'origin'])->findOrFail($id);
        return response()->json($carLocation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarLocation $carLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarLocation $carLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarLocation $carLocation)
    {
        //
    }
}
