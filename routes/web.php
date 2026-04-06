<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ClientTenantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PricingNoteController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\SeasonPriceController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PageStructureController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TemplateSectionController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Slug-basierte Preview-Route (für lokale Entwicklung, kein Domain-Lookup nötig)
// Muss VOR der resolve.tenant-Gruppe stehen, damit kein Domain-Lookup stattfindet
Route::get('/{tenantSlug}', [HomeController::class, 'preview'])
    ->name('tenant.preview')
    ->where('tenantSlug', '^(?!admin|api|ruegen|impressum|datenschutz)[a-z0-9\-]+$');

// Öffentliche Routen (Domain-basiertes Tenant-Resolving)
Route::middleware('resolve.tenant')->group(function () {
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

    // Öffentliche API
    Route::get('/api/bookings',       [\App\Http\Controllers\Api\BookingController::class,     'index']);
    Route::get('/api/seasons',        [\App\Http\Controllers\Api\SeasonController::class,      'index']);
    Route::get('/api/pricing-notes',  [\App\Http\Controllers\Api\PricingNoteController::class, 'index']);
    Route::get('/api/amenities',      [\App\Http\Controllers\Api\AmenityController::class,     'index']);
});

// Admin Auth (kein Tenant-Resolving — Login braucht keinen Kontext)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin (Auth + Tenant-Resolving)
Route::middleware(['auth', 'resolve.tenant'])->prefix('admin')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));

    // Für alle Rollen zugänglich
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings',  [BookingController::class, 'index'])->name('admin.bookings');
    Route::post('/tenant-switch', [ClientTenantController::class, 'switch'])->name('admin.tenant-switch');

    // Buchungen verwalten
    Route::middleware('permission:manage bookings')->group(function () {
        Route::post('/bookings',             [BookingController::class, 'store'])->name('admin.bookings.store');
        Route::put('/bookings/{booking}',    [BookingController::class, 'update'])->name('admin.bookings.update');
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
    });

    // Saisons & Preise verwalten
    Route::middleware('permission:manage seasons')->group(function () {
        Route::get('/seasons',                    [SeasonController::class, 'index'])->name('admin.seasons');
        Route::post('/seasons',                   [SeasonController::class, 'store'])->name('admin.seasons.store');
        Route::put('/seasons/{season}',           [SeasonController::class, 'update'])->name('admin.seasons.update');
        Route::delete('/seasons/{season}',        [SeasonController::class, 'destroy'])->name('admin.seasons.destroy');
        Route::post('/seasons/{season}/activate', [SeasonController::class, 'activate'])->name('admin.seasons.activate');

        Route::post('/seasons/{season}/prices',  [SeasonPriceController::class, 'store'])->name('admin.season-prices.store');
        Route::put('/season-prices/{price}',     [SeasonPriceController::class, 'update'])->name('admin.season-prices.update');
        Route::delete('/season-prices/{price}',  [SeasonPriceController::class, 'destroy'])->name('admin.season-prices.destroy');
    });

    // Zusatzinfos Preise
    Route::middleware('permission:manage pricing')->group(function () {
        Route::get('/pricing-notes', fn () => redirect()->route('admin.seasons'))->name('admin.pricing-notes');
        Route::post('/pricing-notes',              [PricingNoteController::class, 'store'])->name('admin.pricing-notes.store');
        Route::put('/pricing-notes/{note}',        [PricingNoteController::class, 'update'])->name('admin.pricing-notes.update');
        Route::delete('/pricing-notes/{note}',     [PricingNoteController::class, 'destroy'])->name('admin.pricing-notes.destroy');
    });

    // Benutzer-Verwaltung (admin + super-admin)
    Route::middleware('role:admin|super-admin')->group(function () {
        Route::get('/users',                   [UserPermissionController::class, 'index'])->name('admin.users');
        Route::put('/users/{user}',            [UserPermissionController::class, 'update'])->name('admin.users.update');
        Route::put('/users/{user}/profile',    [UserPermissionController::class, 'updateProfile'])->name('admin.users.profile');
    });

    // Templates & Gallery
    Route::middleware('permission:manage templates')->group(function () {
        Route::get('/templates',                       [TemplateController::class, 'index'])->name('admin.templates');
        Route::put('/templates/{template}',            [TemplateController::class, 'update'])->name('admin.templates.update');
        Route::post('/templates/{template}/activate',  [TemplateController::class, 'activate'])->name('admin.templates.activate');
        Route::put('/templates/{template}/sections',   [TemplateController::class, 'updateSections'])->name('admin.templates.sections');

        Route::get('/templates/{template}/sections/{sectionKey}/edit', [TemplateSectionController::class, 'edit'])->name('admin.templates.sections.edit');
        Route::put('/templates/{template}/sections/{sectionKey}',      [TemplateSectionController::class, 'update'])->name('admin.templates.sections.update');

        Route::post('/templates/{template}/sections/{sectionKey}/gallery',           [GalleryController::class, 'store'])->name('admin.gallery.store');
        Route::put('/templates/{template}/sections/{sectionKey}/gallery/{image}',    [GalleryController::class, 'update'])->name('admin.gallery.update');
        Route::delete('/templates/{template}/sections/{sectionKey}/gallery/{image}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');
    });

    // Seitenstruktur (pro Instanz)
    Route::middleware('permission:manage templates')->group(function () {
        Route::get('/page-structure',                                              [PageStructureController::class, 'index'])->name('admin.page-structure');
        Route::put('/page-structure/sections',                                     [PageStructureController::class, 'updateSections'])->name('admin.page-structure.sections');
        Route::get('/page-structure/sections/{sectionKey}/edit',                  [PageStructureController::class, 'edit'])->name('admin.page-structure.edit');
        Route::put('/page-structure/sections/{sectionKey}',                       [PageStructureController::class, 'update'])->name('admin.page-structure.update');

        Route::post('/page-structure/sections/{sectionKey}/gallery',              [GalleryController::class, 'storeForTenant'])->name('admin.page-structure.gallery.store');
        Route::put('/page-structure/sections/{sectionKey}/gallery/{image}',       [GalleryController::class, 'updateForTenant'])->name('admin.page-structure.gallery.update');
        Route::delete('/page-structure/sections/{sectionKey}/gallery/{image}',    [GalleryController::class, 'destroyForTenant'])->name('admin.page-structure.gallery.destroy');
    });

    // Instanzen-Verwaltung (nur Super-Admin)
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/tenants',                        [TenantController::class, 'index'])->name('admin.tenants');
        Route::get('/tenants/create',                 [TenantController::class, 'create'])->name('admin.tenants.create');
        Route::post('/tenants',                       [TenantController::class, 'store'])->name('admin.tenants.store');
        Route::get('/tenants/{tenant}/edit',          [TenantController::class, 'edit'])->name('admin.tenants.edit');
        Route::put('/tenants/{tenant}',               [TenantController::class, 'update'])->name('admin.tenants.update');
        Route::delete('/tenants/{tenant}',            [TenantController::class, 'destroy'])->name('admin.tenants.destroy');
        Route::post('/tenants/{tenant}/manage',       [TenantController::class, 'manage'])->name('admin.tenants.manage');
        Route::post('/tenants/clear-context',         [TenantController::class, 'clearContext'])->name('admin.tenants.clear-context');
    });
});
