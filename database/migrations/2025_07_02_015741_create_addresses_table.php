<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('property_id')->unique()->constrained()->onDelete('cascade'); 

            $table->foreignId('state_id')->constrained()->onDelete('restrict');
            $table->foreignId('municipality_id')->constrained()->onDelete('restrict');
            $table->foreignId('neighborhood_id')->constrained()->onDelete('restrict');

            $table->string('street');
            $table->string('outdoor_number', 20)->nullable();
            $table->string('interior_number', 20)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('google_place_id')->nullable();
            $table->json('google_address_components')->nullable();
            $table->text('additional_references')->nullable();

            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('google_place_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
