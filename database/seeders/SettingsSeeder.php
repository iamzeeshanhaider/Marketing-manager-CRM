<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SettingsModel;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingsModel::create([
            'mailgun_domain' => 'mailer.guardians-training.co.uk',
            'mailgun_secret' => 'key-86c1a22d4074c1eb9954e4d0f1c290f7',
        ]);
    }
}
