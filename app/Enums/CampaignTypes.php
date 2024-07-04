<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Email()
 * @method static static SMS()
 * @method static static Call()
 * @method static static Comment()
 * @method static static Invoice()
 */
final class CampaignTypes extends Enum
{
    const Email = 'Email';
    const SMS = 'SMS';
    const Call = 'Call';
    const Comment = 'Comment';
    const Invoice = 'Invoice';
}
