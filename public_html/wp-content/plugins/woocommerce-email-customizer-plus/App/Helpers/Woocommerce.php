<?php

namespace Wecp\App\Helpers;

use WC_Email;
use WC_Order;
use WC_Order_Refund;
use WP_Post;

class Woocommerce
{
    /**
     * get order object from order id
     * @param $order_id
     * @return array|bool|WC_Order|WC_Order_Refund
     */
    static function getOrder($order_id)
    {
        if (empty($order_id)) {
            return array();
        }
        if (function_exists('wc_get_order')) {
            return wc_get_order($order_id);
        }
        return array();
    }

    /**
     * Get site's default language
     * @return string
     */
    function getSiteDefaultLang()
    {
        $current_lang = 'en_US';
        if (function_exists('get_locale')) {
            $current_lang = get_locale();
            if (empty($current_lang) || $current_lang == 'en') {
                $current_lang = 'en_US';
            }
        }
        return $current_lang;
    }

    /**
     * Get all available language
     * @return array
     */
    function getAvailableLanguages()
    {
        if (function_exists('get_available_languages')) {
            $return_languages = get_available_languages();
            $wpml_available_languages = apply_filters('wpml_active_languages', null, array());
            if (!empty($wpml_available_languages) && is_array($wpml_available_languages)) {
                $wpml_return_languages = array();
                foreach ($wpml_available_languages as $lang_code => $language) {
                    $wpml_return_languages[] = (isset($language['default_locale']) && !empty($language['default_locale'])) ? $language['default_locale'] : $lang_code;
                }
                $return_languages = $wpml_return_languages;
            }
            return $return_languages;
        }
        return array();
    }

    /**
     * get language label by lang code
     * @param $language_code
     * @return mixed|string|null
     */
    function getLanguageLabel($language_code)
    {
        $label = "";
        if ($language_code == 'en_US') {
            $label = "English";
        } else {
            $translations = $this->getWpAvailableTranslations();
            if (isset($translations[$language_code]['native_name'])) {
                $label = $translations[$language_code]['native_name'];
            }
        }
        return apply_filters("woocommerce_email_customizer_plus_get_language_label", $label, $language_code);
    }

    /**
     * Get site's default language
     * @return array
     */
    function getWpAvailableTranslations()
    {
        require_once(ABSPATH . 'wp-admin/includes/translation-install.php');
        if (function_exists('wp_get_available_translations')) {
            return wp_get_available_translations();
        }
        return array();
    }

    /**
     * get Email templates list
     * @return array|WC_Email[]
     */
    function getEmailTypeLists()
    {
        if (function_exists('WC')) {
            if (method_exists(WC(), 'mailer')) {
                return WC()->mailer()->get_emails();
            }
        }
        return array();
    }

    /**
     * get Email object
     * @return array|WC_Email[]
     */
    function getMailer()
    {
        if (function_exists('WC')) {
            if (method_exists(WC(), 'mailer')) {
                return WC()->mailer();
            }
        }
        return array();
    }

    /**
     * get orders list by condition
     * @param array $conditions
     * @return int[]|WP_Post[]
     */
    function getOrdersByConditions($conditions = array())
    {
        $default_conditions = array(
            'numberposts' => -1,
            'post_type' => $this->getOrderPostType(),
            'post_status' => array_keys($this->getOrderStatusList()),
            'orderby' => 'ID',
            'order' => 'DESC'
        );
        if (is_object($conditions)) {
            $conditions = (array)$conditions;
        } elseif (!is_array($conditions)) {
            $conditions = array();
        }
        $final_conditions = array_merge($default_conditions, $conditions);
        return get_posts($final_conditions);
    }

    /**
     * Get all order status lists
     * @param bool $key_only
     * @return array
     */
    function getOrderPostType($key_only = false)
    {
        if (function_exists('wc_get_order_types')) {
            if ($key_only) {
                return array_keys(wc_get_order_types());
            }
            return wc_get_order_types();
        }
        return NULL;
    }

    /**
     * get woocommerce order status
     * @return array
     */
    function getOrderStatusList()
    {
        if (function_exists('wc_get_order_statuses')) {
            return wc_get_order_statuses();
        }
        return array();
    }

