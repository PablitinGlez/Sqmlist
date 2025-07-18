<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profile_details', function (Blueprint $table) {
            // Eliminar columna obsoleta
            $table->dropColumn('role_type');

           
            $table->foreignId('user_application_id')
                ->nullable()
                ->constrained('user_applications')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('profile_details', function (Blueprint $table) {
         
            $table->string('role_type')->nullable();
            $table->dropConstrainedForeignId('user_application_id');
        });
    }
};
