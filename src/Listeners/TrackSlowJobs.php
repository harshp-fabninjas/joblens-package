<?php

namespace Fabninjas\JobLens\Listeners;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\DB;

class TrackSlowJobs
{
    protected static $startTimes = [];

    public static function register()
    {
        Queue::before(function (JobProcessing $event) {
            self::$startTimes[$event->job->getJobId()] = microtime(true);
        });

        Queue::after(function (JobProcessed $event) {
            $jobId = $event->job->getJobId();

            if (!isset(self::$startTimes[$jobId])) {
                return;
            }

            $executionTime = microtime(true) - self::$startTimes[$jobId];

            $jobClass = $event->job->resolveName();

            // get the timeout for the job

            $declareSlowAt = config('joblens.slow_job_threshold');

            if (class_exists($jobClass)) {
                $instance = app($jobClass);
                $timeout = $instance->timeout ?? null;

                if($timeout){
                    $declareSlowAt = $timeout * 0.8; // 80% of the timeout as the slow threshold
                }
            }

            logger()->info("Job {$jobClass} executed in {$executionTime} seconds.");
            logger()->info("Declared slow threshold for {$jobClass} is {$declareSlowAt} seconds.");

            if ($executionTime > $declareSlowAt) {
                DB::table('slow_jobs')->insert([
                    'job_name' => $jobClass,
                    'execution_time' => $executionTime,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
