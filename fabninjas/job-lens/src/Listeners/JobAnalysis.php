<?php

namespace Fabninjas\JobLens\Listeners;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;

class JobAnalysis
{
    protected static array $startTimes = [];
    protected static array $externalApiCalls = [];

    public static function register()
    {
        if (config('joblens.track_external_api')) {

            Event::listen(RequestSending::class, function ($event) {

                $jobId = app()->bound('joblens.current_job_id')
                    ? app('joblens.current_job_id')
                    : null;

                if (!$jobId) return;

                self::$externalApiCalls[$jobId][] = [
                    'url' => (string) $event->request->url(),
                    'method' => $event->request->method(),
                    'start_time' => microtime(true),
                    'time' => null,
                ];
            });

            Event::listen(ResponseReceived::class, function ($event) {

                $jobId = app()->bound('joblens.current_job_id')
                    ? app('joblens.current_job_id')
                    : null;

                if (!$jobId || empty(self::$externalApiCalls[$jobId])) return;

                foreach (self::$externalApiCalls[$jobId] as &$call) {
                    if (
                        $call['url'] === (string) $event->request->url() &&
                        $call['time'] === null
                    ) {
                        $call['time'] = microtime(true) - $call['start_time'];
                    }
                }
            });
        }

        Queue::before(function (JobProcessing $event) {

            $jobId = $event->job->getJobId();

            app()->instance('joblens.current_job_id', $jobId);

            self::$startTimes[$jobId] = [
                'time' => microtime(true),
            ];
        });

        Queue::after(function (JobProcessed $event) {

            $jobId = $event->job->getJobId();

            if (!isset(self::$startTimes[$jobId])) {
                return;
            }

            $start = self::$startTimes[$jobId];

            $executionTime = microtime(true) - $start['time'];
            $jobName = $event->job->resolveName();

            $apiCalls = self::$externalApiCalls[$jobId] ?? [];

            $totalApiTime = 0;
            $apiCount = count($apiCalls);

            foreach ($apiCalls as $call) {
                if (!empty($call['time'])) {
                    $totalApiTime += $call['time'];
                }
            }

            dump(sprintf(
                "[JobLens] %-20s | %5.2fs | EXTERNAL API: %2d calls (%4.2fs)",
                $jobName,
                $executionTime,
                $apiCount,
                $totalApiTime
            ));

            unset(self::$startTimes[$jobId]);
            unset(self::$externalApiCalls[$jobId]);
            app()->forgetInstance('joblens.current_job_id');
        });
    }
}