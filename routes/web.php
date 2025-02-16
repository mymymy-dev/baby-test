<?php

use App\Http\Controllers\ContractionController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [ContractionController::class, 'index'])->name('contractions.index');
Route::post('/contractions/start', [ContractionController::class, 'start'])->name('contractions.start');
Route::patch('/contractions/end', [ContractionController::class, 'end'])->name('contractions.end');
Route::delete('/contractions/{contraction}', [ContractionController::class, 'destroy'])->name('contractions.destroy');

Route::get('/contractions/create', [ContractionController::class, 'create'])->name('contractions.create');
Route::post('/contractions', [ContractionController::class, 'store'])->name('contractions.store');
