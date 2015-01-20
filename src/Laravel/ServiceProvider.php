<?php namespace Arcanedev\Markup\Laravel;

use Arcanedev\Markup\Markup;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package(
            'arcanedev/markup',
            'markup',
            realpath(__DIR__ . '/..')
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.markup'
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Package Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function registerServices()
    {
        $this->app->singleton('arcanedev.markup', function() {
            return new Markup;
        });
    }
}
