<?php

use App\Http\Controllers\CustomPageController;
use Illuminate\Support\Facades\Route;

Route::get('/{slug}', [CustomPageController::class, 'index'])->name('custom-page');

