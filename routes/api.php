<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/family')->name('family.')->group(function () {
    Route::prefix('/{family}')->group(function () {
       Route::post('/promote', [\App\Http\Controllers\FamilyController::class, 'promote_head'])->name('promote');
    });
});
