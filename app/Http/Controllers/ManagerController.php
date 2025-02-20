<?php

namespace App\Http\Controllers;

use App\Mail\RegisterManagerVerificationMail;
use App\Models\CarLocation;
use App\Models\Customer;
use App\Models\Flight;
use App\Models\FlightHotel;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ManagerController extends Controller
{
    
    public function index()
    {
        $managers = User::all();

        return response()->json($managers);
    }

    public function show(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Aucun manager trouvé.'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'address' => 'nullable|string',
        ]);

        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['message' => 'Aucun manager trouvé.'], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return response()->json(['message' => 'Information mise à jour avec succès !']);
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['message' => 'Aucun manager trouvé.'], 404);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Le mot de passe actuel est incorrect.'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Mot de passe mis à jour avec succès !']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'address' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $password = Str::random(8);
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address ?? '',
                'password' => Hash::make($password),
                'verification_token' => Str::random(60),
            ]);
    
            
            $verificationUrl = url("/email/verify/{$user->verification_token}");
    
            Mail::to($user->email)->send(new RegisterManagerVerificationMail($user, $verificationUrl));
    
            // Création du token d'API
            $token = $user->createToken('YourAppName')->plainTextToken;
    
            return response()->json([
                'message' => 'Manager créé avec succès et un email de validation a été transféré.',
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de l\'inscription.', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        $user = User::find($id)->where('role', '!=', 'admin')->first();

        
        if (!$user) {
            return response()->json(['message' => 'Aucun manager trouvé.'], 404);
        }
        
        $user->delete();

        return response()->json(['message' => 'Manager supprimé avec succès !'], 200);
    }

    public function dashboard()
    {
        try {
            $flights = Flight::count();
            $hotels = Hotel::count();
            $flighthotels = FlightHotel::count();
            $carlocations = CarLocation::count();
            
            $reservations = $flights + $hotels + $flighthotels + $carlocations;
            $customers = Customer::count();
            $managers = User::count();
            
            return response()->json(['reservations' => $reservations, 'customers' => $customers, 'managers' => $managers]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
}
