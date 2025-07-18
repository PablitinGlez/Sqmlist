<?php

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
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->onDelete('cascade'); 
            $table->string('name'); 
            $table->string('postal_code', 10)->nullable(); 
            $table->string('google_place_id')->nullable(); 
            $table->boolean('is_active')->default(true); 

            $table->timestamps(); 

            $table->index(['municipality_id', 'postal_code']); 
            $table->index('google_place_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neighborhoods');
    }
};
