<?php

/**
 * Class of Mercado Pago Gateway.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || exit;

/*
 * Class of Mercado Pago Gateway.
 *
 * @since 1.0.0
 */
if (class_exists('LLMS_Payment_Gateway')) {
    final class Payment_Checkout_MercadoPago_For_Lifterlms_Acl_Gateway extends LLMS_Payment_Gateway {
        /**
         * A description of the payment process.
         *
         * @var string
         *
         * @since 1.0.0
         */
        protected $payment_instructions;

        /**
         * Constructor.
         *
         * @since   1.0.0
         *
         * @version 1.0.0
         */
        public function __construct() {
            $this->set_variables();
            
            if (is_admin()) {
                require_once PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_DIR . 'admin/class-payment-checkout-mercadopago-for-lifterlms-acl-admin.php';
                add_filter( 'llms_get_gateway_settings_fields', array('Payment_Checkout_Mercadopago_For_Lifterlms_Acl_Admin', 'add_settings_fields'), 10, 2 );
            }
            add_action( 'lifterlms_before_view_order_table', array($this, 'before_view_order_table') );
            add_action( 'lifterlms_after_view_order_table', array($this, 'after_view_order_table') );
        }

        /**
         * Output payment instructions if the order is pending | on-hold.
         *
         * @since 1.0.0
         */
        public function before_view_order_table(): void {
            $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

            // Get Payment Instruction value.
            $paymentInstruction = esc_html__($configs['paymentInstruction']);

            $payInstTitle = esc_html__( 'Payment Instructions', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG );

            // Make the HTML for present the Payment Instructions.
            $paymentInst = <<<HTML
            <div class="llms-notice llms-info">
                <h3>
                {$payInstTitle}
                </h3>
                {$paymentInstruction}
            </div>
HTML;

            // Below is the verification of payment of the order, to present or not the Instructions.
            global $wp;

            if ( ! empty( $wp->query_vars['orders'] ) ) {
                $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                if (
                    $order->get( 'payment_gateway' ) === $this->id
                    && in_array( $order->get( 'status' ), array('llms-pending', 'llms-on-hold', true), true )
                ) {
                    echo apply_filters( 'llms_get_payment_instructions', $paymentInst, $this->id );
                }
            }
        }
        
        /**
         * Output payment area if the order is pending.
         *
         * @since 1.0.0
         */
        public function after_view_order_table(): void {
            global $wp;

            if ( ! empty( $wp->query_vars['orders'] ) ) {
                $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                // Verification of the gateway, to not execute in other gateways which has no defined this function.
                if ($order->get( 'payment_gateway' ) === $this->id) {
                    // Getting helper functions and values.
                    $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

                    // Getting orderId number.
                    $orderId = $order->get('id');

                    // Getting obj $order from key.
                    $mercadoPagoObjOrder = llms_get_order_by_key('#' . $orderId);

                    // Getting URL for PagSeguro Checkout.
                    $urlMercadoPagoCheckout = $mercadoPagoObjOrder->mercadopagocheckout_url;

                    $title = esc_html__('Payment Area', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);
                    $span = esc_html__('Secure payment by SSL encryption.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);

                    $buttonTitle = esc_html__('Pay', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);
                    $buttonDesc = esc_html__('Mercado Pago Checkout Payment', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);

                    $imgAlt = esc_html__('Mercado Pago payment methods logos', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);
                    $imgTitle = esc_html__('This site accepts payments with the most of flags and banks, balance in Mercado Pago account and bank slip.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);

                    $descript = esc_html__('Pay with Mercado Pago by clicking on button &ldquo;Pay&rdquo; right below', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG);

                    // Make the HTML for present the Payment Area.
	                // TODO Change to Mercado Pago
                    $paymentArea = <<<HTML
                    <h2>{$title}</h2>
                    <div class="lkn_pagseguro_payment_area">
                        <div id="lkn_secure_site_wrapper">
                            <!-- Padlock HTML code (LifterLMS don't have one icons library) -->
                            &#128274;
                            <span>
                                {$span}
                            </span>
                        </div>
                        <img class="lifter_logo_pagseguro" src="//assets.pagseguro.com.br/ps-integration-assets/banners/pagamento/todos_animado_125_150.gif" alt="{$imgAlt}" title="{$imgTitle}">
                        <p id="text_desc_pagseguro"><b>{$descript}</b></p>
                        <a id="lkn_pagseguro_pay" href="{$urlMercadoPagoCheckout}" target="_blank"><button id="lkn_pagseguro_pay_button" title="{$buttonDesc}">{$buttonTitle}</button></a>
                    </div>
HTML;

                    // Below is the verification of payment of the order, to present or not the Payment Area.
                    global $wp;

                    if ( ! empty( $wp->query_vars['orders'] ) ) {
                        $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                        if (
                            $order->get( 'payment_gateway' ) === $this->id
                            && in_array( $order->get( 'status' ), array('llms-pending', 'llms-on-hold', true), true )
                        ) {
                            echo apply_filters( 'llms_get_payment_instructions', $paymentArea, $this->id );
                        }
                    }
                }
            }
        }

        /**
         * Called when the Update Payment Method form is submitted from a single order view on the student dashboard.
         *
         * Gateways should do whatever the gateway needs to do to validate the new payment method and save it to the order
         * so that future payments on the order will use this new source
         *
         * @param obj   $order     Instance of the LLMS_Order
         * @param array $form_data Additional data passed from the submitted form (EG $_POST)
         *
         * @since    3.10.0
         *
         */
        public function handle_payment_source_switch($order, $form_data = array()): void {
            $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

            $previous_gateway = $order->get( 'payment_gateway' );

            if ( $this->get_id() === $previous_gateway ) {
                return;
            }

            $order->set( 'payment_gateway', $this->get_id() );
            $order->set( 'gateway_customer_id', '' );
            $order->set( 'gateway_source_id', '' );
            $order->set( 'gateway_subscription_id', '' );

            // Proccess the switch for PagSeguro Order.
            try {
                $this->proccess_order($order);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago Gateway - Switch payment method process error: ' . $e->getMessage() . \PHP_EOL, 'Mercado Pago - Gateway');
                }
            }

            $order->add_note( sprintf( __( 'Payment method switched from "%1$s" to "%2$s"', 'lifterlms' ), $previous_gateway, $this->get_admin_title() ) );
        }

        /**
         * Handle a Pending Order.
         *
         * @since 3.0.0
         * @since 3.10.0 Unknown.
         * @since 6.4.0 Use `llms_redirect_and_exit()` in favor of `wp_redirect()` and `exit()`.
         *
         * @param LLMS_Order       $order   order object
         * @param LLMS_Access_Plan $plan    access plan object
         * @param LLMS_Student     $student student object
         * @param LLMS_Coupon|bool $coupon  coupon object or `false` when no coupon is being used for the order
         */
        public function handle_pending_order($order, $plan, $student, $coupon = false) {
            $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

            // Make log.
            if ('yes' === $configs['logEnabled']) {
                $this->log( 'Mercado Pago Gateway `handle_pending_order()` started', $order, $plan, $student, $coupon );
            }

            // Make error log.
            if ( ! is_ssl() ) {
                if ('yes' === $configs['logEnabled']) {
                    $this->log( 'Mercado Pago Gateway `handle_pending_order()` ended with validation errors' );
                }

                return llms_add_notice( __('Not secure payment by SSL encryption.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG), 'error' );
            }

            $total = $order->get_price( 'total', array(), 'float' );

            // Validate min value.
            if ( $total < 1000 ) {
                if ('yes' === $configs['logEnabled']) {
                    $this->log( 'Mercado Pago Gateway `handle_pending_order()` ended with validation errors', 'Less than minimum order amount.' );
                }

                return llms_add_notice( __( 'This gateway cannot process transactions for less than $1.000 clp.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG ), 'error' );
            }

            // Free orders (no payment is due).
            if ( (float) 0 === $order->get_initial_price( array(), 'float' ) ) {
                // Free access plans do not generate receipts.
                if ( $plan->is_free() ) {
                    $order->set( 'status', 'completed' );

                    // Free trial, reduced to free via coupon, etc....
                    // We do want to record a transaction and then generate a receipt.
                } else {
                    // Record a $0.00 transaction to ensure a receipt is sent.
                    $order->record_transaction(
                        array(
                            'amount' => (float) 0,
                            'source_description' => __( 'Free', 'lifterlms' ),
                            'transaction_id' => uniqid(),
                            'status' => 'llms-txn-succeeded',
                            'payment_gateway' => 'mercadopago-v1',
                            'payment_type' => 'single'
                        )
                    );
                }

                return $this->complete_transaction( $order );
            }

            // Process PagSeguro Order.
            $this->proccess_order($order);

            /*
             * Action triggered when a Mercado Pago payment is due.
             *
             * @hooked LLMS_Notification: manual_payment_due - 10
             *
             * @since Unknown.
             *
             * @param LLMS_Order                  $order   The order object.
             * @param LLMS_Payment_Gateway_Manual $gateway Manual gateway instance.
             */
            do_action( 'llms_manual_payment_due', $order, $this );

            /*
             * Action triggered when the pending order processing has been completed.
             *
             * @since 1.0.0.
             *
             * @param LLMS_Order $order The order object.
             */
            do_action( 'lifterlms_handle_pending_order_complete', $order );

            llms_redirect_and_exit( $order->get_view_link() );
        }        

        /**
         * Proccess the Mercado Pago order.
         *
         * @since 1.0.0
         *
         * @param LLMS_Order $order order object
         */
        public function proccess_order($order) {
            $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

            // Get the order total price.
            $total = $order->get_price( 'total', array(), 'float' );

            // Payer information
            $payerEmail = $order->billing_email;
            $payerName = $order->get_customer_name();
            $payerPhone = $order->billing_phone;
            $payerPhoneDDD = substr($payerPhone, 0, 2);
            $payerPhone = substr($payerPhone, 2);
            $payerCurrency = $order->currency;

            if ('CLP' !== $payerCurrency) {
                return llms_add_notice( 'Mercado Pago Currency error: Only CLP payments are avaliable.', 'error' );
            } else {
                $itemPriceCents = number_format(filter_var($total, \FILTER_SANITIZE_NUMBER_FLOAT), 2, '.', '');
            }

            // POST parameters
            $tokenKey = $configs['tokenKey'];
            $emailKey = $configs['email'];
            $url = $configs['urlPost'] . 'v2/checkout?email=' . $emailKey . '&token=' . $tokenKey;
            $orderId = $order->get( 'id' );
            $notificationUrl = add_query_arg('lkn_pagseguro_orderid', $orderId, site_url() . '/wp-json/lkn-lifter-pagseguro-listener/v1/notification');
            $itemDesc = $order->product_title . ' | ' . $order->plan_title . ' (ID# ' . $order->get('plan_id') . ')' ?? $order->plan_title;
            $itemDesc = preg_replace(array('/(á|à|ã|â|ä)/', '/(Á|À|Ã|Â|Ä)/', '/(é|è|ê|ë)/', '/(É|È|Ê|Ë)/', '/(í|ì|î|ï)/', '/(Í|Ì|Î|Ï)/', '/(ó|ò|õ|ô|ö)/', '/(Ó|Ò|Õ|Ô|Ö)/', '/(ú|ù|û|ü)/', '/(Ú|Ù|Û|Ü)/', '/(ñ)/', '/(Ñ)/', '/(ç)/', '/(Ç)/'), explode(' ', 'a A e E i I o O u U n N c C'), $itemDesc);
            $itemDesc = substr($itemDesc, 0, 100); // Catch first 100 characters of string (PagSeguro description limit).
            $itemDesc = sanitize_text_field($itemDesc);
            $itemId = $order->product_id;
            $returnUrl = home_url();

            $body = array(
                'currency' => (string) $payerCurrency,
                'itemId1' => (string) $itemId,
                'itemDescription1' => (string) $itemDesc,
                'itemAmount1' => (string) $itemPriceCents,
                'itemQuantity1' => (string) 1,
                'itemWeight' => (string) 0,
                'shippingAddressRequired' => (string) 'false',
                'senderName' => (string) $payerName,
                'senderEmail' => (string) $payerEmail,
                'senderAreaCode' => (string) $payerPhoneDDD,
                'senderPhone' => (string) $payerPhone,
                'reference' => (string) $orderId,
                'redirectURL' => (string) $returnUrl,
                'notificationURL' => (string) $notificationUrl,
                'timeout' => (string) 720 // 12 hours in minutes.
            );

            // Header
            $dataHeader = array(
                'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
            );

            // Build request body query on pattern x-www-form-urlencoded.
            $dataBody = http_build_query($body, 'lkn_', '&', \PHP_QUERY_RFC1738);

            // Reset the order_key of obj $order for further search.
            update_post_meta($orderId, '_llms_order_key', '#' . $orderId);

            // Make the request.
            $requestResponse = $this->mercadoPago_request($dataBody, $dataHeader, $url);

            // Catch XML response data.
            $responseXml = simplexml_load_string((string) $requestResponse);
            $returnCode = $responseXml->{'code'};
            $message = $responseXml->{'message'};

            // Log request error if not success.
            if (($message[0]) && ( ! $returnCode[0])) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log( 'Mercado Pago Gateway `handle_pending_order()` ended with api request errors', 'Mercado Pago - Gateway');
                }

                return llms_add_notice( 'Mercado Pago API Error - Operation rejected, reason: ' . $message, 'error' );
            }

            // If request is success, save the important data for further use in payment area.
            if (isset($requestResponse)) {
                if ($returnCode[0] && ! $message[0]) {
                    // Build the URL for PagSeguro Checkout with the Code.
                    $mercadoPagoCheckoutUrl = $configs['urlQuery'] . 'v2/checkout/payment.html?code=' . $returnCode;

                    // Save URL in object property `mercadopagocheckout_url`.
                    $order->set('mercadopagocheckout_url', $mercadoPagoCheckoutUrl);
                } else {
                    return llms_add_notice( 'Mercado Pago API Error - Operation rejected, reason: ' . $message, 'error' );
                }
            }
        }

        /**
         * PagSeguro Request.
         *
         * @since 1.0.0
         *
         * @param mixed $dataBody
         * @param mixed $dataHeader
         * @param mixed $url
         *
         * @return array
         */
        public function mercadoPago_request($dataBody, $dataHeader, $url) {
            try {
                $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

                // Make the request args.
                $args = array(
                    'headers' => $dataHeader,
                    'body' => $dataBody,
                    'timeout' => '10',
                    'redirection' => '5',
                    'httpversion' => '1.1'
                );

                // Make the request.
                $request = wp_remote_post($url, $args);

                // Register log.
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway POST: ' . var_export($request, true) . \PHP_EOL, 'Mercado Pago - Gateway');
                }

                return wp_remote_retrieve_body($request);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway POST error: ' . $e->getMessage() . \PHP_EOL, 'Mercado Pago - Gateway Error');
                }

                return array();
            }
        }

        /**
         * PagSeguro Query.
         *
         * @since 1.0.0
         *
         * @param mixed $dataBody
         * @param mixed $dataHeader
         * @param mixed $url
         *
         * @return array
         */
        public static function mercadopago_query($dataHeader, $url) {
            try {
                $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

                // Make the query args.
                $args = array(
                    'headers' => $dataHeader,
                    'timeout' => '10',
                    'redirection' => '5',
                    'httpversion' => '1.1'
                );

                // Make the query.
                $query = wp_remote_get($url, $args);

                // Register log.
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway GET: ' . var_export($query, true) . \PHP_EOL, 'PagSeguro - Gateway');
                }

                return wp_remote_retrieve_body($query);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway GET error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway Error');
                }

                return array();
            }
        }

        /**
         * Mercado Pago status Listener.
         *
         * @since 1.0.0
         *
         * @param WP_REST_Request $request Request Object
         *
         * @return WP_REST_Response
         */
        public static function mercadopago_listener($request) {
            // Receive notification.
            if (isset($_GET['lkn_pagseguro_orderid'])) {
                try {
                    $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

                    // Start verification of order status and att order status.
    
                    $notificationCode = sanitize_text_field($_POST['notificationCode']);
                    $mercadopago_order_id = sanitize_text_field($_GET['lkn_pagseguro_orderid']);

                    $emailKey = $configs['email'];
                    $tokenKey = $configs['tokenKey'];
                    $url = $configs['urlPost'] . 'v3/transactions/notifications/' . $notificationCode . '?email=' . $emailKey . '&token=' . $tokenKey;
    
                    $dataHeader = array(
                        'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
                    );

                    // Query for order status verification.
                    $queryResponse = Payment_Checkout_MercadoPago_For_Lifterlms_Acl_Gateway::mercadopago_query($dataHeader, $url);

                    // Catch XML response data.
                    $responseXml = simplexml_load_string((string) $queryResponse);
                    $result = $responseXml->{'status'};
                    
                    // Determine order status text.
                    if (3 == $result[0] || 4 == $result[0]) {
                        $statusText = 'paid';
                    } elseif (8 == $result[0] || 6 == $result[0]) {
                        $statusText = 'refunded';
                    } elseif (7 == $result[0]) {
                        $statusText = 'canceled';
                    } elseif ($result[0]) {
                        $statusText = 'failed';
                    } else {
                        $statusText = 'pending';
                    }

                    // Search $order object.
                    $orderObj = llms_get_order_by_key('#' . $mercadopago_order_id);

                    // Verify if is recurring.
                    $recurrency = $orderObj->is_recurring();

                    // Log informations.
                    if ('yes' === $configs['logEnabled']) {
                        llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago listener - GET order status: Order #' . var_export($mercadopago_order_id, true) . \PHP_EOL . var_export($orderObj, true) . \PHP_EOL . 'Is recurring: ' . var_export($recurrency, true) . \PHP_EOL, 'PagSeguro - Gateway Listener');
                    }

                    // Call the order_status_setter function.
                    Payment_Checkout_MercadoPago_For_Lifterlms_Acl_Gateway::order_set_status($orderObj, $statusText, $recurrency);

                    return rest_ensure_response(array_merge($request));
                } catch (Exception $e) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway listener error: ' . var_export($e, true) . \PHP_EOL, 'Mercado Pago - Gateway Listener');
                }
            }
        }

        /**
         * Set the order status.
         *
         * @since 1.0.0
         *
         * @param LLMS_Order $order      Instance of the LLMS_Order
         * @param string     $status
         * @param bool       $recurrency
         * @param string     $gatewayId
         * @param string     $gatewayName
         */
        public static function order_set_status($order, $status, $recurrency): void {
            try {
                $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

                if ('paid' == $status) {
                    if ($recurrency) {
                        $order->set('status', 'llms-active');

                        Payment_Checkout_MercadoPago_For_Lifterlms_Acl_Gateway::record_pagseguro_transaction($order, 'Signature', 'recurring');
                    } else {
                        $order->set('status', 'llms-completed');

                        Payment_Checkout_MercadoPago_For_Lifterlms_Acl_Gateway::record_pagseguro_transaction($order, 'Signature', 'single');
                    }
                } elseif ('failed' == $status) {
                    $order->set('status', 'llms-failed');
                } elseif ('pending' == $status) {
                    $order->set('status', 'llms-pending');
                } elseif ('refunded' == $status) {
                    $order->set('status', 'llms-refunded');
    
                    // Realiza o processo de reembolo: Altera os valores da dashbord e registra dentro do pedido a nota de reembolso.
                    $order->get_last_transaction()->process_refund($order->get_price( 'total', array(), 'float' ), 'Mercado Pago Gateway - Refund');
                } elseif ('canceled' == $status) {
                    $order->set('status', 'llms-cancelled');
                } else {
                    return;
                }
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway - set order status error: ' . $e->getMessage() . \PHP_EOL, 'Mercado Pago - Gateway');
                }
            }
        }

        /**
         * Update the record transaction dashboard with Mercado Pago transactions.
         *
         * @since 1.0.0
         *
         * @param LLMS_Order $order       Instance of the LLMS_Order
         * @param string     $description
         * @param string     $paymentType
         * @param string     $gatewayId
         */
        public function record_pagseguro_transaction($order, $description, $paymentType): void {
            $order->record_transaction(
                array(
                    'amount' => $order->get_price( 'total', array(), 'float' ),
                    'source_description' => __( $description, 'lifterlms' ),
                    'transaction_id' => uniqid(),
                    'status' => 'llms-txn-succeeded',
                    'payment_gateway' => $this->id,
                    'payment_type' => $paymentType
                )
            );
        }

        /**
         * Called by scheduled actions to charge an order for a scheduled recurring transaction
         * This function must be defined by gateways which support recurring transactions.
         *
         * @param obj $order Instance LLMS_Order for the order being processed
         *
         * @return mixed
         *
         * @since    3.10.0
         *
         * @version  3.10.0
         */
        public function handle_recurring_transaction($order) {
            $configs = ACL_Payment_Checkout_MercadoPago_For_Lifterlms_Helper::get_configs();

            // Switch order status to "on hold" if it's a paid order.
            if ( $order->get_price( 'total', array(), 'float' ) > 0 ) {
                // Update status.
                $order->set_status( 'on-hold' );

                try {
                    $this->proccess_order($order);
                } catch (Exception $e) {
                    if ('yes' === $configs['logEnabled']) {
                        llms_log('Date: ' . date('d M Y H:i:s') . ' Mercado Pago gateway - recurring order process error: ' . $e->getMessage() . \PHP_EOL, 'Mercado Pago - Gateway');
                    }
                }

                // @hooked LLMS_Notification: manual_payment_due - 10
                do_action( 'llms_manual_payment_due', $order, $this );
            }
        }

        /**
         * Determine if the gateway is enabled according to admin settings checkbox.
         *
         * @return bool
         */
        public function is_enabled() {
            return ( 'yes' === $this->get_enabled() ) ? true : false;
        }

        protected function set_variables(): void {
            /*
             * The gateway unique ID.
             *
             * @var string
             */
            $this->id = 'mercadopago-v1';

            /*
             * The title of the gateway displayed in admin panel.
             *
             * @var string
             */
            $this->admin_title = __( 'Mercado Pago (v1)', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG );

            /*
             * The description of the gateway displayed in admin panel on settings screens.
             *
             * @var string
             */
            $this->admin_description = __( 'Allow customers to purchase courses and memberships using Mercado Pago.', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG );

            /*
             * The title of the gateway.
             *
             * @var string
             */
            $this->title = __( 'Mercado Pago Checkout', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG );

            /*
             * The description of the gateway displayed to users.
             *
             * @var string
             */
            $this->description = __( 'Payment via Mercado Pago checkout', PAYMENT_CHECKOUT_MERCADOPAGO_FOR_LIFTERLMS_ACL_SLUG );

            $this->supports = array(
                'checkout_fields' => true,
                'refunds' => false, // Significa que compras feitas com esse gateway podem ser reembolsadas, porém, esse gateway não funciona como um método de reembolso.
                'single_payments' => true,
                'recurring_payments' => true,
                'test_mode' => false
            );

            $this->admin_order_fields = wp_parse_args(
                array(
                    'customer' => true,
                    'source' => true,
                    'subscription' => false
                ),
                $this->admin_order_fields
            );
        }
    }
}
