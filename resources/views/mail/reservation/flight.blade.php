<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réservation - {{ strtoupper($type) }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .header img {
            max-width: 80px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }
        .details {
            padding: 20px;
            font-size: 16px;
            color: #333;
        }
        .details p {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .details p strong {
            width: 150px;
            display: inline-block;
        }
        .icon {
            width: 18px;
            margin-right: 10px;
        }
        .button {
            text-align: center;
            margin: 20px 0;
        }
        .button a {
            background: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }
        .button a:hover {
            background: #0056b3;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 15px;
            background: #f8f8f8;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRfLBpmdBKyB-xVj0Pf_BsXjWBUQWe8CEujg&s" alt="Logo">
            <p class="title">Nouvelle réservation ({{ strtoupper($type) }})</p>
        </div>

        <div class="details">
            <p><img src="https://img.icons8.com/ios/50/user.png" class="icon"><strong>Nom du client :</strong> {{ $reservation->customer->name }}</p>
            <p><img src="https://img.icons8.com/ios/50/email.png" class="icon"><strong>Email :</strong> {{ $reservation->customer->email }}</p>
            <p><img src="https://img.icons8.com/ios/50/phone.png" class="icon"><strong>Téléphone :</strong> {{ $reservation->customer->phone }}</p>

            <p><img src="https://img.icons8.com/ios/50/airport.png" class="icon"><strong>Origine :</strong> {{ $reservation->origin->name }}</p>
            <p><img src="https://img.icons8.com/ios/50/airplane.png" class="icon"><strong>Destination :</strong> {{ $reservation->destination->name }}</p>
            <p><img src="https://img.icons8.com/ios/50/calendar.png" class="icon"><strong>Date de départ :</strong> {{ $reservation->departure_date }}</p>
            <p><img src="https://img.icons8.com/ios/50/calendar.png" class="icon"><strong>Date de retour :</strong> {{ $reservation->return_date ?? 'Non applicable' }}</p>
            <p><img src="https://img.icons8.com/ios/50/user-group-man-man.png" class="icon"><strong>Passagers :</strong> {{ $reservation->passengers }}</p>
            <p><img src="https://img.icons8.com/ios/50/classroom.png" class="icon"><strong>Classe :</strong>
                @if ($reservation->flight_class === 'economy')
                    Économie
                @elseif ($reservation->flight_class === 'business')
                    Affaire
                @else
                    Première
                @endif
            </p>
        </div>

        <div class="button">
            <a href="{{ url('/bookings') }}">Voir la réservation</a>
        </div>

        <div class="footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>

</body>
</html>
