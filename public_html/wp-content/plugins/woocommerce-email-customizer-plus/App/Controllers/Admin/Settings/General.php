<?php

namespace Wecp\App\Controllers\Admin\Settings;
class General extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'general';
        $this->label = __('General', WECP_TEXT_DOMAIN);
    }

    function render()
    {
        $current_status = self::$license->getLicenseStatus();
        $license_key_error = true;
        if ($current_status == "invalid") {
            $license_key_message = __('Please enter valid license key!', WECP_TEXT_DOMAIN);
        } elseif ($current_status == "expired") {
            $license_key_message = __('Your license key was expired!', WECP_TEXT_DOMAIN);
        } else {
            $license_key_error = false;
            $license_key_message = __('Your license key validated successfully!', WECP_TEXT_DOMAIN);
        }
        $options = $this->getOptions();
        $options['license_key'] = self::$license->getLicenseKey();
        $default_language = self::$woocommerce->getSiteDefaultLang();
        $available_languages = self::$woocommerce->getAvailableLanguages();
        if (is_array($available_languages)) {
            //include default language to available languages
            $available_languages = array_merge(array($default_language), $available_languages);
        }
        if (!empty($available_languages)) {
            $available_languages = array_unique($available_languages);
        }
        $params = array(
            'save_key' => $this->key(),
            'options' => $options,
            'available_languages' => $available_languages,
            'license_key_message' => $license_key_message,
            'license_key_error' => $license_key_error,
            'woocommerce_helper' => self::$woocommerce
        );
        return self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/Settings/' . $this->name() . '.php', $params)->render();
    }

    function getDefaultOptions()
    {
        return array(
            'custom_css' => $this->getDefaultCustomCss(),
            'show_sku' => 1,
            'show_product_image' => 1,
            'product_image_size' => 'thumbnail',
            'product_image_height' => 32,
            'product_image_width' => 32,
            'enable_retainful_integration' => 0,
            'autofix_broken_html' => 0,
        );
    }

    function getDefaultCustomCss()
    {
        $css = '.email-subscription-list, .email-product-list, .email-download-list {
                    overflow: auto;
                }
        
                .email-subscription-list table, .email-product-list table, .email-download-list table {
                    border-collapse: collapse;
                    width: 100%;
                    min-width: 480px;
                    border: 1px solid #e1e1e1;
                }
        
                .email-subscription-list table td, .email-subscription-list table th, .email-product-list table td, .email-product-list table th, .email-download-list table td, .email-download-list table th {
                    border: 1px solid #e1e1e1;
                    text-align: left;
                    padding: 10px 5px;
                }
        
                .email-product-list table thead tr:first-child, .email-product-list table tfoot tr:last-child, .email-subscription-list table thead tr, .email-download-list table thead tr {
                    background-color: #f9f9f9;
                }
        
                .email-product-list table tbody tr td:first-child {
                    width: 50%;
                }
        
                .email-product-list table tbody tr td:nth-child(2) {
                    width: 1%;
                    text-align: center;
                }
        
                .email-product-list table tr td:last-child, .email-product-list table tr th:last-child {
                    text-align: right;
                }
        
                table thead tr th {
                    white-space: nowrap;
                }
            ';
        return apply_filters('woocommerce_email_customizer_plus_default_custom_css', $css);
    }
}