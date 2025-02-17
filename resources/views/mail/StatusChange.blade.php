<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            font-size: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #666;
        }
        .status {
            font-weight: bold;
            color: #28a745;
        }
        .status.rejected {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            ✈️ Mise à jour de votre réservation
        </div>
        <div class="content">
            <p>Bonjour {{ $reservation->customer->name }},</p>
            <p>Votre réservation pour <strong>{{ $type }}</strong> a été mise à jour.</p>
            <p>Le nouveau statut est : 
                <span class="status {{ $reservation->status == 'rejected' ? 'rejected' : '' }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </p>
            <p>Merci pour votre confiance.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Amazone Tchad | Support Client
        </div>
    </div>
</body>
</html>
