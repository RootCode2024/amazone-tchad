<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Relation avec les vols
    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    // Relation avec les hôtels
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    // Relation avec les réservations Vol + Hôtel
    public function flightHotels()
    {
        return $this->hasMany(FlightHotel::class);
    }

    // Relation avec les locations de voitures
    public function carLocations()
    {
        return $this->hasMany(CarLocation::class);
    }
}
