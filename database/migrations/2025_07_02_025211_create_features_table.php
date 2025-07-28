<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_section_id')
                ->constrained('feature_sections')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->enum('input_type', ['number', 'checkbox', 'text', 'textarea', 'select', 'radio', 'date', 'file', 'richtext', 'stepper'])->default('text');
            $table->enum('data_type', ['integer', 'float', 'boolean', 'string', 'array', 'json'])->default('string');

            $table->json('options')->nullable();

            $table->string('unit', 50)->nullable();
            $table->string('default_value')->nullable();

            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_searchable')->default(true);
            $table->boolean('is_required')->default(false);

            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['name', 'feature_section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
