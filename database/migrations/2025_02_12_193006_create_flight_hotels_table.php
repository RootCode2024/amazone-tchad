<?php

use App\Models\City;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flight_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class, 'customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->enum('flight_type', ['one_way', 'round_trip', 'multi_destination'])->default('one_way');
            $table->foreignIdFor(City::class, 'departure_city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreignIdFor(City::class, 'destination_city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->integer('passengers');
            $table->enum('flight_class', ['economy', 'first_class', 'business'])->default('economy');
            $table->unsignedInteger('number_of_room');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_hotels');
    }
};