    /**
     * Get order Email form order object
     * @param $order
     * @return null
     */
    function getOrderId($order)
    {
        if (method_exists($order, 'get_id')) {
            return $order->get_id();
        }
        return false;
    }

    /**
     * Get order Email form order object
     * @param $order
     * @return null
     */
    function isOrderPaid($order)
    {
        if (method_exists($order, 'is_paid')) {
            return $order->is_paid();
        }
        return false;
    }

    /**
     * Get order Email form order object
     * @param $order
     * @return null
     */
    function isOrderDownLoadPermitted($order)
    {
        if (method_exists($order, 'is_download_permitted')) {
            return $order->is_download_permitted();
        }
        return false;
    }

    /**
     * Get order Email form order object
     * @param $order
     * @param $tax_display
     * @return null
     */
    function getOrderTotalPrice($order, $tax_display)
    {
        if (method_exists($order, 'get_formatted_order_total')) {
            return $order->get_formatted_order_total($tax_display);
        }
        return NULL;
    }

    /**
     * Get order Email form order object
     * @param $order
     * @return null
     */
    function getOrderNumber($order)
    {
        if (method_exists($order, 'get_order_number')) {
            return $order->get_order_number();
        }
        return NULL;
    }

    /**
     * Get User Last name
     * @param $order
     * @return null
     */
    function getBillingFirstName($order)
    {
        if (method_exists($order, 'get_billing_first_name')) {
            return $order->get_billing_first_name();
        }
        return NULL;
    }

    /**
     * Get User Last name
     * @param $order
     * @return null
     */
    function getBillingLastName($order)
    {
        if (method_exists($order, 'get_billing_last_name')) {
            return $order->get_billing_last_name();
        }
        return NULL;
    }

    /**
     * Get billing Country
     * @param $order
     * @return null
     */
    function getBillingEmail($order)
    {
        if (method_exists($order, 'get_billing_email')) {
            return $order->get_billing_email();
        }
        return NULL;
    }

    /**
     * Get billing address 1
     * @param $order
     * @return null
     */
    function getBillingAddressOne($order)
    {
        if (method_exists($order, 'get_billing_address_1')) {
            return $order->get_billing_address_1();
        }
        return NULL;
    }

    /**
     * Get billing address
     * @param $order
     * @return null
     */
    function getBillingAddressFormatted($order)
    {
        if (method_exists($order, 'get_formatted_billing_address')) {
            return $order->get_formatted_billing_address();
        }
        return NULL;
    }

    /**
     * Get billing address 2
     * @param $order
     * @return null
     */
    function getBillingAddressTwo($order)
    {
        if (method_exists($order, 'get_billing_address_2')) {
            return $order->get_billing_address_2();
        }
        return NULL;
    }

    /**
     * Get billing City
     * @param $order
     * @return null
     */
    function getBillingCity($order)
    {
        if (method_exists($order, 'get_billing_city')) {
            return $order->get_billing_city();
        }
        return NULL;
    }

    /**
     * Get billing City
     * @param $order
     * @return null
     */
    function getBillingState($order)
    {
        if (method_exists($order, 'get_billing_state')) {
            return $order->get_billing_state();
        }
        return NULL;
    }

    /**
     * Get billing Country
     * @param $order
     * @return null
     */
    function getBillingCountry($order)
    {
        if (method_exists($order, 'get_billing_country')) {
            return $order->get_billing_country();
        }
        return NULL;
    }

    /**
     * Get billing Post code
     * @param $order
     * @return null
     */
    function getBillingPostCode($order)
    {
        if (method_exists($order, 'get_billing_postcode')) {
            return $order->get_billing_postcode();
        }
        return NULL;
    }

    /**
     * Get billing company
     * @param $order
     * @return null
     */
    function getBillingCompany($order)
    {
        if (method_exists($order, 'get_billing_company')) {
            return $order->get_billing_company();
        }
        return NULL;
    }

    /**
     * Get billing phone
     * @param $order WC_Order
     * @return null
     */
    function getBillingPhone($order)
    {
        if (method_exists($order, 'get_billing_phone')) {
            return $order->get_billing_phone();
        }
        return NULL;
    }

    /**
     * Get Shipping first name
     * @param $order
     * @return null
     */
    function getShippingFirstName($order)
    {
        if (method_exists($order, 'get_shipping_first_name')) {
            return $order->get_shipping_first_name();
        }
        return NULL;
    }

