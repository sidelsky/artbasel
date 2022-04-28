<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
if( ! class_exists( 'WP_List_Table' ) )
{
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WonderPlugin_Popup_List_Table extends WP_List_Table {

	private $view, $display_status, $published, $trashed;
	public $list_data;
	
	public function __construct($view)
	{
		parent::__construct();
		$this->view = $view;
	}
	
	function get_columns()
	{
		$columns = array(
				'cb' => '<input type="checkbox" />',
				'id' => __('ID', 'wonderplugin_popup'),
				'name' => __('Name', 'wonderplugin_popup'),
				'type'	=> __('Type', 'wonderplugin_popup'),
				'status'	=> __('Status', 'wonderplugin_popup'),
				'time' => __('Created', 'wonderplugin_popup')
		);
		return $columns;
	}
		
	function prepare_items() 
	{
		$this->display_status = ( isset($_REQUEST['item_status']) ? $_REQUEST['item_status'] : 'all');
		
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
				
		usort( $this->list_data, array( &$this, 'usort_reorder' ) );
				
		$this->items = $this->list_data;
		
		$this->published = count($this->items);
		$this->trashed = 0;
		foreach ($this->items as $key => $item)
		{
			$data = json_decode($item['data']);
				
			$is_trash = ( isset($data->publish_status) && ($data->publish_status === 0) );
			if ($is_trash)
				$this->trashed++;
				
			if ( ($this->display_status == 'trash' && !$is_trash) || ($this->display_status != 'trash' && $is_trash))
				unset($this->items[$key]);
		}
		$this->published -= $this->trashed;
	}
	
	function get_sortable_columns() {
		
		$sortable_columns = array(
				'id'  => array('id',true),
				'name' => array('name',true),
				'time'   => array('time',true)
		);
		
		return $sortable_columns;
	}
	
	function usort_reorder( $a, $b ) {
		
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
		
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		
		if ($orderby == 'id')
			$result = ( (int) $a[$orderby] - (int) $b[$orderby] );
		else
			$result = strcmp( $a[$orderby], $b[$orderby] );
		
		return ( $order === 'asc' ) ? $result : -$result;
	}
	
	function column_cb($item) {
		
		return sprintf('<input type="checkbox" name="itemid[]" value="%s" />', $item['id']);
	}
	
	function column_default( $item, $column_name ) 
	{
		$table_nonce = wp_create_nonce( 'wonderplugin-list-table-nonce' );
		
		$data = json_decode($item['data'], true);
		switch( $column_name ) {
			case 'id':
				if ($this->display_status == 'trash')
				{
					$actions = array(
						'restore' => sprintf('<a href="?page=%s&action=%s&itemid=%s&_wpnonce=%s">Restore</a>', $_REQUEST['page'], 'restore', $item['id'], $table_nonce),
						'delete' => sprintf('<a href="?page=%s&action=%s&itemid=%s&_wpnonce=%s">Delete Permanently</a>', $_REQUEST['page'], 'delete', $item['id'], $table_nonce)							
					);
				}
				else
				{
					$actions = array(
						'edit' => sprintf('<a href="?page=%s&itemid=%s">Edit</a>', 'wonderplugin_popup_edit_item', $item['id']),
						'clone' => sprintf('<a href="?page=%s&action=%s&itemid=%s&_wpnonce=%s">Clone</a>', $_REQUEST['page'], 'clone', $item['id'], $table_nonce),
						'trash' => sprintf('<a href="?page=%s&action=%s&itemid=%s&_wpnonce=%s">Trash</a>', $_REQUEST['page'], 'trash', $item['id'], $table_nonce),
						'view' => sprintf('<a href="?page=%s&itemid=%s">View</a>', 'wonderplugin_popup_show_item', $item['id'])							
					);
				}
				return sprintf('%1$s %2$s', $item['id'], $this->row_actions($actions) );
			case 'status':
				if ($data['status'])
				{
					$actions = array(
							'disable' => sprintf('<a href="?page=%s&action=%s&itemid=%s&_wpnonce=%s">Disable</a>', $_REQUEST['page'], 'disable', $item['id'], $table_nonce)
					);
					return sprintf('Active %s', $this->row_actions($actions) );
				}
				else
				{
					$actions = array(
							'enable' => sprintf('<a href="?page=%s&action=%s&itemid=%s&_wpnonce=%s">Enable</a>', $_REQUEST['page'], 'enable', $item['id'], $table_nonce)
					);
					return sprintf('<span style="color:#ff0000;">Paused</span> %s', $this->row_actions($actions) );
				}
			case 'type':
				if ($data['type'] == 'embed')
					return 'Embed';
				else if ($data['type'] == 'lightbox')
					return 'Lightbox';
				else if ($data['type'] == 'slidein')
					return 'Slide In';
				else if ($data['type'] == 'bar')
					return 'Notification Bar';
			case 'name':
			case 'time':
			default:
				return $item[ $column_name ];
		}
	}
	
	function get_bulk_actions() {
		
		if ($this->display_status == 'trash')
		{
			$actions = array(
				'delete' => 'Delete Permanently',
				'restore' => 'Restore'
			);
		}
		else
		{
			$actions = array(
				'trash' => 'Trash'
			);
		}
		return $actions;
	}

	function get_views(){
	
		$views = array();
		$current = ( !empty($_REQUEST['item_status']) ? $_REQUEST['item_status'] : 'all');
	
		// All
		$all_url = admin_url('admin.php?page=wonderplugin_popup_show_items');
		$class = ($current == 'all' ? ' class="current"' :'');
		$views['all'] = "<a href='" . $all_url . "' " . $class . " >All (" . $this->published . ")</a>";
	
		// Published
		$publish_url = admin_url('admin.php?page=wonderplugin_popup_show_items&item_status=publish');
		$class = ($current == 'publish' ? ' class="current"' :'');
		$views['publish'] = "<a href='" . $publish_url . "' " . $class . " >Published (" . $this->published . ")</a>";
	
		// Trash
		$trash_url = admin_url('admin.php?page=wonderplugin_popup_show_items&item_status=trash');
		$class = ($current == 'trash' ? ' class="current"' :'');
		$views['trash'] = "<a href='" . $trash_url . "' " . $class . " >Trash (" . $this->trashed . ")</a>";
	
		return $views;
	}
}