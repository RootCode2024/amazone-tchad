<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'city_id',
        'arrival_date',
        'return_date',
        'number_of_room',
        'note',
        'price',
        'status',
    ];

    public function country() {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
}
