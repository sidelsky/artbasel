<?php

namespace Wecp\App\Controllers\Admin;

use Wecp\App\Controllers\Base;
use WP_Error;

class License
{
    private $remote_license_url = "https://www.flycart.org/";
    private $plugin_slug = "email-customizer-plus";

    /**
     * Validate the license key
     * @param $key
     * @return bool|string
     */
    function validateKey($key)
    {
        if (empty($key)) {
            return false;
        }
        $fields = array(
            'wpaction' => 'licensecheck',
            'wpslug' => urlencode($this->plugin_slug),
            'pro' => 0,
            'dlid' => $key
        );
        $response = $this->request('', $fields);
        $status = $this->validateResponse($response);
        if (!is_wp_error($status)) {
            $json = json_decode($response['body']);
            $current_status = 'invalid';
            if (is_object($json) && isset($json->license_check)) {
                $is_verified = (bool)$json->license_check;
                if ($is_verified) {
                    $current_status = 'active';
                }
            }
            return $current_status;
        }
        return false;
    }

    /**
     * initiate the update checker
     */
    function initUpdateChecker()
    {
        $this->addHooks();
        $this->runUpdater();
    }

    /**
     * To get update URL
     *
     * @return string
     * */
    protected function getUpdateURL()
    {
        $licence_key = $this->getLicenseKey();
        if (empty($licence_key)) {
            return NULL;
        }
        $fields = array(
            'wpaction' => 'updatecheck',
            'wpslug' => urlencode($this->plugin_slug),
            'pro' => 0,
            'dlid' => $licence_key
        );
        $update_url = $this->remote_license_url . 'update?' . http_build_query($fields);
        if ($licence_key == '') {
            add_action('admin_notices', array($this, 'enterLicenceKeyErrorNotice'));
            //return '';
        }
        return $update_url;
    }

    /**
     * To display error message in admin page while there is no licence key
     * */
    public function enterLicenceKeyErrorNotice()
    {
        $notice_on_for_user = get_user_meta(get_current_user_id(), 'dismissed_woo_email_customizer_admin_installed_notice', true);
        if (!$notice_on_for_user) {
            $htmlPrefix = '<div class="updated woocommerce-message"><p>';
            $htmlSuffix = '</p></div>';
            $msg = "<strong>";
            $msg .= __("WooCommerce Email Customizer Plus installed", WECP_TEXT_DOMAIN);
            $msg .= "</strong>";
            $msg .= __(" - Make sure to activate your copy of the plugin to receive updates, support and security fixes!", WECP_TEXT_DOMAIN);
            $msg .= '<p>';
            $msg .= '<a href="' . self::getSettingsPageUrl() . '" class="button-primary">';
            $msg .= __('Settings', WECP_TEXT_DOMAIN);
            $msg .= '</a></p>';
            $msg .= '<a href="' . esc_url(wp_nonce_url(add_query_arg('wemc-hide-notice', 'installed'), 'woo_email_customizer_hide_notices_nonce', '_wemc_notice_nonce')) . '" class="wemc-notice-a notice-dismiss"><span class="screen-reader-text">' . __('Dismiss this notice.', WECP_TEXT_DOMAIN) . '</span></a>';
            echo $htmlPrefix . $msg . $htmlSuffix;
        }
    }

    /**
     * To run the updater
     * */
    protected function runUpdater()
    {
        $update_url = $this->getUpdateURL();
        if (!empty($update_url)) {
            \Puc_v4_Factory::buildUpdateChecker(
                $update_url, WECP_PLUGIN_FILE, $this->plugin_slug
            );
        }
    }

    /**
     * Add hooks
     * */
    function addHooks()
    {
        add_filter('puc_request_info_result-' . $this->plugin_slug, array($this, 'loadEmailCustomizerDescription'), 10, 2);
        add_filter('in_plugin_update_message-' . plugin_basename(WECP_PLUGIN_FILE), array($this, 'showExpiredLicenseMessage'), 10, 2);
    }

    /**
     * Message on plugin page when license is expired
     * @param $plugin_data
     * @param $response
     * @return mixed
     */
    function showExpiredLicenseMessage($plugin_data, $response)
    {
        if (empty($response->package)) {
            echo "<br>";
            echo self::getLicenseExpiredMessage();
        }
        return $plugin_data;
    }

    /**
     * Get licence key
     * */
    function getSettingsPageUrl()
    {
        return admin_url("admin.php?page=" . WECP_PLUGIN_SLUG . "&view=settings");
    }

    /**
     * Get product URL
     * */
    function getProductUrl()
    {
        return 'https://www.flycart.org/products/wordpress/woocommerce-email-customizer';
    }

    /**
     * @return mixed|void
     */
    function getLicenseKey()
    {
        return get_option('woocommerce_email_customizer_plus_license_key', NULL);
    }

    /**
     * get message on license expired
     * @return string
     */
    function getLicenseExpiredMessage()
    {
        $licenseKey = $this->getLicenseKey();
        if ($licenseKey == '') {
            $upgrade_url = '<a href="' . $this->getSettingsPageUrl() . '">' . esc_html__('enter license key', WECP_TEXT_DOMAIN) . '</a>';
            $msg = sprintf(esc_html__('Please %s to receive automatic updates or you can manually update the plugin by downloading it.', WECP_TEXT_DOMAIN), $upgrade_url);
        } else {
            $upgrade_url = '<a target="_blank" href="' . $this->getProductUrl() . '">' . esc_html__('renew your support license', WECP_TEXT_DOMAIN) . '</a>';
            $msg = sprintf(esc_html__('Please %s to receive automatic updates or you can manually update the plugin by downloading it.', WECP_TEXT_DOMAIN), $upgrade_url);
        }
        return $msg;
    }

