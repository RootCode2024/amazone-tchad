<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'place_of_location',
        'started_date',
        'ended_date',
        'age',
        'note',
        'price',
        'status',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function origin()
    {
        return $this->belongsTo(City::class, 'place_of_location');
    }
}
