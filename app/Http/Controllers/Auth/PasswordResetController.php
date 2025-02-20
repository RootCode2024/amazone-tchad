<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Envoie un lien de réinitialisation au client
    public function sendResetLink(Request $request)
    {
        try {
            // Validation de l'email
            $request->validate(['email' => 'required|email']);
    
            // Vérification de l'existence de l'email dans la base de données
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                return response()->json([
                    'message' => 'Aucun utilisateur trouvé avec cet email.'
                ], 400);
            }
    
            // Génération du token de réinitialisation
            $token = Password::createToken($user);
    
            // Envoi de l'email avec le lien de réinitialisation
            Mail::to($user->email)->send(new ResetPasswordMail($user->email, $token));
    
            return response()->json([
                'message' => 'Un lien de réinitialisation a été envoyé à votre email.'
            ], 200);
        } catch (\Exception $e) {
            // Log de l'exception pour diagnostiquer l'erreur
            Log::error('Erreur lors de l\'envoi du lien de réinitialisation : ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.'
            ], 500);
        }
    }
    

    // Affiche le formulaire de réinitialisation de mot de passe
    public function showResetForm($token)
    {
        return response()->json([
            'token' => $token
        ]);
    }

    // Réinitialise le mot de passe
    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();

        $password = Hash::make($request->password);

        $user->password = $password;
        $response = $user->save();
    
        // Vérifier la réponse et renvoyer un JSON
        if ($response) {
            return response()->json([
                'message' => 'Votre mot de passe a été réinitialisé avec succès.',
                'status' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Erreur lors de la réinitialisation du mot de passe. Veuillez vérifier vos informations.',
                'status' => 'error'
            ], 400); 
        }
    }
    
    
}
