<?php

namespace Wecp\App;

use Wecp\App\Controllers\Admin\License;
use Wecp\App\Controllers\EmailManager;
use Wecp\App\Models\EmailCustomizerPlusTemplatesModel as templateModel;

class Router
{
    /**
     * Declare the important variables
     * @var
     */
    private static $admin, $email_manager;
    /** minimum PHP version required by this plugin */
    const MINIMUM_PHP_VERSION = '5.6.0';
    /** minimum WordPress version required by this plugin */
    const MINIMUM_WP_VERSION = '4.9';
    const MINIMUM_WC_VERSION = '3.0.9';
    /** the plugin name, for displaying notices */
    const PLUGIN_NAME = 'Email Customizer Plus';

    function init()
    {
        //Validate and create required tables while activation of our plugin
        register_activation_hook(WECP_PLUGIN_FILE, array($this, 'pluginActivation'));
        if (!$this->isWoocommerceActive()) {
            return;
        }
        add_action('init', array($this, 'initAdminHooks'));
        add_action('woocommerce_init', array($this, 'initSiteHooks'));
        add_action('wpmu_new_blog', array($this, 'onCreateBlog'), 10, 6);
        add_filter('wpmu_drop_tables', array($this, 'onDeleteBlog'));
        add_action('admin_notices', array($this, 'initAdminNotices'));
        //Init the license
        $license = new License();
        $license->initUpdateChecker();
        //Register the endpoints
        //add_action('rest_api_init', array(self::$email_manager, 'registerGetTemplateEndpoint'));
        //add_action('rest_api_init', array(self::$email_manager, 'registerSaveTemplateEndpoint'));
        add_action('plugins_loaded', array($this, 'LoadTextDomain'));
    }

    /**
     * Load the text domain
     */
    function LoadTextDomain()
    {
        load_plugin_textdomain(WECP_TEXT_DOMAIN, false, WECP_PLUGIN_PATH . 'languages/');
    }

    /**
     * When deleting the site delete the plugin related tables
     * @param $tables
     * @return array
     */
    function onDeleteBlog($tables)
    {
        $templates_model = new templateModel();
        $tables[] = $templates_model->getTableName();
        return $tables;
    }

    /**
     * Creating table whenever a new blog is created
     * @param $blog_id
     * @param $user_id
     * @param $domain
     * @param $path
     * @param $site_id
     * @param $meta
     */
    function onCreateBlog($blog_id, $user_id, $domain, $path, $site_id, $meta)
    {
        if (is_plugin_active_for_network(WECP_PLUGIN_FILE)) {
            switch_to_blog($blog_id);
            $this->createRequiredTable();
            restore_current_blog();
        }
    }

    /**
     * Run on plugin activation
     */
    function pluginActivation()
    {
        if (!$this->isEnvironmentCompatible()) {
            exit(sprintf(__('This plugin can not be activated because it requires minimum PHP version of %1$s.', WECP_TEXT_DOMAIN), self::MINIMUM_PHP_VERSION));
        }
        if (!$this->isWoocommerceActive()) {
            exit(__('Woocommerce must installed and activated in-order to use WooCommerce Email Customizer Plus!', WECP_TEXT_DOMAIN));
        }
        if (!$this->isWooCompatible()) {
            exit(__('Woocommerce Email Customizer + requires at least Woocommerce', WECP_TEXT_DOMAIN) . ' ' . self::MINIMUM_WC_VERSION);
        }
        $this->createRequiredTable();
        return true;
    }

    /**
     * Create all the required file
     */
    function createRequiredTable()
    {
        $templates_model = new templateModel();
        $templates_model->create();
    }

