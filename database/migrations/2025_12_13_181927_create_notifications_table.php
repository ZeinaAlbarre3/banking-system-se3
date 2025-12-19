<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->nullable()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // balance_change | large_transaction | ...
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
