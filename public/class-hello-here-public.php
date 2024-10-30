<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hello_Here
 * @subpackage Hello_Here/public
 */

	require_once plugin_dir_path( __FILE__ ) . '../includes/class-hello-here-functions.php';

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hello_Here
 * @subpackage Hello_Here/public
 * @author     Your Name <email@example.com>
 */
class Hello_Here_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $meet_reunion    The ID of this plugin.
	 */
	private $meet_reunion;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $gmf; //meetreunionfunctions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $meet_reunion       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $meet_reunion, $version ) {

		$this->meet_reunion = $meet_reunion;
		$this->version = $version;

		$this->gmf = new HelloHereFunctions('meetreunions');

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->meet_reunion, plugin_dir_url( __FILE__ ) . 'css/hello-here-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->meet_reunion, plugin_dir_url( __FILE__ ) . 'js/hello-here-public.js', array( 'jquery' ), $this->version, false );

	}

	// AJAX RESPONSE
	public function my_action() {
//			global $wpdb; // this is how you get access to the database

//			$whatever = intval( $_POST['whatever'] );
//
//			$whatever += 10;
//
//			echo $whatever;
//            echo 'ooooooooook';
//
//			wp_die(); // this is required to terminate immediately and return a proper response
		$response = array(
			'status'  => 200,
			'content' => 'This is an AJAX response.'
		);

		// Terminate the callback and return a proper response.
		wp_die( json_encode( $response ) );
	}

	// SHORTCODES
	public function showMeet($atts){
        global $current_user;
        if(is_user_logged_in()){
            $user_name = $current_user->display_name;
        }else{
            $user_name = __('Guest', 'hello-here');
        }
		wp_enqueue_script( 'hello-here-vue-js', HELLO_HERE_PLUGIN_DIR_URL . 'js/vue.js', array(), $this->version, false );
		wp_enqueue_script( 'hello-here-jitsi-js', HELLO_HERE_PLUGIN_DIR_URL . 'js/jitsi_external_api.js', array(), $this->version, false );
		wp_enqueue_script( 'hello-here-axios', HELLO_HERE_PLUGIN_DIR_URL . 'js/axios.min.js', array(), $this->version, true );
		wp_enqueue_script( 'hello-here-main-js', plugin_dir_url( __FILE__ ) . 'js/hello-here-show-meet.js', array('hello-here-vue-js'), $this->version, true );
		wp_localize_script( 'hello-here-main-js', 'ajax_var', array(
			'url'    => admin_url( 'admin-ajax.php' ),
			'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
            'msg_invalid_code'  => __('Invalid Code', 'hello-here'),
            'user_name' => $user_name
		) );
	?>
        <?php ob_start(); ?>
		<div class="wrap">
			<?php include( 'partials/hello-here-show-meet.php' ); ?>
		</div>
		<?php return ob_get_clean(); ?>
	<?php
	}

	public function get_meet_reunion(){
		// Check for nonce security
		$nonce = sanitize_text_field( $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'my-ajax-nonce' ) ) {
			$response = [
				'status'        => '0',
				'message'       => __('Hello I am here! not created for security reasons (WP "nonce"). Refresh page and try again!', 'hello-here')
			];
			wp_die( json_encode($response) );
		}
	    $code = sanitize_text_field($_POST['code']);
        $meet = $this->gmf->getGoMeet($code);

		$response = [
			'status'        => '1',
			'meet'       => $meet[0]
		];

		wp_die( json_encode($response) );
    }

}
