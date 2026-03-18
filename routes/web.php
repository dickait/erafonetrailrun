<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Participant\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

// Language Switcher
Route::get('/lang/{locale}', function (string $locale) {
    $available = config('app.available_locales', ['id', 'en']);
    if (in_array($locale, $available)) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

/* |-------------------------------------------------------------------------- | Public Routes |-------------------------------------------------------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/race-course', [HomeController::class, 'raceCourse'])->name('race_course');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/results', [HomeController::class, 'results'])->name('results');

// Registration
Route::get('/event-register', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/event-register', [RegistrationController::class, 'store'])->name('register.store')->middleware('throttle:5,1');
Route::match(['get', 'post'], '/status', [RegistrationController::class, 'checkStatus'])->name('registration.status');
Route::get('/payment', [RegistrationController::class, 'payment'])->name('registration.payment');

// API for cascading dropdowns
Route::get('/api/provinces', [RegistrationController::class, 'getProvinces'])->name('api.provinces');
Route::get('/api/cities', [RegistrationController::class, 'getCities'])->name('api.cities');

// Webhook (CSRF excluded via bootstrap/app.php)
Route::post('/webhook/mayar', [WebhookController::class, 'handleMayar'])->name('webhook.mayar');

/* |-------------------------------------------------------------------------- | Auth Routes (Breeze) |-------------------------------------------------------------------------- */
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('participant.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* |-------------------------------------------------------------------------- | Participant Routes |-------------------------------------------------------------------------- */
Route::middleware(['auth', 'participant'])->prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/payment', [DashboardController::class, 'paymentStatus'])->name('payment');
    Route::get('/bib', [DashboardController::class, 'bib'])->name('bib');
});

/* |-------------------------------------------------------------------------- | Admin Routes |-------------------------------------------------------------------------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/participants', [AdminController::class, 'participants'])->name('participants');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('export-csv');
    Route::post('/generate-bibs', [AdminController::class, 'generateBibs'])->name('generate-bibs');
    Route::get('/email-blast', [AdminController::class, 'emailBlastForm'])->name('email-blast');
    Route::post('/email-blast', [AdminController::class, 'sendEmailBlast'])->name('email-blast.send');
    Route::get('/checkin', [AdminController::class, 'checkinPage'])->name('checkin');
    Route::post('/checkin', [AdminController::class, 'checkin'])->name('checkin.process');
});

/*
|--------------------------------------------------------------------------
| Route untuk optimize & cache
|--------------------------------------------------------------------------
*/

Route::get('/optimize-app', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return 'Optimize & cache selesai!';
});


/*
|--------------------------------------------------------------------------
| Route untuk migrate database
|--------------------------------------------------------------------------
*/

Route::get('/migrate-db', function () {
    Artisan::call('migrate', [
        '--force' => true
    ]);
    return Artisan::output();
});

// Email Preview Route
Route::get('/mail-preview/registration', function () {
    $participant = \App\Models\Participant::with(['latestPayment', 'category', 'event'])->latest()->first();
    if (!$participant) {
        return 'No participants found to preview. Please register one first.';
    }
    return new \App\Mail\RegistrationConfirmation($participant);
});

require __DIR__ . '/auth.php';
