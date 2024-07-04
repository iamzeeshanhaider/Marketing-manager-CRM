<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Active()
 * @method static static InActive()
 */
final class GeneralStatus extends Enum
{
    const Active = 'Active';
    const InActive = 'InActive';
}
