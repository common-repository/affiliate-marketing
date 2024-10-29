<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sdelka
 * @subpackage Sdelka/admin
 * @author     sdelka.biz
 */
class Sdelka_Admin {

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
	 * @param      string    $sdelka       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $sdelka, $version ) {

		$this->sdelka = $sdelka;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->sdelka, plugin_dir_url( __FILE__ ) . 'css/sdelka-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1.17
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->sdelka, plugin_dir_url( __FILE__ ) . 'js/sdelka-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Sending information about confirmation affiliate sale 
	 *
	 * @since    1.1.19
	 */
	public function woocommerce_order_status_completed( $order_id ){

	    $sdelka_apikey = get_option('sdelka_apikey');
	    if( !$sdelka_apikey ){
	        return ;
	    }

	    $WC_Order = new WC_Order($order_id);
        $response = wp_remote_post( "https://api.sdelka.biz/", array(
        	'timeout'     => 45,
        	'redirection' => 5,
        	'httpversion' => '1.0',
        	'blocking'    => true,
        	'headers'     => array(),
        	'body'        => array(
                                'apikey' => $sdelka_apikey,
                                'action' => 'confirm_deal',
    	                        'supplier_deal_id' => $WC_Order->get_id(),
    	                        'cart_total' => $WC_Order->get_total(),
                                'client_email' => $WC_Order->get_billing_email(),
        	                    'client_phone' => $WC_Order->get_billing_phone()
    	                   )
        ));
	}
	
	/**
	 * Sending information about confirmation affiliate sale 
	 *
	 * @since    1.1.19
	 */
	public function woocommerce_order_status_cancelled( $order_id ){

	    $sdelka_apikey = get_option('sdelka_apikey');
	    if( !$sdelka_apikey ){
	        return ;
	    }
	    

	    $WC_Order = new WC_Order($order_id);
        $response = wp_remote_post( "https://api.sdelka.biz/", array(
        	'timeout'     => 45,
        	'redirection' => 5,
        	'httpversion' => '1.0',
        	'blocking'    => true,
        	'headers'     => array(),
        	'body'        => array(
                                'apikey' => $sdelka_apikey,
                                'action' => 'canceled_deal',
    	                        'supplier_deal_id' => $WC_Order->get_id(),
    	                        'cart_total' => $WC_Order->get_total(),
                                'client_email' => $WC_Order->get_billing_email(),
        	                    'client_phone' => $WC_Order->get_billing_phone()
    	                   )
        ));
	}
	
}
