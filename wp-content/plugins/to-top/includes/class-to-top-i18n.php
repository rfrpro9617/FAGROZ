<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       catchplugins.com
 * @since      1.0
 *
 * @package    To_Top
 * @subpackage To_Top/includes
 */

class To_Top_i18n
{


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_plugin_textdomain()
	{
		// Translations load automatically since WP 4.6; explicit call removed.
	}
}
