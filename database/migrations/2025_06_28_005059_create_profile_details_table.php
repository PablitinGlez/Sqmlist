<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->unique()->onDelete('cascade');
            $table->string('role_type'); 
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('identification_type')->nullable();
            $table->string('identification_path')->nullable();
            $table->string('license_path')->nullable();
            $table->integer('years_experience')->nullable();
            $table->string('real_estate_company')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('inactive');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_details');
    }
};
