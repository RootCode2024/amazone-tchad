<?php

namespace App\Http\Controllers;

use App\Mail\RegisterSendVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_token' => Str::random(60), // Vérifier que cette colonne existe dans la table users
            ]);
    
            
            $verificationUrl = url("/email/verify/{$user->verification_token}");
    
            Mail::to($user->email)->send(new RegisterSendVerificationMail($user, $verificationUrl));
    
            // Création du token d'API
            $token = $user->createToken('YourAppName')->plainTextToken;
    
            return response()->json([
                'message' => 'Utilisateur créé avec succès. Vérifiez votre email.',
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de l\'inscription.', 'details' => $e->getMessage()], 500);
        }
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        if (is_null($user->email_verified_at))
        {
            return response()->json(['message' => 'Email non vérifié'], 305);
        }

        // Crée un token d'API
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function verifyEmail($token)
    {
        // Recherche de l'utilisateur avec le token
        $user = User::where('verification_token', $token)->first();
    
        if ($user) {
            // Marquer l'email comme vérifié et supprimer le token
            $user->email_verified_at = now();
            $user->verification_token = null;
            $user->save();
    
            return redirect('/login')->with('status', 'Votre email a été vérifié avec succès.');
        }
    
        return redirect('/login')->with('error', 'Ce lien de vérification est invalide.');
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
    
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié'], 401);
            }
    
            $user->tokens()->delete();
    
            return response()->json(['message' => 'Déconnexion réussie']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la déconnexion', 'details' => $e->getMessage()], 500);
        }
    }
    
}

