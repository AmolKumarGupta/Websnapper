<?php

use App\Http\Controllers\Auth\DashBoardController;
use App\Http\Controllers\{
    CheckoutController,
    ProfileController,
    ServiceController,
    UserPlanController,
    VideoController
};
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
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

    Route::prefix('/video')->group(function () {
        Route::post('/title', [VideoController::class, 'changeTitle'])->name('video.title');
        Route::post('/access', [VideoController::class, 'giveAccess'])->name('video.access');
        Route::post('/views', [VideoController::class, 'views'])->name('video.views');
        Route::post('/sync', [ServiceController::class, 'sync'])->name('video.sync');
        Route::post('/lefted-video-count', [VideoController::class, 'leftedVideoCount'])->name('video.lefted.count');
    });

    Route::get('/upgrade-plan', [UserPlanController::class, 'plans'])->name('upgrade.plan');
    Route::get('/upgrade', [CheckoutController::class, 'index'])->name('upgrade');

    Route::prefix('/stripe')->group(function () {
        Route::post('/create', [CheckoutController::class, 'stripeCreate'])->name('stripe.create');
    });
});

Route::post('/stripe-webhook', [CheckoutController::class, 'webhook'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
