<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_types', function (Blueprint $table) {
            if (!Schema::hasColumn('property_types', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_types', function (Blueprint $table) {
            if (Schema::hasColumn('property_types', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
