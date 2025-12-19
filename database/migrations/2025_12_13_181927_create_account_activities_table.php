<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_activities', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->nullable()->index();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // e.g. balance_change, large_transaction
            $table->decimal('amount', 18, 2)->nullable();
            $table->decimal('balance_before', 18, 2)->nullable();
            $table->decimal('balance_after', 18, 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_activities');
    }
};
