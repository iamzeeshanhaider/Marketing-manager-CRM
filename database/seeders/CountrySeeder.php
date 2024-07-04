<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Country;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(storage_path('keys/all_countries.json'));
        $data = json_decode($json, true);
        foreach ($data as $countryData) {
            $phoneCode =  isset($countryData['idd']['root']) ? $countryData['idd']['root'] . $countryData['idd']['suffixes'][0] : "";
            Country::create([
                'name' => $countryData['name']['common'],
                'phonecode' => $phoneCode,
                'capital' => isset($countryData['capital'])  ? $countryData['capital'][0] : "",
                'currency' =>  isset($countryData['currencies']) ?  array_key_first($countryData['currencies']) : "",
                'region' => $countryData['region'],
                'subregion' => $countryData['subregion'] ?? "",
                'flag' => $countryData['flags']['png'],
                'iso2' => $countryData['cca2'],
                'iso3' => $countryData['cca3'],
            ]);
        }
    }
}
