<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Participant\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

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
Route::post('/webhook/midtrans', [MidtransController::class, 'webhook'])->name('webhook.midtrans');
Route::post('/midtrans/token', [MidtransController::class, 'createToken'])->name('midtrans.token');

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
    Route::get('/participants/export', [AdminController::class, 'exportParticipants'])->name('participants.export');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::post('/payments/{payment}/update-status', [AdminController::class, 'updatePaymentStatus'])->name('payments.update-status');
    Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('export-csv');
    Route::post('/generate-bibs', [AdminController::class, 'generateBibs'])->name('generate-bibs');
    Route::get('/email-blast', [AdminController::class, 'emailBlast'])->name('email-blast');
    Route::post('/email-blast', [AdminController::class, 'sendEmailBlast'])->name('email-blast.send');
    Route::get('/checkin', [AdminController::class, 'checkin'])->name('checkin');
    Route::post('/checkin', [AdminController::class, 'processCheckin'])->name('checkin.process');
    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
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


// /*
// |--------------------------------------------------------------------------
// | Route untuk migrate database
// |--------------------------------------------------------------------------
// */

// Route::get('/migrate-db', function () {
//     Artisan::call('migrate', [
//         '--force' => true
//     ]);
//     return Artisan::output();
// });

// Email Preview Route
Route::get('/mail-preview/registration', function () {
    $participant = \App\Models\Participant::with(['latestPayment.promotion', 'category', 'event'])->latest()->first();
    if (!$participant)
        return 'No participants found.';
    
    // Simulate payment method for preview
    if (request('type') === 'midtrans' && $participant->latestPayment) {
        $participant->latestPayment->payment_method = 'midtrans';
    } elseif (request('type') === 'manual' && $participant->latestPayment) {
        $participant->latestPayment->payment_method = 'manual';
    }

    return new \App\Mail\RegistrationConfirmation($participant);
});

Route::get('/mail-preview/payment', function () {
    $participant = \App\Models\Participant::with(['latestPayment.promotion', 'category', 'event'])->latest()->first();
    if (!$participant)
        return 'No participants found.';
    return new \App\Mail\PaymentConfirmation($participant);
});

require __DIR__ . '/auth.php';

// Route to manually link storage
Route::get('/init-storage', function () {
    // 1. Tentukan path asal (folder storage di dalam eratrailrun)
    // Gunakan base_path karena folder aplikasi Anda ada di luar public_html
    $target = base_path('storage/app/public');

    // 2. Tentukan path tujuan (folder yang akan diakses publik)
    $shortcut = public_path('storage');

    // 3. Cek jika link sudah ada, hapus dulu jika itu adalah link mati (broken link)
    if (is_link($shortcut) || file_exists($shortcut)) {
        // Jika Anda ingin mengulang, hapus manual via File Manager atau gunakan:
        // app('files')->delete($shortcut); 
        return 'Link storage sudah ada atau folder "storage" sudah ada di public_html.';
    }

    try {
        // Menggunakan fungsi symlink bawaan PHP (bukan Artisan/exec)
        if (symlink($target, $shortcut)) {
            return 'Berhasil! Link storage dibuat menggunakan native PHP symlink.';
        } else {
            return 'Gagal membuat symlink. Cek izin akses folder parent.';
        }
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
