<?php namespace Dberry37388\Settings;

use Illuminate\Support\NamespacedItemResolver;
use Dberry37388\Settings\Models\SettingsModel;
use Config;

class Settings extends NamespacedItemResolver {

	/**
	 * Holds all of our configuration (settings) items
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Whether or not our config has already been loaded from disk
	 *
	 * @var boolean
	 */
	protected $isLoaded = false;


	public function __construct()
	{
		// Load Our Settings
		// This will only happen one time. Once the items are loaded they will
		// stay in memory so we don't keep reloading them.
		$this->load();
	}

	/**
	 * Checks to see if a Setting Exists
	 *
	 * @param  string  $key  key we are checking
	 *
	 * @return boolean
	 */
	public function has($key)
	{
		$default = microtime(true);
		return $this->get($key, $default) != $default;
	}

	/**
	 * Retrieves the specified setting.
	 *
	 * @param  string $key     key we are retrieving
	 * @param  mixed $default a default value if the key does not exist.
	 *
	 * @return mixed
	 */
	public function get($key = '', $default = '')
	{
		// parse our key, using the Illuminate NamespaceResolver
		list($namespace, $group, $item) = $this->parseKey($key);

		// namespaces and groups our key.
		$collection = $this->getCollection($namespace, $group);

		if (isset($this->items[$collection]))
		{
			if (isset($this->items[$collection][$item]))
			{
				$setting = $this->items[$collection][$item];
			}
			elseif(empty($item))
			{
				$setting = $this->items[$collection];
			}
		}
		else
		{
			$fromConfig = Config::get($key);

			if ( ! empty($fromConfig))
			{
				$setting = $fromConfig;
			}
			else
			{
				$setting = $default;
			}
		}

		return $setting;
	}

	/**
	 * Returns all of our settings
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->items;
	}

	/**
	 * Grabs all of the config items stored in our settings table and loads them to memory.
	 *
	 * @return void
	 */
	public function load()
	{
		// if we have already loaded our settings, let's not do it again.
		// after the first load, settings are stored in memory.
		if ($this->isLoaded === true)
		{
			return;
		}

		// load all of the settings that we have stored in the database
		// using our SettingsModel
		$allSettings = SettingsModel::all();

		if ( ! empty($allSettings))
		{
			foreach ($allSettings as $setting)
			{
				$this->loadSetting($setting);
			}

			// make sure we set our isLoaded to true, that way we are not
			// continuously loading our settings over and over.
			$this->isLoaded = true;
		}

	}

	public function set($key, $value = '')
	{
		// try to look up our setting to see if it already exists
		$setting = SettingsModel::where('name', '=', $key)->first();

		// detect the format of our value and return the proper insert information
		list($value, $format) = $this->detectSettingFormat($value);


		if ($setting)
		{
			$setting->value  = $value;
			$setting->format = $format;
			$setting->save();
		}
		else
		{
			$setting = SettingsModel::create(array(
				'name'   => $key,
				'value'  => $value,
				'format' => $format
			));
		}

		// load our saved setting info to memory
		$this->loadSetting($setting);
	}

	public function setTemp($key, $value = '')
	{
		// parse our key, using the Illuminate NamespaceResolver
		list($namespace, $group, $item) = $this->parseKey($key);

		// namespaces and groups our key.
		$collection = $this->getCollection($namespace, $group);

		// format our value
		list($value, $format) = $this->detectSettingFormat($value);

		// add our settings to our items array
		$this->items[$collection][$item] = $this->formatSetting($value, $format);

		// now let's set and overwrite the base laravel config if we need to
		Config::set($key, $this->items[$collection][$item]);
	}

	public function forget($key)
	{
		// parse our key, using the Illuminate NamespaceResolver
		list($namespace, $group, $item) = $this->parseKey($key);

		// namespaces and groups our key.
		$collection = $this->getCollection($namespace, $group);

		if (isset($this->items[$collection]))
		{
			$setting = array_forget($this->items[$collection], $item);

			SettingsModel::where('name', '=', $key)->delete();
		}
	}

	protected function loadSetting($setting)
	{
		// parse our key, using the Illuminate NamespaceResolver
		list($namespace, $group, $item) = $this->parseKey($setting->name);

		// namespaces and groups our key.
		$collection = $this->getCollection($namespace, $group);

		// add our settings to our items array
		$this->items[$collection][$item] = $this->formatSetting($setting->value, $setting->format);

		// now let's set and overwrite the base laravel config if we need to
		Config::set($setting->name, $this->items[$collection][$item]);
	}

	/**
	 * Sets the namespace and group collection for our key
	 *
	 * Makes use of Laravel's NamespaceResolver
	 *
	 * @param  string  $key  the key we are working with
	 *
	 * @return string
	 */
	protected function getCollection($namespace, $group)
	{
		// return our collection.
		return empty($namespace) ? $group : "{$namespace}::{$group}"; exit;
	}

	/**
	 * Formats our DB stored value
	 *
	 * When saving a setting to the database, you can either store a string value or an array
	 * of values stored as a json_string.  This is a simple method to make sure we are returning
	 * a properly formatted value.
	 *
	 * @param  string  $value   the value we want to formatt
	 * @param  string  $format  format our value was stored in
	 *
	 * @return string
	 */
	protected function formatSetting($value, $format)
	{
		if ($format === 'json')
		{
			return json_decode($value);
		}

		return $value;
	}

	protected static function detectSettingFormat($value)
	{
		if (is_array($value))
		{
			$setting = array(
				json_encode($value),
				'json'
			);
		}
		else
		{
			$setting = array(
				$value,
				'string'
			);
		}

		return $setting;
	}
}