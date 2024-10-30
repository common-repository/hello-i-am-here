<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hello_Here
 * @subpackage Hello_Here/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Hello_Here
 * @subpackage Hello_Here/includes
 * @author     Your Name <email@example.com>
 */
class Hello_Here_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
//		(new self)->goMeet_deletePluginsTables();
	}

	function goMeet_deletePluginsTables(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'meetreunions';

		$sql = "DROP TABLE $table_name";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$wpdb->query($sql);

		delete_option('meetreunion_db_version');
	}
}
