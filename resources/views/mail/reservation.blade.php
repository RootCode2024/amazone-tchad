<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .content {
            padding: 20px;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .highlight {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Nouvelle réservation reçue</div>

        <div class="content">
            <p>Bonjour,</p>
            <p>Une nouvelle réservation a été effectuée. Voici les détails :</p>

            <h3>Informations du client :</h3>
            <ul>
                <li><span class="highlight">ID :</span> {{ $reservation->customer->id }}</li>
                <li><span class="highlight">Nom :</span> {{ $reservation->customer->name }}</li>
                <li><span class="highlight">Email :</span> {{ $reservation->customer->email }}</li>
                <li><span class="highlight">Téléphone :</span> {{ $reservation->customer->phone }}</li>
            </ul>

            <h3>Détails du vol :</h3>
            <ul>
                <li><span class="highlight">Type de vol :</span>
                    @if ($reservation['flight_type'] === 'round_trip')
                        Aller Retour
                    @elseif ($reservation['flight_type'] === 'one_way')
                        Aller Simple
                    @else
                        Multi Destination
                    @endif
                </li>
                <li><span class="highlight">Origine :</span>
                    {{ \App\Models\City::find($reservation['departure_city_id'])->name }}</li>
                <li><span class="highlight">Destination :</span>
                    {{ \App\Models\City::find($reservation['destination_city_id'])->name }}</li>

                <li><span class="highlight">Date de départ :</span>
                    {{ \Carbon\Carbon::parse($reservation['departure_date'])->format('d F Y') }}</li>

                @if (!empty($reservation['return_date']))
                    <li><span class="highlight">Date de retour :</span>
                        {{ \Carbon\Carbon::parse($reservation['return_date'])->format('d F Y') }}</li>
                @endif

                <li><span class="highlight">Nombre de Passagers :</span> {{ $reservation['passengers'] }}</li>
                <li><span class="highlight">Classe :</span>
                    {{ ucfirst(str_replace('_', ' ', $reservation['flight_class'])) }}
                    @if ($reservation['flight_class'] === 'economy')
                        Economique
                    @elseif ($reservation['flight_class'] === 'business')
                        Affaires
                    @else
                        Première Classe
                    @endif
                </li>
            </ul>

            <a href="" class="text-indigo-600 underline">Se Connecter</a>

            <p>
                Merci de vérifier et traiter cette réservation dans les plus brefs délais.
            </p>

        </div>

        <div class="footer">
            <p>Cet email est généré automatiquement par {{ config('app.name') }}.</p>
        </div>
    </div>
</body>

</html>
