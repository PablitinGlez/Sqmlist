<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_type_id')->constrained()->onDelete('restrict');
            // $table->foreignId('address_id')->nullable()->unique()->constrained()->onDelete('set null'); 

            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->enum('status', ['draft', 'pending_review', 'published', 'rejected', 'expired_draft', 'inactive', 'sold', 'rented'])->default('draft');
            $table->timestamp('draft_expires_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('property_type_id');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
