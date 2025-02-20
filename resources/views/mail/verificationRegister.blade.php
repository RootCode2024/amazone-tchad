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
              <h2 style="color: #2563eb; font-size: 24px;">🔹 Vérification de votre email</h2>
              <p style="color: #374151; font-size: 16px;">Bonjour <strong>{{ $user->name }}</strong>,</p>
              <p style="color: #4b5563;">Merci de vous être inscrit(e) sur <strong>{{ config('app.name') }}</strong>. Pour finaliser votre inscription, veuillez vérifier votre adresse email en cliquant sur le bouton ci-dessous :</p>
            </td>
          </tr>

          <!-- Bouton de vérification -->
          <tr>
            <td align="center" style="padding: 20px;">
              <a href="{{ $verificationUrl }}" style="background-color: #2563eb; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-size: 16px; display: inline-block;">Vérifier mon email</a>
            </td>
          </tr>

          <!-- Explication supplémentaire -->
          <tr>
            <td style="padding: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Si vous n’avez pas créé de compte, vous pouvez ignorer cet email en toute sécurité.</p>
              <p style="color: #4b5563; font-size: 14px;">Ce lien expirera dans <strong>24 heures</strong>. Si vous avez besoin d'un nouveau lien, veuillez en demander un depuis notre site.</p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding-top: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Besoin d'aide ? Contactez-nous au <strong>[Email de support]</strong>.</p>
              <p style="font-size: 14px; font-weight: bold; color: #1e40af;">{{ config('app.name') }}</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
