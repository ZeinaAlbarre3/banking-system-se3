<?php

namespace App\Domains\Account\Services;

use App\Domains\Account\Composites\AccountTreeBuilder;
use App\Domains\Account\Data\AccountCreateData;
use App\Domains\Account\Data\AccountData;
use App\Domains\Account\Data\AccountStateChangeData;
use App\Domains\Account\Data\AccountUpdateData;
use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Account\Rules\EnsureNotSelfParentRule;
use App\Domains\Account\Rules\EnsureSameOwnerRule;
use App\Domains\Account\States\AccountStateFactory;
use App\Domains\Auth\Models\User;
use Illuminate\Validation\ValidationException;

class AccountService
{
    public function __construct(
        private AccountRepositoryInterface   $accounts,
        private AccountTreeBuilder           $treeBuilder,
        private EnsureSameOwnerRule          $sameOwnerRule,
        private EnsureNotSelfParentRule      $notSelfParentRule,
    ) {}

    public function create(AccountCreateData $data): AccountData
    {
        $parent = $this->getParentIfProvided($data->parent_id);

        if ($parent) {
            $fakeChild = new Account([
                'user_id' => $data->user_id,
            ]);

            $this->sameOwnerRule->validate($fakeChild, $parent);
        }

        $account = $this->accounts->create([
            'user_id'   => $data->user_id,
            'type'      => $data->type,
            'parent_id' => $data->parent_id,
            'state'     => AccountStateEnum::ACTIVE->value,
            'balance'   => 0,
            'metadata'  => $data->metadata ?? [],
        ]);

        return AccountData::fromModel($account);
    }

    public function update(Account $account, AccountUpdateData $data): AccountData
    {
        $payload = $data->toFilteredArray();

        $parent = $this->getParentIfProvided($payload['parent_id'] ?? null);

        if ($parent) {
            $this->sameOwnerRule->validate($account, $parent);
            $this->notSelfParentRule->validate($account, $parent);
        }

        $updated = $this->accounts->update($account, $payload);

        return AccountData::fromModel($updated);
    }

    public function changeState(Account $account, AccountStateChangeData $data): AccountData
    {
        if ($data->state === AccountStateEnum::CLOSED->value) {
            $this->canBeClosedRule->validate($account);
        }

        $account->state = $data->state;
        $account->save();

        return AccountData::fromModel($account);
    }

    public function getUserPortfolioBalance(User $user): float
    {
        $tree = $this->treeBuilder->buildForUser($user->accounts);

        return $tree->getBalance();
    }

    public function ensureCanWithdraw(Account $account): void
    {
        $state = AccountStateFactory::fromAccount($account);
    }
    public function ensureCanDeposit(Account $account): void
    {
        $state = AccountStateFactory::fromAccount($account);
    }

    public function ensureCanTransfer(Account $account): void
    {
        $state = AccountStateFactory::fromAccount($account);
    }


    private function getParentIfProvided(?int $parentId): ?Account
    {
        if ($parentId === null) {
            return null;
        }

        return $this->accounts->findOrFail($parentId);
    }
}
