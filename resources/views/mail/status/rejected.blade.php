<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color: #f3f4f6; font-family: Arial, sans-serif; padding: 20px;">
  <table align="center" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">
        <table width="600" style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
          
          <!-- En-tête -->
          <tr>
            <td align="center" style="padding-bottom: 20px;">
              <h2 style="color: #b91c1c; font-size: 24px;">⚠️ Changement de statut ({{ $type }})</h2>
              <p style="color: #374151; font-size: 16px;">Bonjour <strong>{{ $reservation->customer->name }}</strong>,</p>
              <p style="color: #4b5563;">Nous vous informons que le statut de votre demande a changé.</p>
            </td>
          </tr>

          <!-- Détails -->
          <tr>
            <td style="padding: 20px; background-color: #fef2f2; border-radius: 8px;">
              <table width="100%">
                <tr>
                  <td style="padding: 10px 0;"><strong>Numéro de la demande :</strong></td>
                  <td style="padding: 10px 0; color: #b91c1c;">{{ $type }}{{ $reservation->id }}</td>
                </tr>
                <tr>
                  <td style="padding: 10px 0;"><strong>Statut précédent :</strong></td>
                  <td style="padding: 10px 0;">🟡 En attente</td>
                </tr>
                <tr>
                  <td style="padding: 10px 0;"><strong>Nouveau statut :</strong></td>
                  <td style="padding: 10px 0; color: #b91c1c;">🔴 Rejeté</td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Explication -->
          <tr>
            <td style="padding: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Malheureusement, après examen, nous n'avons pas pu approuver votre demande pour la raison suivante :</p>
              <p style="color: #b91c1c; font-size: 16px; font-weight: bold;">{{ $reservation->note }}</p>
              <hr>
              @if ($type === 'VOL' || $type === 'VOL + HOTEL')
                <p class="text-blue-600 text-lg font-thin">
                  Date de départ trouvée : {{ \Carbon\Carbon::parse($reservation->departure_date)->locale('fr')->format('d F Y') }}
                </p>
                @elseif ($type === 'HOTEL')
                <p class="text-blue-600 text-lg font-thin">
                  Date de d'arrivée : {{ \Carbon\Carbon::parse($reservation->arrival_date)->locale('fr')->format('d F Y') }}
                </p>            
                <p class="text-blue-600 text-lg font-thin">
                  Date de retour : {{ \Carbon\Carbon::parse($reservation->return_date)->locale('fr')->format('d F Y') }}
                </p>
                @elseif ($type === 'LOCATION') 
                <p class="text-blue-600 text-lg font-thin">
                  Date de début : {{ \Carbon\Carbon::parse($reservation->started_date)->locale('fr')->format('d F Y') }}
                </p>            
                <p class="text-blue-600 text-lg font-thin">
                  Date de fin : {{ \Carbon\Carbon::parse($reservation->ended_date)->locale('fr')->format('d F Y') }}
                </p>
              @endif    
              <p style="color: blue; font-size: 16px; font-weight: bold;">Prix : {{ number_format($reservation->price, 0, ',', ' ') }} F CFA</p>
              
              <p style="color: #4b5563; font-size: 14px;">Si vous souhaitez obtenir plus d’informations ou contester cette décision, vous pouvez nous contacter via le lien ci-dessous.</p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding-top: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Besoin d'aide ? Contactez-nous au <strong>{{ env("APP_PHONE") }}</strong>.</p>
              <p style="font-size: 14px; color: #6b7280;">Merci pour votre confiance !</p>
              <p style="font-size: 14px; font-weight: bold; color: #1e40af;">{{ env("APP_NAME") }}</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
