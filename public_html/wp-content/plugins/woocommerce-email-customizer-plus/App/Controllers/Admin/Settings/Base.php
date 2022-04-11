<?php

namespace Wecp\App\Controllers\Admin\Settings;

use Wecp\App\Controllers\Admin\License;
use Wecp\App\Helpers\Template;
use Wecp\App\Helpers\Woocommerce;

abstract class Base
{
    protected static $license, $template, $woocommerce;
    public $name = NULL, $label, $settings_key = 'woo_customizer_plus_settings_';

    /**
     * Base constructor.
     * init all required helper
     */
    function __construct()
    {
        self::$template = empty(self::$template) ? new Template() : self::$template;
        self::$license = empty(self::$license) ? new License() : self::$license;
        self::$woocommerce = empty(self::$woocommerce) ? new Woocommerce() : self::$woocommerce;
    }

    /**
     * display the page
     * @return mixed
     */
    abstract function render();

    /**
     * default options
     * @return mixed
     */
    abstract function getDefaultOptions();

    /**
     * get the settings by the option key
     * @param $key
     * @return mixed|void
     */
    function getSettings($key)
    {
        return get_option($key, array());
    }

    /**
     * get settings
     * @return mixed
     */
    function getOptions()
    {
        $key = $this->key();
        $options = $this->getSettings($key);
        $default_options = $this->getDefaultOptions();
        if (is_array($options) && is_array($default_options)) {
            return array_merge($default_options, $options);
        }
        return $options;
    }

    /**
     * return the name of the settings page
     * @return string|null
     */
    function name()
    {
        return $this->name;
    }

    /**
     * return the label for the settings page
     * @return string|null
     */
    function label()
    {
        return $this->label;
    }

    /**
     * @return mixed|void
     */
    function link()
    {
        $url = admin_url("admin.php?" . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'settings', 'tab' => $this->name())));
        return apply_filters("wecp_change_settings_page_url", $url, $this->name());
    }

    /**
     * gives the key of the option
     * @return string
     */
    function key()
    {
        return $this->settings_key . $this->name();
    }
}