<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.17
 * @package    Sdelka
 * @subpackage Sdelka/includes
 * @author     sdelka.biz
 */
class Sdelka_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.17
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sdelka',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
