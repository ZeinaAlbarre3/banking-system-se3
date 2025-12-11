<?php

namespace App\Domains\Account\Services;

use App\Domains\Account\Composites\AccountTreeBuilder;
use App\Domains\Account\Data\AccountCreateData;
use App\Domains\Account\Data\AccountStateChangeData;
use App\Domains\Account\Data\AccountUpdateData;
use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Exceptions\AccountStateException;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Account\Rules\EnsureAccountCanBeClosedRule;
use App\Domains\Account\Rules\EnsureNotSelfParentRule;
use App\Domains\Account\Rules\EnsureSameOwnerRule;
use App\Domains\Account\States\AccountStateFactory;
use App\Domains\Auth\Models\User;

class AccountService
{
    public function __construct(
        private readonly AccountRepositoryInterface   $accounts,
        private readonly AccountTreeBuilder           $treeBuilder,
        private readonly EnsureSameOwnerRule          $sameOwnerRule,
        private readonly EnsureNotSelfParentRule      $notSelfParentRule,
        private readonly EnsureAccountCanBeClosedRule $canBeClosedRule,
    ) {}

    public function create(AccountCreateData $data): Account
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

        return $account;
    }

    public function update(Account $account, AccountUpdateData $data): Account
    {
        $payload = $data->toFilteredArray();

        $parent = $this->getParentIfProvided($payload['parent_id'] ?? null);

        if ($parent) {
            $this->sameOwnerRule->validate($account, $parent);
            $this->notSelfParentRule->validate($account, $parent);
        }

        $updated = $this->accounts->update($account, $payload);

        return $updated;
    }

    public function changeState(Account $account, AccountStateChangeData $data): Account
    {
        if ($data->state === AccountStateEnum::CLOSED->value) {
            $this->canBeClosedRule->validate($account);
        }

        $account->state = $data->state;
        $account->save();

        return $account;
    }

    public function getUserPortfolioBalance(User $user): float
    {
        $tree = $this->treeBuilder->buildForUser($user->accounts);

        return $tree->getBalance();
    }

    public function ensureCanWithdraw(Account $account): void
    {
        $this->ensureOperationAllowed(
            account: $account,
            operation: 'withdraw',
            errorMessage: 'This account is not allowed to withdraw in its current state.'
        );
    }

    public function ensureCanDeposit(Account $account): void
    {
        $this->ensureOperationAllowed(
            account: $account,
            operation: 'deposit',
            errorMessage: 'This account is not allowed to deposit in its current state.'
        );
    }

    public function ensureCanTransfer(Account $account): void
    {
        $this->ensureOperationAllowed(
            account: $account,
            operation: 'transfer',
            errorMessage: 'This account is not allowed to transfer in its current state.'
        );
    }

    private function ensureOperationAllowed(Account $account, string $operation, string $errorMessage): void
    {
        $state = AccountStateFactory::fromAccount($account);

        $allowed = match ($operation) {
            'withdraw' => $state->canWithdraw($account),
            'deposit'  => $state->canDeposit($account),
            'transfer' => $state->canTransfer($account),
            default    => false,
        };

        if (! $allowed) {
            throw new AccountStateException($errorMessage);
        }
    }


    private function getParentIfProvided(?int $parentId): ?Account
    {
        if ($parentId === null) {
            return null;
        }

        return $this->accounts->findOrFail($parentId);
    }
}
