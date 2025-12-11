<?php

use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Enums\AccountTypeEnum;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->nullable()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type',array_column(AccountTypeEnum::cases(), 'value')); // saving, current, loan, investment
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->enum('state',array_column(AccountStateEnum::cases(), 'value'))->default('active'); // active, frozen, suspended, closed
            $table->decimal('balance', 18, 2)->default(0);
            $table->json('metadata')->nullable(); // أي بيانات إضافية (مثلاً: currency, iban, label...)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
