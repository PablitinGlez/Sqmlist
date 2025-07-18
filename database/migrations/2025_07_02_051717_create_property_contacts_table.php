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
        Schema::create('property_contacts', function (Blueprint $table) {
            $table->id();

            // Relación con la propiedad a la que se refiere el mensaje
            // Si la propiedad se elimina, sus mensajes de contacto también.
            $table->foreignId('property_id')
                ->constrained('properties') // Asegúrate de que la tabla 'properties' exista
                ->onDelete('cascade');

            // Datos del remitente del mensaje
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('sender_phone')->nullable(); // El teléfono es opcional

            // El mensaje en sí
            $table->text('message_text'); // Usamos 'message_text' para evitar conflicto con el método 'message' de Eloquent/Mailables

            // Campos para la gestión del mensaje en el panel del anunciante
            $table->boolean('is_read')->default(false); // Indica si el dueño ya leyó el mensaje
            $table->timestamp('read_at')->nullable(); // Fecha y hora en que fue leído
            $table->boolean('is_archived')->default(false); // Para archivar mensajes
            $table->timestamp('archived_at')->nullable(); // Fecha y hora en que fue archivado

            $table->timestamps(); // created_at y updated_at

            // Índices para optimizar consultas
            $table->index('property_id');
            $table->index('is_read');
            $table->index('is_archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_contacts');
    }
};
