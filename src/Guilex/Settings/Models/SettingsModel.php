<?php namespace Guilex\Settings\Models;

use Eloquent;

class SettingsModel extends Eloquent {

    public function freshTimestamp()
	{
	    return time();
	}

	/**
	 * Holds our table name
	 *
	 * @var string
	 */
	protected $table = 'settings';

}
