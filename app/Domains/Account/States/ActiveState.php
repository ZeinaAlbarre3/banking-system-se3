<?php

namespace App\Domains\Account\States;

class ActiveState extends AbstractAccountState
{
    public function name(): string
    {
        return 'active';
    }
}
