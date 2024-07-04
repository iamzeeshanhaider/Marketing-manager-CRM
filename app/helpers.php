<?php


use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class constPaths
{
    const CompanyLogo  = '/uploads/company/';
    const UserAvatar  = '/uploads/user/';
    const EmployeeAvatar  = '/uploads/employee/';
    const Default  = '/assets/crm/logo.png';
    const DefaultAvatar  = '/assets/crm/avatar.png';
    const LEADS  = '/uploads/leads/';
}

if (!function_exists('uploadOrUpdateFile')) {
    function uploadOrUpdateFile($_file, $old_file, $path)
    {
        if ($_file) {
            if ($old_file) {
                $old_file_path = public_path() . $path . '/' . $old_file;
                unlink($old_file_path);
            }
            $randomName = time() . '_' . $_file->getClientOriginalName();
            $_file->move(public_path() . $path, $randomName);
            $_file = $randomName;
        } else {
            $_file = $old_file;
        }
        return $_file;
    }
}



if (!function_exists('calculateContrastColor')) {
    function calculateContrastColor($colorCode)
    {
        // Convert the hexadecimal color code to RGB values
        list($r, $g, $b) = sscanf($colorCode, "#%02x%02x%02x");

        // Calculate the relative luminance using the formula for sRGB
        $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;

        // Return either black or white based on the luminance
        return ($luminance > 0.5) ? '#000000' : '#FFFFFF';
    }
}

if (!function_exists('canEngageWithLead')) {
    function canEngageWithLead($leadId = null)
    {
        $user = Auth::user();
        return $user->hasRole(['Admin', 'Agent']) || $leadId && $user->leads->pluck('id')->contains($leadId);
    }
}


if (!function_exists('getTableSubtitle')) {
    function getTableSubtitle($title)
    {
        $subtitle = 'Showing ' . $title . ' List';

        $companyName = request('company') ? Company::find(request('company'))->name : null;
        $departmentName = request('department') ? Department::find(request('department'))->name : null;

        $subtitle .= $companyName ? ' For Company: ' . $companyName : '';
        $subtitle .= $departmentName ? ' For Department: ' . $departmentName : '';

        return $subtitle;
    }
}

if (!function_exists('getCompany')) {
    function getCompany($companyId)
    {
        return Company::find($companyId) ?? null;
    }
}