    /**
     * Get shipping Last name
     * @param $order
     * @return null
     */
    function getShippingLastName($order)
    {
        if (method_exists($order, 'get_shipping_last_name')) {
            return $order->get_shipping_last_name();
        }
        return NULL;
    }

    /**
     * Get shipping address 1
     * @param $order
     * @return null
     */
    function getShippingAddressOne($order)
    {
        if (method_exists($order, 'get_shipping_address_1')) {
            return $order->get_shipping_address_1();
        }
        return NULL;
    }

    /**
     * Get shipping address
     * @param $order
     * @return null
     */
    function getShippingAddressFormatted($order)
    {
        if (method_exists($order, 'get_formatted_shipping_address')) {
            return $order->get_formatted_shipping_address();
        }
        return NULL;
    }

    /**
     * Get shipping address 2
     * @param $order
     * @return null
     */
    function getShippingAddressTwo($order)
    {
        if (method_exists($order, 'get_shipping_address_2')) {
            return $order->get_shipping_address_2();
        }
        return NULL;
    }

    /**
     * Get shipping City
     * @param $order
     * @return null
     */
    function getShippingCity($order)
    {
        if (method_exists($order, 'get_shipping_city')) {
            return $order->get_shipping_city();
        }
        return NULL;
    }

    /**
     * Get shipping City
     * @param $order
     * @return null
     */
    function getShippingState($order)
    {
        if (method_exists($order, 'get_shipping_state')) {
            return $order->get_shipping_state();
        }
        return NULL;
    }

    /**
     * Get shipping Country
     * @param $order
     * @return null
     */
    function getShippingCountry($order)
    {
        if (method_exists($order, 'get_shipping_country')) {
            return $order->get_shipping_country();
        }
        return NULL;
    }

    /**
     * Get shipping Post code
     * @param $order
     * @return null
     */
    function getShippingPostCode($order)
    {
        if (method_exists($order, 'get_shipping_postcode')) {
            return $order->get_shipping_postcode();
        }
        return NULL;
    }

    /**
     * Get shipping company
     * @param $order
     * @return null
     */
    function getShippingCompany($order)
    {
        if (method_exists($order, 'get_shipping_company')) {
            return $order->get_shipping_company();
        }
        return NULL;
    }

    /**
     * Get shipping method
     * @param $order
     * @return null
     */
    function getShippingMethod($order)
    {
        if (method_exists($order, 'get_shipping_method')) {
            return $order->get_shipping_method();
        }
        return NULL;
    }

    /**
     * Get all states of country
     * @param $country
     * @return array
     */
    function getStates($country)
    {
        if (function_exists('WC') && method_exists(WC()->countries, 'get_states')) {
            return WC()->countries->get_states($country);
        }
        return array();
    }

    /**
     * Get country name
     * @param $country
     * @return string
     */
    function getCountry($country)
    {
        if (function_exists('WC') && isset(WC()->countries->countries[$country])) {
            return WC()->countries->countries[$country];
        }
        return '';
    }

    /**
     * Get shipping cost
     * @param $order
     * @return null
     */
    function getShippingCost($order)
    {
        if (method_exists($order, 'get_shipping_total')) {
            return $order->get_shipping_total();
        }
        return NULL;
    }

    /**
     * Get User Last name
     * @param $order
     * @return null
     */
    function getOrderCustomer($order)
    {
        if (method_exists($order, 'get_user')) {
            return $order->get_user();
        }
        return NULL;
    }

    /**
     * Get order url
     * @param $order
     * @return null
     */
    function getOrderUrl($order)
    {
        if (method_exists($order, 'get_view_order_url')) {
            return $order->get_view_order_url();
        }
        return NULL;
    }

    /**
     * Get order date
     * @param $order
     * @return null
     */
    function getOrderDate($order)
    {
        if (method_exists($order, 'get_date_created')) {
            $date_format = $this->dateFormat();
            return $order->get_date_created()->date_i18n($date_format);
        }
        return NULL;
    }

    /**
     * Get order total
     * @param $order
     * @param $format_price
     * @return null
     */
    function getOrderTotal($order, $format_price = true)
    {
        if (method_exists($order, 'get_total')) {
            $price = $order->get_total();
            if ($format_price) {
                $price = $this->formatPrice($price);
            }
            return $price;
        }
        return NULL;
    }

