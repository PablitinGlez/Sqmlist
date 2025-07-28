<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colonias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('postal_code', 5);
            $table->string('tipo_asentamiento')->nullable();
            $table->string('zona')->nullable();
            $table->timestamps();

            $table->unique(['municipality_id', 'name', 'postal_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colonias');
    }
};
