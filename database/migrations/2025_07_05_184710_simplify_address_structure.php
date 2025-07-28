<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');

            $table->dropForeign(['municipality_id']);
            $table->dropColumn('municipality_id');

            $table->dropForeign(['neighborhood_id']);
            $table->dropColumn('neighborhood_id');

            $table->string('state_name')->nullable()->after('google_address_components');
            $table->string('municipality_name')->nullable()->after('state_name');
            $table->string('neighborhood_name')->nullable()->after('municipality_name');
        });

        Schema::dropIfExists('neighborhoods');
        Schema::dropIfExists('municipalities');
        Schema::dropIfExists('states');
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['state_name', 'municipality_name', 'neighborhood_name']);
        });
    }
};
