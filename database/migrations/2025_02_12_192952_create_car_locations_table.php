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
        Schema::create('car_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->references('id')->on('customers')->cascadeOnDelete();
            $table->foreignIdFor(City::class, 'place_of_location')->references('id')->on('cities')->cascadeOnDelete();
            $table->date('started_date');
            $table->date('ended_date');
            $table->unsignedInteger('age');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_locations');
    }
};
