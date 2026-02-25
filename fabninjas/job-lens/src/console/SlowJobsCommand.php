<?php

namespace Fabninjas\JobLens\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SlowJobsCommand extends Command
{
    protected $signature = 'joblens:slowjobs';

    protected $description = 'Show slow jobs';

    public function handle()
    {
        $limit = 5;

        $jobs = DB::table('slow_jobs')
            ->orderByDesc('execution_time')
            ->limit($limit)
            ->get();

        if ($jobs->isEmpty()) {
            $this->info('No slow jobs found.');
            return Command::SUCCESS;
        }

        $this->table(
            ['Job Name', 'Queue', 'Time (seconds)', 'Date'],
            $jobs->map(function ($job) {
                return [
                    $job->job_name,
                    $job->queue ?? '-',
                    round($job->execution_time, 2),
                    $job->created_at,
                ];
            })
        );

        return Command::SUCCESS;
    }
}
