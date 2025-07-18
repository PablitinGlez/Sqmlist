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
        Schema::create('property_types_features', function (Blueprint $table) {
            // Claves foráneas a las tablas 'property_types' y 'features'
            $table->foreignId('property_type_id')
                ->constrained('property_types') // Asegúrate de que 'property_types' exista
                ->onDelete('cascade'); // Si se borra un tipo de propiedad, se borra su vínculo aquí

            $table->foreignId('feature_id')
                ->constrained('features')
                ->onDelete('cascade'); // Si se borra una característica, se borra su vínculo aquí

            $table->boolean('is_required_for_type')->default(false); // ¿Es obligatoria para este tipo específico?
            $table->integer('order_for_type')->default(0); // Orden de visualización para este tipo de propiedad

            // Clave primaria compuesta para asegurar que una combinación tipo-característica sea única
            $table->primary(['property_type_id', 'feature_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_types_features');
    }
};
