<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #f3f4f6; font-family: Arial, sans-serif; padding: 20px;">
  <div>
    <img src="{{ asset('Assets/Images/background.jpg') }}" alt="" class="h-24">
  </div>
  <table align="center" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">
        <table width="600" style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
          
          <!-- En-tête -->
          <tr>
            <td align="center" style="padding-bottom: 20px;">
              <h2 style="color: #dc2626; font-size: 24px;">🔐 Réinitialisation de votre mot de passe</h2>
              <p style="color: #374151; font-size: 16px;">Bonjour <strong>[Nom du destinataire]</strong>,</p>
              <p style="color: #4b5563;">Nous avons reçu une demande de réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe.</p>
            </td>
          </tr>

          <!-- Bouton de réinitialisation -->
          <tr>
            <td align="center" style="padding: 20px;">
              <a href="[Lien de réinitialisation]" style="background-color: #dc2626; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-size: 16px; display: inline-block;">Réinitialiser mon mot de passe</a>
            </td>
          </tr>

          <!-- Explication supplémentaire -->
          <tr>
            <td style="padding: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Ce lien est valide pendant <strong>30 minutes</strong>. Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
              <p style="color: #4b5563; font-size: 14px;">Pour assurer la sécurité de votre compte, ne partagez jamais votre mot de passe avec qui que ce soit.</p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding-top: 20px;">
              <p style="color: #4b5563; font-size: 14px;">Besoin d'aide ? Contactez-nous à <strong>[Email de support]</strong>.</p>
              <p style="font-size: 14px; font-weight: bold; color: #1e40af;">[Nom de l'entreprise]</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
