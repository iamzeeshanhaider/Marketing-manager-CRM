<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Employed()
 * @method static static FullTime()
 * @method static static PartTime()
 * @method static static CareerBreak()
 * @method static static SeekingEmployment()
 *
 */

final class EmploymentStatus extends Enum
{
    const Employed = 'employed';
    const FullTime = 'fullTime';
    const PartTime = 'partTime';
    const CareerBreak = 'careerBreak';
    const SeekingEmployment = 'seekingEmployment';

    protected static function values(): array
    {
        return [
            'employed' => 'Employed',
            'fullTime' => 'FullTime',
            'partTime' => 'PartTime',
            'careerBreak' => 'CareerBreak',
            'seekingEmployment' => 'SeekingEmployment'
        ];
    }
}
