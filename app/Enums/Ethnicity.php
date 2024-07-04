<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WhiteBritish()
 * @method static static WhiteIrish()
 * @method static static OtherWhite()
 * @method static static WhiteBlackCaribbean()
 * @method static static WhiteBlackAfrican()
 * @method static static WhiteAsian()
 * @method static static OtherMixed()
 * @method static static Indian()
 * @method static static Pakistani()
 * @method static static Bangladeshi()
 * @method static static OtherAsian()
 * @method static static BlackCaribbean()
 * @method static static BlackAfrican()
 * @method static static OtherBlack()
 * @method static static Chinese()
 * @method static static AnyOther()
 */
final class Ethnicity extends Enum
{
    const WhiteBritish = 'White British';
    const WhiteIrish = 'White Irish';
    const OtherWhite = 'Other White';
    const WhiteBlackCaribbean = 'White and Black Caribbean';
    const WhiteBlackAfrican = 'White and Black African';
    const WhiteAsian = 'White and Asian';
    const OtherMixed = 'Other Mixed';
    const Indian = 'Indian';
    const Pakistani = 'Pakistani';
    const Bangladeshi = 'Bangladeshi';
    const OtherAsian = 'Other Asian';
    const BlackCaribbean = 'Black Caribbean';
    const BlackAfrican = 'Black African';
    const OtherBlack = 'Other Black';
    const Chinese = 'Chinese';
    const AnyOther = 'Any other ethnic group';
}
