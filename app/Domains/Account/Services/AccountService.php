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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
        $parent = null;

        if ($data->parent_reference) {
            $parent = $this->accounts->findByReference($data->parent_reference);
        }

        if ($parent) {
            $fakeChild = new Account([
                'user_id' => Auth::id()
            ]);

            $this->sameOwnerRule->validate($fakeChild, $parent);
        }

        $account = $this->accounts->create([
            'user_id'   => Auth::id(),
            'name'      => $data->name,
            'type'      => $data->type,
            'parent_id' => $parent?->id,
            'state'     => AccountStateEnum::ACTIVE->value,
            'balance'   => 0,
            'metadata'  => $data->metadata ?? [],
        ]);

        return $account;
    }

    public function update(Account $account, AccountUpdateData $data): Account
    {
        $payload = $data->toFilteredArray();

        if ($data->parent_reference !== null) {
            $parent = $this->accounts->findByReferenceOrFail($data->parent_reference);

            $this->sameOwnerRule->validate($account, $parent);
            $this->notSelfParentRule->validate($account, $parent);

            $payload['parent_id'] = $parent->id;
        }

        return $this->accounts->update($account, $payload);
    }

    public function getMyAccounts()
    {
        $user = auth()->user();

         return $user->accounts()
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->get();
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
