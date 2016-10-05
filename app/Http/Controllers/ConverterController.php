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
    public function toEmbeddedJson(Request $request)
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
        $lang = $request->get('lang');

        $output = array();
        $output["wilayas"] = [];
        // get all wilayas
        $wilayas = Wilaya::all();
        foreach($wilayas as $wilaya) {
            $w = [
                "id" => $wilaya->id,
                "code" => $wilaya->code,
            ];

            switch($lang) {
                case "ar":
                $w["name_ar"] = $wilaya->name_ar;
                break;

                case "fr":
                $w["name"] = $wilaya->name;
                break;

                default:
                $w["name_ar"] = $wilaya->name_ar;
                $w["name"] = $wilaya->name;
                break;
            }

            // get all dairas
            $dairas = $wilaya->dairas;
            foreach($dairas as $daira) {
                $d = [
                    "id" => $daira->id,
                    "code" => $daira->code,
                ];

                switch($lang) {
                    case "ar":
                    $d["name_ar"] = $daira->name_ar;
                    break;

                    case "fr":
                    $d["name"] = $daira->name;
                    break;

                    default:
                    $d["name_ar"] = $daira->name_ar;
                    $d["name"] = $daira->name;
                    break;
                }

                // get all communes
                $communes = $daira->communes;
                foreach($communes as $commune) {
                    $c = [
                        "id" => $commune->id,
                        "code" => $commune->code,
                    ];

                    switch($lang) {
                        case "ar":
                        $c["name_ar"] = $commune->name_ar;
                        break;

                        case "fr":
                        $c["name"] = $commune->name;
                        break;

                        default:
                        $c["name_ar"] = $commune->name_ar;
                        $c["name"] = $commune->name;
                        break;
                    }

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

    /**
     * Convert to csv file
     *
     * @return File
     */
    public function toCsv(Request $request)
    {
        
    }
}
