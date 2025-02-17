<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'flight_type',
        'departure_city_id',
        'destination_city_id',
        'departure_date',
        'return_date',
        'passengers',
        'flight_class',
        'note',
        'price',
        'status',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function origin()
    {
        return $this->belongsTo(City::class, 'departure_city_id');
    }
    
    public function destination()
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }
    
}
