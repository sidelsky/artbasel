<?php

namespace Wecp\App\Controllers\Compatibles;

use Wecp\App\Controllers\Admin\Settings\General;
use Wecp\App\Helpers\Template;
use Wecp\App\Helpers\Woocommerce;

class WoocommerceBookings
{
    function init()
    {
        if ($this->hasWoocommerceBookingPlugin()) {
            add_filter('woocommerce_email_customizer_plus_short_code_values', array($this, 'loadAdditionalData'), 10, 4);
            add_filter('woocommerce_email_customizer_plus_additional_short_codes_list', array($this, 'loadAdditionalShortCodes'), 10, 3);
        }
    }

    /**
     * Check the site has loyalty plugin
     * @return bool
     */
    function hasWoocommerceBookingPlugin()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        return in_array('woocommerce-bookings/woocommerce-bookings.php', $active_plugins);
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
        if ($sample) {
            if (is_a($order, '\WC_Order')) {
                $order_id = $order->get_id();
                $booking_post = get_children(array(
                    'numberposts' => 1,
                    'post_type' => 'wc_booking',
                    'post_status' => 'any',
                    'post_parent' => $order_id,
                ));
                $booking_post = reset($booking_post);
                if (!empty($booking_post)) {
                    $booking_id = $booking_post->ID;
                    $args['booking'] = get_wc_booking($booking_id);
                    $this->getBookingShortCodeValues($short_codes, $order, $args);
                }
            }
        } else {
            if (is_array($short_codes) && isset($args['booking'])) {
                $this->getBookingShortCodeValues($short_codes, $order, $args);
            }
        }
        return apply_filters('woocommerce_email_customizer_plus_woocommerce_bookings_additional_short_code_values', $short_codes, $order, $args);
    }

    function getBookingShortCodeValues(&$short_codes, $order, $args)
    {
        $booking = $args['booking'];
        $short_codes['booking']['resource'] = $this->getBookingResources($booking);;
        $short_codes['booking']['details'] = $this->getBookingDetailTable($order, $args);;
        $short_codes['booking']['edit_url'] = admin_url('post.php?post=' . $booking->get_id() . '&action=edit');
        $short_codes['booking']['requires_confirmation'] = wc_booking_order_requires_confirmation($booking->get_order());
        $short_codes['booking']['status'] = $booking->get_status();
        $short_codes['booking']['start_date'] = $booking->get_start_date();
        $short_codes['booking']['end_date'] = $booking->get_end_date();
        $short_codes['booking']['notification_message'] = isset($args['notification_message']) ? wptexturize($args['notification_message']) : '';
    }

    /**
     * get booking resources
     * @param $booking
     * @return mixed|void
     */
    function getBookingResources($booking)
    {
        $resource = $booking->get_resource();
        if ($booking->has_resources() && $resource) {
            return esc_html($resource->post_title);
        }
        return "";
    }

    /**
     * Get booking detail table
     *
     * @param $order object
     * @param $args array
     * @return string
     * */
    public static function getBookingDetailTable($order, $args)
    {
        $template_path = WECP_PLUGIN_PATH . 'templates/booking_details.php';
        $override_path = get_theme_file_path('woocommerce-email-customizer-plus/templates/booking_details.php');
        if (file_exists($override_path)) {
            $template_path = $override_path;
        }
        $template = new Template();
        $data = $template->setData($template_path, $args)->render();
        $general_settings = new General();
        $settings_data = $general_settings->getOptions();
        $css = isset($settings_data['custom_css']) ? $settings_data['custom_css'] : '';
        $woocommerce = new Woocommerce();
        $content = $woocommerce->styleInline($data, $css);
        return apply_filters('woocommerce_email_customizer_plus_get_booking_details_table', $content, $order, $args);
    }

    /**
     * Additional short codes
     * @param $additional_short_codes
     * @return mixed
     */
    function loadAdditionalShortCodes($additional_short_codes)
    {
        $additional_short_codes['booking.resource'] = __('Woocommerce bookings resources', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.details'] = __('Woocommerce bookings table', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.edit_url'] = __('URL to edit booking order', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.requires_confirmation'] = __('Does bookings need confirmation', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.status'] = __('Status of the booking order', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.start_date'] = __('Start date of the booking order', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.end_date'] = __('End date of the booking order', WECP_TEXT_DOMAIN);
        $additional_short_codes['booking.notification_message'] = __('Notification of the booking order', WECP_TEXT_DOMAIN);
        return $additional_short_codes;
    }
}