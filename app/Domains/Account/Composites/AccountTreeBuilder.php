<?php

namespace App\Domains\Account\Composites;

use App\Domains\Account\Models\Account;
use Illuminate\Support\Collection;

class AccountTreeBuilder
{
    /**
     *
     * @param Collection<int, Account> $accounts
     * @return AccountGroup
     */
    public function buildForUser(Collection $accounts): AccountGroup
    {
        $rootGroup = new AccountGroup();

        $byParent = $accounts->groupBy('parent_id');

        $rootAccounts = $byParent->get(null, collect());

        foreach ($rootAccounts as $account) {
            $component = $this->buildComponent($account, $byParent);
            $rootGroup->add($component);
        }

        return $rootGroup;
    }

    /**
     *
     * @param Account $account
     * @param Collection<int, Collection<int, Account>> $byParent
     * @return AccountLeaf
     */
    protected function buildComponent(Account $account, Collection $byParent): AccountComponent
    {
        $children = $byParent->get($account->id, collect());

        if ($children->isEmpty()) {
            return new AccountLeaf($account);
        }

        $group = new AccountGroup();
        $group->add(new AccountLeaf($account));

        foreach ($children as $child) {
            $group->add($this->buildComponent($child, $byParent));
        }

        return $group;
    }
}
