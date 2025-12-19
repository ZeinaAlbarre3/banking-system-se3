<?php

namespace App\Domains\Transaction\Chains;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;

abstract class AbstractTransactionChain implements TransactionChain
{
    protected ?TransactionChain $next = null;

    public function setNext(?TransactionChain $next): TransactionChain
    {
        $this->next = $next;
        return $next ?? $this;
    }

    public function handle(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void
    {
        $this->process($account, $data, $relatedAccount);

        if ($this->next) {
            $this->next->handle($account, $data, $relatedAccount);
        }
    }

    abstract public function process(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void;
}
