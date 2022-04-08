<?php

namespace Wecp\App\Controllers\Compatibles;
class LoyaltyPointAndRewards
{
    function init()
    {
        if ($this->hasLoyaltyRewardsPlugin()) {
            add_filter('woocommerce_email_customizer_plus_short_code_values', array($this, 'loadAdditionalData'), 10, 4);
            add_filter('woocommerce_email_customizer_plus_additional_short_codes_list', array($this, 'loadAdditionalShortCodes'), 10, 3);
        }
    }

    /**
     * Check the site has loyalty plugin
     * @return bool
     */
    function hasLoyaltyRewardsPlugin()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        $loyalty_plugin = array('wp-loyalty-points-rewards/wp-loyalty-points-rewards.php', 'loyalty-points-rewards/wp-loyalty-points-rewards.php');
        return (count(array_intersect($loyalty_plugin, $active_plugins)) > 0);
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
                    'wlpr_order_id' => 206,
                    'wlpr_earn_point' => 500,
                    'wlpr_referral_url' => site_url() . 'wlpr=sampleurl',
                    'wlpr_expire_point' => 5,
                    'wlpr_point_expiry_date' => date('Y-m-d H:i:s', current_time('timestamp') + 3600),
                    'wlpr_point_expiry_redeem_url' => site_url() . 'wlpr=sampleurl',
                    'wlpr_referee_point' => 40,
                    'wlpr_referral_point' => 20,
                );
                $short_codes = array_merge($short_codes, $sample_data);
            } else {
                $short_codes['wlpr_order_id'] = isset($args['{wlpr_order_id}']) ? $args['{wlpr_order_id}'] : '';
                $short_codes['wlpr_earn_point'] = isset($args['{wlpr_earn_point}']) ? $args['{wlpr_earn_point}'] : '';
                $short_codes['wlpr_referral_url'] = isset($args['{wlpr_referral_url}']) ? $args['{wlpr_referral_url}'] : '';
                $short_codes['wlpr_expire_point'] = isset($args['{wlpr_expire_point}']) ? $args['{wlpr_expire_point}'] : '';
                $short_codes['wlpr_point_expiry_date'] = isset($args['{wlpr_point_expiry_date}']) ? $args['{wlpr_point_expiry_date}'] : '';
                $short_codes['wlpr_point_expiry_redeem_url'] = isset($args['{wlpr_point_expiry_redeem_url}']) ? $args['{wlpr_point_expiry_redeem_url}'] : '';
                $short_codes['wlpr_referee_point'] = isset($args['{wlpr_referee_point}']) ? $args['{wlpr_referee_point}'] : '';
                $short_codes['wlpr_referral_point'] = isset($args['{wlpr_referral_point}']) ? $args['{wlpr_referral_point}'] : '';
            }
        }
        return apply_filters('woocommerce_email_customizer_plus_loyalty_points_additional_short_code_values', $short_codes, $order, $args);
    }

    /**
     * Additional short codes
     * @param $additional_short_codes
     * @return mixed
     */
    function loadAdditionalShortCodes($additional_short_codes)
    {
        //earn points email
        $additional_short_codes['wlpr_order_id'] = __('Points generated for order ID', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_earn_point'] = __('Total points earned', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_referral_url'] = __('Referral link to earn more points', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_expire_point'] = __('Points going to expire', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_point_expiry_date'] = __('Date where points going to expire', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_point_expiry_redeem_url'] = __('URL to redeem the expired point', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_referee_point'] = __('Point earned for referee', WECP_TEXT_DOMAIN);
        $additional_short_codes['wlpr_referral_point'] = __('Point earned for referral', WECP_TEXT_DOMAIN);
        return $additional_short_codes;
    }
}