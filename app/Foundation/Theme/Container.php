<?php 
namespace FluentKit\Foundation\Theme;

use Illuminate\Container\Container as Application;

class Container
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Filesystem path of theme.
     *
     * @var string
     */
    protected $path;

    /**
     * URL path of theme.
     *
     * @var string
     */
    protected $absoluteUrl;

    /**
     * Relative URL path of theme.
     *
     * @var string
     */
    protected $relativeUrl;

    /**
     * Booted indicator.
     *
     * @var boolean
     */
    protected $booted = false;

    /**
     * Theme name.
     *
     * @var string
     */
    protected $theme = null;

    /**
     * Start theme engine, this should be called from application booted
     * or whenever we need to overwrite current active theme per request.
     *
     * @param  \Illuminate\Container\Container  $app
     * @param  string                           $name
     */
    public function __construct(Application $app)
    {
        $this->app  = $app;
        $baseUrl    = $app['request']->root();
        $this->path = $app['path.public'].'/resources/themes';

        // Register relative and absolute URL for theme usage.
        $this->absoluteUrl = rtrim($baseUrl, '/').'/resources/themes';
        $this->relativeUrl = trim(str_replace($baseUrl, '/', $this->absoluteUrl), '/');
    }

    /**
     * Set the theme, this would also load the theme manifest.
     *
     * @param  string   $theme
     * @return void
     */
    public function setTheme($theme)
    {
        $viewFinder = $this->app['view.finder'];
        $paths      = $viewFinder->getPaths();
        
        if (! is_null($this->theme)) {
            if ($paths[0] === $this->getThemePath() . '/views') {
                array_shift($paths);
            }

            $this->app['events']->fire("theme.unset: {$this->theme}");
        }

        $this->theme = $theme;
        $this->app['events']->fire("theme.set: {$this->theme}");

        $paths = array_unique(array_merge(array($this->getThemePath() . '/views', $this->path . '/default/views'), $paths));
        $this->app['config']->set('view::paths', $paths);
        $viewFinder->setPaths($paths);
    }

    /**
     * Get the theme.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Boot the theme by autoloading all the relevant files.
     *
     * @return boolean
     */
    public function boot()
    {
        if ($this->booted || is_null($this->theme)) {
            return false;
        }

        $this->booted = true;

        $themePath = $this->getThemePath();

        // There might be situation where Orchestra Platform was unable
        // to get theme information, we should only assume there a valid
        // theme when manifest is actually an instance of
        // Orchestra\View\Theme\Manifest.
        if (! $this->app['files']->isDirectory($themePath)) {
            return false;
        }
        
        $this->app['events']->fire("theme.pre.boot: {$this->theme}");

        $autoload = $this->getThemeAutoloadFiles($themePath);

        foreach ($autoload as $file) {
            $file = ltrim($file, '/');
            $this->app['files']->requireOnce("{$themePath}/{$file}");
        }

        $this->app['events']->fire("theme.boot: {$this->theme}");

        return true;
    }
    
    /**
     * Set theme paths to view file finder paths.
     *
     * @return void
     */
    protected function setViewPaths()
    {
        $viewFinder = $this->app['view.finder'];

        $themePaths = $this->getAvailableThemePaths();

        if (! empty($themePaths)) {
            $viewFinder->setPaths(array_merge($themePaths, $viewFinder->getPaths()));
        }
    }

    /**
     * Get theme path.
     *
     * @return string
     */
    public function getThemePath()
    {
        return "{$this->path}/{$this->theme}";
    }

    /**
     * URL helper for the theme.
     *
     * @param  string   $url
     * @return string
     */
    public function to($url = '')
    {
        return "{$this->absoluteUrl}/{$this->theme}/{$url}";
    }

    /**
     * Relative URL helper for theme.
     *
     * @param  string   $url
     * @return string
     */
    public function asset($url = '')
    {
        return "/{$this->relativeUrl}/{$this->theme}/{$url}";
    }

    /**
     * Get theme autoload files from manifest.
     *
     * @param  string $themePath
     * @return array
     */
    protected function getThemeAutoloadFiles($themePath)
    {
        $autoload = array();
        $manifest = new Manifest($this->app['files'], $themePath);

        if (isset($manifest->autoload) && is_array($manifest->autoload)) {
            $autoload = $manifest->autoload;
        }

        return $autoload;
    }
}