    /**
     * get license key status
     * @return mixed|void
     */
    function getLicenseStatus()
    {
        return get_option("woocommerce_email_customizer_plus_license_key_status", "invalid");
    }

    /**
     * Load plugin description
     * @param $pluginInfo
     * @param $result
     * @return mixed
     */
    function loadEmailCustomizerDescription($pluginInfo, $result)
    {
        if (isset($pluginInfo->sections)) {
            $section = $pluginInfo->sections;
            if (empty($section['description'])) {
                $section['description'] = $this->pluginDescriptionContent();
                $section['changelog'] = $this->pluginChangeLogContent();
                $pluginInfo->sections = $section;
            }
        } else {
            $pluginInfo->sections = array(
                'description' => $this->pluginDescriptionContent(),
                'changelog' => $this->pluginChangeLogContent(),
            );
        }
        $pluginInfo->name = "Woocommerce Email Customizer Plus";
        $pluginInfo->author = "Flycart";
        $pluginInfo->last_updated = "2021-04-05 5:30";
        $pluginInfo->active_installs = 500;
        return $pluginInfo;
    }

    /**
     * plugin description
     * @return string
     */
    function pluginDescriptionContent()
    {
        return '<div class="js-item-description item-description has-toggle">
                  <h4>Build your brand by sending well-designed transactional emails</h4>
                  <p>Add a logo, header, footer, body text, custom paragraph texts, social icons, images and more with a simple, intuitive drag and drop interface.
Create professional, beautiful transactional emails for your WooCommerce Online store.</p>
                  <p>Say goodbye to template based email plugins where you can only edit only header and footer. Say hello to the email builder which helps you create beautiful, elegant transactional emails and impress your customers.</p>
                  <p>For more details visit :<a target="_blank" href="https://www.flycart.org/products/wordpress/woocommerce-email-customizer">https://www.flycart.org/products/wordpress/woocommerce-email-customizer</a></p>
                 </div>';
    }

    /**
     * plugin description
     * @return string
     */
    function pluginChangeLogContent()
    {
        return '
                <h4>1.2.7</h4>
                <ul>
                    <li>Fix: Subscription table end date wrong </li>
                    <li>Fix: Order table Responsive css improved(New installation only)</li>
                </ul>
                <h4>1.2.6</h4>
                <ul>
                    <li>Fix: Subscription block auto add without shortcode </li>
                    <li>Fix: Order table was not responsive in mobile devices<b>(New installations only)</b> </li>
                    <li>Fix: Order summery block max-width 600px removed</li>
                    <li>Improvement: Compatibility for WooCommerce 4.8.0 and Wordpress 5.6 added </li>
                    <li>Improvement: new shortcode ({{booking.resource}}) added for booking plugin </li>
                </ul>
                <h4>1.2.5</h4>
                <ul>
                    <li>Improvement: security( Nonce and capability check) added </li>
                </ul>
                <h4>1.2.4</h4>
                <ul>
                    <li>Improvement: Wordpress 5.5 support added</li>
                </ul>
                <h4>1.2.3</h4>
                <ul>
                    <li>Improvement: </li>
                    <li>Improvement: 14 new templates was added for WooCommerce booking plugins</li>
                </ul>
                ';
    }

    /**
     * Check if $result is a successful update API response.
     *
     * @param array|WP_Error $result
     * @return true|WP_Error
     */
    protected function validateResponse($result)
    {
        if (is_wp_error($result)) {
            /** @var WP_Error $result */
            return new WP_Error($result->get_error_code(), 'WP HTTP Error: ' . $result->get_error_message());
        }
        if (!isset($result['response']['code'])) {
            return new WP_Error(
                'puc_no_response_code',
                'wp_remote_get() returned an unexpected result.'
            );
        }
        if ($result['response']['code'] !== 200) {
            return new WP_Error(
                'puc_unexpected_response_code',
                'HTTP response code is ' . $result['response']['code'] . ' (expected: 200)'
            );
        }
        if (empty($result['body'])) {
            return new WP_Error('puc_empty_response', 'The metadata file appears to be empty.');
        }
        return true;
    }

    /**
     * get operation for Remote URL
     * @param $url
     * @param $need_domain_in_suffix
     * @param array $fields
     * @return array|bool|mixed|object|string
     */
    function request($url, $fields = array(), $need_domain_in_suffix = true)
    {
        try {
            if (is_array($fields) && !empty($fields)) {
                $url = rtrim($url, '/');
                $url .= '?' . http_build_query($fields);
            }
            if ($need_domain_in_suffix)
                $url = $this->remote_license_url . $url;
            $response = wp_remote_get($url);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }

    /**
     * Check curl is enbled or not
     * @return bool
     */
    function isCurlEnabled()
    {
        return function_exists('curl_version');
    }

    /**
     * Check the given license is valid or not
     * @return bool
     */
    function isValidLicense()
    {
        $current_status = $this->getLicenseStatus();
        $is_valid_license = ($current_status == "active");
        return apply_filters('woocommerce_email_customizer_plus_is_valid_license', $is_valid_license);
    }
}