<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatuses: string
{
    case DRAFT = 'DRAFT';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
}
