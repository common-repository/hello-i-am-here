<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hello_Here
 * @subpackage Hello_Here/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hello_Here
 * @subpackage Hello_Here/includes
 * @author     Your Name <email@example.com>
 */
class Hello_Here_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		(new self)->createPluginTables();
	}

	public function createPluginTables() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'meetreunions';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		title varchar(200) DEFAULT '',
		meeting_room varchar(200) NOT NULL,
		code varchar(50) NOT NULL,
		is_scheduled tinyint DEFAULT 0 NOT NULL,
		scheduled_date datetime DEFAULT NULL,		
		created_at datetime DEFAULT NULL,
		domain varchar(100) DEFAULT NULL,
		is_custom_domain tinyint DEFAULT NULL,	
		PRIMARY KEY  (id)
	) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( 'meetreunion_db_version', HELLO_HERE_VERSION );
	}

}
