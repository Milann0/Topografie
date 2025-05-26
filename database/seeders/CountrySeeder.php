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
        DB::table('countries')->truncate();
        $countries = [
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Italy', 'code' => 'IT'],
            ['name' => 'Spain', 'code' => 'ES'],
            ['name' => 'Poland', 'code' => 'PL'],
            ['name' => 'Romania', 'code' => 'RO'],
            ['name' => 'Netherlands', 'code' => 'NL'],
            ['name' => 'Belgium', 'code' => 'BE'],
            ['name' => 'Czech Republic', 'code' => 'CZ'],
            ['name' => 'Greece', 'code' => 'GR'],
            ['name' => 'Portugal', 'code' => 'PT'],
            ['name' => 'Sweden', 'code' => 'SE'],
            ['name' => 'Hungary', 'code' => 'HU'],
            ['name' => 'Austria', 'code' => 'AT'],
            ['name' => 'Belarus', 'code' => 'BY'],
            ['name' => 'Switzerland', 'code' => 'CH'],
            ['name' => 'Bulgaria', 'code' => 'BG'],
            ['name' => 'Serbia', 'code' => 'RS'],
            ['name' => 'Denmark', 'code' => 'DK'],
            ['name' => 'Finland', 'code' => 'FI'],
            ['name' => 'Slovakia', 'code' => 'SK'],
            ['name' => 'Norway', 'code' => 'NO'],
            ['name' => 'Ireland', 'code' => 'IE'],
            ['name' => 'Croatia', 'code' => 'HR'],
            ['name' => 'Bosnia and Herzegovina', 'code' => 'BA'],
            ['name' => 'Albania', 'code' => 'AL'],
            ['name' => 'Lithuania', 'code' => 'LT'],
            ['name' => 'Slovenia', 'code' => 'SI'],
            ['name' => 'Latvia', 'code' => 'LV'],
            ['name' => 'Estonia', 'code' => 'EE'],
            ['name' => 'North Macedonia', 'code' => 'MK'],
            ['name' => 'Moldova', 'code' => 'MD'],
            ['name' => 'Luxembourg', 'code' => 'LU'],
            ['name' => 'Malta', 'code' => 'MT'],
            ['name' => 'Iceland', 'code' => 'IS'],
            ['name' => 'Turkey', 'code' => 'TR'],
            ['name' => 'Montenegro', 'code' => 'ME'],
            ['name' => 'Kosovo', 'code' => 'XK'],
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
