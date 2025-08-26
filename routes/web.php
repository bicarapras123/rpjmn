<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\IndikatorController;
use App\Http\Controllers\LaporanController;

// Auth Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordController;

/*
|--------------------------------------------------------------------------
| Tes Kirim Email
|--------------------------------------------------------------------------
*/
Route::get('/tes-email', function () {
    Mail::raw('Tes email dari Laravel', function ($message) {
        $message->to('prassprs@gmail.com')
                ->subject('Tes Kirim Email dari Laravel');
    });

    return 'Email tes terkirim!';
});

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH (Login, Register, Forgot Password)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Verifikasi Email
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Dashboard & Evaluasi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/evaluasi', [EvaluasiController::class, 'index'])->name('evaluasi.index');
});

/*
|--------------------------------------------------------------------------
| Monitoring & Indikator
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // CRUD Monitoring (biar ada monitoring.store, monitoring.update, dll)
    Route::resource('indikator', IndikatorController::class);

    // route untuk validasi indikator
    Route::post('indikator/{id}/set-status/{status}', [IndikatorController::class, 'setStatus'])
    ->name('indikator.setStatus');

    // Monitoring diarahkan ke Indikator index
    Route::get('/monitoring', [IndikatorController::class, 'index'])->name('monitoring.index');

    // Tambahan khusus Monitoring
    Route::get('/monitoring/cetak', [MonitoringController::class, 'cetak'])->name('monitoring.cetak');
    Route::delete('/monitoring/hapus-semua', [MonitoringController::class, 'hapusSemua'])->name('monitoring.hapusSemua');

    // CRUD Indikator
    Route::resource('indikator', IndikatorController::class);

    // Kalau tetap butuh akses Indikator via URL monitoring, bisa pakai alias
    Route::get('/indikator-monitoring', [IndikatorController::class, 'index'])->name('indikator.monitoring.index');
});

/*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

    Route::post('/laporan/kirim', function (Request $request) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses khusus Admin');
        }
        return app(LaporanController::class)->kirim($request);
    })->name('laporan.kirim');
});

/*
|--------------------------------------------------------------------------
| Profile & Password
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/password', [PasswordController::class, 'update'])->name('user-password.update');
});
