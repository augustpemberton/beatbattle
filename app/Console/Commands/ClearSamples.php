<?php

namespace App\Console\Commands;

use App\Sample;
use Storage;
use Illuminate\Console\Command;

class ClearSamples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'samples:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes sample files from server.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $samples = Sample::get();
        foreach ($samples as $sample) {
          Storage::disk('uploads')->delete($sample->path);
        }
    }
}
