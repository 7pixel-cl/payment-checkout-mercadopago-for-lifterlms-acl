<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://agenciacl.tech
 * @since      1.0.0
 *
 * @package    Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 * @subpackage Payment_Checkout_Mercadopago_For_Lifterlms_Acl/includes
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
 * @package    Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 * @subpackage Payment_Checkout_Mercadopago_For_Lifterlms_Acl/includes
 * @author     Marco Alvarado <hola@agenciacl.tech>
 */
class Payment_Checkout_Mercadopago_For_Lifterlms_Acl {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION' ) ) {
			$this->version = PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'payment-checkout-mercadopago-for-lifterlms-acl';

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
	 * - Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader. Orchestrates the hooks of the plugin.
	 * - Payment_Checkout_Mercadopago_For_Lifterlms_Acl_i18n. Defines internationalization functionality.
	 * - Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Admin. Defines all hooks for the admin area.
	 * - Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payment-checkout-mercadopago-for-lifterlms-acl-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payment-checkout-mercadopago-for-lifterlms-acl-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-payment-checkout-mercadopago-for-lifterlms-acl-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-payment-checkout-mercadopago-for-lifterlms-acl-public.php';

		/**
		 * The file responsible for useful functions of plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-payment-checkout-mercadopago-for-lifterlms-helper.php';

		/**
		 * The class responsible for Mercado Pago gateway.
		 */

		require_once plugin_dir_path( __DIR__ ) . 'public/class-payment-checkout-mercadopago-for-lifterlms-acl-gateway.php';

		/**
		 * The class responsible for plugin updater checker of plugin.
		 */
		include_once plugin_dir_path( __DIR__ ) . 'includes/plugin-updater/plugin-update-checker.php';


		$this->loader = new Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Payment_Checkout_Mercadopago_For_Lifterlms_Acl_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Payment_Checkout_Mercadopago_For_Lifterlms_Acl_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Add Mercado Pago Payment to Lifter LMS
	 *
	 * @since 1.0.0
	 */

	public static function add_gateway($gateways) {
		$gateways[] = 'Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Gateway';
		return $gateways;
	}

	/**
	 * Routes register
	 *
	 * @since 1.0.0
	 */

	public function listener_register_routes(): void {
		register_rest_route( 'payment-checkout-mercadopago-for-lifterlms-acl/v1', '/notification', array(
			'methods' => 'POST',
			'callback' => array( 'Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Gateway', 'mercadoPago_listener' ),
			'permission_callback' => __return_empty_string(),
		) );
	}

	public function updater_init():?object {
		if(class_exists('ACL_Puc_Plugin_UpdateChecker')){
			return new ACL_Puc_Plugin_UpdateChecker(
				'https://api.linknacional.com.br/v2/u/?slug=payment-checkout-pagseguro-for-lifterlms',
				PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_FILE,
				PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG
			);
		}else{
			return null;
		}
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter('plugin_action_links_' . PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_BASENAME, 'ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper', 'add_plugin_row_meta', 10, 2);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $this, 'updater_init' );
		$this->loader->add_action( 'plugins_loaded', 'ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper', 'verify_plugin_dependencies', 999 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		$this->loader->add_filter( 'lifterlms_payment_gateways', $this, 'add_gateway' );
		$this->loader->add_action( 'rest_api_init', $this, 'listener_register_routes' );

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
	 * @return    Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader    Orchestrates the hooks of the plugin.
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

}
