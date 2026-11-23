<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\County;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // A Pest és a Nógrád van elöl, hogy az id-juk (1, 2) egyezzen a Bruno kérésekkel.
        $data = [
            'Pest' => [
                'Vác' => '2600', 'Szob' => '2628', 'Szentendre' => '2000',
                'Gödöllő' => '2100', 'Cegléd' => '2700', 'Dunakeszi' => '2120',
                'Érd' => '2030',
            ],
            'Nógrád' => [
                'Balassagyarmat' => '2660', 'Salgótarján' => '3100',
            ],
            'Komárom-Esztergom' => [
                'Esztergom' => '2500', 'Tatabánya' => '2800', 'Tata' => '2890',
            ],
            'Fejér' => ['Székesfehérvár' => '8000'],
            'Veszprém' => ['Veszprém' => '8200'],
            'Győr-Moson-Sopron' => ['Győr' => '9021', 'Sopron' => '9400'],
            'Vas' => ['Szombathely' => '9700'],
            'Zala' => ['Zalaegerszeg' => '8900', 'Nagykanizsa' => '8800'],
            'Somogy' => ['Kaposvár' => '7400', 'Siófok' => '8600'],
            'Tolna' => ['Szekszárd' => '7100'],
            'Baranya' => ['Pécs' => '7621'],
            'Bács-Kiskun' => ['Kecskemét' => '6000'],
            'Csongrád-Csanád' => ['Szeged' => '6720', 'Szentes' => '6600'],
            'Békés' => ['Békéscsaba' => '5600'],
            'Jász-Nagykun-Szolnok' => ['Szolnok' => '5000'],
            'Hajdú-Bihar' => ['Debrecen' => '4024'],
            'Szabolcs-Szatmár-Bereg' => ['Nyíregyháza' => '4400'],
            'Borsod-Abaúj-Zemplén' => ['Miskolc' => '3525'],
            'Heves' => ['Eger' => '3300'],
        ];

        foreach ($data as $countyName => $cities) {
            $county = County::create(['name' => $countyName]);

            foreach ($cities as $cityName => $zip) {
                City::create([
                    'county_id' => $county->id,
                    'name' => $cityName,
                    'zip_code' => $zip,
                ]);
            }
        }
    }
}
