<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspended_reason')->nullable();
            $table->unsignedBigInteger('suspended_by')->nullable();
            $table->string('status')->default('active')->change();
            $table->index(['status', 'deleted_at']);
            $table->index('suspended_at');
            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'suspended_at',
                'suspended_reason',
                'suspended_by'
            ]);
            $table->dropForeign(['suspended_by']);
        });
    }
};
