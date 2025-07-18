<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::table('user_applications', function (Blueprint $table) {
           
            $table->string('rfc')->nullable()->after('real_estate_company');
        });
    }

   
    public function down(): void
    {
        Schema::table('user_applications', function (Blueprint $table) {
           
            $table->dropColumn('rfc');
        });
    }
};
