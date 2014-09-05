<?php namespace FluentKit\Providers;

use Illuminate\Support\ServiceProvider;

use FluentKit\Foundation\Filter;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any necessary services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your services
		// in the IoC container. If you wish, you may make additional methods
		// or service providers to keep the code more focused and granular.

		$this->app['fluentkit.filter'] = $this->app->share(function ($app) {
            return new Filter();
        });
        
        $this->app['path.resources'] = $this->app['path.public'] . '/resources';
        $this->app['path.plugins'] = $this->app['path.resources'] . '/plugins';
        $this->app['path.themes'] = $this->app['path.resources'] . '/themes';
	}

}