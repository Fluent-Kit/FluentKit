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
        
        
        $app = $this->app;
        $app['router']->get('/test', function() use ($app){
            $filter = $app['fluentkit.filter'];
        
            $filter->add('test2.filter', function($value){
                return $value;
            }, 100, 'unique_id');
            
            
            
            dd($filter->apply('test2.filter', ['original value', 'second value', 5, false]));
        });
	}

}