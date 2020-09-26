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

use App\Daira;
use Log;

class ImportCommunesJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
    * @var $url_communes
    */
    private $url_communes;

    /**
    * @var $url_communes_ar
    */
    private $url_communes_ar;

    /**
    * @var $communes array
    */
    private $communes;

    /**
    * Create a new job instance.
    *
    * @param  string $url_communes
    * @param  string $url_communes_ar
    *
    * @return void
    */
    public function __construct()
    {
        // links in french
        $this->url_communes = "https://www.interieur.gov.dz/data/comune2.php?_=".time()."&d=";
        // links in arabic
        $this->url_communes_ar = "https://www.interieur.gov.dz/data/comune2_ar.php?_=".time()."&d=";

        $this->communes = array();
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {

        Log::alert('Start import communes...');

        $dairas = Daira::all();

        // array for communes
        $communes = array();

        // for each daira, get all the communes
        $client = new Client();

        $request_communes = function ($dairas) use ($client) {
            for ($i = 0; $i < count($dairas); $i++) {
                $uri = $this->url_communes.$dairas->get($i)->code;
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        $request_communes_ar = function ($dairas) use ($client) {
            for ($i = 0; $i < count($dairas); $i++) {
                $uri = $this->url_communes_ar.$dairas->get($i)->code;
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        // execute for all dairas
        $pool_fr = new Pool($client, $request_communes($dairas), [
            'concurrency' => 20,
            'fulfilled' => function ($response, $index) use(&$communes, $dairas) {
                // get the response for each daira
                $body = (string) $response->getBody();

                // get all the options
                $doc = new \DOMDocument();
                @$doc->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));
                $options = $doc->getElementsByTagName('option');
                // get all the dairas
                foreach ($options as $option) {
                    $code = $option->getAttribute('value');
                    if($code != "") {
                        if(!isset($communes[$code])) {
                            $commune = [
                                'id' => intval($code),
                                'code' => $code,
                                'daira_id' => $dairas->get($index)->id,
                                'name' => title_case($option->nodeValue)
                            ];
                            $communes[$code] = $commune;
                        } else {
                            $communes[$code]['name'] = title_case($option->nodeValue);
                        }
                    }
                }

            },
            'rejected' => function ($reason, $index) {
                // DO NOTHING!
            },
        ]);

        $pool_ar = new Pool($client, $request_communes_ar($dairas), [
            'concurrency' => 20,
            'fulfilled' => function ($response, $index) use(&$communes, $dairas) {
                // get the response for each daira
                $body = (string) $response->getBody();

                // get all the options
                $doc = new \DOMDocument();
                @$doc->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));
                $options = $doc->getElementsByTagName('option');
                // get all the dairas
                foreach ($options as $option) {
                    $code = $option->getAttribute('value');
                    if($code != "") {
                        if(!isset($communes[$code])) {
                            $commune = [
                                'id' => intval($code),
                                'code' => $code,
                                'daira_id' => $dairas->get($index)->id,
                                'name_ar' => $option->nodeValue
                            ];
                            $communes[$code] = $commune;
                        } else {
                            $communes[$code]['name_ar'] = $option->nodeValue;
                        }
                    }
                }

            },
            'rejected' => function ($reason, $index) {
                // DO NOTHING!
            },
        ]);

        $promise_fr = $pool_fr->promise();
        $promise_fr->wait();

        $promise_ar = $pool_ar->promise();
        $promise_ar->wait();

        Log::alert('Finish import communes: '.count($communes).'...');

        // Save in database
        $this->communes = $communes;
        dispatch(new SaveCommunesJob($communes));

    }
}
