<?php

namespace Wecp\App\Controllers\Compatibles;
class YithCustomizeMyAccount
{
    function init()
    {
        if ($this->hasYithCustomizeMyAccountPlugin()) {
            add_filter('woocommerce_email_customizer_plus_short_code_values', array($this, 'loadAdditionalData'), 10, 4);
            add_filter('woocommerce_email_customizer_plus_additional_short_codes_list', array($this, 'loadAdditionalShortCodes'), 10, 3);
        }
    }

    /**
     * Check the site has loyalty plugin
     * @return bool
     */
    function hasYithCustomizeMyAccountPlugin()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        $account_customize_plugin = array('yith-woocommerce-customize-myaccount-page/init.php', 'yith-woocommerce-customize-myaccount-page/init.php');
        return (count(array_intersect($account_customize_plugin, $active_plugins)) > 0);
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
        if (is_array($short_codes)) {
            if ($sample) {
                $sample_data = array(
                    'wcmap_verify_url' => '#'
                );
                $short_codes = array_merge($short_codes, $sample_data);
            } else {
                $short_codes['wcmap_verify_url'] = isset($args['verify_url']) ? $args['verify_url'] : '#';
            }
        }
        return $short_codes;
    }

    /**
     * Additional short codes
     * @param $additional_short_codes
     * @return mixed
     */
    function loadAdditionalShortCodes($additional_short_codes)
    {
        //earn points email
        $additional_short_codes['wcmap_verify_url'] = __('YITH Account verification URL', WECP_TEXT_DOMAIN);
        return $additional_short_codes;
    }
}