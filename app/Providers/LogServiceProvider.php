<?php namespace FluentKit\Providers;

use Log;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider {

	/**
	 * Configure the application's logging facilities.
	 *
	 * @return void
	 */
	public function boot()
	{
		Log::useFiles(storage_path().'/logs/fluentkit.log');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}