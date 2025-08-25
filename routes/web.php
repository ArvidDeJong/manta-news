<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| News Routes
|--------------------------------------------------------------------------
|
| Hier definiëren we de routes voor de News package.
|
*/

Route::middleware(['web', 'auth:staff'])->prefix(config('manta-news.route_prefix'))
    ->name('news.')
    ->group(function () {
        Route::get("", Darvis\MantaNews\Livewire\News\NewsList::class)->name('list');
        Route::get("/toevoegen", Darvis\MantaNews\Livewire\News\NewsCreate::class)->name('create');
        Route::get("/aanpassen/{news}", Darvis\MantaNews\Livewire\News\NewsUpdate::class)->name('update');
        Route::get("/lezen/{news}", Darvis\MantaNews\Livewire\News\NewsRead::class)->name('read');
        Route::get("/bestanden/{news}", Darvis\MantaNews\Livewire\News\NewsUpload::class)->name('upload');

        Route::get("/categorieen", Darvis\MantaNews\Livewire\Newscat\NewscatList::class)->name('cat.list');
        Route::get("/categorieen/toevoegen", Darvis\MantaNews\Livewire\Newscat\NewscatCreate::class)->name('cat.create');
        Route::get("/categorieen/aanpassen/{newscat}", Darvis\MantaNews\Livewire\Newscat\NewscatUpdate::class)->name('cat.update');
        Route::get("/categorieen/lezen/{newscat}", Darvis\MantaNews\Livewire\Newscat\NewscatRead::class)->name('cat.read');
        Route::get("/categorieen/bestanden/{newscat}", Darvis\MantaNews\Livewire\Newscat\NewscatUpload::class)->name('cat.upload');
    });
