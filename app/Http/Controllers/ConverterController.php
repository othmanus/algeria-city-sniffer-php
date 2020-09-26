<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Wilaya;
use App\Daira;
use App\Commune;

use File;
use Excel;

class ConverterController extends Controller
{
    /**
     * Convert to json file
     *
     * @return File
     */
    public function toEmbeddedJson(Request $request)
    {
        // $lang = $request->get('lang');
        // get embedded array
        $output = $this->toEmbeddedArray();

        // Create the file
        $json = json_encode($output, JSON_UNESCAPED_UNICODE);
        File::put(public_path('communes.json'), $json);
        return response()->download(public_path('communes.json'));
    }

    /**
     * Convert to csv file
     *
     * @return File
     */
    public function toEmbeddedCsv(Request $request)
    {
        // get embedded array
        $output = $this->toArray();

        $excel = Excel::create('communes', function($excel) use($output) {
            $excel->sheet('Algérie', function($sheet) use($output) {
                $sheet->fromArray($output);
            });
        })->store('csv', public_path());

        return $excel->export('csv');
    }

    /**
    * Convert to excel file
    *
    * @return File
    */
    public function toEmbeddedExcel(Request $request)
    {
        // get embedded array
        $output = $this->toArray();

        $excel = Excel::create('communes', function($excel) use($output) {
            $excel->sheet('Algérie', function($sheet) use($output) {
                $sheet->fromArray($output);
            });
        })->store('xlsx', public_path());

        return $excel->export('xlsx');
    }

    /**
    * Convert to xml file
    *
    * @return File
    */
    public function toEmbeddedXml(Request $request)
    {
        // get embedded array
        $wilayas = Wilaya::all();

        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><wilayas></wilayas>");

        foreach($wilayas as $wilaya) {
            $w = $xml->addChild("wilaya");
            $w->addAttribute("code", $wilaya->code);
            $w->addChild("name", $wilaya->name);
            $w->addChild("name_ar", $wilaya->name_ar);

            $dairas = $w->addChild("dairas");
            foreach($wilaya->dairas as $daira) {
                $d = $dairas->addChild("daira");
                $d->addAttribute("code", $daira->code);
                $d->addChild("name", $daira->name);
                $d->addChild("name_ar", $daira->name_ar);

                $communes = $d->addChild("communes");
                foreach($daira->communes as $commune) {
                    $c = $communes->addChild("commune");
                    $c->addAttribute("code", $commune->code);
                    $c->addChild("name", $commune->name);
                    $c->addChild("name_ar", $commune->name_ar);
                }
            }
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $dom->saveXML();
        $dom->save(public_path('communes.xml'));
        return response()->download(public_path('communes.xml'));
    }

    public function toEmbeddedPhp(Request $request)
    {
        // get embedded array
        $output = $this->toEmbeddedArray();

        // Create the file
        File::put(public_path('communes.php'), $this->outArray($output));
        return response()->download(public_path('communes.php'));
    }

    /**
     * Make an embedded array of communes within dairas within wilayas
     *
     * @param  $lang fr, ar or both
     * @return array
     */
    private function toEmbeddedArray($lang = "both")
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

                    $d["communes"][$commune->code] = $c;
                }

                $w["dairas"][$daira->code] = $d;
            }

            $output["wilayas"][$wilaya->code] = $w;
        }

        return $output;
    }

    /**
     * Make an array of communes within dairas within wilayas
     *
     * @param  $lang fr, ar or both
     * @return array
     */
    private function toArray($lang = "both")
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

        return $output;
    }

    public function outArray($array, $lvl=0){
        $sub = $lvl+1;
        $return = "";
        if($lvl==null){
            $return = "<?php\n\treturn array(\n";
        }
        foreach($array as $key => $mixed){
            $key = trim($key);
            if(!is_array($mixed)){
                $mixed = "'".addslashes(trim($mixed))."'";
            }
            if(empty($key) && empty($mixed)){continue;}
            $key = "'".addslashes($key)."'";

            if($mixed === null){
                $mixed = 'null';
            } elseif($mixed === false){
                $mixed = 'false';
            } elseif($mixed === true){
                $mixed = 'true';
            } elseif($mixed === ""){
                $mixed = "''";
            }

            //CONVERT STRINGS 'true', 'false' and 'null' TO true, false and null
            //uncomment if needed
            //elseif(!is_numeric($mixed) && !is_array($mixed) && !empty($mixed)){
            //  if($mixed != 'false' && $mixed != 'true' && $mixed != 'null'){
            //    $mixed = "'".addslashes($mixed)."'";
            //  }
            //}


            if(is_array($mixed)){
                if($key !== null){
                    $return .= "\t".str_repeat("\t", $sub)."$key => array(\n";
                    $return .= $this->outArray($mixed, $sub);
                    $return .= "\t".str_repeat("\t", $sub)."),\n";
                } else {
                    $return .= "\t".str_repeat("\t", $sub)."array(\n";
                    $return .= $this->outArray($mixed, $sub);
                    $return .= "\t".str_repeat("\t", $sub)."),\n";
                }
            } else {
                if($key !== null){
                    $return .= "\t".str_repeat("\t", $sub)."$key => $mixed,\n";
                } else {
                    $return .= "\t".str_repeat("\t", $sub).$mixed.",\n";
                }
            }
        }
        if($lvl==null){
            $return .= "\t);\n";
        }
        return $return;
    }
}
