<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PelaporanKerusakanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister'])->name('postRegister');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('{role}')
            ->whereIn('role', ['admin', 'user'])
            ->name('user.')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('list', [UserController::class, 'list'])->name('list');
                Route::get('create', [UserController::class, 'create'])->name('create');
                Route::post('store', [UserController::class, 'store'])->name('store');
                Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
                Route::post('{id}/update', [UserController::class, 'update'])->name('update');
                Route::get('{id}/confirm', [UserController::class, 'confirm'])->name('confirm');
                Route::post('{id}/destroy', [UserController::class, 'destroy'])->name('destroy');
            });

        // Alat Routes
        Route::prefix('alat')->name('alat.')->group(function () {
            Route::get('/', [AlatController::class, 'index'])->name('index');
            Route::get('list', [AlatController::class, 'list'])->name('list');
            Route::get('{id}/show', [AlatController::class, 'show'])->name('show');
            Route::get('create', [AlatController::class, 'create'])->name('create');
            Route::post('store', [AlatController::class, 'store'])->name('store');
            Route::get('{id}/edit', [AlatController::class, 'edit'])->name('edit');
            Route::post('{id}/update', [AlatController::class, 'update'])->name('update');
            Route::get('{id}/confirm', [AlatController::class, 'confirm'])->name('confirm');
            Route::post('{id}/destroy', [AlatController::class, 'destroy'])->name('destroy');
        });

        // Laboratorium Routes
        Route::prefix('laboratorium')->name('laboratorium.')->group(function () {
            Route::get('/', [LaboratoriumController::class, 'index'])->name('index');
            Route::get('list', [LaboratoriumController::class, 'list'])->name('list');
            Route::get('create', [LaboratoriumController::class, 'create'])->name('create');
            Route::post('store', [LaboratoriumController::class, 'store'])->name('store');
            Route::get('{id}/edit', [LaboratoriumController::class, 'edit'])->name('edit');
            Route::post('{id}/update', [LaboratoriumController::class, 'update'])->name('update');
            Route::get('{id}/confirm', [LaboratoriumController::class, 'confirm'])->name('confirm');
            Route::post('{id}/destroy', [LaboratoriumController::class, 'destroy'])->name('destroy');
        });

        // Peminjaman Routes
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/validasi', [PeminjamanController::class, 'indexValidasi'])->name('validasi');
            Route::get('/listValidasi', [PeminjamanController::class, 'listValidasi'])->name('listValidasi');
            Route::get('{id}/showValidasi', [PeminjamanController::class, 'showValidasi'])->name('showValidasi');
            Route::patch('/{id}/approve', [PeminjamanController::class, 'approve'])->name('approve');
            Route::patch('/{id}/reject', [PeminjamanController::class, 'reject'])->name('reject');

            Route::get('/monitoring', [PeminjamanController::class, 'indexMonitoring'])->name('monitoring');
            Route::get('/listMonitoring', [PeminjamanController::class, 'listMonitoring'])->name('listMonitoring');
            Route::get('{id}/showMonitoring', [PeminjamanController::class, 'showMonitoring'])->name('showMonitoring');
            Route::get('{id}/editMonitoring', [PeminjamanController::class, 'editMonitoring'])->name('editMonitoring');
            Route::post('/{id}/kembali', [PeminjamanController::class, 'prosesPengembalian'])->name('kembali');

            Route::get('/riwayat', [PeminjamanController::class, 'indexRiwayat'])->name('riwayat');
            Route::get('/listRiwayat', [PeminjamanController::class, 'listRiwayat'])->name('listRiwayat');
        });

        // Laporan Routes
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [PelaporanKerusakanController::class, 'index'])->name('index');
            Route::get('list', [PelaporanKerusakanController::class, 'list'])->name('list');
            Route::get('{id}/show', [PelaporanKerusakanController::class, 'show'])->name('show');
            Route::get('{id}/edit', [PelaporanKerusakanController::class, 'edit'])->name('edit');
            Route::post('{id}/update', [PelaporanKerusakanController::class, 'update'])->name('update');
        });
    });
    Route::middleware(['role:user'])->group(function () {
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/create', [PeminjamanController::class, 'create'])->name('create');
            Route::post('/store', [PeminjamanController::class, 'store'])->name('store');
            Route::get('/schedule', [PeminjamanController::class, 'schedule'])->name('schedule');
            Route::get('/riwayat', [PeminjamanController::class, 'riwayatUser'])->name('riwayatUser');
            Route::get('/riwayat/show/{id}', [PeminjamanController::class, 'showRiwayatUser'])->name('riwayatUser.show');
            Route::post('/cancel/{id}', [PeminjamanController::class, 'cancel'])->name('cancel');
        });
    });
});
