<?php namespace Dberry37388\Settings;

class Site {

	/**
	 * Holds the namespace for our key
	 *
	 * @var string
	 */
	protected $namespace = 'site';

	public function __construct($app)
	{
		// if we have a custom configured namespace, then use that. If not default to our
		// hard-coded namespace of site
		static::$namespace = Config::get('settings::config.namespace', static::$namespace);
	}

	/**
	 * Get a value from our site config
	 *
	 * @param  string $key
	 * @param  string $default
	 * @return string
	 */
	public function get($key = '', $default = '')
	{
		if (empty($key))
		{
			return;
		}

		return Config::get("site::{$key}", $default);
	}

	/**
	 * Sets a value in our site config
	 *
	 * @param string $key
	 * @param string
	 *
	 * @return  void
	 */
	public function set($key = '', $value = '')
	{
		if (empty($key))
		{
			return;
		}

		Config::set("site::{$key}", $value);
	}

	/**
	 * Set multiple values at one time
	 *
	 * Example:
	 * $attributes = array(
	 * 	'page_tile' => 'This is My Page Title',
	 * 	'section'   => 'Users'
	 * );
	 *
	 * Site::setMultiple($attributes);
	 *
	 * @param array $keys
	 */
	public function setMultiple($keys = array())
	{
		if (is_array($keys) and ! empty($keys))
		{
			foreach ($keys as $key=>$value)
			{
				$this->set($key, $value);
			}
		}
	}

	/**
	 * Checks to see if a uri matches our pattern
	 *
	 * You can pass an optional class for using with css. This can actually be anything
	 * that you may want to return.
	 *
	 * If the second parameter is empty, this will just return true.
	 *
	 * @param  string  $pattern
	 * @param  string  $class
	 *
	 * @return mixed
	 */
	public function uriIs($pattern, $class)
	{
		// use Laravel's built-in method to check for matching uri
		if (Request::is($pattern))
		{
			// check to see if a class has been set
			if ( ! empty($class))
			{
				// return our css class
				return $class;
			}

			// no class is set, we are just checking
			return true;
		}

		// does not match
		return false;
	}

	/**
	 * Sets the current section
	 *
	 * @param string $value
	 */
	public function setPageTitle($value = '')
	{
		$this->set('page_title', $value);
	}

	/**
	 * Gets the current section
	 *
	 * @return string
	 */
	public function getPageTitle()
	{
		return $this->get('page_title');
	}

	/**
	 * Sets the current section
	 *
	 * @param string $value
	 */
	public function setSection($value = '')
	{
		$this->set('section', $value);
	}

	/**
	 * Gets the current section
	 *
	 * @return string
	 */
	public function getSection()
	{
		return $this->get('section');
	}
}