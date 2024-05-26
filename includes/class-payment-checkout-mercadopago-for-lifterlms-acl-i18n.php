<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://agenciacl.tech
 * @since      1.0.0
 *
 * @package    Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 * @subpackage Payment_Checkout_Mercadopago_For_Lifterlms_Acl/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 * @subpackage Payment_Checkout_Mercadopago_For_Lifterlms_Acl/includes
 * @author     Marco Alvarado <hola@agenciacl.tech>
 */
class Payment_Checkout_Mercadopago_For_Lifterlms_Acl_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'payment-checkout-mercadopago-for-lifterlms-acl',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
