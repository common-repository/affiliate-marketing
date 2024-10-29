<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sdelka
 * @subpackage Sdelka/public
 * @author     sdelka.biz
 */
class Sdelka_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.17
	 * @access   private
	 * @var      string    $sdelka    The ID of this plugin.
	 */
	private $sdelka;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.17
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.17
	 * @param      string    $sdelka       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $sdelka, $version ) {

		$this->sdelka = $sdelka;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.1.17
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sdelka_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sdelka_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->sdelka, plugin_dir_url( __FILE__ ) . 'css/sdelka-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.1.17
	 */
	public function enqueue_scripts() {

		/**
		 * Insert Sdelka Tracking script to head of page
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sdelka_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sdelka_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	    $site_url = site_url();
	    $site_url_parse = parse_url($site_url);
	    $site_host = str_replace('www.', '', $site_url_parse['host']);
	    $sdelka_js_path = get_option('sdelka_js_path');
	    $sdelka_js_path = $sdelka_js_path ? $sdelka_js_path : $site_host;

	    wp_enqueue_script('sdelka', 'https://s.sdelka.biz/'.$sdelka_js_path.'.js');

	}
	
	/**
	 * Sending information about affiliate sale 
	 *
	 * @since    1.1.19
	 */
	public function create_affiliate_deal( $order_id ){

	    $sdelka_apikey = get_option('sdelka_apikey');
	    if( !$sdelka_apikey ){
	        return ;
	    }
	    
	    $sa_cartid = sanitize_text_field($_COOKIE['sa_cartid_last_checkout']);
	    $WC_Order = new WC_Order($order_id);

        $response = wp_remote_post( "https://api.sdelka.biz/", array(
        	'timeout'     => 45,
        	'redirection' => 5,
        	'httpversion' => '1.0',
        	'blocking'    => true,
        	'headers'     => array(),
        	'body'        => array(
                                'apikey' => $sdelka_apikey,
                                'action' => 'create_deal',
                                'sa_cartid' => $sa_cartid,
    	                        'supplier_deal_id' => $WC_Order->get_id(),
    	                        'cart_total' => $WC_Order->get_total(),
                                'client_email' => $WC_Order->get_billing_email(),
        	                    'client_phone' => $WC_Order->get_billing_phone()
    	                   )
        ));
	}

}