    /**
     * Get order items total
     * @param $order
     * @return null
     */
    function getOrderItemsTotal($order)
    {
        if (method_exists($order, 'get_order_item_totals')) {
            return $order->get_order_item_totals();
        }
        return NULL;
    }

    /**
     * Get order items total
     * @param $order
     * @return null
     */
    function getTaxTotals($order)
    {
        if (method_exists($order, 'get_tax_totals')) {
            return $order->get_tax_totals();
        }
        return NULL;
    }

    /**
     * Get order items total
     * @param $order
     * @return null
     */
    function getTotalTax($order)
    {
        if (method_exists($order, 'get_total_tax')) {
            return $order->get_total_tax();
        }
        return NULL;
    }

    /**
     * Get order items total
     * @param $order
     * @return null
     */
    function getOrderCurrency($order)
    {
        if (method_exists($order, 'get_currency')) {
            return $order->get_currency();
        }
        return NULL;
    }

    /**
     * Get order tax details
     * @param $order
     * @param $tax_display
     * @return null
     */
    function getOrderTaxLines($order, $tax_display)
    {
        $total_rows = array();
        // Tax for tax exclusive prices.
        if ('excl' === $tax_display && wc_tax_enabled()) {
            if ('itemized' === get_option('woocommerce_tax_total_display')) {
                foreach ($this->getTaxTotals($order) as $code => $tax) {
                    $total_rows[sanitize_title($code)] = array(
                        'label' => $tax->label . ':',
                        'value' => $tax->formatted_amount,
                    );
                }
            } else {
                $total_rows['tax'] = array(
                    'label' => WC()->countries->tax_or_vat() . ':',
                    'value' => wc_price($this->getTotalTax($order), array('currency' => $this->getOrderCurrency($order))),
                );
            }
        }
        return $total_rows;
    }

    /**
     * Get order fees
     * @param $order
     * @return null
     */
    function getOrderFees($order)
    {
        if (method_exists($order, 'get_fees')) {
            return $order->get_fees();
        }
        return NULL;
    }

    /**
     * Get order payment method
     * @param $order
     * @return null
     */
    function getOrderPaymentMethod($order)
    {
        if (method_exists($order, 'get_payment_method_title')) {
            return $order->get_payment_method_title();
        }
        return NULL;
    }

    /**
     * Get order payment URL
     * @param $order
     * @return null
     */
    function getOrderPaymentUrl($order)
    {
        if (method_exists($order, 'get_checkout_payment_url')) {
            return $order->get_checkout_payment_url();
        }
        return NULL;
    }

    /**
     * Get order payment URL
     * @param $order
     * @return null
     */
    function getOrderPaymentTransactionId($order)
    {
        if (method_exists($order, 'get_transaction_id')) {
            return $order->get_transaction_id();
        }
        return NULL;
    }

    /**
     * Get order order note
     * @param $order
     * @return array
     */
    function getOrderCustomerNote($order)
    {
        if (method_exists($order, 'get_customer_note')) {
            return $order->get_customer_note();
        }
        return array();
    }

    /**
     * Get order item
     * @param $item
     * @return array
     */
    function getItem($item)
    {
        if (method_exists($item, 'get_product')) {
            return $item->get_product();
        }
        return array();
    }

    /**
     * Get order item name
     * @param $item
     * @return array
     */
    function getItemName($item)
    {
        if (method_exists($item, 'get_name')) {
            return $item->get_name();
        }
        return array();
    }

    /**
     * Get order item quantity
     * @param $item
     * @return array
     */
    function getItemQuantity($item)
    {
        if (method_exists($item, 'get_quantity')) {
            return $item->get_quantity();
        }
        return array();
    }

    /**
     * Get order item image id
     * @param $item
     * @return integer
     */
    function getItemImageId($item)
    {
        if (method_exists($item, 'get_image_id')) {
            return $item->get_image_id();
        }
        return 0;
    }

    /**
     * Get order item line subtotal
     * @param $order
     * @param $item
     * @return array
     */
    function getOrderLineSubTotal($order, $item)
    {
        if (method_exists($order, 'get_formatted_line_subtotal')) {
            return $order->get_formatted_line_subtotal($item);
        }
        return array();
    }

