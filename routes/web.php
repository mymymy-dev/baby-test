<?php

use Illuminate\Support\Facades\Route;
use My\Baby\Http\Controllers\BabyActivityController;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [BabyActivityController::class, 'index'])->name('baby_activities.index');
Route::get('/baby-activities/create', [BabyActivityController::class, 'create'])->name('baby_activities.create');
Route::post('/baby-activities', [BabyActivityController::class, 'store'])->name('baby_activities.store');
Route::delete('/baby-activities/{model}', [BabyActivityController::class, 'destroy'])->name('baby_activities.destroy');

//Route::get('/', [ActivityController::class, 'index'])->name('activities.index');
//Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
//Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
//Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
//
//Route::get('/contractions', [ContractionController::class, 'index'])->name('contractions.index');
//Route::post('/contractions/start', [ContractionController::class, 'start'])->name('contractions.start');
//Route::patch('/contractions/end', [ContractionController::class, 'end'])->name('contractions.end');
//Route::delete('/contractions/{contraction}', [ContractionController::class, 'destroy'])->name('contractions.destroy');
//
//Route::get('/contractions/create', [ContractionController::class, 'create'])->name('contractions.create');
//Route::post('/contractions', [ContractionController::class, 'store'])->name('contractions.store');
