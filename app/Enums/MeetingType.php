<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Google()
 * @method static static Zoom()
 * @method static static Microsoft()
 */
final class MeetingType extends Enum
{
    const Google = 'google';
    const Zoom = 'zoom';
    const Microsoft = 'microsoft';
}
