<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static InActive()
 * @method static static Active()
 */
final class StatusType extends Enum
{
    const InActive = 0;
    const Active = 1;
}
