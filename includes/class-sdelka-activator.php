<?php

/**
 * Fired during plugin activation
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.17
 * @package    Sdelka
 * @subpackage Sdelka/includes
 * @author     sdelka.biz
 */
class Sdelka_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.17
	 */
	public static function activate() {

	    $sdelka_login = get_userdata(get_current_user_id())->user_email;

        $response = wp_remote_post( "https://api.sdelka.biz/", array(
        	'timeout'     => 45,
        	'redirection' => 5,
        	'httpversion' => '1.0',
        	'blocking'    => true,
        	'headers'     => array(),
        	'body'        => array(
                                'action' => 'registration',
                                'engine' =>'wp',
                                'site' => site_url(),
                                'email' => $sdelka_login
    	                   )
        ));

        $data = json_decode($response['body'],1);

        $sdelka_ssid = isset($data['ssid']) ? $data['ssid'] : '';
        $sdelka_apikey = isset($data['apikey']) ? $data['apikey'] : '';
        $sdelka_js_path = isset($data['js_path']) ? $data['js_path'] : '';
        $sdelka_user_exist  = isset($data['user_exist']) ? $data['user_exist'] : '';
        $sdelka_termlink  = isset($data['termlink']) ? $data['termlink'] : '';

        setcookie('sa_ssid', $sdelka_ssid, time()+31536000, COOKIEPATH, COOKIE_DOMAIN, false);

        update_option('sdelka_js_path', $sdelka_js_path);
	    update_option('sdelka_apikey', $sdelka_apikey);
	    update_option('sdelka_login', $sdelka_login);
	    update_option('sdelka_ssid', $sdelka_ssid);
        update_option('sdelka_user_exist', $sdelka_user_exist);
        update_option('sdelka_termlink', $sdelka_termlink);
	}

}
