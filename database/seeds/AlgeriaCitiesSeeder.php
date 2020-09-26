<?php

use App\Wilaya;
use Illuminate\Database\Seeder;

class AlgeriaCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ---------------------------------------------------------------------
        // Normal version
        // ---------------------------------------------------------------------
        // [
        //     {
        //         "wilaya_code": "...",
        //         "wilaya_name": "...",
        //         "wilaya_name_ar": "...",
        //         "daira_code": "...",
        //         "daira_name": "...",
        //         "daira_name_ar": "...",
        //         "commune_code": "...",
        //         "commune_name": "...",
        //         "commune_name_ar": "...",
        //     },
        //     { ... }
        // ]
        // ---------------------------------------------------------------------
        $output = array();
        // get all wilayas
        $wilayas = Wilaya::all();
        foreach($wilayas as $wilaya) {
            // get all dairas
            $dairas = $wilaya->dairas;
            foreach($dairas as $daira) {
                // get all communes
                $communes = $daira->communes;
                foreach($communes as $commune) {
                    $c = [
                        "wilaya_code" => $wilaya->code,
                        "wilaya_name" => $wilaya->name,
                        "wilaya_name_ar" => $wilaya->name_ar,

                        "daira_code" => $daira->code,
                        "daira_name" => $daira->name,
                        "daira_name_ar" => $daira->name_ar,

                        "commune_code" => $commune->code,
                        "commune_name" => $commune->name,
                        "commune_name_ar" => $commune->name_ar,
                    ];
                    $output[] = $c;
                }
            }
        }

        DB::table('algeria_cities')->insert($output);
    }
}
