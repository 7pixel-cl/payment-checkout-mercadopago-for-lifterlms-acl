<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://agenciacl.tech
 * @since      1.0.0
 *
 * @package    Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 * @subpackage Payment_Checkout_Mercadopago_For_Lifterlms_Acl/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 * @subpackage Payment_Checkout_Mercadopago_For_Lifterlms_Acl/admin
 * @author     Marco Alvarado <hola@agenciacl.tech>
 */
class Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public static function add_settings_fields($default_fields, $gateway_id) {
		$gateway = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_lifter_gateway( 'mercadopago-v1' );

		$fields = array();

		// Field for Payment instructions.
		$fields[] = array(
			'id' => $gateway->get_option_name( 'payment_instructions' ),
            'desc' => '<br>' . __( 'Displayed to the user when this gateway is selected during checkout. Add information here instructing the student on how to send payment.', 'lifterlms' ),
			'title' => __( 'Payment Instructions', 'lifterlms' ),
			'type' => 'textarea',
		);

		$fields[] = array(
			'id' => $gateway->get_option_name( 'email' ),
			'title' => __( 'E-mail of Mercado Pago', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG ),
			'desc' => '<br>' . __( 'E-mail registered in the administrative area of Mercado Pago.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG ),
			'type' => 'text',
		);
		// TODO: Change to Mercado Pago
		$fields[] = array(
			'id' => $gateway->get_option_name( 'env_type' ),
			'title' => __( 'Type of environment', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG ),
			'desc' => '<br>' . __('Enable environment of test or production.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG),
			'type' => 'radio',
			'default' => 'sandbox',
			'options' => array(
				'sandbox' => __('Sandbox', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG),
				'production' => __('Production', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG),
			),
		);

		if ($gateway->id === $gateway_id){
			$default_fields = array_merge($default_fields, $fields);
		}
		return $default_fields;
	}

	// TODO: Check if needed to remove
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
		 * defined in Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/payment-checkout-mercadopago-for-lifterlms-acl-admin.css', array(), $this->version, 'all' );

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
		 * defined in Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/payment-checkout-mercadopago-for-lifterlms-acl-admin.js', array( 'jquery' ), $this->version, false );

	}



}
