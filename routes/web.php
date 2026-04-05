<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PricingNoteController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\SeasonPriceController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
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

    Route::get('/bookings',              [BookingController::class, 'index'])->name('admin.bookings');
    Route::post('/bookings',             [BookingController::class, 'store'])->name('admin.bookings.store');
    Route::put('/bookings/{booking}',    [BookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');

    Route::get('/seasons',                    [SeasonController::class, 'index'])->name('admin.seasons');
    Route::post('/seasons',                   [SeasonController::class, 'store'])->name('admin.seasons.store');
    Route::put('/seasons/{season}',           [SeasonController::class, 'update'])->name('admin.seasons.update');
    Route::delete('/seasons/{season}',        [SeasonController::class, 'destroy'])->name('admin.seasons.destroy');
    Route::post('/seasons/{season}/activate', [SeasonController::class, 'activate'])->name('admin.seasons.activate');

    Route::post('/seasons/{season}/prices',  [SeasonPriceController::class, 'store'])->name('admin.season-prices.store');
    Route::put('/season-prices/{price}',     [SeasonPriceController::class, 'update'])->name('admin.season-prices.update');
    Route::delete('/season-prices/{price}',  [SeasonPriceController::class, 'destroy'])->name('admin.season-prices.destroy');

    Route::get('/pricing-notes',               [PricingNoteController::class, 'index'])->name('admin.pricing-notes');
    Route::post('/pricing-notes',              [PricingNoteController::class, 'store'])->name('admin.pricing-notes.store');
    Route::put('/pricing-notes/{note}',        [PricingNoteController::class, 'update'])->name('admin.pricing-notes.update');
    Route::delete('/pricing-notes/{note}',     [PricingNoteController::class, 'destroy'])->name('admin.pricing-notes.destroy');

    Route::get('/templates',                       [TemplateController::class, 'index'])->name('admin.templates');
    Route::put('/templates/{template}',            [TemplateController::class, 'update'])->name('admin.templates.update');
    Route::post('/templates/{template}/activate',  [TemplateController::class, 'activate'])->name('admin.templates.activate');
    Route::put('/templates/{template}/sections',   [TemplateController::class, 'updateSections'])->name('admin.templates.sections');
});

// Öffentliche API
Route::get('/api/bookings',       [\App\Http\Controllers\Api\BookingController::class,     'index']);
Route::get('/api/seasons',        [\App\Http\Controllers\Api\SeasonController::class,      'index']);
Route::get('/api/pricing-notes',  [\App\Http\Controllers\Api\PricingNoteController::class, 'index']);
