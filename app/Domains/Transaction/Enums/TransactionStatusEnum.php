<?php

namespace App\Domains\Transaction\Enums;

enum TransactionStatusEnum: string
{
    case PENDING    = 'pending';
    case APPROVED   = 'approved';
    case REJECTED   = 'rejected';
    case COMPLETED  = 'completed';
    case FAILED     = 'failed';
}
