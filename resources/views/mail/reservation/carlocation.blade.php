<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background-color: #f3f4f6; font-family: Arial, sans-serif; padding: 20px;">
    <div>
        <img src="{{ asset('Assets/Images/background.jpg') }}" alt="" class="h-24">
    </div>
    <table align="center" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td align="center">
            <table width="600" style="background-color: #ffffff; padding: 20px; border-radius: 10px;">
            <tr>
                <td align="center" style="padding-bottom: 20px;">
                <h2 style="color: #1e40af; font-size: 24px;">Nouvelle réservation de voiture</h2>
                <p style="color: #374151; font-size: 16px;">Bonjour <strong>Admin</strong>,</p>
                <p style="color: #4b5563;">Une nouvelle réservation de voiture a été soumise sur <strong>Amazone Tchad</strong>. Voici les détails :</p>
                </td>
            </tr>

            <tr>
                <td style="padding: 20px; background-color: #eff6ff; border-radius: 8px;">
                <table width="100%">
                    <tr>
                    <td style="padding: 10px 0;"><strong>Numéro de réservation :</strong></td>
                    <td style="padding: 10px 0; color: #1e40af;">CAR{{ $reservation->id }}</td>
                    </tr>
                    <tr>
                    <td style="padding: 10px 0;"><strong>Client :</strong></td>
                    <td style="padding: 10px 0;">{{ $reservation->customer->name }}</td>
                    </tr>
                    <tr>
                    <td style="padding: 10px 0;"><strong>Lieu de location :</strong></td>
                    <td style="padding: 10px 0;">{{ $reservation->origin->name }}</td>
                    </tr>
                    <tr>
                    <td style="padding: 10px 0;"><strong>Date de début & de fin :</strong></td>
                    <td style="padding: 10px 0;">{{ $reservation->started_date }} - {{ $reservation->ended_date }}</td>
                    </tr>
                    <tr>
                    <td style="padding: 10px 0;"><strong>Age :</strong></td>
                    <td style="padding: 10px 0;">{{ $reservation->age }} ans</td>
                    </tr>
                </table>
                </td>
            </tr>

            <tr>
                <td align="center" style="padding-top: 20px;">
                <p style="color: #4b5563;">Connectez-vous à votre tableau de bord pour traiter cette réservation.</p>
                <a href="{{ config('app.url') }}/dashboard" style="background-color: #1e40af; color: #ffffff; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">Accéder au tableau de bord</a>
                </td>
            </tr>
            </table>
        </td>
        </tr>
    </table>
</body>
</html>
