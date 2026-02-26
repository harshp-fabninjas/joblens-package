<?php

namespace Fabninjas\JobLens\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class JobLensController extends Controller
{
    public function index()
    {
        $jobs = DB::table('slow_jobs')
            ->latest()
            ->paginate(15);

        return view('joblens::slow-jobs', compact('jobs'));
    }
}
