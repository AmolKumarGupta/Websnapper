<?php

use App\Http\Controllers\Auth\DashBoardController;
use App\Http\Controllers\{
    ProfileController,
    VideoController
};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name("home");

Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    
    Route::resource('videos', VideoController::class)->except(['create', 'edit']);
    Route::get('play/{video}', [VideoController::class, 'play'])->name('video.play');
    Route::post('video/title', [VideoController::class, 'changeTitle'])->name('video.title');
    Route::post('video/access', [VideoController::class, 'giveAccess'])->name('video.access');
    Route::post('video/views', [VideoController::class, 'views'])->name('video.views');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
