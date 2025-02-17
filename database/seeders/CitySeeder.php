<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'Alger'],
            ['name' => 'Luanda'],
            ['name' => 'Cotonou'],
            ['name' => 'Gaborone'],
            ['name' => 'Ouagadougou'],
            ['name' => 'Bujumbura'],
            ['name' => 'Praia'],
            ['name' => 'Yaoundé'],
            ['name' => 'Bangui'],
            ['name' => 'N\'Djamena'],
            ['name' => 'Moroni'],
            ['name' => 'Brazzaville'],
            ['name' => 'Kinshasa'],
            ['name' => 'Djibouti'],
            ['name' => 'Le Caire'],
            ['name' => 'Malabo'],
            ['name' => 'Asmara'],
            ['name' => 'Mbabane'],
            ['name' => 'Addis-Abeba'],
            ['name' => 'Libreville'],
            ['name' => 'Banjul'],
            ['name' => 'Accra'],
            ['name' => 'Conakry'],
            ['name' => 'Bissau'],
            ['name' => 'Nairobi'],
            ['name' => 'Maseru'],
            ['name' => 'Monrovia'],
            ['name' => 'Tripoli'],
            ['name' => 'Antananarivo'],
            ['name' => 'Lilongwe'],
            ['name' => 'Bamako'],
            ['name' => 'Nouakchott'],
            ['name' => 'Port-Louis'],
            ['name' => 'Rabat'],
            ['name' => 'Maputo'],
            ['name' => 'Windhoek'],
            ['name' => 'Niamey'],
            ['name' => 'Abuja'],
            ['name' => 'Kigali'],
            ['name' => 'Sao Tomé'],
            ['name' => 'Dakar'],
            ['name' => 'Victoria'],
            ['name' => 'Freetown'],
            ['name' => 'Mogadiscio'],
            ['name' => 'Pretoria'],
            ['name' => 'Juba'],
            ['name' => 'Khartoum'],
            ['name' => 'Dodoma'],
            ['name' => 'Lomé'],
            ['name' => 'Tunis'],
            ['name' => 'Kampala'],
            ['name' => 'Lusaka'],
            ['name' => 'Harare'],
            // 🌆 Villes touristiques européennes
            ['name' => 'Paris'], ['name' => 'Londres'], ['name' => 'Rome'], ['name' => 'Berlin'],
            ['name' => 'Madrid'], ['name' => 'Barcelone'], ['name' => 'Amsterdam'], ['name' => 'Lisbonne'],
            ['name' => 'Vienne'], ['name' => 'Prague'], ['name' => 'Budapest'], ['name' => 'Stockholm'],
            ['name' => 'Athènes'], ['name' => 'Bruxelles'], ['name' => 'Milan'], ['name' => 'Dublin'],

            // 🗽 Villes les plus visitées en Amérique
            ['name' => 'New York'], ['name' => 'Los Angeles'], ['name' => 'Las Vegas'], ['name' => 'San Francisco'],
            ['name' => 'Miami'], ['name' => 'Orlando'], ['name' => 'Chicago'], ['name' => 'Washington'],
            ['name' => 'Toronto'], ['name' => 'Vancouver'], ['name' => 'Montréal'], ['name' => 'Rio de Janeiro'],
            ['name' => 'São Paulo'], ['name' => 'Buenos Aires'], ['name' => 'Mexico City'], ['name' => 'Lima'],

            // 🏯 Villes touristiques en Asie
            ['name' => 'Bangkok'], ['name' => 'Tokyo'], ['name' => 'Singapour'], ['name' => 'Hong Kong'],
            ['name' => 'Dubaï'], ['name' => 'Shanghai'], ['name' => 'Séoul'], ['name' => 'Bali'],
            ['name' => 'Kuala Lumpur'], ['name' => 'Delhi'], ['name' => 'Istanbul'], ['name' => 'Doha']
                   
        ];

        DB::table('cities')->insert($cities);
    }
}