    /**
     * Check the woocommerce is active or not
     * @return bool
     */
    function isWoocommerceActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('woocommerce/woocommerce.php', $active_plugins, false) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }

    /**
     * Check woocommerce version is compatibility
     * @return bool
     */
    function isWooCompatible()
    {
        if (!self::MINIMUM_WC_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = defined('WC_VERSION') && version_compare(WC_VERSION, self::MINIMUM_WC_VERSION, '>=');
        }
        return $is_compatible;
    }

    /**
     * Determines if the WordPress compatible.
     *
     * @return bool
     * @since 1.0.0
     *
     */
    protected function isWordPressCompatible()
    {
        if (!self::MINIMUM_WP_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare(get_bloginfo('version'), self::MINIMUM_WP_VERSION, '>=');
        }
        return $is_compatible;
    }

    /**
     * Determines if the server environment is compatible with this plugin.
     *
     * @return bool
     * @since 1.0.0
     *
     */
    protected function isEnvironmentCompatible()
    {
        return version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=');
    }

    /**
     * Deactivates the plugin.
     *
     * @since 1.0.0
     */
    protected function deactivatePlugin()
    {
        deactivate_plugins(WECP_PLUGIN_FILE);
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }

    /**
     * Init all the admin notice
     */
    function initAdminNotices()
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }
        if (!$this->isWordPressCompatible()) {
            $this->addAdminNotice('update_wordpress', 'error', sprintf(
                '%s requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s',
                '<strong>' . self::PLUGIN_NAME . '</strong>',
                self::MINIMUM_WP_VERSION,
                '<a href="' . esc_url(admin_url('update-core.php')) . '">', '</a>'
            ));
            $this->deactivatePlugin();
        }
        if (!$this->isWooCompatible()) {
            $this->addAdminNotice('update_woocommerce', 'error', sprintf(
                '%s requires WooCommerce version %s or higher. Please %supdate WooCommerce &raquo;%s',
                '<strong>' . self::PLUGIN_NAME . '</strong>',
                self::MINIMUM_WC_VERSION,
                '<a href="' . esc_url(admin_url('update-core.php')) . '">', '</a>'
            ));
            $this->deactivatePlugin();
        }
    }

    /**
     * print the admin notices
     * @param $slug
     * @param $class
     * @param $message
     */
    public function addAdminNotice($slug, $class, $message)
    {
        echo '<div class="' . esc_attr($class) . '" style="position:relative;"><p>';
        echo wp_kses($message, array('a' => array('href' => array(), 'target' => array())));
        echo '</p></div>';
    }

    /**
     * init all site related hooks
     */
    function initSiteHooks()
    {
        $license = new License();
        //Allow only if the license is valid
        if ($license->isValidLicense()) {
            self::$email_manager = empty(self::$email_manager) ? new EmailManager() : self::$email_manager;
            add_filter('wc_get_template', array(self::$email_manager, 'overrideEmailTemplates'), 10, 5);
            add_filter('woocommerce_mail_content', array(self::$email_manager, 'reproduceHtmlContent'), 10, 1);
        }
    }

    /**
     * init all admin related hooks
     */
    function initAdminHooks()
    {
        if (is_admin() || wp_doing_ajax()) {
            self::$admin = empty(self::$admin) ? new Controllers\Admin\Main() : self::$admin;
            add_action('admin_menu', array(self::$admin, 'AddMenu'));
            add_action('woocommerce_email_customizer_plus_load_editor_assets', array(self::$admin, 'addDefaultEditorAssets'), 100);
            add_action('admin_enqueue_scripts', array(self::$admin, 'adminScripts'), 100);
            add_filter('plugin_action_links_' . plugin_basename(WECP_PLUGIN_FILE), array(self::$admin, 'pluginActionLinks'));
            //Ajax calls
            add_action('wp_ajax_wecp_get_short_code_lists', array(self::$admin, 'getShortCodeList'));
            add_action('wp_ajax_wecp_save_settings', array(self::$admin, 'saveSettings'));
            add_action('wp_ajax_wecp_get_template', array(self::$admin, 'getTemplate'));
            add_action('wp_ajax_wecp_get_sample_data', array(self::$admin, 'getSampleData'));
            add_action('wp_ajax_wecp_send_test_email', array(self::$admin, 'sendTestMail'));
            add_action('wp_ajax_wecp_validate_license_key', array(self::$admin, 'validateLicenseKey'));
            //add_action('wp_ajax_wecp_save_custom_template', array(self::$admin, 'saveTemplates'));
            add_action('wp_ajax_wecp_auto_save_edited_template', array(self::$admin, 'autoSaveTemplate'));
            add_action('wp_ajax_wecp_activate_or_deactivate_template', array(self::$admin, 'activateOrDeactivateTemplate'));
            add_action('wp_ajax_wecp_export_email_template', array(self::$admin, 'exportEmailTemplate'));
            add_action('wp_ajax_wecp_export_all_email_templates', array(self::$admin, 'exportAllEmailTemplates'));
            add_action('wp_ajax_wecp_copy_template_to', array(self::$admin, 'copyTemplateTo'));
            add_action('wp_ajax_wecp_get_template_preview', array(self::$admin, 'getTemplatePreview'));
            add_action('wp_ajax_wecp_get_order_meta_shortcode_list', array(self::$admin, 'getOrderMetaShortCodeList'));
        }
    }
}