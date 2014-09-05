<?php 
namespace FluentKit\Foundation\Theme;

use Illuminate\Support\Manager;

class ThemeManager extends Manager
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

    /**
     * Detect available themes.
     *
     * @return array
     */
    public function detect()
    {
        return $this->app['theme.finder']->detect();
    }
}