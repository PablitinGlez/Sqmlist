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
        Schema::table('properties', function (Blueprint $table) {
            // Añadir el número de WhatsApp para contacto, puede ser nulo
            $table->string('contact_whatsapp_number', 20)->nullable()->after('price');

            // Añadir el número de teléfono para contacto, puede ser nulo
            $table->string('contact_phone_number', 20)->nullable()->after('contact_whatsapp_number');

            // Añadir el correo electrónico para contacto, puede ser nulo
            $table->string('contact_email')->nullable()->after('contact_phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Eliminar las columnas si se revierte la migración
            $table->dropColumn([
                'contact_whatsapp_number',
                'contact_phone_number',
                'contact_email',
            ]);
        });
    }
};
