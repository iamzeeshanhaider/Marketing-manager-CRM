<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Guardians Training', 'Guardians Accountants', 'Guardians Cooperate', 'Rizwa Hotels'] as $coy_name) {
            $company = Company::create(['name' => $coy_name, 'email' => fake()->safeEmail()]);
            for ($i = 0; $i < 3; $i++) {
                $company->departments()->create(['name' => fake()->word]);
            }
        }
    }
}
