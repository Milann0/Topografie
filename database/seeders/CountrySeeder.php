<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['code' => 'PT', 'name' => 'Portugal'],
            ['code' => 'ES', 'name' => 'Spain'],
            ['code' => 'FR', 'name' => 'France'],
            ['code' => 'BE', 'name' => 'Belgium'],
            ['code' => 'NL', 'name' => 'Netherlands'],
            ['code' => 'LU', 'name' => 'Luxembourg'],
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'IE', 'name' => 'Ireland'],
            ['code' => 'DE', 'name' => 'Germany'],
            ['code' => 'DK', 'name' => 'Denmark'],
            ['code' => 'NO', 'name' => 'Norway'],
            ['code' => 'SE', 'name' => 'Sweden'],
            ['code' => 'FI', 'name' => 'Finland'],
            ['code' => 'EE', 'name' => 'Estonia'],
            ['code' => 'LV', 'name' => 'Latvia'],
            ['code' => 'LT', 'name' => 'Lithuania'],
            ['code' => 'PL', 'name' => 'Poland'],
            ['code' => 'CZ', 'name' => 'Czech Republic'],
            ['code' => 'SK', 'name' => 'Slovakia'],
            ['code' => 'AT', 'name' => 'Austria'],
            ['code' => 'HU', 'name' => 'Hungary'],
            ['code' => 'CH', 'name' => 'Switzerland'],
            ['code' => 'IT', 'name' => 'Italy'],
            ['code' => 'SI', 'name' => 'Slovenia'],
            ['code' => 'HR', 'name' => 'Croatia'],
            ['code' => 'BA', 'name' => 'Bosnia and Herzegovina'],
            ['code' => 'RS', 'name' => 'Serbia'],
            ['code' => 'ME', 'name' => 'Montenegro'],
            ['code' => 'XK', 'name' => 'Kosovo'],
            ['code' => 'AL', 'name' => 'Albania'],
            ['code' => 'MK', 'name' => 'North Macedonia'],
            ['code' => 'GR', 'name' => 'Greece'],
            ['code' => 'BG', 'name' => 'Bulgaria'],
            ['code' => 'RO', 'name' => 'Romania'],
            ['code' => 'MD', 'name' => 'Moldova'],
            ['code' => 'UA', 'name' => 'Ukraine'],
            ['code' => 'BY', 'name' => 'Belarus'],
            ['code' => 'RU', 'name' => 'Russia (western part)'],
            ['code' => 'TR', 'name' => 'Turkey (European part)'],
            ['code' => 'IS', 'name' => 'Iceland'],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'code' => $country['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
