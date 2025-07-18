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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_section_id')
                ->constrained('feature_sections')
                ->onDelete('cascade'); // Si se borra una sección, sus características también

            $table->string('name'); // Nombre visible de la característica (ej. "Número de Recámaras")
            $table->string('slug')->unique(); // Slug para uso en código (ej. "num_recamaras")
            $table->text('description')->nullable(); // Descripción para el admin o tooltip en UI

            // Tipo de entrada para el formulario (ej. 'number', 'checkbox', 'text', 'select', 'textarea')
            $table->enum('input_type', ['number', 'checkbox', 'text', 'textarea', 'select', 'radio', 'date', 'file', 'richtext', 'stepper'])->default('text');

            // Tipo de dato para el backend y validación (ej. 'integer', 'float', 'boolean', 'string')
            $table->enum('data_type', ['integer', 'float', 'boolean', 'string', 'array', 'json'])->default('string');

            // Opciones para 'select' o 'radio' como JSON (ej. ["Bifásica", "Trifásica"])
            $table->json('options')->nullable();

            $table->string('unit', 50)->nullable(); // Unidad de medida (ej. "m²", "habitaciones", "kW")
            $table->string('default_value')->nullable(); // Valor por defecto si aplica

            $table->boolean('is_filterable')->default(true); // ¿Se puede usar para filtrar propiedades?
            $table->boolean('is_searchable')->default(true); // ¿Se puede buscar texto en esta característica?
            $table->boolean('is_required')->default(false); // ¿Es obligatoria por defecto? (puede ser anulado por property_types_features)

            $table->integer('order')->default(0); // Orden de la característica dentro de su sección
            $table->timestamps();

             $table->unique(['name', 'feature_section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
