<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Bonjour {{ $user->name }},</h2>
    <p>Merci de vous être inscrit. Veuillez cliquer sur le lien ci-dessous pour vérifier votre adresse email :</p>
    <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 10px 20px; color: white; background-color: blue; text-decoration: none; border-radius: 5px;">
        Vérifier mon email
    </a>
    <p>Si vous n'avez pas demandé cette vérification, ignorez cet email.</p>
</body>
</html>

