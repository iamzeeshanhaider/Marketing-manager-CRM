<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Mainsheet()
 * @method static static Linkedin()
 * @method static static Indeed()
 * @method static static Reed()
 * @method static static ReedCourses()
 * @method static static TotalJobsCV()
 * @method static static TotalJobsCourses()
 * @method static static Referral()
 * @method static static Facebook()
 * @method static static Instagram()
 * @method static static Twitter()
 * @method static static Youtube()
 * @method static static Whatsapp()
 * @method static static Chatwoo()
 * @method static static ContactUs()
 * @method static static EmailSub()
 * @method static static EbookRequest()
 * @method static static WebsiteBlogs()
 *
 */
final class LeadSource extends Enum
{
    const Mainsheet = 'Mainsheet';
    const Linkedin = 'Linkedin';
    const Indeed = 'Indeed';
    const Reed = 'Reed';
    const ReedCourses = 'ReedCourses';
    const TotalJobsCV = 'TotalJobsCV';
    const TotalJobsCourses = 'TotalJobsCourses';
    const Referral = 'Referral';
    const Facebook = 'Facebook';
    const Instagram = 'Instagram';
    const Twitter = 'Twitter';
    const Youtube = 'Youtube';
    const Whatsapp = 'Whatsapp';
    const Chatwoo = 'Chatwoo';
    const ContactUs = 'ContactUs';
    const EmailSub = 'EmailSub';
    const EbookRequest = 'EbookRequest';
    const WebsiteBlogs = 'Website Blogs';
}
