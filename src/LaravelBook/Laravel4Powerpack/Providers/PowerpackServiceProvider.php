<?php namespace LaravelBook\Laravel4Powerpack\Providers;

/*
 * This file is part of the Laravel 4 Powerpack package.
 *
 * Portions Copyright (c) Max Ehsan <contact@laravelbook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\ServiceProvider;
use LaravelBook\Laravel4Powerpack\HTML;
use LaravelBook\Laravel4Powerpack\Form;
use LaravelBook\Laravel4Powerpack\Str;

class PowerpackServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$this->package( 'laravelbook/laravel4-powerpack' );
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app['html'] = $this->app->share( function( $app ) {
				return new HTML( $app['url'] );
			} );

		$this->app['form'] = $this->app->share( function( $app ) {
				return new Form( $app['html'] );
			} );

		$this->app['str'] = $this->app->share( function( $app ) {
				return new Str;
			} );
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array( 'html', 'form', 'str' );
	}

}
