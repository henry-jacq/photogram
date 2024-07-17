<?php

declare(strict_types=1);

namespace App\Enum;

enum StorageSpace: int
{
    case Free = 536870912;
    case Premium = 2147483648;
}
