<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
if( ! class_exists( 'WP_List_Table' ) )
{
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WonderPlugin_Popup_Analytics_Table extends WP_List_Table {

	private $view;
	public $list_data;
	
	public function __construct($view)
	{
		parent::__construct();
		$this->view = $view;
	}
	
	function get_columns()
	{
		$columns = array(
				'popupid' 		=> __('Popup ID', 'wonderplugin_popup'),
				'popupname' 	=> __('Popup Name', 'wonderplugin_popup'),
				'status'		=> __('Local Analytics', 'wonderplugin_popup'),
				'showevent'		=> __('Show', 'wonderplugin_popup'),
				'actionevent'	=> __('Action', 'wonderplugin_popup'),
				'actionrate'	=> __('Action Rate', 'wonderplugin_popup')
		);
		return $columns;
	}
		
	function prepare_items() 
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		usort( $this->list_data, array( &$this, 'usort_reorder' ) );
		
		$this->items = $this->list_data;
	}
	
	function get_sortable_columns() {
	
		$sortable_columns = array(
				'popupid'		=> array('popupid',true),
				'popupname'  	=> array('popupname',true),
				'status'  		=> array('status',true),
				'showevent' 	=> array('showevent',true),
				'actionevent'   => array('actionevent',true),
				'actionrate'  	=> array('actionrate',true)
		);
	
		return $sortable_columns;
	}
	
	function usort_reorder( $a, $b ) {
	
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'popupid';
	
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
	
		if ($orderby == 'popupid' || $orderby == 'showevent' || $orderby == 'actionevent' || $orderby == 'actionrate')
			$result = ( (int) $a[$orderby] - (int) $b[$orderby] );
		else
			$result = strcmp( $a[$orderby], $b[$orderby] );
	
		return ( $order === 'asc' ) ? $result : -$result;
	}

	function column_default( $item, $column_name ) 
	{	
		if ($column_name == 'actionrate')
			return $item[$column_name] . '%';
		else if ($column_name == 'status' && $item[$column_name] == 'Disabled')
			return '<span style="color:#ff0000;">' . $item[$column_name] . '</span>';
		else
			return $item[$column_name];
	}
}