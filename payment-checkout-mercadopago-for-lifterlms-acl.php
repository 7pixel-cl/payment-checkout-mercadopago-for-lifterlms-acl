<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://agenciacl.tech
 * @since             1.0.0
 * @package           Payment_Checkout_Mercadopago_For_Lifterlms_Acl
 *
 * @wordpress-plugin
 * Plugin Name:       Mercado Pago Payment option for LifterLMS
 * Plugin URI:        https://agenciacl.tech/lms-mercadopago
 * Description:       Enable new payment methods for LifterLMS using MercadoPago Chile.
 * Version:           1.0.0
 * Author:            Marco Alvarado
 * Author URI:        https://agenciacl.tech/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       payment-checkout-mercadopago-for-lifterlms-acl
 * Domain Path:       /languages
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
define( 'PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION', '1.0.0' );

define ('PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_MIN_LIFTERLMS_VERSION', '7.2.0');

define ('PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_FILE', __FILE__);

define ('PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_SLUG', 'payment-checkout-mercadopago-for-lifterlms-acl');

define ('PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_DIR', plugin_dir_path(PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_FILE));

define ('PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_URL', plugin_dir_url(PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_FILE));

define ('PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_BASENAME', plugin_basename(PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_VERSION_FILE));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-payment-checkout-mercadopago-for-lifterlms-acl-activator.php
 */
function activate_payment_checkout_mercadopago_for_lifterlms_acl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-payment-checkout-mercadopago-for-lifterlms-acl-activator.php';
	Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-payment-checkout-mercadopago-for-lifterlms-acl-deactivator.php
 */
function deactivate_payment_checkout_mercadopago_for_lifterlms_acl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-payment-checkout-mercadopago-for-lifterlms-acl-deactivator.php';
	Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_payment_checkout_mercadopago_for_lifterlms_acl' );
register_deactivation_hook( __FILE__, 'deactivate_payment_checkout_mercadopago_for_lifterlms_acl' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-payment-checkout-mercadopago-for-lifterlms-acl.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_payment_checkout_mercadopago_for_lifterlms_acl() {

	$plugin = new Payment_Checkout_Mercadopago_For_Lifterlms_Acl();
	$plugin->run();

}
run_payment_checkout_mercadopago_for_lifterlms_acl();
