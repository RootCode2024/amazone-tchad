<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlightHotelRequestStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'flight_type' => 'required|in:one_way,round_trip,multi_destination',
            'departure_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date',
            'passengers' => 'required|integer',
            'flight_class' => 'required|in:economy,first_class,business',
            'number_of_room' => 'required',
        ];
    }
}
