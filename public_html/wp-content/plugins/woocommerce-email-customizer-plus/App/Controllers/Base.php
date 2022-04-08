<?php

namespace Wecp\App\Controllers;

use Wecp\App\Controllers\Admin\License;
use Wecp\App\Controllers\Admin\Settings\General;
use Wecp\App\Controllers\Compatibles\Subscription;
use Wecp\App\Controllers\Compatibles\Retainful;
use Wecp\App\Controllers\Compatibles\LoyaltyPointAndRewards;
use Wecp\App\Controllers\Compatibles\WoocommerceBookings;
use Wecp\App\Controllers\Compatibles\YithCustomizeMyAccount;
use Wecp\App\Helpers\Input;
use Wecp\App\Helpers\Template;
use Wecp\App\Helpers\Woocommerce;
use Wecp\App\Models\EmailCustomizerPlusTemplatesModel;

class Base
{
    protected static $input, $template, $woocommerce, $license, $general_settings;

    /**
     * Base constructor.
     * init all required helper
     */
    function __construct()
    {
        self::$input = empty(self::$input) ? new Input() : self::$input;
        self::$template = empty(self::$template) ? new Template() : self::$template;
        self::$woocommerce = empty(self::$woocommerce) ? new Woocommerce() : self::$woocommerce;
        self::$license = empty(self::$license) ? new License() : self::$license;
        self::$general_settings = empty(self::$general_settings) ? new General() : self::$general_settings;
        $this->initCompatibles();
    }

    /**
     * Init all the required compatibles
     */
    function initCompatibles()
    {
        $subscription = new Subscription();
        $subscription->init();
        $retainful = new Retainful();
        $retainful->init();
        $loyalty = new LoyaltyPointAndRewards();
        $loyalty->init();
        $bookings = new WoocommerceBookings();
        $bookings->init();
        $my_account = new YithCustomizeMyAccount();
        $my_account->init();
    }

    /**
     * Create all required tables needed for Email customizer plus
     */
    function createRequiredTables()
    {
        $email_customizer_model = new EmailCustomizerPlusTemplatesModel();
        $email_customizer_model->create();
    }

    /**
     * change the direction for the content
     * @param $content
     * @param string $direction
     * @return mixed|void
     */
    function changeDirection($content, $direction = 'ltr')
    {
        $replace_contents = $this->getToReplaceDirectionContents($direction);
        if (empty($replace_contents)) {
            return $content;
        }
        $replaced_content = NULL;
        if (is_array($replace_contents)) {
            $search = array_keys($replace_contents);
            $replace = array_values($replace_contents);
            $replaced_content = str_replace($search, $replace, $content);
        }
        return apply_filters('woocommerce_email_customizer_plus_direction_replace_contents', $replaced_content, $content, $direction);
    }

    /**
     * get the search and replace array to replace the content
     * @param string $direction
     * @return mixed|void
     */
    function getToReplaceDirectionContents($direction = "ltr")
    {
        $replace_contents = array();
        if ($direction == 'rtl') {
            $replace_contents['<body>'] = '<body dir="rtl">';
            $replace_contents['dir="ltr"'] = 'dir="rtl"';
            $replace_contents['direction:ltr'] = 'direction:rtl';
            $replace_contents['align="left"'] = 'align="right"';
            $replace_contents['align = "left"'] = 'align="right"';
            $replace_contents[':left;'] = ':right;direction:rtl;';
            $replace_contents[': left;'] = ':right;direction:rtl;';
        }
        return apply_filters('woocommerce_email_customizer_plus_direction_replace_contents', $replace_contents, $direction);
    }

    function supportedEmailTemplates()
    {
        $templates = array(
            'WC_Email_New_Order',
            'WC_Email_Cancelled_Order',
            'WC_Email_Failed_Order',
            'WC_Email_Customer_On_Hold_Order',
            'WC_Email_Customer_Processing_Order',
            'WC_Email_Customer_Completed_Order',
            'WC_Email_Customer_Refunded_Order',
            'WC_Email_Customer_Invoice',
            'WC_Email_Customer_Note',
            'WC_Email_Customer_Reset_Password',
            'WC_Email_Customer_New_Account',
            'WCS_Email_New_Renewal_Order',
            'WCS_Email_New_Switch_Order',
            'WCS_Email_Processing_Renewal_Order',
            'WCS_Email_Completed_Renewal_Order',
            'WCS_Email_Customer_On_Hold_Renewal_Order',
            'WCS_Email_Completed_Switch_Order',
            'WCS_Email_Customer_Renewal_Invoice',
            'WCS_Email_Cancelled_Subscription',
            'WCS_Email_Expired_Subscription',
            'WCS_Email_On_Hold_Subscription');
        return apply_filters('woocommerce_email_customizer_plus_supported_email_templates', $templates);
    }
}