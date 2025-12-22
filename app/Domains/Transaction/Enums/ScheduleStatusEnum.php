<?php

namespace App\Domains\Transaction\Enums;

enum ScheduleStatusEnum: string
{
    case ACTIVE  = 'active';
    case PAUSED  = 'paused';
}
