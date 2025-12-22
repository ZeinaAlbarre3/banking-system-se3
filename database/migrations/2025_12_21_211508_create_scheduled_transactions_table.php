<?php

use App\Domains\Transaction\Enums\ScheduleStatusEnum;
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
        Schema::create('scheduled_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->index()->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('related_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->enum('type', array_column(TransactionTypeEnum::cases(), 'value'));
            $table->decimal('amount', 18, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('metadata')->nullable();
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->unsignedTinyInteger('day_of_week')->nullable();
            $table->unsignedTinyInteger('day_of_month')->nullable();
            $table->string('time_of_day')->default('09:00');
            $table->string('timezone')->default('UTC');
            $table->enum('status', array_column(ScheduleStatusEnum::cases(),'value'))->default('active');
            $table->timestamp('next_run_at')->nullable()->index();
            $table->timestamp('last_run_at')->nullable();
            $table->unsignedInteger('runs_count')->default(0);
            $table->string('last_run_key')->nullable()->index();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_transactions');
    }
};
