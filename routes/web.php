<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\Users\PenumpangController;
use App\Http\Controllers\Admin\Users\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('beranda');
});

Route::get('/pesantiket', function () {
    return view('pesantiket');
});

Route::prefix('auth')->group(function () {
    // Hanya untuk guest (belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    });

    // Logout hanya untuk user yang sudah login
    Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin Kai
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return view('admin_kai.dashboard');
    })->name('admin.dashboard');

    Route::get('/kereta', function () {
        return view('admin_kai.kereta');
    })->name('admin.kereta');

    Route::resource('users/admin', AdminController::class)->names('admin.users.admin');

    Route::get('/users/petugas', function () {
        return view('admin_kai.users.petugas');
    })->name('admin.users.petugas');

    // Replace the penumpang route with resource routing
    Route::resource('users/penumpang', PenumpangController::class)->names('admin.users.penumpang');

    Route::get('/jadwal-kereta', function () {
        return view('admin_kai.jadwal');
    })->name('admin.jadwal');

    Route::get('/gerbong', function () {
        return view('admin_kai.gerbong');
    })->name('admin.gerbong');

    Route::get('/stasiun', function () {
        return view('admin_kai.stasiun');
    })->name('admin.stasiun');

    Route::get('/pengaturan', function () {
        return view('admin_kai.settings');
    })->name('admin.settings');
});

// Petugas
Route::middleware(['auth'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Unauthorized');
        }
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});

// Penumpang
Route::middleware(['auth'])->prefix('penumpang')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'penumpang') {
            abort(403, 'Unauthorized');
        }
        return view('penumpang.dashboard');
    })->name('penumpang.dashboard');
});
