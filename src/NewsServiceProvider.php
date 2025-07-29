<?php

namespace Darvis\MantaNews;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register package services
        $this->mergeConfigFrom(
            __DIR__ . '/../config/manta-news.php',
            'manta-news'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publiceer configuratie
        $this->publishes([
            __DIR__ . '/../config/manta-news.php' => config_path('manta-news.php'),
        ], 'manta-news-config');

        // Publiceer migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'manta-news-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'manta-news');

        // Register Livewire components
        $this->registerLivewireComponents();

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Darvis\MantaNews\Console\Commands\InstallCommand::class,
            ]);
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register Livewire components
     */
    private function registerLivewireComponents(): void
    {
        // News components
        Livewire::component('news-list', \Darvis\MantaNews\Livewire\News\NewsList::class);
        Livewire::component('news-create', \Darvis\MantaNews\Livewire\News\NewsCreate::class);
        Livewire::component('news-read', \Darvis\MantaNews\Livewire\News\NewsRead::class);
        Livewire::component('news-update', \Darvis\MantaNews\Livewire\News\NewsUpdate::class);
        Livewire::component('news-upload', \Darvis\MantaNews\Livewire\News\NewsUpload::class);

        // Newscat components
        Livewire::component('newscat-list', \Darvis\MantaNews\Livewire\Newscat\NewscatList::class);
        Livewire::component('newscat-create', \Darvis\MantaNews\Livewire\Newscat\NewscatCreate::class);
        Livewire::component('newscat-read', \Darvis\MantaNews\Livewire\Newscat\NewscatRead::class);
        Livewire::component('newscat-update', \Darvis\MantaNews\Livewire\Newscat\NewscatUpdate::class);
        Livewire::component('newscat-upload', \Darvis\MantaNews\Livewire\Newscat\NewscatUpload::class);
    }
}
