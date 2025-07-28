<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->onDelete('cascade');

            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('sender_phone')->nullable();

            $table->text('message_text');

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();

            $table->timestamps();

            $table->index('property_id');
            $table->index('is_read');
            $table->index('is_archived');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_contacts');
    }
};
