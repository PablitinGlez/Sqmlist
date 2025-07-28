<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_types_features', function (Blueprint $table) {
            $table->foreignId('property_type_id')
                ->constrained('property_types')
                ->onDelete('cascade');

            $table->foreignId('feature_id')
                ->constrained('features')
                ->onDelete('cascade');

            $table->boolean('is_required_for_type')->default(false);
            $table->integer('order_for_type')->default(0);

            $table->primary(['property_type_id', 'feature_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_types_features');
    }
};
