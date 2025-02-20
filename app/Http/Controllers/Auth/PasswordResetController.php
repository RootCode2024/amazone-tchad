<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Envoie un lien de réinitialisation au client
    public function sendResetLink(Request $request)
    {
        // Validation de l'email
        $request->validate(['email' => 'required|email']);

        // Envoi du lien de réinitialisation
        $response = Password::sendResetLink(
            $request->only('email')
        );

        // Retour de la réponse selon le résultat
        if ($response == Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Un lien de réinitialisation a été envoyé à votre email.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Erreur lors de l\'envoi du lien. Assurez-vous que l\'email existe.'
            ], 400);
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
        // Validation des données du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);
    
        // Réinitialisation du mot de passe
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();
            }
        );
    
        // Réponse de succès ou d'échec
        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->json([
                    'message' => 'Votre mot de passe a été réinitialisé avec succès.'
                ], 200);
    
            case Password::INVALID_TOKEN:
                return response()->json([
                    'message' => 'Le token de réinitialisation est invalide ou expiré.'
                ], 400);
    
            case Password::INVALID_USER:
                return response()->json([
                    'message' => 'Aucun utilisateur trouvé avec cet email.'
                ], 400);
    
            default:
                return response()->json([
                    'message' => 'Une erreur s\'est produite lors de la réinitialisation du mot de passe.'
                ], 400);
        }
    }
    
}
