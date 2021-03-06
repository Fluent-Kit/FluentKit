<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application Namespace
	|--------------------------------------------------------------------------
	|
	| This is the root namespace used by the various Laravel generator tasks
	| that are able to build controllers, console commands and many other
	| classes for you. You may set the name via the "app:name" command.
	|
	*/

	'root' => 'FluentKit\\',

	/*
	|--------------------------------------------------------------------------
	| Generator Namespaces
	|--------------------------------------------------------------------------
	|
	| These namespaces are utilized by the various class generator Artisan
	| commands. You are free to change them to whatever you wish or not
	| at all. The "app:name" command is the easiest way to set these.
	|
	*/

	'console' => 'FluentKit\Console\\',

	'controllers' => 'FluentKit\Http\Controllers\\',

	'filters' => 'FluentKit\Http\Filters\\',

	'providers' => 'FluentKit\Providers\\',

	'requests' => 'FluentKit\Http\Requests\\',

];
