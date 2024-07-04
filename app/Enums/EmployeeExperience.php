<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Months()
 * @method static static Years3()
 * @method static static Years6()
 * @method static static Years7()
 */
final class EmployeeExperience extends Enum
{
    const Months = 'Less than 6 Months';
    const Years3 = '1 - 3 Years';
    const Years6 = '4 - 6';
    const Years7 = '7 Years Plus';

    protected static function values(): array
    {
        return [
            'Less than 6 Months' => 'Months',
            '1 - 3 Years' => 'Years3',
            '4 - 6' => 'Years6',
            '7 Years Plus' => 'Years7',
        ];
    }
}
