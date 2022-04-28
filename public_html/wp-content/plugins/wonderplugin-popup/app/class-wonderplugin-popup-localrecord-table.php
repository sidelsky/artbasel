<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
if( ! class_exists( 'WP_List_Table' ) )
{
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WonderPlugin_Popup_Localrecord_Table extends WP_List_Table {

	private $view, $columns, $data;
	
	public function __construct($view)
	{
		parent::__construct();
		
		$this->view = $view;
	}
	
	function set_data($columns, $data) 
	{
		$this->columns = $columns;
		$this->data = $data;
	}
	
	function get_columns()
	{
		$columns = array(
			'cb' => '<input type="checkbox" />'
		);
				
		foreach($this->columns as $column)
			$columns[$column] = $column;

		unset($columns['RECORDID']);
		
		return $columns;
	}
		
	function get_bulk_actions() {
	
		$actions = array(
				'delete' => 'Delete'
		);
		
		return $actions;
	}
	
	function prepare_items() 
	{		
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
								
		$this->items = $this->data;
	}
	
	function column_cb($item) {
		
		return sprintf('<input type="checkbox" name="itemid[]" value="%s" />', $item['RECORDID']);
	}
	
	function column_default( $item, $column_name ) {
		
		if (!isset($item[$column_name]))
			return '';
		
		if (strtolower($column_name) == 'email')
			return '<a href="mailto:' . $item[$column_name] . '">' . $item[$column_name] . '</a>';
		else
			return $item[$column_name];
	}
}