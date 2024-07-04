@include('layouts.partials.navbar')

@php

    $salesManagementOptions = [];
    if (auth()->user()->hasPermissionTo('all_leads') || auth()->user()->hasPermissionTo('all_permissions')) {
        $salesManagementOptions[] = [
            'name' => 'All Leads',
            'route' => route('allLeads'),
        ];
    }
    if (auth()->user()->hasPermissionTo('company_leads') || auth()->user()->hasPermissionTo('all_permissions')) {
        $salesManagementOptions[] = [
            'name' => 'Company Leads',
            'route' => route('leads.index'),
        ];
    }
    if (auth()->user()->hasPermissionTo('not_interested') || auth()->user()->hasPermissionTo('all_permissions')) {
        $salesManagementOptions[] = [
            'name' => 'Not Interested',
            'route' => route('leads.index', ['status' => '9']),
        ];
    }
    if (auth()->user()->hasPermissionTo('cosultation_booked') || auth()->user()->hasPermissionTo('all_permissions')) {
        $salesManagementOptions[] = [
            'name' => 'Consultation Booked',
            'route' => route('leads.index', ['status' => '16']),
        ];
    }
    if (auth()->user()->hasPermissionTo('sales_done') || auth()->user()->hasPermissionTo('all_permissions')) {
        $salesManagementOptions[] = [
            'name' => 'Sales Follow up',
            'route' => route('leads.index', ['status' => '6']),
        ];
    }
    if (auth()->user()->hasPermissionTo('sales_done') || auth()->user()->hasPermissionTo('all_permissions')) {
        $salesManagementOptions[] = [
            'name' => 'Sales Done',
            'route' => route('leads.index', ['status' => '7']),
        ];
    }

    $marketerOptions = [];
    if (auth()->user()->hasPermissionTo('marketing_campaign') || auth()->user()->hasPermissionTo('all_permissions')) {
        $marketerOptions[] = [
            'name' => 'Add Marketing Campaign',
            'route' => route('campaign.index'),
        ];
    }

    $hrManagementOptions = [];
    if (auth()->user()->hasPermissionTo('manage_departments') || auth()->user()->hasPermissionTo('all_permissions')) {
        $hrManagementOptions[] = [
            'name' => 'Manage Departments',
            'route' => url('company/' . \App\Models\Company::first()->id . '/department'),
        ];
    }

    if (auth()->user()->hasPermissionTo('all_permissions') || auth()->user()->hasPermissionTo('manage_employees')) {
        $hrManagementOptions[] = [
            'name' => 'Manage Employees',
            'route' => route('employee.index'),
        ];
    }
    if (auth()->user()->hasPermissionTo('all_permissions') || auth()->user()->hasPermissionTo('activity_logs')) {
        $hrManagementOptions[] = [
            'name' => 'Activity Logs',
            'route' => route('logs'),
        ];
    }

    $adminSettings = [];
    if (auth()->user()->hasPermissionTo('all_permissions') || auth()->user()->hasPermissionTo('calender_settings')) {
        $adminSettings[] = [
            'name' => 'Admin Settings',
            'route' => route('settings'),
        ];
    }
    if (auth()->user()->hasPermissionTo('all_permissions') || auth()->user()->hasPermissionTo('calender_settings')) {
        $adminSettings[] = [
            'name' => 'Calander',
            'route' => route('company.calender'),
        ];
    }
    if (auth()->user()->hasPermissionTo('all_permissions') || auth()->user()->hasPermissionTo('emails')) {
        $adminSettings[] = [
            'name' => 'Emails',
            'route' => route('email.index'),
        ];
    }

    $permissions = [];
    if (auth()->user()->hasPermissionTo('assign_permissions') || auth()->user()->hasPermissionTo('all_permissions')) {
        $adminSettings[] = [
            'name' => 'Permissions',
            'route' => route('permisions.index'),
        ];
        $adminSettings[] = [
            'name' => 'Items',
            'route' => route('items.index'),
        ];
    }

@endphp


<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow mt-3" data-scroll-to-active="true">
    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <x-sidebar.nav-link route="/" />
            <x-sidebar.nav-link title="Admin Settings" icon="ft-film" :options="$adminSettings" />
            <x-sidebar.nav-link title="Sales Management" icon="ft-credit-card" :options="$salesManagementOptions" />
            <x-sidebar.nav-link title="Marketer" icon="ft-film" :options="$marketerOptions" />
            <x-sidebar.nav-link title="HR Management" icon="ft-users" :options="$hrManagementOptions" />
        </ul>
    </div>
</div>
