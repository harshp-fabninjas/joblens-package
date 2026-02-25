<?php

namespace Fabninjas\JobLens;

use Fabninjas\JobLens\Console\SlowJobsCommand;
use Fabninjas\JobLens\Console\JobCostCommand;
use Fabninjas\JobLens\Console\JobHealthCommand;
use Fabninjas\JobLens\Listeners\JobAnalysis;
use Illuminate\Support\ServiceProvider;
use Fabninjas\JobLens\Listeners\TrackSlowJobs;

class JobLensServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/joblens.php',
            'joblens'
        );
    }

    public function boot()
    {
        // Publish the migration file for creating the slow_jobs table
        if ($this->app->runningInConsole()) {
            // Migration file
            $this->publishes([
                __DIR__.'/database/migrations/create_slow_jobs_table.php' =>
                database_path('migrations/'.date('Y_m_d_His').'_create_slow_jobs_table.php'),
            ], 'joblens-migrations');

            // Config file
            $this->publishes([
                __DIR__ . '/../config/joblens.php' => config_path('joblens.php'),
            ], 'joblens-config');
        }

        // Register the event listener to track slow jobs
        TrackSlowJobs::register();

        JobAnalysis::register();

        // Register the console command to show slow jobs
        if ($this->app->runningInConsole()) {
            $this->commands([
                SlowJobsCommand::class,
            ]);
        }
    }

}
