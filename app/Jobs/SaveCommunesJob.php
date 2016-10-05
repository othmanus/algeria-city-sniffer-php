<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Commune;
use Log;

class SaveCommunesJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $communes
     */
    private $communes;

    /**
     * Create a new job instance.
     *
     * @param  array $communes
     * @return void
     */
    public function __construct($communes)
    {
        $this->communes = $communes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::alert('Start save communes...');

        foreach($this->communes as $commune) {
            if(!Commune::find($commune['id']))
                Commune::create($commune);

            // Log::info("Save commune: ".$commune['code']." - ".$commune['name']);
        }

        Log::alert('Finish save communes...');
    }
}