    /**
     * Get order item sku
     * @param $item
     * @return array
     */
    function getItemSku($item)
    {
        if (method_exists($item, 'get_sku')) {
            return $item->get_sku();
        }
        return array();
    }

    /**
     * Get order order note
     * @param $order
     * @return array
     */
    function getOrderItems($order)
    {
        if (method_exists($order, 'get_items')) {
            return $order->get_items();
        }
        return array();
    }

    /**
     * Get order payment URL
     * @param $order
     * @return array
     */
    function getOrderNotes($order)
    {
        if (method_exists($order, 'get_customer_order_notes')) {
            return $order->get_customer_order_notes();
        }
        return array();
    }

    /**
     * Get order refund
     * @param $order
     * @return null
     */
    function getOrderRefundAmount($order)
    {
        if (method_exists($order, 'get_total_refunded')) {
            return $order->get_total_refunded();
        }
        return NULL;
    }

    /**
     * Get fee amount
     * @param $fee_obj
     * @return null
     */
    function getOrderFeeAmount($fee_obj)
    {
        if (method_exists($fee_obj, 'get_amount')) {
            return $fee_obj->get_amount();
        }
        return NULL;
    }

    /**
     * Get order url
     * @param $price
     * @return null
     */
    function formatPrice($price)
    {
        if (function_exists('wc_price')) {
            return wc_price($price);
        }
        return $price;
    }

    /**
     * Get order url
     * @return null
     */
    function getPlaceHolderImage()
    {
        if (function_exists('wc_placeholder_img_src')) {
            return wc_placeholder_img_src();
        }
        return NULL;
    }

    /**
     * Get date format
     * @return null
     */
    function dateFormat()
    {
        if (function_exists('wc_date_format')) {
            return wc_date_format();
        }
        return NULL;
    }

    /**
     * get order custom fields
     * @param $order
     * @return array
     */
    function getOrderCustomFields($order)
    {
        if (function_exists('wc_get_custom_checkout_fields')) {
            return wc_get_custom_checkout_fields($order);
        }
        return array();
    }

    /**
     * Tax display
     * @return mixed|void
     */
    function taxDisplay()
    {
        return get_option('woocommerce_tax_display_cart');
    }

    /**
     * get the last order ID
     * @return int
     */
    function getLastOrderId()
    {
        global $wpdb;
        // Getting last Order ID (max value)
        $results = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type LIKE 'shop_order' ORDER BY ID DESC ");
        if (!empty($results)) {
            return $results->ID;
        }
        return 0;
    }

    /**
     * Inline the custom css
     * @param $content
     * @param $css
     * @return mixed|string|void
     */
    function styleInline($content, $css)
    {
        if (!empty($css) && !empty($content)) {
            if ($this->supportsEmogrifier()) {
                $content = apply_filters('woocommerce_email_customizer_plus_before_inline_style_content', $content);
                $css = apply_filters('woocommerce_email_customizer_plus_before_inline_style_css', $css);
                if (apply_filters('woocommerce_email_customizer_plus_need_inline_style', true)) {
                    $emogrifier_class = '\\Pelago\\Emogrifier';
                    $emogrifier_file = ABSPATH . '/wp-content/plugins/woocommerce/includes/libraries/class-emogrifier.php';
                    if (!class_exists($emogrifier_class)) {
                        if (file_exists($emogrifier_file)) {
                            include_once ABSPATH . '/wp-content/plugins/woocommerce/includes/libraries/class-emogrifier.php';
                        } else {
                            $content = '<style type="text/css">' . $css . '</style>' . $content;
                        }
                    }
                    try {
                        if (class_exists($emogrifier_class)) {
                            $emogrifier = new $emogrifier_class($content, $css);
                            $content = $emogrifier->emogrify();
                        }
                    } catch (\Exception $e) {
                        // handle exception
                    }
                }
                $content = apply_filters('woocommerce_email_customizer_plus_after_inline_style_content', $content);
            } else {
                $content = '<style type="text/css">' . $css . '</style>' . $content;
            }
        }
        return $content;
    }

    /**
     * Return if emogrifier library is supported.
     *
     * @return bool
     * @since 1.0.0
     */
    protected function supportsEmogrifier()
    {
        return class_exists('DOMDocument') && version_compare(PHP_VERSION, '5.5', '>=');
    }
}