<?php

namespace Celebgramme\Jobs;

use Celebgramme\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Celebgramme\Models\AmazonLog;

use Carbon;

class Automation extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			$dt = Carbon::now();
        $log = new AmazonLog;
				$log->created = $dt->toDateTimeString();
				$log->save();
    }
}
