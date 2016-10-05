<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Daira;
use Log;

class SaveDairasJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $dairas
     */
    private $dairas;

    /**
     * Create a new job instance.
     *
     * @param  array $dairas
     * @return void
     */
    public function __construct($dairas)
    {
        $this->dairas = $dairas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::alert('Start save dairas...');

        foreach($this->dairas as $daira) {
            if(!Daira::find($daira['id']))
                Daira::create($daira);

            // Log::info("Save daira: ".$daira['code']." - ".$daira['name']);
        }

        Log::alert('Finish save dairas...');
        dispatch(new ImportCommunesJob());
    }
}
