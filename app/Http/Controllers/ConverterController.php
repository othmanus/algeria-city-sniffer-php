<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Wilaya;
use App\Daira;
use App\Commune;

use Storage;

class ConverterController extends Controller
{
    /**
     * Convert to json file
     *
     * @return File
     */
    public function toEmbeddedJson()
    {
        // ---------------------------------------------------------------------
        // Embedded version
        // ---------------------------------------------------------------------
        // The format of the json file will be:
        // {
        //     "wilayas": [
        //         {
        //             "id": "...", "code": "...", "name": "...", "name_ar": "...",
        //             "dairas": [
        //                 {
        //                     "id": "...", "code": "...", "name": "...", "name_ar": "...",
        //                     "communes": [
        //                         {
        //                             "id": "...", "code": "...", "name": "...", "name_ar": "..."
        //                         }
        //                     ]
        //                 }
        //             ]
        //         }
        //     ]
        // }
        // ---------------------------------------------------------------------
        $output = array();
        $output["wilayas"] = [];
        // get all wilayas
        $wilayas = Wilaya::all();
        foreach($wilayas as $wilaya) {
            $w = [
                "id" => $wilaya->id,
                "code" => $wilaya->code,
                "name" => $wilaya->name,
                "name_ar" => $wilaya->name_ar,
            ];

            // get all dairas
            $dairas = $wilaya->dairas;
            foreach($dairas as $daira) {
                $d = [
                    "id" => $daira->id,
                    "code" => $daira->code,
                    "name" => $daira->name,
                    "name_ar" => $daira->name_ar,
                ];

                // get all communes
                $communes = $daira->communes;
                foreach($communes as $commune) {
                    $c = [
                        "id" => $commune->id,
                        "code" => $commune->code,
                        "name" => $commune->name,
                        "name_ar" => $commune->name_ar,
                    ];

                    $d["communes"][] = $c;
                }

                $w["dairas"][] = $d;
            }

            $output["wilayas"][] = $w;
        }
        // Create the file
        $json = json_encode(json_decode(json_encode($output, JSON_UNESCAPED_UNICODE)), JSON_PRETTY_PRINT);
        Storage::disk('public')->put('communes.json', $json);
        return response()->download(public_path('storage/communes.json'));
    }
}
