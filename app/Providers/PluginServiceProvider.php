<?php
namespace FluentKit\Providers;

use Exception;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ClassLoader;

use FluentKit\Foundation\Plugin\PluginManager;
use FluentKit\Foundation\Plugin\Finder;

class PluginServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

    public function register()
    {
        try{
            $this->app->bindShared('plugin', function ($app) {
                return new PluginManager($app);
            });
            $this->app->bindShared('plugin.finder', function ($app) {
                return new Finder($app, new \Illuminate\Support\Collection);
            });
            $this->app['plugin']->activated()->each(function($plugin){
                $plugin->register();
            });
        }catch( Exception $e){
            //log error here   
        }
        
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
 
    }

    public function provides(){
    	return array('plugin', 'plugin.finder');
    }

}