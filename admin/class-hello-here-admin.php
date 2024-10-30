<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hello_Here
 * @subpackage Hello_Here/admin
 */

	require_once plugin_dir_path( __FILE__ ) . '../includes/class-hello-here-functions.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hello_Here
 * @subpackage Hello_Here/admin
 * @author     Your Name <email@example.com>
 */
class Hello_Here_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $meet_reunion    The ID of this plugin.
	 */
	private $meet_reunion;
    private $public_servers = array();

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $gmf;  // meet_reunion_functions
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $meet_reunion       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $meet_reunion, $version ) {

		$this->meet_reunion = $meet_reunion;
		$this->version = $version;

		$this->gmf = new HelloHereFunctions('meetreunions');

        $this->public_servers = array(
	        array(
		        'priority' => 10,
		        'server' => 'webconf.viviers-fibre.net'
	        ),
	        array(
		        'priority' => 20,
		        'server' => 'jitsi.uni-kl.de'
	        ),
	        array(
		        'priority' => 30,
		        'server' => 'video.devloprog.org'
	        ),
	        array(
		        'priority' => 40,
		        'server' => 'video.omicro.org'
	        ),
        );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hello_Here_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hello_Here_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->meet_reunion, plugin_dir_url( __FILE__ ) . 'css/hello-here-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap-css', HELLO_HERE_PLUGIN_DIR_URL . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hello_Here_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hello_Here_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->meet_reunion, plugin_dir_url( __FILE__ ) . 'js/hello-here-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bootstrap-js', HELLO_HERE_PLUGIN_DIR_URL . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'popper-js', HELLO_HERE_PLUGIN_DIR_URL . 'js/popper.min.js', array( 'jquery' ), $this->version, false );
	}

	public function meetreunion_update_db_check() {
		if ( get_site_option( 'meetreunion_db_version' ) != HELLO_HERE_VERSION ) {
			require_once plugin_dir_path( __FILE__ ) . '../includes/class-hello-here-activator.php';
			Hello_Here_Activator::activate();
		}
	}

	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'Hello I am here!';
		$menu_title = 'Hello I am here!';
		$capability = 'manage_options';
		$slug = 'meet_reunion';
		$callback = array( $this, 'plugin_settings_page_content' );
//		$icon = 'dashicons-admin-plugins';
		$icon = plugin_dir_url(__FILE__).'../images/page-icon.png';
		$position = 100;
		add_menu_page(  $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
	}

	public function plugin_settings_page_content() {
		wp_enqueue_script( 'hello-here-vue-js', HELLO_HERE_PLUGIN_DIR_URL . 'js/vue.js', array(), $this->version, false );
		wp_enqueue_script( 'hello-here-axios', HELLO_HERE_PLUGIN_DIR_URL . 'js/axios.min.js', array(), $this->version, true );
		wp_enqueue_script( 'hello-here-main-js', plugin_dir_url( __FILE__ ) . 'js/hello-here-main.js', array('hello-here-vue-js'), $this->version, true );
		wp_localize_script( 'hello-here-main-js', 'ajax_var', array(
			'url'    => admin_url( 'admin-ajax.php' ),
			'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
			'slug'   => sanitize_title(get_bloginfo('name')),
			'action' => 'meetreunion_save_meetreunion',
            'domain' => get_option(HELLO_HERE_OPTIONS . 'domain'),
		) );
		wp_enqueue_script( 'flatpickr-js', HELLO_HERE_PLUGIN_DIR_URL . 'js/flatpickr.js', array('jquery'), $this->version, true );
		wp_enqueue_style('flatpicker-css',HELLO_HERE_PLUGIN_DIR_URL . 'css/flatpickr.min.css');
		?>
		<div class="wrap">
			<h2><?php _e('Hello I am here!', 'hello-here'); ?></h2>
            <?php include( 'partials/hello-here-admin-main.php' ); ?>
		</div> <?php
	}

	// sections
	public function setup_sections() {
	}

    // AJAX RESPONSE
    public function save_meet_reunion() {
        // Check for nonce security
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( ! wp_verify_nonce( $nonce, 'my-ajax-nonce' ) ) {
            $response = [
                'status'        => '0',
                'message'       => __('Hello I am here! not created for security reasons (WP "nonce"). Refresh page and try again!', 'hello-here')
            ];
            wp_die( json_encode($response) );
        }

        $data = array();
	    $data['title']              = sanitize_text_field($_POST['title']);
	    $data['meeting_room']       = sanitize_text_field($_POST['jitsiid']);
	    $data['code']               = sanitize_text_field($_POST['code']);
	    $data['is_scheduled']       = sanitize_text_field($_POST['is_scheduled']);
	    $data['domain']             = sanitize_text_field($_POST['domain']);
	    $data['scheduled_date']     = sanitize_text_field($_POST['scheduled_date']);
	    $data['custom_domain']     = $_POST['custom_domain'];

        if($data['custom_domain'] === 'false'){
            // random select server
            $servers_num = count($this->public_servers);
            $server_num = rand(0, $servers_num - 1);
            $data['domain'] = $this->public_servers[$server_num]['server'];
        }

	    if($data['title'] == ''){
		    $data['title'] = __('Meeting created at', 'hello-here') . ' ' . date('d/m/y H:i');
	    }

	    // save domain for future uses
        if($data['custom_domain'] === 'true'){
	        update_option(HELLO_HERE_OPTIONS . 'domain', $data['domain']);
        }else{
	        update_option(HELLO_HERE_OPTIONS . 'domain', '');
        }
        $message = $this->gmf->createGoMeet($data);
        if($message === false){
            $status = 0;
            $message = __('Meeting not created', 'hello-here');
        }else{
            $status = 1;
            $message = __('Meeting created!', 'hello-here');
        }
	    $response = [
		    'status'        => $status,
		    'message'       => $message
	    ];

        // Terminate the callback and return a proper response.
        wp_die( json_encode($response) );
    }

    public function get_meet_reunions(){
	    // Check for nonce security
	    $nonce = sanitize_text_field( $_POST['nonce'] );

	    if ( ! wp_verify_nonce( $nonce, 'my-ajax-nonce' ) ) {
		    $response = [
			    'status'        => '0',
			    'message'       => __("Can't get any meeting", 'hello-here')
		    ];
		    wp_die( json_encode($response) );
	    }

	    $meets = $this->gmf->getGoMeets();
	    $response = [
		    'meets'       => $meets
	    ];

	    wp_die(json_encode($response));
    }

    public function delete_meet_reunion(){
	    // Check for nonce security
	    $nonce = sanitize_text_field( $_POST['nonce'] );

	    if ( ! wp_verify_nonce( $nonce, 'my-ajax-nonce' ) ) {
		    $response = [
			    'status'        => '0',
			    'message'       => __("Security error")
		    ];
		    wp_die( json_encode($response) );
	    }

	    $id = sanitize_text_field($_POST['id']);
        $response = $this->gmf->deleteGoMeet($id);

	    $response = [
				'status'        => '1',
				'deleted'       => $id,
				'meets'         => $this->gmf->getGoMeets()
			];

	    wp_die(json_encode($response));
    }
}
