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
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();

          
            $table->foreignId('property_id')->constrained()->onDelete('cascade');

            $table->string('path');
            $table->string('filename'); 
            $table->string('alt_text')->nullable(); 
            $table->integer('order')->default(0); 
            $table->boolean('is_featured')->default(false); 

            $table->timestamps(); 
            $table->index('property_id');
            $table->index(['property_id', 'order']); 
            $table->index(['property_id', 'is_featured']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};
