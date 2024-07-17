<?php

declare(strict_types=1);

namespace App\Enum;

enum BillingCycle: string
{
    case MONTHLY = 'monthly';
    case ANNUAL = 'annual';
}
