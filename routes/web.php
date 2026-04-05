<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SeasonController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'));
Route::get('/ruegen-erleben', fn () => view('ruegen-erleben'));
Route::get('/ruegen/{page}', fn (string $page) => view("ruegen.{$page}"));
Route::get('/impressum', fn () => view('impressum'));
Route::get('/datenschutz', fn () => view('datenschutz'));

// 301-Redirects für alte .html-URLs (SEO / Bookmarks)
Route::redirect('/ruegen-erleben.html', '/ruegen-erleben', 301);
Route::redirect('/impressum.html', '/impressum', 301);
Route::redirect('/datenschutz.html', '/datenschutz', 301);
Route::redirect('/ruegen/wandern.html', '/ruegen/wandern', 301);
Route::redirect('/ruegen/radfahren.html', '/ruegen/radfahren', 301);
Route::redirect('/ruegen/ausflugsziele.html', '/ruegen/ausflugsziele', 301);
Route::redirect('/ruegen/sehenswuerdigkeiten.html', '/ruegen/sehenswuerdigkeiten', 301);
Route::redirect('/ruegen/schloesser-parks.html', '/ruegen/schloesser-parks', 301);
Route::redirect('/ruegen/familie.html', '/ruegen/familie', 301);

// Admin Auth
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin (geschützt)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/',          fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings',  [BookingController::class, 'index'])->name('admin.bookings');
    Route::post('/bookings', [BookingController::class, 'store'])->name('admin.bookings.store');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/bookings/{booking}',  [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
    Route::get('/seasons',                [SeasonController::class, 'index'])->name('admin.seasons');
    Route::post('/seasons',               [SeasonController::class, 'store'])->name('admin.seasons.store');
    Route::put('/seasons/{season}',       [SeasonController::class, 'update'])->name('admin.seasons.update');
    Route::delete('/seasons/{season}',    [SeasonController::class, 'destroy'])->name('admin.seasons.destroy');
});

// Öffentliche API
Route::get('/api/bookings', [\App\Http\Controllers\Api\BookingController::class, 'index']);
Route::get('/api/seasons',  [\App\Http\Controllers\Api\SeasonController::class,  'index']);
