<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Sent()
 * @method static static Opened()
 * @method static static Clicked()
 * @method static static Bounced()
 * @method static static Unsubscribed()
 * @method static static Spam()
 * @method static static Invalid()
 * @method static static Deferred()
 * @method static static Blocked()
 * @method static static Error()
 * @method static static Scheduled()
 * @method static static Queued()
 * @method static static Expired()
 * @method static static Deleted()
 * @method static static Unconfirmed()
 * @method static static Active()
 * @method static static InActive()
 */
final class EmailStatus extends Enum
{
    const Sent          = 'Sent';
    const Opened        = 'Opened';
    const Clicked       = 'Clicked';
    const Bounced       = 'Bounced';
    const Unsubscribed  = 'Unsubscribed';
    const Spam          = 'Spam';
    const Invalid       = 'Invalid';
    const Deferred      = 'Deferred';
    const Blocked       = 'Blocked';
    const Error         = 'Error';
    const Scheduled     = 'Scheduled';
    const Queued        = 'Queued';
    const Expired       = 'Expired';
    const Deleted       = 'Deleted';
    const Unconfirmed   = 'Unconfirmed';
    const Active        = 'Active';
    const InActive      = 'InActive';
}
