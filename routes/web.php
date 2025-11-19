<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GerbongController;
use App\Http\Controllers\Admin\JadwalKeretaController;
use App\Http\Controllers\Admin\KeretaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\StasiunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\Users\PenumpangController;
use App\Http\Controllers\Admin\Users\PetugasController;
use App\Http\Controllers\Admin\Users\AdminController;
use App\Http\Controllers\Penumpang\DashboardController;
use App\Http\Controllers\Penumpang\PesanTiketController;
use App\Models\JadwalKereta; // <<-- pastikan model ini ada
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Helper role check (bisa string atau array)
|--------------------------------------------------------------------------
*/
if (! function_exists('checkRole')) {
    function checkRole($roles)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $userRole = Auth::user()->role;

        if (is_array($roles)) {
            if (! in_array($userRole, $roles)) {
                abort(403, 'Unauthorized');
            }
        } else {
            if ($userRole !== $roles) {
                abort(403, 'Unauthorized');
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // kalau login dan role penumpang, kirim ke dashboard penumpang
    if (Auth::check() && Auth::user()->role === 'penumpang') {
        return redirect()->route('penumpang.dashboard');
    }

    // ambil jadwal kereta hanya untuk hari ini
    $today = Carbon::today();
    $jadwal = JadwalKereta::whereDate('jam_keberangkatan', $today)
                ->orderBy('jam_keberangkatan', 'asc')
                ->take(10)
                ->get();

    return view('beranda', compact('jadwal'));
})->name('beranda.index');
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    });

    Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        checkRole('admin');
        return app(AdminDashboardController::class)->index();
    })->name('admin.dashboard');

    Route::resource('/kereta', KeretaController::class)->names('admin.kereta');
    Route::resource('users/admin', AdminController::class)->names('admin.users.admin');
    Route::resource('users/petugas', PetugasController::class)->names('admin.users.petugas');
    Route::resource('users/penumpang', PenumpangController::class)->names('admin.users.penumpang');
    Route::resource('/jadwal-kereta', JadwalKeretaController::class)->names('admin.jadwal');
    Route::resource('/gerbong', GerbongController::class)->names('admin.gerbong');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/pdf', [App\Http\Controllers\Admin\LaporanController::class, 'pdf'])->name('admin.laporan.pdf');
    Route::resource('/stasiun', StasiunController::class)->names('admin.stasiun');

    Route::get('/pengaturan', function () {
        checkRole('admin');
        return view('admin_kai.settings');
    })->name('admin.settings');
});

/*
|--------------------------------------------------------------------------
| Petugas Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', function () {
        checkRole('petugas');
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});

/*
|--------------------------------------------------------------------------
| Penumpang Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('penumpang')->group(function () {
    Route::get('/dashboard', function () {
        checkRole('penumpang');
        return app(DashboardController::class)->index();
    })->name('penumpang.dashboard');
});

/*
|--------------------------------------------------------------------------
| Ticket Booking Routes
|--------------------------------------------------------------------------
*/
Route::get('/pesantiket', [PesanTiketController::class, 'index'])->name('pesantiket.index');
Route::post('/pesantiket/search', [PesanTiketController::class, 'search'])->name('pesantiket.search');
Route::get('/pesantiket/transaksi/{id}', [PesanTiketController::class, 'showTransaction'])->name('pesantiket.transaction');
Route::post('/pesantiket/store', [PesanTiketController::class, 'store'])->middleware('auth')->name('pesantiket.store');
Route::get('/pesantiket/pembayaran/{id}', [PesanTiketController::class, 'showPayment'])->middleware('auth')->name('pesantiket.payment');
Route::get('/pesantiket/cetak/{id}', [PesanTiketController::class, 'cetakTiket'])->middleware('auth')->name('pesantiket.cetak');
Route::post('/pesantiket/pembayaran/proses', [PesanTiketController::class, 'processPayment'])->middleware('auth')->name('pesantiket.payment.process');
/*
|--------------------------------------------------------------------------
| Help Page
|--------------------------------------------------------------------------
*/
Route::get('/layanan-bantuan', function () {
    return view('bantuan');
})->name('bantuan');
