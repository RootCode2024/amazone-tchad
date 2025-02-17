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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class, 'customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreignIdFor(City::class, 'city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->date('arrival_date');
            $table->date('return_date');
            $table->unsignedInteger('number_of_room');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
