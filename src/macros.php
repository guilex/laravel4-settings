<?php

/**
 * These are examples of using a custom macro. You can add as many of these as you like.
 * I will be adding a few common ones as the need arises.
 *
 * Use the example below as a guide to create more. You can place these anywhere you want,
 * you just need to make sure you include them.
 *
 * I do not recommend putting them in this file, as it will be overwritten when you do a composer update.
 */

/**
 * Gets our Page TItle
 * Will use the Site::get() to pull our page title from the config
 */
Site::macro('getPageTitle', function() {

	// return our page title from the site config
	return Site::get('page_title');
});