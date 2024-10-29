<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sdelka.biz
 * @since             1.1.17
 * @package           Sdelka
 *
 * @wordpress-plugin
 * Plugin Name:       Affiliate program for your website ( integration with Sdelka.biz )
 * Plugin URI:        https://sdelka.biz/integrations/wp-plugin
 * Description:       Модуль для интеграции с платформой партнёрских программ Sdelka.biz для создания партнёрской программы
 * Version:           1.1.20
 * Requires at least: 4.7
 * Requires PHP:      5.2
 * Author:            Sdelka.biz
 * Author URI:        https://sdelka.biz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sdelka
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.17 and use SemVer - https://semver.org
 * Update it as you release new versions.
 */
define( 'SDELKA_VERSION', '1.1.20' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sdelka-activator.php
 */
function activate_sdelka() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sdelka-activator.php';
	Sdelka_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sdelka-deactivator.php
 */
function deactivate_sdelka() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sdelka-deactivator.php';
	Sdelka_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sdelka' );
register_deactivation_hook( __FILE__, 'deactivate_sdelka' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sdelka.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-sdelka-widget.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.17
 */
function run_sdelka() {

	$plugin = new Sdelka();
	$plugin->run();

}
run_sdelka();
