<?php

namespace Wecp\App\Controllers;

use Liquid\Template;
use PasswordHash;

class ShortCodes extends Base
{
    private $order, $type, $email_arguments, $html_template, $language, $sample = false;

    /**
     * ShortCodes constructor.
     * @param $order_id
     * @param $html_template
     * @param $args
     * @param $type
     * @param $language
     * @param bool $sample
     */
    function __construct($type, $html_template, $args, $order_id = '', $language = '', $sample = false)
    {
        parent::__construct();
        if (empty($language)) {
            $language = self::$woocommerce->getSiteDefaultLang();
        }
        if (!empty($order_id)) {
            $this->order = self::$woocommerce->getOrder($order_id);
        } else {
            $this->order = NULL;
        }
        $this->sample = $sample;
        $this->html_template = $html_template;
        $this->type = $type;
        $this->email_arguments = $args;
        $this->language = $language;
    }

    /**
     * Create email required data to process template
     * @return array
     */
    function organizeTemplateData()
    {
        $short_codes = array();
        if (!empty($this->order)) {
            $this->order = apply_filters("woocommerce_email_customizer_plus_before_processing_short_codes", $this->order);
            $tax_display = self::$woocommerce->taxDisplay();
            //Billing details
            $first_name = self::$woocommerce->getBillingFirstName($this->order);
            $last_name = self::$woocommerce->getBillingLastName($this->order);
            $short_codes['billing_address']['formatted_address'] = self::$woocommerce->getBillingAddressFormatted($this->order);
            $short_codes['billing_address']['first_name'] = $first_name;
            $short_codes['billing_address']['last_name'] = $last_name;
            $short_codes['billing_address']['name'] = $first_name . ' ' . $last_name;
            $short_codes['billing_address']['company'] = self::$woocommerce->getBillingCompany($this->order);
            $short_codes['billing_address']['phone'] = self::$woocommerce->getBillingPhone($this->order);
            $short_codes['billing_address']['address1'] = self::$woocommerce->getBillingAddressOne($this->order);
            $short_codes['billing_address']['address2'] = self::$woocommerce->getBillingAddressTwo($this->order);
            $short_codes['billing_address']['city'] = self::$woocommerce->getBillingCity($this->order);
            $bill_country_code = self::$woocommerce->getBillingCountry($this->order);
            $bill_state_code = self::$woocommerce->getBillingState($this->order);
            $states = self::$woocommerce->getStates($bill_country_code);
            $short_codes['billing_address']['state'] = $bill_state_code;
            $short_codes['billing_address']['province'] = isset($states[$bill_state_code]) ? $states[$bill_state_code] : NULL;
            $short_codes['billing_address']['zip'] = self::$woocommerce->getBillingPostCode($this->order);
            $short_codes['billing_address']['country'] = self::$woocommerce->getCountry($bill_country_code);;
            //Shipping details
            $short_codes['shipping_address']['formatted_address'] = self::$woocommerce->getShippingAddressFormatted($this->order);
            $short_codes['shipping_address']['first_name'] = self::$woocommerce->getShippingFirstName($this->order);
            $short_codes['shipping_address']['last_name'] = self::$woocommerce->getShippingLastName($this->order);
            $short_codes['shipping_address']['name'] = self::$woocommerce->getShippingFirstName($this->order) . ' ' . self::$woocommerce->getShippingLastName($this->order);
            $short_codes['shipping_address']['company'] = self::$woocommerce->getShippingCompany($this->order);
            $short_codes['shipping_address']['address1'] = self::$woocommerce->getShippingAddressOne($this->order);
            $short_codes['shipping_address']['address2'] = self::$woocommerce->getShippingAddressTwo($this->order);
            $short_codes['shipping_address']['city'] = self::$woocommerce->getShippingCity($this->order);
            $ship_country_code = self::$woocommerce->getShippingCountry($this->order);
            $ship_state_code = self::$woocommerce->getShippingState($this->order);
            $states = self::$woocommerce->getStates($ship_country_code);
            $short_codes['shipping_address']['state'] = $ship_state_code;
            $short_codes['shipping_address']['province'] = isset($states[$ship_state_code]) ? $states[$ship_state_code] : NULL;
            $short_codes['shipping_address']['zip'] = self::$woocommerce->getShippingPostCode($this->order);
            $short_codes['shipping_address']['country'] = self::$woocommerce->getCountry($ship_country_code);
            $short_codes['shipping_method']['title'] = self::$woocommerce->getShippingMethod($this->order);
            $shipping_cost = self::$woocommerce->getShippingCost($this->order);
            $short_codes['shipping_method']['price'] = self::$woocommerce->formatPrice($shipping_cost);
            $short_codes['shipping_price'] = $shipping_cost;
            //$short_codes['tax_lines'] = $this->getTaxLines($tax_display);
            //Order details
            $items_totals = self::$woocommerce->getOrderItemsTotal($this->order);
            $order_subtotal = '';
            $order_fee = $order_refund = 0;
            if (!empty($items_totals)) {
                foreach ($items_totals as $item_total_key => $items_total_value) {
                    if (strpos($item_total_key, 'fee') !== false) {
                        $fees = self::$woocommerce->getOrderFees($this->order);
                        foreach ($fees as $fee) {
                            $order_fee += self::$woocommerce->getOrderFeeAmount($fee);
                        }
                    }
                    if (strpos($item_total_key, 'refund') !== false) {
                        $order_refund = self::$woocommerce->getOrderRefundAmount($this->order);
                    }
                }
            }
            if (isset($items_totals['cart_subtotal']['value'])) {
                $order_subtotal = $items_totals['cart_subtotal']['value'];
            }
            $order_notes_html = $order_last_note_html = '';
            $order_notes = self::$woocommerce->getOrderNotes($this->order);
            if (!empty($order_notes) && is_array($order_notes)) {
                $order_notes_html = $this->orderCustomerNotes($order_notes);
                $last_note = isset($order_notes[0]) ? array($order_notes[0]) : array();
                $order_last_note_html = $this->orderCustomerNotes($last_note);
            }
            $order_url = self::$woocommerce->getOrderUrl($this->order);
            $cart_table_html = $this->orderCartTable();
            $order_email = self::$woocommerce->getBillingEmail($this->order);
            $short_codes['id'] = self::$woocommerce->getOrderId($this->order);
            $short_codes['email'] = $order_email;
            $short_codes['order_number'] = self::$woocommerce->getOrderNumber($this->order);
            $order_items = $this->getLineItemsDetails();
            $short_codes['item_count'] = count($order_items);
            //$short_codes['line_items'] = $order_items;
            $short_codes['last_note'] = $order_last_note_html;
            $short_codes['landing_site'] = site_url();
            $short_codes['note'] = $order_notes_html;
            $short_codes['order_status_url'] = $order_url;
            $short_codes['order_status_link'] = '<a href="' . $order_url . '">' . esc_html__("Order", WECP_TEXT_DOMAIN) . '</a>';
            $short_codes['subtotal_price'] = $order_subtotal;
            $short_codes['total_price'] = self::$woocommerce->getOrderTotalPrice($this->order, $tax_display);
            $short_codes['created_at'] = self::$woocommerce->getOrderDate($this->order);
            $short_codes['order']['id'] = self::$woocommerce->getOrderId($this->order);
            $short_codes['order']['fee'] = $order_fee;
            $short_codes['order']['refund'] = $order_refund;
            $short_codes['order']['url'] = $order_url;
            $short_codes['order']['cart'] = $cart_table_html;
            $short_codes['order']['downloads'] = $this->orderDownloadsTable();
            $short_codes['order']['customer_provided_note'] = self::$woocommerce->getOrderCustomerNote($this->order);
            //Payment details
            $payment_method = '';
            if (isset($items_totals['payment_method']['value'])) {
                $payment_method = $items_totals['payment_method']['value'];
            }
            if (empty($payment_method)) {
                $payment_method = self::$woocommerce->getOrderPaymentMethod($this->order);
            }
            $short_codes['order_meta'] = $this->getOrderMeta();
            $short_codes['payment_method'] = $payment_method;
            $short_codes['payment_url'] = self::$woocommerce->getOrderPaymentUrl($this->order);
            $short_codes['payment_transaction_id'] = self::$woocommerce->getOrderPaymentTransactionId($this->order);
            $short_codes['customer']['name'] = $first_name . ' ' . $last_name;
            $short_codes['customer']['last_name'] = $last_name;
            $short_codes['customer']['first_name'] = $first_name;
            $short_codes['customer']['email'] = $order_email;
            $short_codes['customer']['password'] = NULL;
            $short_codes['customer']['activation_url'] = NULL;
            $short_codes['customer']['password_reset_url'] = NULL;
        } else {
            $user_email = self::$input->post('billing_email', NULL, 'email');
            $user_password = $activation_link = $password_reset_url = NULL;
            if (empty($user_email)) {
                $user_email = self::$input->post('user_email', NULL, 'email');
            }
            if (empty($user_email)) {
                $user_email = self::$input->post('email', NULL, 'email');
            }
            if (!empty($this->email_arguments)) {
                //Password
                if (isset($this->email_arguments['email']->id) && ($this->email_arguments['email']->id == 'customer_new_account' || $this->email_arguments['email']->id == 'customer_new_account_activation')) {
                    if (isset($this->email_arguments['email']->user_pass) && !empty($this->email_arguments['email']->user_pass)) {
                        $user_password = $this->email_arguments['email']->user_pass;
                    } else {
                        $user_password = self::$input->post('pass1-text', NULL);
                        if (empty($user_password)) {
                            $user_password = self::$input->post('pass1', NULL);
                        } else if (isset($_REQUEST['pass1']) && $_REQUEST['pass1'] != '') {
                            $user_password = $_REQUEST['pass1-text'];
                        }
                    }
                }
                //Password reset link
                if (isset($this->email_arguments['email']->id) && $this->email_arguments['email']->id == 'customer_reset_password') {
                    $reset_key = isset($this->email_arguments['email']->reset_key) ? $this->email_arguments['email']->reset_key : NULL;
                    $login_name = isset($this->email_arguments['email']->user_login) ? $this->email_arguments['email']->user_login : NULL;
                    $password_reset_url = esc_url(add_query_arg(array('key' => $reset_key, 'login' => rawurlencode($login_name)), wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount'))));
                } else {
                    if (isset($this->email_arguments['email']->user_login)) {
                        global $wpdb, $wp_hasher;
                        // Generate something random for a password reset key.
                        $key = wp_generate_password(20, false);
                        // This action is documented in wp-login.php
                        do_action('retrieve_password_key', $this->email_arguments['email']->user_login, $key);
                        // Now insert the key, hashed, into the DB.
                        if (empty($wp_hasher)) {
                            if (!class_exists('PasswordHash')) {
                                require_once(ABSPATH . 'wp-includes/class-phpass.php');
                            }
                            $wp_hasher = new PasswordHash(8, true);
                        }
                        $hashed = time() . ':' . $wp_hasher->HashPassword($key);
                        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $this->email_arguments['email']->user_login));
                        $activation_link = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($this->email_arguments['email']->user_login), 'login');
                    }
                }
                //Account activation link
                if (isset($this->email_arguments['email']->id) && ($this->email_arguments['email']->id == 'customer_new_account_activation')) {
                    if (isset($this->email_arguments['email']->user_activation_url) && !empty($this->email_arguments['email']->user_activation_url)) {
                        $activation_link = $this->email_arguments['email']->user_activation_url;
                    }
                }
                //Getting user email
                if (empty($user_email) && isset($this->email_arguments['email']->user_email) && !empty($this->email_arguments['email']->user_email)) {
                    $user_email = $this->email_arguments['email']->user_email;
                }
            }
            $user = get_user_by('email', $user_email);
            if ($user) {
                $short_codes['customer']['name'] = $user->user_login;
                $short_codes['customer']['first_name'] = get_user_meta($user->ID, 'first_name', true);;
                $short_codes['customer']['last_name'] = get_user_meta($user->ID, 'last_name', true);;
            }
            $short_codes['customer']['email'] = $user_email;
            $short_codes['customer']['password'] = $user_password;
            $short_codes['customer']['activation_url'] = $activation_link;
            $short_codes['customer']['password_reset_url'] = $password_reset_url;
        }
        //Store details
        $short_codes['shop'] = $this->getShopDetails();
        $this->getSocialShortCodes($short_codes);
        $short_codes = apply_filters('woocommerce_email_customizer_plus_short_code_values', $short_codes, $this->order, $this->email_arguments, $this->sample);
        return apply_filters('woocommerce_email_customizer_plus_after_short_codes_content_generating', $short_codes, $this->order, $this->email_arguments);
    }

    /**
     * get the order meta data
     * @return mixed|void
     */
    function getOrderMeta()
    {
        $order_id = $this->order->get_id();
        $order_meta_keys = get_post_custom_keys($order_id);
        $order_meta = array();
        if (!empty($order_meta_keys)) {
            $order_meta_values = get_post_meta($order_id);
            foreach ($order_meta_keys as $order_meta_key) {
                if (isset($order_meta_values[$order_meta_key]) && isset($order_meta_values[$order_meta_key][0])) {
                    $order_meta_key_for_short_code = str_replace(' ', '_', $order_meta_key);
                    if (is_string($order_meta_values[$order_meta_key][0])) {
                        $order_meta[$order_meta_key_for_short_code] = $order_meta_values[$order_meta_key][0];
                    }
                }
            }
        }
        return apply_filters('woocommerce_email_customizer_plus_get_order_meta_data', $order_meta, $this->order, $this->email_arguments);
    }

    /**
     * get line totals
     * @param $tax_display
     * @return array
     */
    function getTaxLines($tax_display)
    {
        $lines = array();
        $totals = self::$woocommerce->getOrderTaxLines($this->order, $tax_display);
        if (!empty($totals)) {
            foreach ($totals as $total) {
                $lines[] = array('title' => $total['label'],
                    'price' => $total['value']);
            }
        }
        return apply_filters('woocommerce_email_customizer_plus_get_tax_lines', $lines, $this->order);
    }

    /**
     * Get line item details
     * @return array
     */
    function getLineItemsDetails()
    {
        $order_items = self::$woocommerce->getOrderItems($this->order);
        $items = array();
        if (!empty($order_items)) {
            foreach ($order_items as $item_id => $item) {
                $product = self::$woocommerce->getItem($item);
                $plain_text = isset($this->email_arguments['plain_text']) ? $this->email_arguments['plain_text'] : false;
                ob_start();
                do_action('woocommerce_order_item_meta_start', $item_id, $item, $this->order, $plain_text);
                wc_display_item_meta($item);
                do_action('woocommerce_order_item_meta_end', $item_id, $item, $this->order, $this->email_arguments);
                $additional_attributes = ob_get_clean();
                if (self::$woocommerce->getItemImageId($product)) {
                    $image_src = current(wp_get_attachment_image_src(self::$woocommerce->getItemImageId($product), 'thumbnail'));
                } else {
                    $image_src = self::$woocommerce->getPlaceHolderImage();
                }
                $items[] = array(
                    'image_url' => apply_filters('woocommerce_order_item_thumbnail', $image_src, $product),
                    'title' => apply_filters('woocommerce_order_item_name', self::$woocommerce->getItemName($item), $item, false),
                    'quantity' => apply_filters('woocommerce_email_order_item_quantity', self::$woocommerce->getItemQuantity($item), $item),
                    'line_price' => self::$woocommerce->getOrderLineSubTotal($this->order, $item),
                    'product_attributes' => $additional_attributes,
                );
            }
        }
        return apply_filters('woocommerce_email_customizer_plus_get_line_items_details', $items, $this->order);
    }

    function orderDownloadsTable()
    {
        $order = $this->order;
        $show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();
        if (!$show_downloads) {
            return;
        }
        $template_path = WECP_PLUGIN_PATH . 'templates/order_downloads.php';
        $override_path = get_theme_file_path('woocommerce-email-customizer-plus/templates/order_downloads.php');
        if (file_exists($override_path)) {
            $template_path = $override_path;
        }
        $downloads = $order->get_downloadable_items();
        $columns = apply_filters(
            'woocommerce_email_downloads_columns',
            array(
                'download-product' => __('Product', 'woocommerce'),
                'download-expires' => __('Expires', 'woocommerce'),
                'download-file' => __('Download', 'woocommerce'),
            )
        );
        $details = array(
            'direction' => $this->getLanguageDirection($this->language),
            'columns' => $columns,
            'downloads' => $downloads
        );
        $settings_data = self::$general_settings->getOptions();
        $content = self::$template->setData($template_path, $details)->render();
        $css = isset($settings_data['custom_css']) ? $settings_data['custom_css'] : '';
        if (!empty($css)) {
            $content = self::$woocommerce->styleInline($content, $css);
        }
        return apply_filters('woocommerce_email_customizer_plus_get_order_downloadstable', $content, $this->order);
    }

    /**
     * Order table html
     * @return false|string|void
     */
    function orderCartTable()
    {
        $template_path = WECP_PLUGIN_PATH . 'templates/order_items.php';
        $override_path = get_theme_file_path('woocommerce-email-customizer-plus/templates/order_items.php');
        if (file_exists($override_path)) {
            $template_path = $override_path;
        }
        $template_path = apply_filters('woocommerce_email_customizer_plus_order_notes_template', $template_path);
        $settings_data = self::$general_settings->getOptions();
        $this->email_arguments['image_size'][0] = isset($settings_data['product_image_width']) ? $settings_data['product_image_width'] : 32;
        $this->email_arguments['image_size'][1] = isset($settings_data['product_image_height']) ? $settings_data['product_image_height'] : 32;
        $this->email_arguments['image_size'][2] = isset($settings_data['product_image_size']) ? $settings_data['product_image_size'] : 'thumbnail';
        //TODO: Add settings for image show or not
        $arguments = array(
            'show_image' => isset($settings_data['show_product_image']) ? $settings_data['show_product_image'] : 1,
            'show_sku' => isset($settings_data['show_sku']) ? $settings_data['show_sku'] : 1,
            'plain_text' => isset($this->email_arguments['plain_text']) ? $this->email_arguments['plain_text'] : false,
            'image_size' => $this->email_arguments['image_size'],
            'show_download_links' => self::$woocommerce->isOrderDownLoadPermitted($this->order) && (isset($this->email_arguments['sent_to_admin']) && !$this->email_arguments['sent_to_admin']),
            'show_purchase_note' => self::$woocommerce->isOrderPaid($this->order) && (isset($this->email_arguments['sent_to_admin']) && !$this->email_arguments['sent_to_admin']),
        );
        $details = array(
            'order' => $this->order,
            'show_sku' => isset($this->email_arguments['show_sku']) ? $this->email_arguments['show_sku'] : 1,
            'show_image' => isset($settings_data['show_product_image']) ? $settings_data['show_product_image'] : 1,
            'sent_to_admin' => isset($this->email_arguments['sent_to_admin']) ? $this->email_arguments['sent_to_admin'] : false,
            'order_item_table_border_color' => isset($this->email_arguments['order_item_table_border_color']) ? $this->email_arguments['order_item_table_border_color'] : '#dddddd',
            'mso' => isset($this->email_arguments['mso']) ? $this->email_arguments['mso'] : false,
            'order_items' => self::$woocommerce->getOrderItems($this->order),
            'totals' => self::$woocommerce->getOrderItemsTotal($this->order),
            'woocommerce' => self::$woocommerce,
            'args' => $arguments,
            'settings' => $settings_data,
            'direction' => $this->getLanguageDirection($this->language),
            'email_arguments' => $this->email_arguments
        );
        $content = self::$template->setData($template_path, $details)->render();
        $css = isset($settings_data['custom_css']) ? $settings_data['custom_css'] : '';
        if (!empty($css)) {
            $content = self::$woocommerce->styleInline($content, $css);
        }
        return apply_filters('woocommerce_email_customizer_plus_get_order_table', $content, $this->order);
    }

    /**
     * Order customer notes html
     * @param $order_notes
     * @return false|string|void
     */
    function orderCustomerNotes($order_notes)
    {
        $template_path = WECP_PLUGIN_PATH . 'templates/customer_notes.php';
        $override_path = get_theme_file_path('woocommerce-email-customizer-plus/templates/customer_notes.php');
        if (file_exists($override_path)) {
            $template_path = $override_path;
        }
        $template_path = apply_filters('woocommerce_email_customizer_plus_order_notes_template', $template_path);
        $content = self::$template->setData($template_path, array('order_notes' => $order_notes))->render();
        $settings_data = self::$general_settings->getOptions();
        $css = isset($settings_data['custom_css']) ? $settings_data['custom_css'] : '';
        if (!empty($css)) {
            $content = self::$woocommerce->styleInline($content, $css);
        }
        return apply_filters('woocommerce_email_customizer_plus_get_order_notes', $content, $this->order);
    }

    /**
     * render template with the short codes
     * @param $encoded_content bool
     * @return mixed
     */
    function renderTemplate($encoded_content = true)
    {
        $liquid_template = new Template();
        $this->html_template = apply_filters('woocommerce_email_customizer_plus_get_raw_html_template', $this->html_template, $this->order);
        $liquid_template->parse($this->html_template);
        $short_code_values = $this->organizeTemplateData();
        $html = $liquid_template->render($short_code_values);
        $direction = $this->getLanguageDirection($this->language);
        $html = $this->changeDirection($html, $direction);
        if ($encoded_content) {
            $html = '<wecp>' . base64_encode($html) . '</wecp>';
        }
        return apply_filters('woocommerce_email_customizer_plus_get_short_code_replaced_template', $html, $this->order, $encoded_content);
    }

    /**
     * get the direction og the language
     * @param $language
     * @return mixed|void
     */
    function getLanguageDirection($language)
    {
        $key = 'language_direction_for_' . $language;
        $settings = self::$general_settings->getOptions();
        $direction = isset($settings[$key]) ? $settings[$key] : 'ltr';
        return apply_filters('woocommerce_email_customizer_plus_get_language_direction', $direction, $language);
    }

    /**
     * get the sample template
     * @param $encoded_content bool
     * @return mixed|void
     */
    function renderSampleTemplate($encoded_content = true)
    {
        $liquid_template = new Template();
        $short_code_values = self::getSampleData();
        $liquid_template->parse($this->html_template);
        $html = $liquid_template->render($short_code_values);
        $direction = $this->getLanguageDirection($this->language);
        $html = $this->changeDirection($html, $direction);
        if ($encoded_content) {
            $html = '<wecp>' . base64_encode($html) . '</wecp>';
        }
        return apply_filters('woocommerce_email_customizer_plus_get_short_code_replaced_sample_template', $html, $this->order, $encoded_content);
    }

    /**
     * Sample data to render sample template
     * @return mixed|void
     */
    function getSampleData()
    {
        $short_codes = array(
            'billing_address' =>
                array(
                    'formatted_address' => 'Cecilia Chapman<br/>8105 West Brickell Drive<br/>Pomona, CA 92703<br/>United States (US)',
                    'first_name' => 'Cecilia',
                    'last_name' => 'Chapman',
                    'name' => 'Cecilia Chapman',
                    'company' => '',
                    'phone' => '563-7401',
                    'address1' => '8105 West Brickell Drive',
                    'address2' => '',
                    'city' => 'Pomona',
                    'state' => 'CA',
                    'province' => 'California',
                    'zip' => '92703',
                    'country' => 'United States (US)',
                ),
            'shipping_address' =>
                array(
                    'formatted_address' => 'Cecilia Chapman<br/>8105 West Brickell Drive<br/>Pomona, CA 92703<br/>United States (US)',
                    'first_name' => 'Cecilia',
                    'last_name' => 'Chapman',
                    'name' => 'Cecilia Chapman',
                    'company' => '',
                    'address1' => '8105 West Brickell Drive',
                    'address2' => '',
                    'city' => 'Pomona',
                    'state' => 'CA',
                    'province' => 'California',
                    'zip' => '92703',
                    'country' => 'United States (US)',
                ),
            'shipping_method' =>
                array(
                    'title' => 'Flat rate',
                    'price' => self::$woocommerce->formatPrice(50),
                ),
            'shipping_price' => '50.00',
            'id' => 206,
            'email' => 'cecilia.chapman@flycart.test',
            'order_number' => '206',
            'item_count' => 1,
            'last_note' => '',
            'landing_site' => '',
            'note' => '',
            'order_status_url' => '',
            'order_status_link' => '<a href="#">Order</a>',
            'subtotal_price' => self::$woocommerce->formatPrice(640),
            'total_price' => self::$woocommerce->formatPrice(690),
            'created_at' => date('F d, Y', current_time('timestamp')),
            'order' =>
                array(
                    'id' => 1,
                    'fee' => 0,
                    'refund' => 0,
                    'url' => '',
                    'cart' => '<div><img alt = "sample cart " src="https://www.dropbox.com/s/ebek9iih9p0746k/cart-summary.png?raw=1" width="100%"></div>',
                    'customer_provided_note' => 'Sample note by the customer.',
                ),
            'order_meta' =>
                array(
                    '_order_key' => 'sample',
                    '_customer_user' => '0',
                    '_payment_method' => 'cheque',
                    '_payment_method_title' => 'Check payments',
                    '_customer_ip_address' => '127.0.0.1',
                    '_customer_user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:72.0) Gecko/20100101 Firefox/72.0',
                    '_created_via' => 'checkout',
                    '_cart_hash' => '52e12998f191b8cec6ce6e23b58a841a',
                    '_billing_first_name' => 'Cecilia',
                    '_billing_last_name' => 'Chapman',
                    '_billing_address_1' => '8105 West Brickell Drive',
                    '_billing_city' => 'Pomona',
                    '_billing_state' => 'CA',
                    '_billing_postcode' => '92703',
                    '_billing_country' => 'US',
                    '_billing_email' => 'cecilia.chapman@flycart.test',
                    '_billing_phone' => '563-7401',
                    '_shipping_first_name' => 'Cecilia',
                    '_shipping_last_name' => 'Chapman',
                    '_shipping_address_1' => '8105 West Brickell Drive',
                    '_shipping_city' => 'Pomona',
                    '_shipping_state' => 'CA',
                    '_shipping_postcode' => '92703',
                    '_shipping_country' => 'US',
                    '_order_currency' => 'INR',
                    '_cart_discount' => '0',
                    '_cart_discount_tax' => '0',
                    '_order_shipping' => '50.00',
                    '_order_shipping_tax' => '0',
                    '_order_tax' => '0',
                    '_order_total' => '690.00',
                    '_order_version' => '3.9.1',
                    '_prices_include_tax' => 'no',
                    '_billing_address_index' => 'Cecilia Chapman  8105 West Brickell Drive  Pomona CA 92703 US cecilia.chapman@flycart.test 563-7401',
                    '_shipping_address_index' => 'Cecilia Chapman  8105 West Brickell Drive  Pomona CA 92703 US',
                    'is_vat_exempt' => 'no',
                    '_recorded_sales' => 'yes',
                    '_recorded_coupon_usage_counts' => 'yes',
                    '_order_stock_reduced' => 'yes',
                    '_woocs_order_rate' => '1',
                    '_woocs_order_base_currency' => 'INR',
                    '_woocs_order_currency_changed_mannualy' => '0',
                ),
            'payment_method' => 'Check payments',
            'payment_url' => '#',
            'payment_transaction_id' => '',
            'customer' =>
                array(
                    'name' => 'Cecilia Chapman',
                    'last_name' => 'Chapman',
                    'first_name' => 'Cecilia',
                    'email' => 'cecilia.chapman@flycart.test',
                    'password' => 'sample password',
                    'activation_url' => '#',
                    'password_reset_url' => "#",
                ),
            'facebook' => '#',
            'twitter' => '#',
            'instagram' => '#',
            'linkedin' => '#',
            'pinterest' => '#',
            'wlpr_order_id' => 206,
            'wlpr_earn_point' => 500,
            'wlpr_referral_url' => site_url() . 'wlpr=sampleurl',
            'wlpr_expire_point' => 5,
            'wlpr_point_expiry_date' => date('Y-m-d H:i:s', current_time('timestamp') + 3600),
            'wlpr_point_expiry_redeem_url' => site_url() . 'wlpr=sampleurl',
            'wlpr_referee_point' => 40,
            'wlpr_referral_point' => 20,
        );
        $short_codes['shop'] = $this->getShopDetails();
        $this->getSocialShortCodes($short_codes);
        return apply_filters('woocommerce_email_customizer_plus_gest_dummy_data', $short_codes);
    }

    /**
     * get the social meadia short codes
     * @param $short_codes
     */
    function getSocialShortCodes(&$short_codes)
    {
        $general_settings = self::$general_settings->getOptions();
        $short_codes['facebook'] = isset($general_settings['facebook']) ? $general_settings['facebook'] : '';
        $short_codes['twitter'] = isset($general_settings['twitter']) ? $general_settings['twitter'] : '';
        $short_codes['instagram'] = isset($general_settings['instagram']) ? $general_settings['instagram'] : '';
        $short_codes['linkedin'] = isset($general_settings['linkedin']) ? $general_settings['linkedin'] : '';
        $short_codes['pinterest'] = isset($general_settings['pinterest']) ? $general_settings['pinterest'] : '';
    }

    /**
     * get the shop details short codes
     * @return mixed
     */
    function getShopDetails()
    {
        $scheme = wc_site_is_https() ? 'https' : 'http';
        $short_codes = array(
            'name' => get_option('blogname'),
            'email' => get_option('admin_email'),
            'url' => get_home_url(null, null, $scheme),
            'address1' => get_option('woocommerce_store_address', NULL),
            'address2' => get_option('woocommerce_store_address_2', NULL),
            'city' => get_option('woocommerce_store_city', NULL),
            'zip' => get_option('woocommerce_store_postcode', NULL)
        );
        return apply_filters('woocommerce_emnail_customizer_plus_get_shop_short_codes', $short_codes);
    }
}