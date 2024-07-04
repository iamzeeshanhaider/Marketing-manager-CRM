<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Individual()
 * @method static static Business()
 */
final class LeadType extends Enum
{
    const Individual = "Individual";
    const Business = "Business";
}
