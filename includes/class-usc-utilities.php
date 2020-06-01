<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://usc.edu/lan
 * @since      1.0.0
 *
 * @package    Usc_Utilities
 * @subpackage Usc_Utilities/includes
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
 * @since      1.0.0
 * @package    Usc_Utilities
 * @subpackage Usc_Utilities/includes
 * @author     Lan Jin <lan.jin@usc.edu>
 */
class Usc_Utilities {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Usc_Utilities_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $maps_post_type ;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'USC_UTILITIES_VERSION' ) ) {
			$this->version = USC_UTILITIES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'usc-utilities';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_shortcodes();
		$this->register_post_types();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Usc_Utilities_Loader. Orchestrates the hooks of the plugin.
	 * - Usc_Utilities_i18n. Defines internationalization functionality.
	 * - Usc_Utilities_Admin. Defines all hooks for the admin area.
	 * - Usc_Utilities_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-utilities-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-utilities-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-usc-utilities-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-usc-utilities-public.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-utilities-shortcodes.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-maps-post-type.php';

		// wp-content/plugins/usc-utilities/includes/classusc-utili-ties-loader.php

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-utilities-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-utilities-maps-meta-box.php';



		$this->loader = new Usc_Utilities_Loader();

		$this->maps_post_type = new Maps_Post_Type();



	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Usc_Utilities_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Usc_Utilities_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Usc_Utilities_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		switch ( $this->get_current_post_type() ) {
			case 'maps':
			case 'edit-maps':
				$maps_meta_box = new Usc_Utilities_Maps_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $maps_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $maps_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $maps_meta_box, 'post_meta_save' );
				break;


			default:
				break;
		}


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Usc_Utilities_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Usc_Utilities_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register shortcodes
	 */
	private function register_shortcodes() {
		$shortcodes = new Usc_Utilities_Shortcodes();
		$shortcodes->register_shortcodes();
	}

	private function register_post_types() {
		$this->loader->add_action('init', $this->maps_post_type, 'register');
	}


	private function get_current_post_type() {
		if ( isset( $_REQUEST['post_type'] )  ) {
			return $_REQUEST['post_type'];
		}
		elseif (isset( $_REQUEST['screen_id'] ) ) {
			return $_REQUEST['screen_id'];
		}
		elseif (isset( $_POST['screen_id'] ) ) {
			return $_POST['screen_id'];
		}
		else {
			if ( isset( $_REQUEST['post'])) {
				$post_type = get_post_type( $_REQUEST['post'] );
				return $post_type;
			}
		}
	}


}
