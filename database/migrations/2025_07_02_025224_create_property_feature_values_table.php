<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_feature_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->onDelete('cascade');

            $table->foreignId('feature_id')
                ->constrained('features')
                ->onDelete('cascade');

            $table->text('value')->nullable();

            $table->timestamps();

            $table->unique(['property_id', 'feature_id']);

            $table->index('property_id');
            $table->index('feature_id');
            $table->index(['property_id', 'feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_feature_values');
    }
};
