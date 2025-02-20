<!DOCTYPE html>
<html>
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
              <h2 style="color: #d97706; font-size: 24px;">🔄 Mise en attente de votre demande</h2>
              <p style="color: #374151; font-size: 16px;">Bonjour <strong>{{ env('APP_NAME') }}</strong>,</p>
              <p style="color: #4b5563;">Nous vous informons que le statut de votre demande a été mis en attente.</p>
            </td>
          </tr>

          <!-- Détails -->
          <tr>
            <td style="padding: 20px; background-color: #fef3c7; border-radius: 8px;">
              <table width="100%">
                <tr>
                  <td style="padding: 10px 0;"><strong>Numéro de la demande :</strong></td>
                  <td style="padding: 10px 0; color: #d97706;">{{ $type }}{{ $reservation->id }}</td>
                </tr>
                <tr>
                  <td style="padding: 10px 0;"><strong>Statut précédent :</strong></td>
                  <td style="padding: 10px 0;">(Approuvé/Rejeté)</td>
                </tr>
                <tr>
                  <td style="padding: 10px 0;"><strong>Nouveau statut :</strong></td>
                  <td style="padding: 10px 0; color: #d97706;">🟡 En attente</td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Explication -->
          <tr>
            <td style="padding: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Votre demande est actuellement en cours de réévaluation pour les raisons suivantes :</p>
              <p style="color: black; font-size: 16px; font-weight: bold;">{{ $reservation->note }}</p><p class="text-blue-600 text-lg font-thin">
                Date de départ trouvée : {{ \Carbon\Carbon::parse($reservation->departure_date)->format('d F Y') }}
              </p>            
              <p style="color: blue; font-size: 16px; font-weight: bold;">Prix : {{ number_format($reservation->price, 0, ',', ' ') }} F CFA</p>
              <p style="color: #4b5563; font-size: 14px;">Nous vous informerons dès qu'une décision finale aura été prise.</p>
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
