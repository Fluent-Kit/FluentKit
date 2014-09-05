<?php
namespace FluentKit\Foundation\Plugin;

use Illuminate\Support\ServiceProvider as LaravelProvider;

use Illuminate\Foundation\AliasLoader;

class ServiceProvider extends LaravelProvider {
    
    public function register(){}
    
    public function boot(){}

    public function registerPlugin($plugin)
	{
		$path = $this->app['path.plugins'] . '/' . $plugin . '/src';
		return $this->package($plugin, $plugin, $path);
	}
    
    public function registerFacade($facade, $namespace)
	{
		$loader = AliasLoader::getInstance();
        $loader->alias($facade, $namespace);
	}

}