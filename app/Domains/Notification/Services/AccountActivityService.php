<?php


namespace App\Domains\Notification\Services;

use App\Domains\Account\Models\Account;
use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Notification\Data\AccountActivityCreateData;
use Illuminate\Support\Facades\DB;

class AccountActivityService
{
    public function create(AccountActivityCreateData $data): AccountActivity
    {
        return DB::transaction(function () use ($data) {

            $account = Account::query()->where('reference_number', $data->account_reference)
                ->lockForUpdate()
                ->firstOrFail();
            $balanceBefore = $account->balance;
            $balanceAfter  = $balanceBefore + $data->amount;

            // تحديث رصيد الحساب
            $account->update([
                'balance' => $balanceAfter,
            ]);

            // تسجيل الحركة
            $activity = AccountActivity::create([
                'account_id'     => $account->id,
                'user_id'        => auth()->id(),
                'type'           => $data->type,
                'amount'         => $data->amount,
                'balance_before' => $balanceBefore,
                'balance_after'  => $balanceAfter,
                'meta'           => $data->meta,
            ]);

            return $activity;
        });
    }
}
