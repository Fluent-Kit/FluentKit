<?php
namespace FluentKit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

use FluentKit\Foundation\Theme\FileViewFinder;
use FluentKit\Foundation\Theme\ThemeManager;
use FluentKit\Foundation\Theme\ThemeFinder;

class ThemeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

    public function register()
    {
        //register theme view finder
        $this->app->bindShared('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];
            return new FileViewFinder($app['files'], $paths);
        });
        $this->app->bindShared('theme', function ($app) {
            return new ThemeManager($app);
        });
        $this->app->bindShared('theme.finder', function ($app) {
            return new ThemeFinder($app);
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $app = $this->app;

        //set theme
        $app['theme']->setTheme('default');

        // The theme is only booted when the first view is being composed.
        // This would prevent multiple theme being booted in the same
        // request.
        $app['events']->listen('creating: *', function () use ($app) {
            $app['theme']->boot();
        });

    }
    
    public function provides(){
        return ['view.finder', 'theme', 'theme.finder'];   
    }

}