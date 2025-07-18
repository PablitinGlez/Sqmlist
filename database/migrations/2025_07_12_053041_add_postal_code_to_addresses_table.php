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
        Schema::table('addresses', function (Blueprint $table) {
            // Añadir la columna 'postal_code' como string y nullable
            // Puedes ajustar la longitud (ej. 10) si sabes que los códigos postales siempre son de un largo específico.
            // La hacemos nullable porque tu formulario puede no siempre tenerla.
            $table->string('postal_code', 10)->nullable()->after('interior_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // En caso de rollback, eliminar la columna 'postal_code'
            $table->dropColumn('postal_code');
        });
    }
};
