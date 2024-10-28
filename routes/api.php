<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/families')->name('families.')->group(function () {

    Route::prefix('/{family}')->group(function () {

       Route::prefix('/citizens')->name('citizens.')->group(function () {
           Route::post('/', [\App\Http\Controllers\FamilyController::class, 'join'])->name('join');

           Route::prefix('/{citizen}')->group(function () {
               Route::post('/promote', [\App\Http\Controllers\FamilyController::class, 'promote_head'])->name('promote');
               Route::delete('/', [\App\Http\Controllers\FamilyController::class, 'remove'])->name('remove');
           });
       });
    });
});

Route::prefix('/citizens')->name('citizens.')->group(function () {

    Route::prefix('/{citizen}')->group(function () {
        Route::post('/move', [\App\Http\Controllers\CitizenController::class, 'move'])->name('move');
    });
});
