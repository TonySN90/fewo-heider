<?php

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
