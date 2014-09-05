<?php 
namespace FluentKit\Foundation\Plugin;

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
    
    public $buffer;

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
        $this->path = $app['path.plugins'];

        // Register relative and absolute URL for theme usage.
        $this->absoluteUrl = rtrim($baseUrl, '/').'/resources/plugins';
        $this->relativeUrl = trim(str_replace($baseUrl, '/', $this->absoluteUrl), '/');
    }


    /**
     * Detect available themes.
     *
     * @return array
     */
    public function all()
    {
        return $this->app['plugin.finder']->collection();
    }

    public function activated(){
    	return $this->app['plugin.finder']->collection()->filter(function($plugin){
            return $plugin->active;
        });
    }

    /**
     * Detect available themes.
     *
     * @return array
     */
    public function get($key)
    {

		$plugins = $this->app['plugin.finder']->collection()->filter(function($plugin) use ($key){
			return ($plugin->uid == $key) ? true : false;
		});
		return $plugins->first();
    }
    
    public function activate($key){
        $plugin = $this->get($key);
        
        if($plugin->active)
            return false;
        
        $plugin = $this->get($key);
        $this->app['db']->table('plugins')->insert(array(
            array('plugin' => $key, 'key' => 'active', 'value' => true),
            array('plugin' => $key, 'key' => 'version', 'value' => $plugin->version)
        ));
        $this->app['events']->fire('plugin.'.$key.'.activated');
    }
    
    public function upgrade($key){
        $plugin = $this->get($key);
        
        if($plugin->version == $plugin->folder_version || !$plugin->active)
            return false;
        
        $this->app['events']->fire('plugin.'.$key.'.upgrade', $plugin);
        $this->app['db']->table('plugins')->where('plugin', $key)->where('key', 'version')->update(array('value' => $plugin->folder_version));
        $this->app['events']->fire('plugin.'.$key.'.upgraded');
        
        return true;
    }
    
    public function deactivate($key){
        $plugin = $this->get($key);
        if(!$plugin->active)
            return false;
        
        $this->app['db']->table('plugins')->where('plugin', $key)->where('key', 'active')->delete();
        $this->app['events']->fire('plugin.'.$key.'.deactivated');
    }
    
    public function uninstall($key){
        $this->deactivate($key);
        $this->app['db']->table('plugins')->where('plugin', $key)->delete();
        $this->app['events']->fire('plugin.'.$key.'.uninstalled');
        return true;
    }
    
    public function delete($key){
        $this->uninstall($key);
        try{
            $path = $this->app['path.plugins'] . '/'.$key;
            $this->app['files']->remove($path);
        }catch(\Symfony\Component\Filesystem\Exception\IOException $e){
            return false;
        }
        $this->app['events']->fire('plugin.'.$key.'.deleted');
        return true;
    }

}