<?php

use App\Http\Controllers\Files\FileController;
use App\Http\Controllers\Files\SharedFilesController;
use App\Http\Controllers\Files\ShareFilesController;
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

//Files
Route::get('admin/files', [FileController::class, '__invoke'])->middleware(['auth', 'verified'])->name('files');
Route::get('admin/files/shares', [ShareFilesController::class, '__invoke'])->middleware(['auth', 'verified'])->name('share_files');
Route::get('admin/files/shared', [SharedFilesController::class, '__invoke'])->middleware(['auth', 'verified'])->name('shared_files');

//Trash

require __DIR__.'/settings.php';
