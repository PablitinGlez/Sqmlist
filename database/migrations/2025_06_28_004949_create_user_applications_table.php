<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('requested_user_type');
            $table->string('phone_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('identification_type');
            $table->string('identification_path');
            $table->string('license_path')->nullable(); 
            $table->integer('years_experience')->nullable(); 
            $table->string('real_estate_company')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('status_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_applications');
    }
};
