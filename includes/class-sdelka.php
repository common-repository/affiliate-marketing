<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.17
 * @package    Sdelka
 * @subpackage Sdelka/includes
 * @author     sdelka.biz
 */
class Sdelka {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.1.17
	 * @access   protected
	 * @var      Sdelka_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.1.17
	 * @access   protected
	 * @var      string    $sdelka    The string used to uniquely identify this plugin.
	 */
	protected $sdelka;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.1.17
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.1.17
	 */
	public function __construct() {
		if ( defined( 'SDELKA_VERSION' ) ) {
			$this->version = SDELKA_VERSION;
		} else {
			$this->version = '1.1.17';
		}
		$this->sdelka = 'sdelka';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sdelka_Loader. Orchestrates the hooks of the plugin.
	 * - Sdelka_i18n. Defines internationalization functionality.
	 * - Sdelka_Admin. Defines all hooks for the admin area.
	 * - Sdelka_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.1.17
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sdelka-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sdelka-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sdelka-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sdelka-public.php';

		$this->loader = new Sdelka_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sdelka_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.1.17
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sdelka_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.1.17
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sdelka_Admin( $this->get_sdelka(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	
	    if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $this->loader->add_action('woocommerce_order_status_completed', $plugin_admin, 'woocommerce_order_status_completed');
            $this->loader->add_action('woocommerce_order_status_cancelled', $plugin_admin, 'woocommerce_order_status_cancelled');
            $this->loader->add_action('woocommerce_order_status_failed', $plugin_admin, 'woocommerce_order_status_cancelled');
            $this->loader->add_action('woocommerce_order_status_refunded', $plugin_admin, 'woocommerce_order_status_cancelled');
	    }
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.1.17
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sdelka_Public( $this->get_sdelka(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		if( isset($_COOKIE['sa_cartid_last_checkout']) && in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
            $this->loader->add_action( 'woocommerce_new_order', $plugin_public, 'create_affiliate_deal' );
            //$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'create_affiliate_deal' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.1.17
	 */
	public function run() {

    	//Add Sdelka Plugin homepage to admin menu
        $this->loader->add_action( 'admin_menu', $this, 'register_sdelka_menu_page');
    	
        //Add settings link of Sdelka Plugin to plugins menu
        $this->loader->add_action('plugin_action_links_sdelka/sdelka.php', $this, 'plugin_action_links');
        
        //Register sdelka widget
        $this->loader->add_action( 'widgets_init', $this,'register_sdelka_widget' );
        
		$this->loader->run();
	}
	
	/**
	 * register_sdelka_menu_page
	 *
	 * @since    1.1.17
	 */
	
	public function register_sdelka_menu_page() {
	    
    	add_menu_page( 'Настройка партнёрской платформы SDELKA.biz', 'Партнёрская программа', 'edit_others_posts', 'sdelka',  array($this,'add_sdelka_setting'), plugins_url( 'affiliate-marketing/images/handshake.svg' ), 80 );
    }
	
	/**
	 * Register sdelka widget
	 *
	 * @since    1.1.17
	 */
    // 
    public function register_sdelka_widget() {
        register_widget( 'Sdelka_Widget' );
    }

    
	/**
	 * add_sdelka_setting
	 *
	 * @since    1.1.20
	 */
	
	public function add_sdelka_setting(){
	    
        if ( isset($_POST['action']) && $_POST['action'] == 'setting' ) {
            $this->update_sdelka_settings();

        }
	    $sdelka_ssid = get_option('sdelka_ssid');
	    $sdelka_apikey = get_option('sdelka_apikey');
	    $sdelka_login = get_option('sdelka_login');
        $sdelka_user_exist = get_option('sdelka_user_exist');
        
	    ?>
<div style="max-width:950px">
<form method="post" action="">
<input type="hidden" name="action" value="setting">

</table>

<h2 class="title">Модуль для интеграции с платформой партнёрских программ Sdelka.biz для создания партнёрской программы</h2>
<p>Теперь партнёрские продажи или другие целевые действия будут регистрироваться на платформе Sdelka.biz <br />
Необходимо только настроить условия сотрудничества с вашими партнёрами, для этого перейдите по ссылке: <br /> 
<a href="https://sdelka.biz/terms/new/?ssid=<?php esc_html_e($sdelka_ssid); ?>" target="_blank">https://sdelka.biz/terms/new/?ssid=<?php esc_html_e($sdelka_ssid); ?></a></p>
<p>
Затем настройте получение реферальных ссылок для ваших пользователей ( размещение партнёрских кнопок или всплывающего окна вашей партнёрской программы ) - <a href="https://sdelka.biz/promo/?ssid=<?php esc_html_e($sdelka_ssid); ?>" target="_blank">Настроить</a><br />
Ссылку на условия сотрудничества с вашими партнёрами, вы можете разместить с помощью <a href="<?php _e(get_admin_url(null, 'widgets.php')); ?>">виджета "Партнёрская программа"</a> ( Ссылка на условия вашей партнёрской программы  ) в любом блоке вашего сайта
</p>
<?php 

if( !preg_match('@^[a-z0-9]{32}$@ui', $sdelka_apikey) && $sdelka_user_exist ){
    ?>
<p>Ваш логин: <?php esc_html_e($sdelka_login); ?> зарегистрирован на платформе Sdelka.biz, чтобы получить API Key, <a href="https://sdelka.biz/shop/?ssid=<?php esc_html_e($sdelka_ssid); ?>" target="_blank">перейдите по ссылке</a> </p>    
    <?php 
}

?>
<table class="form-table">
<tbody>
<tr>
	<th scope="row"><label>Ваш логин на <a href="https://sdelka.biz/?ssid=<?php esc_html_e($sdelka_ssid); ?>" target="_blank">Sdelka.biz</a>:<br /> (пароль был отправлен вам на почту)</label></th>
	<td><input name="sdelka_login" type="text" id="sdelka_login" value="<?php esc_html_e($sdelka_login); ?>" class="regular-text ltr"></td>
</tr>
<tr>
	<th scope="row"><label>API Key:</label><br />( <a href="https://sdelka.biz/shop/?ssid=<?php esc_html_e($sdelka_ssid); ?>" target="_blank">получить или обновить ключ</a> )</th>
	<td><input name="sdelka_apikey" type="text" id="sdelka_apikey" value="<?php esc_html_e($sdelka_apikey); ?>" class="regular-text ltr"></td>
</tr>
<tr>
	<th scope="row"><label>&nbsp;</label></th>
	<td><input type="submit" name="submit" id="submit" class="button button-primary" value="Обновить данные"></td>
</tr>
</tbody></table>
</form>
<div>
<h3>Платформа Sdelka.biz позволяет</h3>
<p>
    <ul>
        <li>1. Создать свою партнёрскую программу</li>
        <li>2. Сформировать условия сотрудничества с партнёрами ( размер и условия вознаграждения за продажу или целевое действие )</li>
        <li>3. Регистрировать партнёрские действия/продажи по <b>партнёрским ссылкам</b> (реферальным ссылкам)</li>
        <li>4  Принимать и выдавать <b>партнёрские промокоды</b> своим посетителям для распространения их в соц.сетях и через мессенджеры</li>
        <li>5. Разместить информацию о своей партнёрской программе у себя на сайте в два клика. </li>
        <li>6. Выплачивать партнёрские вознаграждения централизованно ( возможна оплата по счёту )</li>
        <li>7. Согласовывать вознаграждения за партнёрские продажи оффлайн ( продажи по оффлайн рекомендациям )</li>
    </ul>
</p>
<h3>Полезные материалы</h3>
<p>
    <ul>
        <li>1. Короткий ролик о партнёрском маркетинге: <a href="https://sdelka.biz/#video" target="_blank">https://sdelka.biz/#video</a></li>
        <li>2. Пример магазина на WordPress: <a href="https://sdelka.biz/examples/wordpress" target="_blank">https://sdelka.biz/examples/wordpress</a></li>
        <li>3. Промо-материалы и варианты размещения информации о партнёрской программе: <a href="https://sdelka.biz/promo/?ssid=<?php esc_html_e($sdelka_ssid); ?>" target="_blank">https://sdelka.biz/promo/</a></li>
        <li>4. Инструкция для ваших пользователей - "Партнёрская программа - Как это работает ?": <a href="https://sdelka.biz/how-it-work" target="_blank">https://sdelka.biz/how-it-work</a></li>
        <li>5. Партнёрские промокоды - Как это работает ? ( Схема работы ): <a href="https://sdelka.biz/promo/promocodes/how-it-work" target="_blank">https://sdelka.biz/promo/promocodes/how-it-work</a></li>
        <li>6. Преимущества Партнёрских промокодов: <a href="https://sdelka.biz/promo/promocodes/advantage" target="_blank">https://sdelka.biz/promo/promocodes/advantage</a></li>

    </ul>
</p>
</div>
</div><?php 

	}
	
	/**
	 * update_sdelka_settings
	 *
	 * @since    1.1.17
	 */
	
	public function update_sdelka_settings() {

        if( isset($_POST['sdelka_apikey']) ) {
            $sdelka_apikey = sanitize_text_field($_POST['sdelka_apikey']);
            update_option('sdelka_apikey', $sdelka_apikey);
        }
        else {
            return ;
        }
        
	    $sdelka_login = isset($_POST['sdelka_login']) ? sanitize_text_field( $_POST['sdelka_login']) : '';

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
                                'email' => $sdelka_login,
        	                    'apikey' => $sdelka_apikey
    	                   )
        ));
        
        $data = json_decode($response['body'],1);

        $sdelka_ssid = isset($data['ssid']) ? $data['ssid'] : $sdelka_ssid;
        $sdelka_apikey = isset($data['apikey']) ? $data['apikey'] : $sdelka_apikey;
        $sdelka_js_path = isset($data['js_path']) ? $data['js_path'] : $sdelka_js_path;
        $sdelka_login = isset($data['login']) ? $data['login'] : $sdelka_login;
        $sdelka_termlink = isset($data['termlink']) ? $data['termlink'] : '';

        setcookie('sa_ssid', $sdelka_ssid, time()+31536000, COOKIEPATH, COOKIE_DOMAIN, false);

        update_option('sdelka_js_path', $sdelka_js_path);
	    update_option('sdelka_apikey', $sdelka_apikey);
	    update_option('sdelka_login', $sdelka_login);
	    update_option('sdelka_ssid', $sdelka_ssid);
        update_option('sdelka_termlink', $sdelka_termlink);
    }
	
	/**
	 * Add settings link of Sdelka Plugin to plugins menu
	 *
	 * @since    1.1.17
	 */
	
	public function plugin_action_links($links) {
        $links[] = '<a href="'.get_admin_url(null, 'admin.php?page=sdelka').'">Настройки</a>';
    
        return $links;
    }
	

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.1.17
	 * @return    string    The name of the plugin.
	 */
	public function get_sdelka() {
		return $this->sdelka;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.1.17
	 * @return    Sdelka_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.1.17
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
