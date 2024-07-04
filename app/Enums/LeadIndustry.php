<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Technology()
 * @method static static Healthcare()
 * @method static static Finance()
 * @method static static Accounting()
 * @method static static Education()
 * @method static static RealEstate()
 * @method static static Entertainment()
 * @method static static Energy()
 * @method static static Agriculture()
 * @method static static Telecommunications()
 */
final class LeadIndustry extends Enum
{
    const Technology = "Technology";
    const Healthcare = "Healthcare";
    const Finance = "Finance";
    const Accounting = "Accounting";
    const Education      = "Education   ";
    const RealEstate = "RealEstate";
    const Entertainment = "Entertainment";
    const Energy = "Energy";
    const Agriculture = "Agriculture";
    const Telecommunications = "Telecommunications";
}
