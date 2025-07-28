<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('contact_whatsapp_number', 20)->nullable()->after('price');
            $table->string('contact_phone_number', 20)->nullable()->after('contact_whatsapp_number');
            $table->string('contact_email')->nullable()->after('contact_phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'contact_whatsapp_number',
                'contact_phone_number',
                'contact_email',
            ]);
        });
    }
};
