<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', Controllers\HomeController::class)->name('home');

Route::get('cv/{curriculum_vitae:slug}', [Controllers\CurriculumVitaeController::class, 'show'])->name('cv.show');
