<?php

namespace SauloSilva\NovaGallery;

use SauloSilva\NovaGallery\Http\Middleware\Authorize;
use SauloSilva\NovaGallery\Http\Observers\AlbumObserver;
use SauloSilva\NovaGallery\Http\Observers\PhotoObserver;
use SauloSilva\NovaGallery\Models\Album;
use SauloSilva\NovaGallery\Models\Photo;
use SauloSilva\NovaGallery\Resources\AlbumResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-gallery');

        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishMigrations();

        Nova::serving(function (ServingNova $event) {
            Album::observe(new AlbumObserver());
            Photo::observe(new PhotoObserver());
        });

        Nova::resources([
            AlbumResource::class,
        ]);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-gallery')
            ->namespace('SauloSilva\NovaGallery\Http\Controllers')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Publish required migration
     */
    private function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/Migrations/create_albums_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_albums_table.php'),
        ], 'gallery-migration');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
