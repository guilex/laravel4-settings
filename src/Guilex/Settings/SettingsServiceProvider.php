<?php namespace Guilex\Settings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider {

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
	public function boot()
	{
		$this->package('guilex/settings');

		$this->app['guilex.settings']->load();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['guilex.settings'] = $this->app->share(function($app)
		{
			return new \Guilex\Settings\Settings();
		});

		$this->app['guilex.site'] = $this->app->share(function($app)
		{
			return new \Guilex\Settings\Site($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}