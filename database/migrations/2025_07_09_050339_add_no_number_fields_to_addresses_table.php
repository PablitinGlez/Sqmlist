<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('no_external_number')->default(false)->after('interior_number');
            $table->boolean('no_interior_number')->default(false)->after('no_external_number');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('no_external_number');
            $table->dropColumn('no_interior_number');
        });
    }
};
