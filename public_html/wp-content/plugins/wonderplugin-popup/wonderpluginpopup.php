<?php
/*
Plugin Name: Wonder Popup Pro
Plugin URI: https://www.wonderplugin.com
Description: WordPress Popup Plugin
Version: 6.9C
Author: Magic Hills Pty Ltd
Author URI: https://www.wonderplugin.com
License: Copyright 2019 Magic Hills Pty Ltd, All Rights Reserved
*/

if ( ! defined( 'ABSPATH' ) )
	exit;
	
if (defined('WONDERPLUGIN_POPUP_VERSION'))
	return;

define('WONDERPLUGIN_POPUP_VERSION', '6.9C');
define('WONDERPLUGIN_POPUP_URL', plugin_dir_url( __FILE__ ));
define('WONDERPLUGIN_POPUP_PATH', plugin_dir_path( __FILE__ ));
define('WONDERPLUGIN_POPUP_PLUGIN', basename(dirname(__FILE__)) . '/' . basename(__FILE__));
define('WONDERPLUGIN_POPUP_PLUGIN_VERSION', '6.9');

require_once 'app/class-wonderplugin-popup-controller.php';

class WonderPlugin_Popup_Plugin {
	
	function __construct() {
	
		$this->init();
	}
	
	public function init() {
		
		// init controller
		$this->wonderplugin_popup_controller = new WonderPlugin_Popup_Controller();
		
		add_action( 'admin_menu', array($this, 'register_menu') );
		
		// shortcode
		add_shortcode( 'wonderplugin_popup', array($this, 'shortcode_handler') );
		
		add_action( 'init', array($this, 'register_script') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script') );
		
		if ( is_admin() )
		{
			add_action( 'admin_init', array($this, 'admin_init_hook') );
			
			add_action( 'wp_ajax_wonderplugin_popup_subscribe', array($this, 'wp_ajax_subscribe') );
			add_action( 'wp_ajax_nopriv_wonderplugin_popup_subscribe', array($this, 'wp_ajax_subscribe') );		
			add_action( 'wp_ajax_wonderplugin_popup_log_analytics', array($this, 'wp_ajax_log_analytics') );
			add_action( 'wp_ajax_nopriv_wonderplugin_popup_log_analytics', array($this, 'wp_ajax_log_analytics') );
			
			add_action( 'admin_post_wonderplugin_popup_export_csv', array($this, 'export_csv') );
			add_action( 'admin_post_wonderplugin_popup_export', array($this, 'export_popup') );
		}
		
		$supportwidget = get_option( 'wonderplugin_popup_supportwidget', 1 );
		if ( $supportwidget == 1 )
		{
			add_filter('widget_text', 'do_shortcode');
		}
		
		// insert popup to footer
		add_action( 'wp_footer', array($this, 'add_popup_to_page') );
	}
	
	function register_menu()
	{
		
		$settings = $this->get_settings();
		$userrole = $settings['userrole'];
		
		$menu = add_menu_page(
				__('Wonder Popup Pro', 'wonderplugin_popup'),
				__('Wonder Popup Pro', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_overview',
				array($this, 'show_overview'),
				WONDERPLUGIN_POPUP_URL . 'images/logo-16.png' );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Overview', 'wonderplugin_popup'),
				__('Overview', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_overview',
				array($this, 'show_overview' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Add New', 'wonderplugin_popup'),
				__('Add New', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_add_new',
				array($this, 'add_new' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_editor_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Manage Popups', 'wonderplugin_popup'),
				__('Manage Popups', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_show_items',
				array($this, 'show_items' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Analytics', 'wonderplugin_popup'),
				__('Analytics', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_show_analytics',
				array($this, 'show_analytics' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Local Record', 'wonderplugin_popup'),
				__('Local Record', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_show_localrecord',
				array($this, 'show_localrecord' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Import/Export', 'wonderplugin_popup'),
				__('Import/Export', 'wonderplugin_popup'),
				'manage_options',
				'wonderplugin_popup_import_export',
				array($this, 'import_export' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Settings', 'wonderplugin_popup'),
				__('Settings', 'wonderplugin_popup'),
				'manage_options',
				'wonderplugin_popup_edit_settings',
				array($this, 'edit_settings' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_popup_overview',
				__('Register', 'wonderplugin_popup'),
				__('Register', 'wonderplugin_popup'),
				'manage_options',
				'wonderplugin_popup_register',
				array($this, 'register' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('View Popup', 'wonderplugin_popup'),
				__('View Popup', 'wonderplugin_popup'),	
				$userrole,	
				'wonderplugin_popup_show_item',	
				array($this, 'show_item' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('Edit Popup', 'wonderplugin_popup'),
				__('Edit Popup', 'wonderplugin_popup'),
				$userrole,
				'wonderplugin_popup_edit_item',
				array($this, 'edit_item' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_editor_script') );
	}
	
	function register_script()
	{		
		wp_register_style('wonderplugin-popup-engine-css', WONDERPLUGIN_POPUP_URL . 'engine/wonderplugin-popup-engine.css', array(), WONDERPLUGIN_POPUP_VERSION);
		wp_register_script('wonderplugin-popup-engine-script', WONDERPLUGIN_POPUP_URL . 'engine/wonderplugin-popup-engine.js', array('jquery'), WONDERPLUGIN_POPUP_VERSION, false);
		
		wp_register_style('wonderplugin-popup-admin-css', WONDERPLUGIN_POPUP_URL . 'wonderpluginpopup.css', array(), WONDERPLUGIN_POPUP_VERSION);
		wp_register_script('wonderplugin-popup-creator-script', WONDERPLUGIN_POPUP_URL . 'app/wonderplugin-popup-creator.js', array('jquery', 'wp-color-picker'), WONDERPLUGIN_POPUP_VERSION, false);	
		wp_register_script('wonderplugin-popup-skins-script', WONDERPLUGIN_POPUP_URL . 'app/wonderplugin-popup-skins.js', array('jquery'), WONDERPLUGIN_POPUP_VERSION, false);
		wp_register_script('wonderplugin-popup-functions-script', WONDERPLUGIN_POPUP_URL . 'app/wonderplugin-popup-functions.js', array('jquery'), WONDERPLUGIN_POPUP_VERSION, false);
	}
	
	function enqueue_script()
	{
		wp_enqueue_style('wonderplugin-popup-engine-css');
		
		$addjstofooter = get_option( 'wonderplugin_popup_addjstofooter', 0 );
		if ($addjstofooter == 1)
		{
			wp_enqueue_script('wonderplugin-popup-engine-script', false, array(), false, true);
		}
		else
		{
			wp_enqueue_script('wonderplugin-popup-engine-script');
		}	
		wp_localize_script('wonderplugin-popup-engine-script', 'wonderpluginpopup_ajaxobject', array( 'ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('wonderplugin-popup-ajaxnonce') ));
	}
	
	function enqueue_admin_editor_script($hook)
	{
		
		wp_enqueue_script('post');
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style ('wp-jquery-ui-dialog');
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');
		
		wp_enqueue_style('wonderplugin-popup-admin-css');
		
		wp_enqueue_style('wonderplugin-popup-engine-css');
		
		wp_enqueue_script('wonderplugin-popup-creator-script');
		wp_enqueue_script('wonderplugin-popup-skins-script');
		wp_enqueue_script('wonderplugin-popup-functions-script');
		
		wp_enqueue_style('wonderplugin-popup-engine-css');
	}
	
	function enqueue_admin_script($hook)
	{
		wp_enqueue_script('post');
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style ('wp-jquery-ui-dialog');
		
		wp_enqueue_script('jquery-ui-datepicker');
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');
		
		wp_enqueue_style('wonderplugin-popup-admin-css');
		wp_enqueue_script('wonderplugin-popup-functions-script');
		
		wp_enqueue_style('wonderplugin-popup-engine-css');
		wp_enqueue_script('wonderplugin-popup-engine-script');
		
		wp_localize_script('wonderplugin-popup-engine-script', 'wonderpluginpopup_ajaxobject', array( 'ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('wonderplugin-popup-ajaxnonce') ));
	}
	
	function admin_init_hook()
	{
		$settings = $this->get_settings();
		$userrole = $settings['userrole'];
		
		if ( !current_user_can($userrole) )
			return;
		
		// add meta boxes
		$this->wonderplugin_popup_controller->add_metaboxes();
		
	}
	
	function show_overview() {
		
		$this->wonderplugin_popup_controller->show_overview();
	}
	
	function show_items() {
		
		$this->wonderplugin_popup_controller->show_items();
	}
	
	function show_analytics() {
		
		$this->wonderplugin_popup_controller->show_analytics();
	}
	
	function show_localrecord() {
	
		$this->wonderplugin_popup_controller->show_localrecord();
	}
	
	function add_new() {
		
		$this->wonderplugin_popup_controller->add_new();
	}
	
	function show_item() {
		
		$this->wonderplugin_popup_controller->show_item();
	}
	
	function edit_item() {
	
		$this->wonderplugin_popup_controller->edit_item();
	}
	
	function edit_settings() {
	
		$this->wonderplugin_popup_controller->edit_settings();
	}
	
	function get_settings() {
	
		return $this->wonderplugin_popup_controller->get_settings();
	}
	
	function register() {
	
		$this->wonderplugin_popup_controller->register();
	}	
	
	function shortcode_handler($atts) {
	
		if ( !isset($atts['id']) || !is_numeric($atts['id']))
			return __('Please specify a popup id', 'wonderplugin_popup');
				
		return $this->wonderplugin_popup_controller->generate_body_code( $atts['id'], false);
	}
	
	function add_popup_to_page() {
		
		echo $this->wonderplugin_popup_controller->add_popup_to_page();
	}
	
	function wp_ajax_subscribe() {
		
		$ajaxverifynonce = get_option( 'wonderplugin_popup_ajaxverifynonce', 0 );
		if ( $ajaxverifynonce == 1 )
			check_ajax_referer('wonderplugin-popup-ajaxnonce', 'nonce');
		
		header('Content-Type: application/json');
		echo json_encode($this->wonderplugin_popup_controller->subscribe($_POST));
		wp_die();
	}
	
	function wp_ajax_log_analytics() {
				
		$ajaxverifynonce = get_option( 'wonderplugin_popup_ajaxverifynonce', 0 );
		if ( $ajaxverifynonce == 1 )
			check_ajax_referer('wonderplugin-popup-ajaxnonce', 'nonce');			
		
		header('Content-Type: application/json');
		echo json_encode($this->wonderplugin_popup_controller->log_analytics($_POST));
		wp_die();
	}
	
	function export_csv() {
		
		$settings = $this->get_settings();
		$userrole = $settings['userrole'];
		
		if ( !current_user_can($userrole) )
			return;
		
		$this->wonderplugin_popup_controller->export_csv();
	}
	
	function import_export() {
	
		$this->wonderplugin_popup_controller->import_export();
	}
	
	function export_popup() {
	
		check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-export');
	
		if ( !current_user_can('manage_options') )
			return;
	
		$this->wonderplugin_popup_controller->export_popup();
	}
}

/**
 * Init the plugin
 */
$wonderplugin_popup_plugin = new WonderPlugin_Popup_Plugin();

/**
 * Uninstallation
 */
if ( !function_exists('wonderplugin_popup_uninstall') )
{
	function wonderplugin_popup_uninstall() {

		if ( ! current_user_can( 'activate_plugins' ) )
			return;
		
		global $wpdb;
		
		$keepdata = get_option( 'wonderplugin_popup_keepdata', 1 );
		if ( $keepdata == 0 )
		{
			$table_name = $wpdb->prefix . "wonderplugin_popup";
			$wpdb->query("DROP TABLE IF EXISTS $table_name");
		}	

	}

	if ( function_exists('register_uninstall_hook') )
	{
		register_uninstall_hook( __FILE__, 'wonderplugin_popup_uninstall' );
	}
}

define('WONDERPLUGIN_POPUP_VERSION_TYPE', 'C');
