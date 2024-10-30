<?php

	/**
	 * Plugin Name:     Hello I am here!
	 * Description:     Create instant meetings with your clients or potential clients.
	 * Version:         3.0
	 * Author:          goliver79@gmail.com
	 * Text Domain:     hello-here
	 * Domain Path:     /languages
	 * License:         GPL2
	 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HELLO_HERE_VERSION', '3.0' );
define ('HELLO_HERE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));
define( 'HELLO_HERE_OPTIONS', 'hello_i_am_here_' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hello-here-activator.php
 */
function hello_here_activate_meet_reunion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hello-here-activator.php';
	Hello_Here_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hello-here-deactivator.php
 */
function hello_here_deactivate_meet_reunion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hello-here-deactivator.php';
	Hello_Here_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'hello_here_activate_meet_reunion' );
register_deactivation_hook( __FILE__, 'hello_here_deactivate_meet_reunion' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hello-here.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function hello_here_run_meet_reunion() {

	$plugin = new Hello_Here();
	$plugin->run();

}
hello_here_run_meet_reunion();


