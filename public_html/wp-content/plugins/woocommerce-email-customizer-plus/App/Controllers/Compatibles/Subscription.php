<?php

namespace Wecp\App\Controllers\Compatibles;

use Wecp\App\Controllers\Admin\Settings\General;
use Wecp\App\Helpers\Template;
use Wecp\App\Helpers\Woocommerce;

class Subscription
{
    function init()
    {
        if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_filter('woocommerce_email_customizer_plus_short_code_values', array($this, 'loadAdditionalData'), 10, 4);
            add_filter('woocommerce_email_customizer_plus_additional_short_codes_list', array($this, 'loadAdditionalShortCodes'), 10, 3);
        }
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
        if (isset($args['subscription'])) {
            /**
             * @var $subscription \WC_Subscription
             */
            $subscription = $args['subscription'];
            $subscription_id = $subscription->get_id();
        } else {
            $subscription_id = $subscription = NULL;
            $subscription_ids = $this->getSubscriptionIdsFromOrder($order);
            if (!empty($subscription_ids)) {
                if (isset($subscription_ids[0]) && !empty($subscription_ids[0])) {
                    $subscription_id = $subscription_ids[0];
                }
            }
            if (function_exists('wcs_get_subscription')) {
                if (!empty($subscription_id)) {
                    $subscription = wcs_get_subscription($subscription_id);
                }
            }
        }
        $short_codes['subscription_detail_table'] = $this->getSubscriptionDetailTable($order, $subscription); // Here we need to pass the short code value
        $short_codes['subscription_start_on'] = $this->getSubscriptionDate($subscription, 'start');
        $short_codes['subscription_end_on'] = $this->getSubscriptionDate($subscription, 'end');
        $short_codes['subscription_next_payment'] = $this->getSubscriptionDate($subscription, 'next_payment');
        $short_codes['subscription_trial_end'] = $this->getSubscriptionDate($subscription, 'trial_end');
        $short_codes['subscription_cancelled'] = $this->getSubscriptionDate($subscription, 'cancelled');
        $short_codes['subscription_payment_retry'] = $this->getSubscriptionDate($subscription, 'payment_retry');
        $short_codes['subscription_last_order_date_created'] = $this->getSubscriptionDate($subscription, 'last_order_date_created');
        return apply_filters('woocommerce_email_customizer_plus_subscription_order_additional_short_code_values', $short_codes, $subscription, $order, $args);
    }

    /**
     * Additional short codes
     * @param $additional_short_codes
     * @return mixed
     */
    function loadAdditionalShortCodes($additional_short_codes)
    {
        $additional_short_codes['subscription_detail_table'] = esc_html__("To load the subscription details in table format", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_start_on'] = esc_html__("Display subscription start date", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_end_on'] = esc_html__("Display subscription end date", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_next_payment'] = esc_html__("Display subscription next payment date", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_trial_end'] = esc_html__("Display subscription trial end date", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_cancelled'] = esc_html__("Display subscription cancelled date", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_payment_retry'] = esc_html__("Display subscription payment retry date", WECP_TEXT_DOMAIN);
        $additional_short_codes['subscription_last_order_date_created'] = esc_html__("Display subscription last order created", WECP_TEXT_DOMAIN);
        return $additional_short_codes;
    }

    /**
     * Get Subscription start date
     *
     * @param $subscription object
     * @param $type string
     * @return string
     * */
    public static function getSubscriptionDate($subscription, $type)
    {
        $date = '';
        if (!empty($subscription)) {
            if (method_exists($subscription, 'get_time')) {
                $date_of_type = $subscription->get_time($type, 'site');
                if (!empty($date_of_type)) {
                    $date = date_i18n(wc_date_format(), $date_of_type);
                }
            }
        }
        return $date;
    }

    /**
     * Get subscription detail table
     *
     * @param $order object
     * @return string
     * */
    public static function getSubscriptionDetailTable($order, $subscription)
    {
        if (!empty($subscription)) {
            $woocommerce = new Woocommerce();
            $template_path = WECP_PLUGIN_PATH . 'templates/subscription_details.php';
            $override_path = get_theme_file_path('woocommerce-email-customizer-plus/templates/subscription_details.php');
            if (file_exists($override_path)) {
                $template_path = $override_path;
            }
            $template = new Template();
            $data = $template->setData($template_path, array('subscription' => $subscription, 'order' => $order))->render();
            $general_settings = new General();
            $settings_data = $general_settings->getOptions();
            $css = isset($settings_data['custom_css']) ? $settings_data['custom_css'] : '';
            $content = $woocommerce->styleInline($data, $css);
            return apply_filters('woocommerce_email_customizer_plus_get_subscription_details_table', $content, $order);
        }
        return "";
    }

    /**
     * Get subscription Ids from order
     *
     * @param $order object
     * @return array
     * */
    public static function getSubscriptionIdsFromOrder($order)
    {
        $subscriptions_ids = array();
        if (method_exists($order, 'get_id') && method_exists($order, 'get_type')) {
            $order_id = $order->get_id();
            if (function_exists('wcs_get_subscriptions_for_order')) {
                $subscription_ids = wcs_get_subscriptions_for_order($order_id);
                // We get the related subscription for this order
                if (!empty($subscription_ids)) {
                    foreach ($subscription_ids as $subscription_id => $subscription_obj) {
                        if ($subscription_obj->get_parent()->get_id() == $order_id) {
                            $subscriptions_ids[] = $subscription_id;
                        }
                    }
                }
            }
            if (empty($subscriptions_ids)) {
                if ($order->get_type() == 'shop_subscription') {
                    $subscriptions_ids[] = $order_id;
                } else {
                    if (function_exists('wcs_get_subscription')) {
                        $subscription = wcs_get_subscription($order_id);
                        if (!empty($subscription)) {
                            $subscriptions_ids[] = $order_id;
                        } else {
                            if (function_exists('wcs_get_subscriptions_for_renewal_order')) {
                                $subscription_ids = wcs_get_subscriptions_for_renewal_order($order_id);
                                foreach ($subscription_ids as $subscription_id => $subscription_obj) {
                                    $subscriptions_ids[] = $subscription_id;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $subscriptions_ids;
    }
}