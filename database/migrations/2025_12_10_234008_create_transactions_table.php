<?php

use App\Domains\Transaction\Enums\TransactionStatusEnum;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->nullable()->index();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('related_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->enum('type',array_column(TransactionTypeEnum::cases(), 'value'));
            $table->enum('status',array_column(TransactionStatusEnum::cases(), 'value'));
            $table->decimal('amount', 18, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index('type');
            $table->index('status');
            $table->index('account_id');
            $table->index('related_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
