<?php

use App\Http\Controllers\Clusters;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\EcsahcTimelines;
use App\Http\Controllers\EntitiesController;
use App\Http\Controllers\IndicatorsController;
use App\Http\Controllers\MpaIndicatorsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

// Step 1: Display entity selection
    Route::get('/mpa-indicators/select-entity', [MpaIndicatorsController::class, 'SelectEntity'])
        ->name('mpaIndicators.SelectEntity');

// Step 2: Show indicators for chosen entity
    Route::get('/mpa-indicators/entity-indicators', [MpaIndicatorsController::class, 'ShowEntityIndicators'])
        ->name('mpaIndicators.ShowEntityIndicators');

// Step 3: CRUD
    Route::post('/mpa-indicators/store', [MpaIndicatorsController::class, 'StoreIndicator'])
        ->name('mpaIndicators.StoreIndicator');

    Route::put('/mpa-indicators/update', [MpaIndicatorsController::class, 'UpdateIndicator'])
        ->name('mpaIndicators.UpdateIndicator');

    Route::delete('/mpa-indicators/delete', [MpaIndicatorsController::class, 'DeleteIndicator'])
        ->name('mpaIndicators.DeleteIndicator');

    //
    //
    //
    //
    //

    Route::any('/UpdateEcsahcIndicators', [IndicatorsController::class, 'UpdateEcsahcIndicators'])->name('UpdateEcsahcIndicators');

    Route::any('/DeleteEcsahcIndicators', [IndicatorsController::class, 'DeleteEcsahcIndicators'])->name('DeleteEcsahcIndicators');

    Route::post('/AddEcsahcIndicators', [IndicatorsController::class, 'AddEcsahcIndicators'])->name('AddEcsahcIndicators');

    // Mass Insert Route
    Route::any('/MgtEcsaIndicators', [IndicatorsController::class, 'MgtEcsaIndicators'])
        ->name('MgtEcsaIndicators');

    Route::get('/SelectSo', [IndicatorsController::class, 'SelectSo'])->name('SelectSo');

    Route::get('/MgtSO', [IndicatorsController::class, 'MgtSO'])->name('MgtSO');
    Route::get('/MgtEcsaUsers', [UsersController::class, 'MgtEcsaUsers'])->name('MgtEcsaUsers');

    Route::get('/MgtMpaUsers', [UsersController::class, 'MgtMpaUsers'])->name('MgtMpaUsers');

    //

    Route::post('/MassInsert', [CrudController::class, 'MassInsert'])->name('MassInsert');

// Mass Update Route
    Route::put('/MassUpdate', [CrudController::class, 'MassUpdate'])->name('MassUpdate');

// Mass Delete Route
    Route::delete('/MassDelete', [CrudController::class, 'MassDelete'])->name('MassDelete');

// Get Table Columns Route

    // Route::delete('/delete/{id}/{tableName}', [CrudController::class, 'deleteData'])
    //     ->name('delete');
    // Route::post('/insert', [CrudController::class, 'massInsert'])->name('insert');
    // Route::put('/update', [CrudController::class, 'massUpdate'])->name('update');

    Route::get('/MgtMpaTimelines', [EcsahcTimelines::class, 'MgtMpaTimelines'])->name('MgtMpaTimelines');

    Route::get('/MgtEcsaTimelines', [EcsahcTimelines::class, 'MgtEcsaTimelines'])->name('MgtEcsaTimelines');

    Route::get('/MgtClusters', [Clusters::class, 'MgtClusters'])->name('MgtClusters');

    Route::get('/MgtEntities', [EntitiesController::class, 'MgtEntities'])->name('MgtEntities');
});
Route::get('/', function () {
    return view('scrn');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';