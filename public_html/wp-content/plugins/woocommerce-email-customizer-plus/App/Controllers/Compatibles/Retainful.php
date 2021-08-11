<?php

namespace Wecp\App\Controllers\Compatibles;

use Wecp\App\Controllers\Admin\Settings\General;

class Retainful
{
    function init()
    {
        $general_settings = new General();
        $settings_data = $general_settings->getOptions();
        $enable_retainful_integration = isset($settings_data['enable_retainful_integration']) ? $settings_data['enable_retainful_integration'] : 0;
        if ($this->hasRetainfulPlugin() && $enable_retainful_integration == "1") {
            add_filter('woocommerce_email_customizer_plus_short_code_values', array($this, 'loadAdditionalData'), 10, 4);
            add_filter('woocommerce_email_customizer_plus_additional_short_codes_list', array($this, 'loadAdditionalShortCodes'), 10, 3);
        }
    }

    /**
     * Check the site has retainful plugin
     * @return bool
     */
    function hasRetainfulPlugin()
    {
        return in_array('retainful-next-order-coupon-for-woocommerce/retainful-next-order-coupon-for-woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }

    /**
     * Load additional data of order
     * @param $short_codes
     * @param $order
     * @param $args
     * @param $sample
     * @return mixed
     */
    function loadAdditionalData($short_codes, $order, $args, $sample)
    {
        /**
         * create coupon if one not found already in order
         * @since 1.0.8
         */
        $order_id = 0;
        if (method_exists($order, 'get_id')) {
            $order_id = $order->get_id();
        } elseif (isset($order->id)) {
            $order_id = $order->id;
        }
        if (!empty($order_id)) {
            do_action('rnoc_create_new_next_order_coupon', $order_id, array());
        }
        /*******/
        $sent_to_admin = isset($args['sent_to_admin']) ? $args['sent_to_admin'] : false;
        $retainful_data = apply_filters('woo_email_drag_and_drop_builder_retainful_next_order_coupon_data', array(), $order, $sent_to_admin);
        if (is_array($retainful_data) && !empty($retainful_data)) {
            $short_codes = array_merge($short_codes, $retainful_data);
        }
        $short_codes = apply_filters('woo_email_drag_and_drop_builder_load_additional_shortcode_data', $short_codes, $order, $sent_to_admin);
        return apply_filters('woocommerce_email_customizer_plus_retainful_next_order_additional_short_code_values', $short_codes, $order, $args);
    }

    /**
     * Additional short codes
     * @param $additional_short_codes
     * @return mixed
     */
    function loadAdditionalShortCodes($additional_short_codes)
    {
        $additional_short_codes['wec_next_order_coupon_code'] = __('Display next order coupon code', WECP_TEXT_DOMAIN);
        $additional_short_codes['wec_next_order_coupon'] = __('Display next order coupon code with E-mail tracking', WECP_TEXT_DOMAIN);
        $additional_short_codes['wec_next_order_coupon_value'] = __('Display next order coupon value', WECP_TEXT_DOMAIN);
        $additional_short_codes['woo_mb_site_url_link_with_coupon'] = __('Next order coupon auto apply URL', WECP_TEXT_DOMAIN);
        return apply_filters('woo_email_drag_and_drop_builder_load_additional_shortcode', $additional_short_codes);
    }
}