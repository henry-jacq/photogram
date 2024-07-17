<?php

declare(strict_types=1);

namespace App\Enum;

enum SubscriptionPlan: string
{
    case Free = 'free';
    case Premium  = 'premium';

    public static function isPremium(string $plan): bool
    {
        return self::tryFrom($plan) === self::Premium;
    }

    public static function isFree(string $plan): bool
    {
        return self::tryFrom($plan) === self::Free;
    }
}
