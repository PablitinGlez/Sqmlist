<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); 
            $table->string('code', 5)->unique(); 
            $table->string('country_code', 3)->default('MEX'); 
            $table->boolean('is_active')->default(true); 

            $table->timestamps(); 

            $table->unique(['code', 'country_code']);
            $table->index('is_active'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
