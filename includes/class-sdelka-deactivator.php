<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.1.17
 * @package    Sdelka
 * @subpackage Sdelka/includes
 * @author     sdelka.biz
 */
class Sdelka_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.17
	 */
	public static function deactivate() {

	    delete_option('sdelka_js_path');
	    delete_option('sdelka_apikey');
	    delete_option('sdelka_login');
	    delete_option('sdelka_ssid');
	    delete_option('sdelka_user_exist');
	    delete_option('sdelka_termlink');
	}

}
