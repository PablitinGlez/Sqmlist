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
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('avatar')->nullable();
            $table->string('external_id')->nullable();
            $table->string('external_auth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminamos las columnas añadidas
            $table->dropColumn(['avatar', 'external_id', 'external_auth']);

            // Revertir el 'nullable()->change()' de la contraseña es complicado.
            // Si la columna 'password' era NO nula originalmente y tenías usuarios
            // existentes que usaban contraseña, restaurar la restricción NOT NULL
            // podría causar errores si tienes usuarios que solo se autentican con Google
            // y no tienen contraseña.
            //
            // Si realmente necesitas revertir la nulabilidad, tendrías que:
            // 1. Asegurarte de que todos los usuarios tengan un valor de contraseña.
            // 2. Luego, cambiar la columna de nuevo a no anulable.
            // Por simplicidad, a menudo se omite la reversión de nullable() si no es estrictamente necesario.
            //
            // Si quieres que la contraseña sea NO nula de nuevo, y has manejado los datos,
            // descomenta y usa esta línea:
            // $table->string('password')->nullable(false)->change();
        });
    
    }
};
