<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;

use Log;

class ImportDairasJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
    * @var $url_dairas
    */
    private $url_dairas;

    /**
    * @var $url_dairas_ar
    */
    private $url_dairas_ar;

    /**
    * @var $dairas array
    */
    private $dairas;

    /**
    * Create a new job instance.
    *
    * @param  string $url_dairas
    * @param  string $url_dairas_ar
    *
    * @return void
    */
    public function __construct()
    {
        // links in french
        $this->url_dairas = "https://www.interieur.gov.dz/data/comune.php?_=".time()."&w=";
        // links in arabic
        $this->url_dairas_ar = "https://www.interieur.gov.dz/data/comune_ar.php?_=".time()."&w=";

        $this->dairas = array();
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        Log::alert('Start import dairas...');

        // array for dairas
        $dairas = array();

        // array for communes
        $communes = array();

        // create the client (Guzzle)
        $client = new Client([
            'connect_timeout' => 600
        ]);

        // search dairas for 48 wilayas (fr)
        $request_dairas = function ($total) use ($client) {
            for ($i = 1; $i <= $total; $i++) {
                $uri = $this->url_dairas."$i";
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };
        // search dairas for 48 wilayas (ar)
        $request_dairas_ar = function ($total) use ($client) {
            for ($i = 1; $i <= $total; $i++) {
                $uri = $this->url_dairas_ar."$i";
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        // get all dairas in french
        $pool_fr = new Pool($client, $request_dairas(48), [
            'concurrency' => 20,
            'fulfilled' => function ($response, $index) use (&$dairas) {
                // get the response for each wilaya
                $body = (string) $response->getBody();

                // each response is an HTML select tag, containing many options
                // containing dairas, so we have to filter each option and
                // get the key => value which is "code" => "name"

                // get all the options
                $doc = new \DOMDocument();
                @$doc->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));

                // get all the dairas
                $options = $doc->getElementsByTagName('option');
                foreach ($options as $option) {
                    $code = $option->getAttribute('value');

                    if($code != "") {
                        if(!isset($dairas[$code])) {
                            $daira = [
                                'id' => intval($code),
                                'code' => $code,
                                'wilaya_id' => $index+1,
                                'name' => title_case($option->nodeValue)
                            ];
                            $dairas[$code] = $daira;

                            Log::info("Import daira: ".$code." - ".$option->nodeValue);
                        } else {
                            $daira[$code]['name'] = title_case($option->nodeValue);
                        }
                    }

                }

            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                // DO NOTHING!
                Log::error('Failed to import dairas: '.$reason);
            },
        ]);

        // Get arabic names of dairas
        $pool_ar = new Pool($client, $request_dairas_ar(48), [
            'concurrency' => 20,
            'fulfilled' => function ($response, $index) use (&$dairas) {

                // get the response for each wilaya
                $body = (string) $response->getBody();

                // get all the options
                $doc = new \DOMDocument();
                @$doc->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));

                // get all the dairas
                $options = $doc->getElementsByTagName('option');

                foreach ($options as $option) {
                    $code = $option->getAttribute('value');

                    if($code != "") {
                        if(!isset($dairas[$code])) {
                            $daira = [
                                'id' => intval($code),
                                'code' => $code,
                                'wilaya_id' => $index+1,
                                'name_ar' => $option->nodeValue
                            ];
                            $dairas[$code] = $daira;
                        } else {
                            $dairas[$code]['name_ar'] = $option->nodeValue;

                            Log::info("Import daira (ar): ".$code." - ".$option->nodeValue);
                        }
                    }
                }

            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                // DO NOTHING!
                Log::error('Failed to import dairas (ar): '.$reason);
            },
        ]);

        $promise_fr = $pool_fr->promise();
        $promise_fr->wait();
        $promise_ar = $pool_ar->promise();
        $promise_ar->wait();


        Log::alert('Finish import dairas: '.count($dairas).'...');

        // Save in database
        $this->dairas = $dairas;
        dispatch(new SaveDairasJob($dairas));

    }
}
