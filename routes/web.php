<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;


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

Route::match(['get', 'post'], '/', [AuthController::class, 'login'])->name('login');
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class)->names('kategori');
    Route::get('produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');
    Route::resource('produk', ProdukController::class)->names('produk');
    Route::resource('stok', StokController::class)->names('stok');
    Route::resource('user', UserController::class)->names('user');
    Route::match(['get', 'put'], 'profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
