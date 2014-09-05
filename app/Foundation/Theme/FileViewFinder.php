<?php 
namespace FluentKit\Foundation\Theme;

use \Illuminate\View\FileViewFinder as ViewFinder;

class FileViewFinder extends ViewFinder
{
    
    /**
     * {@inheritdoc}
     */
    protected function findNamedPathView($name)
    {
        
        list($namespace, $view) = $this->getNamespaceSegments($name);

        // Prepend global view paths to namespace hints path. This would
        // allow theme to take priority if such view exist.
        $generatePath = function ($path) use ($namespace) {
            return "{$path}/packages/{$namespace}";
        };

        $paths = array_map($generatePath, $this->paths);
        
        //fix for plugins
        $generatePath2 = function ($path) use ($namespace) {
            return "{$path}/plugins/{$namespace}";
        };

        $paths2 = array_map($generatePath2, $this->paths);

        return $this->findInPaths($view, array_merge($paths, $paths2, $this->hints[$namespace]));
    }

    /**
     * Set the active view paths.
     *
     * @param  array    $paths
     * @return array
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }
}