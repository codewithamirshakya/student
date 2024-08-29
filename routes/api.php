<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('events', [\App\Http\Controllers\EventController::class, 'index']);

