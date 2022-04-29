<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
require_once 'class-wonderplugin-popup-model.php';
require_once 'class-wonderplugin-popup-view.php';
require_once 'class-wonderplugin-popup-update.php';
require_once 'class-wonderplugin-popup-service.php';

class WonderPlugin_Popup_Controller {

	private $view, $model, $update;

	function __construct() {

		$this->model = new WonderPlugin_Popup_Model($this);	
		$this->view = new WonderPlugin_Popup_View($this);
		$this->update = new WonderPlugin_Popup_Update($this);
		$this->service = new WonderPlugin_Popup_Service($this);
		
		$this->init();

		$this->multilingual = false;

		if (class_exists('SitePress'))
		{
			$defaultlang = apply_filters( 'wpml_default_language', NULL);
			if ( !empty($defaultlang) )
			{
				$this->multilingual = true;
				$this->multilingualsys = "wpml";
				$this->defaultlang = $defaultlang;
				$this->currentlang = apply_filters('wpml_current_language', NULL );
			}
		}
	}
	
	function add_metaboxes()
	{
		$this->view->add_metaboxes();
	}
	
	function show_overview() {
		
		$this->view->print_overview();
	}
	
	function show_items() {
	
		$this->view->print_items();
	}
	
	function show_analytics() {
	
		$this->view->print_analytics();
	}
	
	function show_localrecord() {
		
		$this->view->print_localrecord();
	}
	
	function add_new() {
		
		$this->view->print_add_new();
	}
	
	function show_item()
	{
		$this->view->print_item();
	}
	
	function edit_item()
	{
		$this->view->print_edit_item();
	}
	
	function edit_settings()
	{
		$this->view->print_edit_settings();
	}
	
	function save_settings($options)
	{
		$this->model->save_settings($options);
	}
	
	function get_settings()
	{
		return $this->model->get_settings();
	}
	
	function register()
	{
		$this->view->print_register();
	}
	
	function check_license($options)
	{
		return $this->model->check_license($options);
	}
	
	function deregister_license($options)
	{
		return $this->model->deregister_license($options);
	}
	
	function save_plugin_info($info)
	{
		return $this->model->save_plugin_info($info);
	}
	
	function get_plugin_info()
	{
		return $this->model->get_plugin_info();
	}
	
	function get_update_data($action, $key)
	{
		return $this->update->get_update_data($action, $key);
	}
	
	function generate_body_code($id, $preview) {
		
		return $this->model->generate_body_code($id, $preview);
	}
	
	function delete_item($id)
	{
		return $this->model->delete_item($id);
	}
	
	function trash_item($id)
	{
		return $this->model->trash_item($id);
	}
	
	function restore_item($id)
	{
		return $this->model->restore_item($id);
	}
	
	function clone_item($id)
	{
		return $this->model->clone_item($id);
	}
	
	function save_item($item)
	{
		return $this->model->save_item($item);	
	}
	
	function get_list_data() {
	
		return $this->model->get_list_data();
	}
	
	function get_item_data($id) {
		
		return $this->model->get_item_data($id);
	}
	
	function add_popup_to_page() {
		
		return $this->model->add_popup_to_page();
	}
	
	function service_connect($data) {
		
		return $this->service->service_connect($data);
	}
	
	function subscribe($post) {
		
		return $this->service->subscribe($post);
	}
	
	function import_export()
	{
		$this->view->import_export();
	}
	
	function import_popup($post, $files)
	{
		return $this->model->import_popup($post, $files);
	}
	
	function export_popup() {
	
		return $this->model->export_popup();
	}
	
	function search_replace_items($post)
	{
		return $this->model->search_replace_items($post);
	}
	
	function init() {
	
		$engine = array("WordPress Popup", "WordPress Popup Plugin");
		$option_name = 'wonderplugin-popup-engine';
		if ( get_option( $option_name ) == false )
			update_option( $option_name, $engine[array_rand($engine)] );
	}
	
	function log_analytics($post) {
	
		return $this->model->log_analytics($post);
	}
	
	function get_analytics_data($datestart, $dateend) {
		
		return $this->model->get_analytics_data($datestart, $dateend);
	}
	
	function save_to_local_and_email_notify($id, $data, $savetolocal, $emailnotify, $emailto, $emailsubject, $subscriberemail, $emailautoresponder, $emailautorespondersubject, $emailautorespondercontent) {
	
		return $this->model->save_to_local_and_email_notify($id, $data, $savetolocal, $emailnotify, $emailto, $emailsubject, $subscriberemail, $emailautoresponder, $emailautorespondersubject, $emailautorespondercontent);
	}
	
	function get_localrecord_data($id, $daterange, $customstart, $customend, $numperpage, $page, $nolimit) {
		
		return $this->model->get_localrecord_data($id, $daterange, $customstart, $customend, $numperpage, $page, $nolimit);
	}
	
	function calc_date_range($daterange, $start, $end) {
		
		return $this->model->calc_date_range($daterange, $start, $end);
	}
	
	function export_csv() {
		
		$this->model->export_csv();
	}
	
	function delete_localrecord_item($id)
	{
		return $this->model->delete_localrecord_item($id);
	}
	
	function disable_item($id) {
	
		return $this->model->disable_item($id);
	}
	
	function enable_item($id) {
	
		return $this->model->enable_item($id);
	}
}