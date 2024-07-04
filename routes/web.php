<?php

use App\Http\Controllers\ActivateCampaignController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\WebHookController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalanderController;
use App\Http\Controllers\CompanyCalenderController;
use App\Http\Controllers\EmailCampaignTest;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ZoomMeetingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/select-company', [GeneralController::class, 'showSelectionForm'])->name('select.company');
    Route::resource('companies', CompanyController::class);
    Route::resource('employee', EmployeeController::class);
    Route::get('calender', [CompanyCalenderController::class, 'companyCalender'])->name('company.calender');
    Route::post('updat/meeting/type', [CompanyCalenderController::class, 'UpdateMeetingType'])->name('company.calender.update');
    Route::prefix('company/{company}')->group(function () {
        Route::resource('/department', DepartmentController::class);
    });
    Route::resource('/leads', LeadController::class);
    Route::get('/all-leads', [LeadController::class, 'allLeads'])->name('allLeads');
    Route::post('/leads/assign', [LeadController::class, 'assignLead'])->name('leads.assign');
    Route::post('/comapny/agents', [LeadController::class, 'companyAgents'])->name('company.agents');
    Route::resource('/lead_status', LeadStatusController::class)->parameters(['lead_status' => 'status'])->except(['show']);
    Route::get('/upload-leads', 'App\Http\Controllers\LeadController@uploadLeads')->name('upload-leads');
    Route::post('/import-excel', 'App\Http\Controllers\LeadController@importExcel')->name('importExcel');
    // General routes
    Route::resource('campaign', CampaignController::class);
    Route::get('test-sms', [CampaignController::class, 'testSMS'])->name('test.sms');
    Route::get('company', [GeneralController::class, 'getCompanyList'])->name('company.list');
    Route::get('department/{company}', [GeneralController::class, 'getDepartmentList'])->name('department.list');
    Route::get('role', [GeneralController::class, 'getRoleList'])->name('roles.list');
    Route::get('permisions', [GeneralController::class, 'getPermissionsList'])->name('permissions.list');
    Route::get('items-select', [GeneralController::class, 'getItems'])->name('items.select');
    Route::post('save/invoice/{lead}', [GeneralController::class, 'saveInvoice'])->name('save.invoice');
    Route::post('update/invoice/{invoice}', [GeneralController::class, 'updateInvoice'])->name('update.invoice');
    Route::post('send/invoice/{invoice}', [GeneralController::class, 'sendInvoice'])->name('send.invoice');
    Route::post('update/selected/company/{company?}', [GeneralController::class, 'updateSelectedCompany'])->name('update.selectedCompany');
    Route::get('user/permisions', [GeneralController::class, 'getuserPermissionsList'])->name('users.permission');
    Route::get('countries', [GeneralController::class, 'getCountryList'])->name('country.list');
    Route::get('lead_status_list', [GeneralController::class, 'getLeadStatusList'])->name('lead_status.list');
    Route::get('users_list', [GeneralController::class, 'getUserList'])->name('users.list');

    Route::get('logs', [App\Http\Controllers\ActivityLogsController::class, 'index'])->name('logs');
    Route::get('logs/{log}', [App\Http\Controllers\ActivityLogsController::class, 'show'])->name('logs.show');

    Route::get('profile', [App\Http\Controllers\EmployeeProfileController::class, 'index'])->name('user.profile');
    Route::post('profile_photo', [App\Http\Controllers\EmployeeProfileController::class, 'store_photo'])->name('user.profile.photo');
    Route::post('update_password', [App\Http\Controllers\EmployeeProfileController::class, 'store_password'])->name('user.profile.password');
    Route::get('profile-submit-{id}', [App\Http\Controllers\EmployeeProfileController::class, 'store'])->name('user.profile.submit');
    Route::get('/google/calendar/connect', [CalanderController::class, 'connect'])->name('google.calendar.connect');
    Route::get('/google/calendar/callback', [CalanderController::class, 'callback'])->name('google.calendar.callback');
    Route::get('/google/calendar/events', [CalanderController::class, 'listEvents'])->name('google.calendar.events');
    Route::post('/google/calendar/addEvent', [CalanderController::class, 'createEvent'])->name('google.calendar.event.create');
    Route::post('/google/calendar/deleteEvent', [CalanderController::class, 'deleteEvent'])->name('google.calendar.event.delete');
    Route::post('/google/calendar/updateEvent', [CalanderController::class, 'updateEvent'])->name('google.calendar.event.delete');
    //Zoom Meeting
    Route::get('/zoom/calendar/callback', [ZoomMeetingController::class, 'handleZoomCallback'])->name('zoom.calendar.callback');
    Route::get('/zoom/calendar/events', [ZoomMeetingController::class, 'redirectToZoomProvider'])->name('zoom.calendar.events');
    Route::get('/zoom/meetings/events', [ZoomMeetingController::class, 'zoomMeetings'])->name('zoom.calendar.meetings');
    Route::post('/zoom/meetings/create', [ZoomMeetingController::class, 'createMeeting'])->name('zoom.meetings.create');
    Route::post('/zoom/meetings/delete', [ZoomMeetingController::class, 'deleteZoomMeeting'])->name('zoom.meetings.delete');
    Route::post('/zoom/meetings/update', [ZoomMeetingController::class, 'updateZoomMeeting'])->name('zoom.meetings.update');
    //general settings
    Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [SettingsController::class, 'updateSettings'])->name('settings.update');

    //Permissions
    Route::get('/permissions', [PermissionsController::class, 'index'])->name('permisions.index');
    Route::delete('/permissions/destroy/{permission}', [PermissionsController::class, 'destroy'])->name('permission.destroy');
    Route::get('/permissions/edit/{permission}', [PermissionsController::class, 'edit'])->name('permissions.edit');
    Route::get('/permissions/create', [PermissionsController::class, 'create'])->name('permissions.create');
    Route::patch('/permissions/update/{permission}', [PermissionsController::class, 'update'])->name('permissions.update');
    Route::post('/permissions/store', [PermissionsController::class, 'store'])->name('permissions.store');

    //Assign Permissions
    Route::get('/permissions/assign', [PermissionsController::class, 'assign'])->name('permissions.assign');
    Route::post('/permissions/select', [PermissionsController::class, 'assignPermission'])->name('permissions.select');
    Route::post('/permissions/detatch', [PermissionsController::class, 'removePermission'])->name('user.permissions.remove');
    Route::get('/permissions/remove', [PermissionsController::class, 'remove'])->name('permissions.remove');
    // items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::delete('/items/destroy/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('/items/edit/{item}', [ItemController::class, 'edit'])->name('items.edit');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::patch('/items/update/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    // items
    Route::get('/emails', [EmailController::class, 'index'])->name('email.index');
    Route::delete('/emails/destroy/{email}', [EmailController::class, 'destroy'])->name('email.destroy');
    Route::get('/emails/edit/{email}', [EmailController::class, 'edit'])->name('email.edit');
    Route::get('/emails/create', [EmailController::class, 'create'])->name('email.create');
    Route::post('/emails/update/{email}', [EmailController::class, 'update'])->name('email.update');
    Route::post('/emails/store', [EmailController::class, 'store'])->name('email.store');
    Route::get('/email/show/{email}', [EmailController::class, 'show'])->name('email.show');
});
Route::post('/portfolio-api', [LeadController::class, 'contactUsLeadform']);


Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
});
Route::get('/optimize-cache', function () {
    $exitCode = Artisan::call('optimize:clear');
    return 'Config cache cleared';
});
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// Web hooks for mail gun
Route::post('/webhooks/mailgun', [\App\Services\MailgunWebhookService::class, 'handleWebhook']);
Route::get('/webhook/vonage/{$action}', [WebHookController::class, 'handle_vonage_webhook'])->name('vonage.webhook')->whereIn('action', ['event', 'answer']);

Route::get('/activate/email', [ActivateCampaignController::class, 'activateCampaign'])->name('activate.campign');
