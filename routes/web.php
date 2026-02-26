<?php

use Fabninjas\JobLens\Http\Controllers\JobLensController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->get('/slowjobs', [JobLensController::class, 'index']);
