<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static Spoken()
 * @method static static VoicemailSent()
 * @method static static CallBack()
 * @method static static NotAnswered()
 * @method static static InvalidNumber()
 */

final class CallStatus extends Enum
{
    const Spoken            = 'Spoken';
    const VoicemailSent     = 'VoicemailSent';
    const CallBack          = 'CallBack';
    const NotAnswered       = 'NotAnswered';
    const InvalidNumber     = 'InvalidNumber';
}
