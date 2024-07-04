<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 *
 */
final class UKStatus extends Enum
{
    const BritishCitizen = 'British Citizen';
    const EUCitizen = 'EU Citizen';
    const ILR = 'ILR';
    const NonEUCitizen = 'Non EU Citizen';
    const StudentVisa = 'Student Visa';
    const DependentVisa = 'Dependent Visa';
}
