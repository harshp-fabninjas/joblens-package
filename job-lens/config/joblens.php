<?php

return [

    // If job has timeout mentioned, then slow job threshold will be 80% of the timeout by default,  otherwise it will be the value mentioned below
    'slow_job_threshold' => 2, // seconds

    // Whether to track external API calls made during job execution. This can help identify if slow jobs are due to external dependencies.
    'track_external_api' => true,

];
