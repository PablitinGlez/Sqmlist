<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profile_details', function (Blueprint $table) {
           
            if (!Schema::hasColumn('profile_details', 'rfc')) {
                $table->string('rfc')->nullable()->after('real_estate_company');
            }
            if (!Schema::hasColumn('profile_details', 'identification_type')) {
                $table->string('identification_type')->nullable()->after('rfc');
            }
            if (!Schema::hasColumn('profile_details', 'identification_path')) {
                $table->string('identification_path')->nullable()->after('identification_type');
            }
            if (!Schema::hasColumn('profile_details', 'license_path')) {
                $table->string('license_path')->nullable()->after('identification_path');
            }
        });
    }

    public function down()
    {
        Schema::table('profile_details', function (Blueprint $table) {
            
            $columnsToDrop = [];
            if (Schema::hasColumn('profile_details', 'rfc')) {
                $columnsToDrop[] = 'rfc';
            }
            if (Schema::hasColumn('profile_details', 'identification_type')) {
                $columnsToDrop[] = 'identification_type';
            }
            if (Schema::hasColumn('profile_details', 'identification_path')) {
                $columnsToDrop[] = 'identification_path';
            }
            if (Schema::hasColumn('profile_details', 'license_path')) {
                $columnsToDrop[] = 'license_path';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
