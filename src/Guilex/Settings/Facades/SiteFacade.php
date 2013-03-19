<?php namespace Guilex\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class SiteFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'guilex.site'; }

}