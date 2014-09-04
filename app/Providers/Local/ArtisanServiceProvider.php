<?php namespace FluentKit\Providers\Local;

use Illuminate\Support\ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->commands('FluentKit\Console\Local\TestCommand');
	}

}