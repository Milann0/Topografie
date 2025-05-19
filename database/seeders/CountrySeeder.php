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
            'Albania', 'Andorra', 'Armenia', 'Austria', 'Azerbaijan',
            'Belarus', 'Belgium', 'Bosnia and Herzegovina', 'Bulgaria',
            'Croatia', 'Cyprus', 'Czech Republic', 'Denmark', 'Estonia',
            'Finland', 'France', 'Georgia', 'Germany', 'Greece',
            'Hungary', 'Iceland', 'Ireland', 'Italy', 'Kazakhstan',
            'Kosovo', 'Latvia', 'Liechtenstein', 'Lithuania',
            'Luxembourg', 'Malta', 'Moldova', 'Monaco', 'Montenegro',
            'Netherlands', 'North Macedonia', 'Norway', 'Poland',
            'Portugal', 'Romania', 'Russia', 'San Marino', 'Serbia',
            'Slovakia', 'Slovenia', 'Spain', 'Sweden', 'Switzerland',
            'Turkey', 'Ukraine', 'United Kingdom', 'Vatican City'
        ];

        $countryCodes = [
            'AL', 'AD', 'AM', 'AT', 'AZ',
            'BY', 'BE', 'BA', 'BG',
            'HR', 'CY', 'CZ', 'DK', 'EE',
            'FI', 'FR', 'GE', 'DE', 'GR',
            'HU', 'IS', 'IE', 'IT', 'KZ',
            'XK', 'LV', 'LI', 'LT',
            'LU', 'MT', 'MD', 'MC', 'ME',
            'NL', 'MK', 'NO', 'PL',
            'PT', 'RO', 'RU', 'SM', 'RS',
            'SK', 'SI', 'ES', 'SE', 'CH',
            'TR', 'UA', 'GB', 'VA'
        ];

        foreach ($countries as $index => $country) {
            DB::table('brands')->insert([
                'name' => $country,
                'code' => $countryCodes[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
