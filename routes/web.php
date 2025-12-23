<?php

use App\Http\Controllers\Files\FileController;
use App\Http\Controllers\Files\SharedFilesController;
use App\Http\Controllers\Files\ShareFilesController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->group(function () {
        //Files
        Route::get('files', [FileController::class, '__invoke'])->name('files');
        Route::post('files/folder', [FileController::class, 'createFolder'])->name('create_folder');
        Route::get('files/shares', [ShareFilesController::class, '__invoke'])->name('share_files');
        Route::get('files/shared', [SharedFilesController::class, '__invoke'])->name('shared_files');
    });
});


require __DIR__ . '/settings.php';
