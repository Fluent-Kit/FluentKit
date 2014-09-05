<?php 
namespace FluentKit\Foundation\Plugin;

use Illuminate\Support\Manager;

class PluginManager extends Manager
{
    /**
     * Create an instance of the orchestra theme driver.
     *
     * @return Container
     */
    protected function createFluentKitDriver()
    {
        return new Container($this->app);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return 'fluentkit';
    }
}