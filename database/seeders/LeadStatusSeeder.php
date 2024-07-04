<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\LeadStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colorCodes = LeadStatus::defaultColorCodes();
        $leadStatuses = ['Open', 'Prospect', 'Contacted', 'Consultation Booked', 'Consultation Missed','Sales Followup','Sale Done','In Accounting Role','Not Interested','Industry Changed','Ineligible','Out of UK'];

        foreach (Company::all() as $company) {
            foreach ($leadStatuses as $status) {
                $company->leadStatus()->create([
                    'name' => $status,
                    'color_code' => $colorCodes[array_rand($colorCodes)], // Add the missing closing parenthesis
                ]);
            }
        }
    }
}
