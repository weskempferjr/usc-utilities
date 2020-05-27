<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://usc.edu/lan
 * @since      1.0.0
 *
 * @package    Usc_Utilities
 * @subpackage Usc_Utilities/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Usc_Utilities
 * @subpackage Usc_Utilities/includes
 * @author     Lan Jin <lan.jin@usc.edu>
 */
class Usc_Utilities_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'usc-utilities',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
