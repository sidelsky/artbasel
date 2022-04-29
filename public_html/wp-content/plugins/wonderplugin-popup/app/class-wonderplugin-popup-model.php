<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
require_once 'wonderplugin-popup-functions.php';

class WonderPlugin_Popup_Model {

	private $controller;
	
	function __construct($controller) {
		
		$this->controller = $controller;
	}
	
	function get_upload_path() {
		
		$uploads = wp_upload_dir();
		return $uploads['basedir'] . '/wonderplugin-popup/';
	}
	
	function get_upload_url() {
	
		$uploads = wp_upload_dir();
		return $uploads['baseurl'] . '/wonderplugin-popup/';
	}
	
	function search_replace_items($post)
	{
		$allitems = sanitize_text_field($_POST['allitems']);
		$itemid = sanitize_text_field($_POST['itemid']);

		$replace_list = array();
		for ($i = 0; ; $i++)
		{
			if (empty($post['standalonesearch' . $i]) || empty($post['standalonereplace' . $i]))
				break;

			$replace_list[] = array(
					'search' => str_replace('/', '\\/', sanitize_text_field($post['standalonesearch' . $i])),
					'replace' => str_replace('/', '\\/', sanitize_text_field($post['standalonereplace' . $i]))
			);
		}

		global $wpdb;

		if (!$this->is_db_table_exists())
			$this->create_db_table();

		$table_name = $wpdb->prefix . "wonderplugin_popup";

		$total = 0;

		foreach($replace_list as $replace)
		{
			$search = $replace['search'];
			$replace = $replace['replace'];

			if ($allitems)
			{
				$ret = $wpdb->query( $wpdb->prepare(
						"UPDATE $table_name SET data = REPLACE(data, %s, %s) WHERE INSTR(data, %s) > 0",
						$search,
						$replace,
						$search
				));
			}
			else
			{
				$ret = $wpdb->query( $wpdb->prepare(
						"UPDATE $table_name SET data = REPLACE(data, %s, %s) WHERE INSTR(data, %s) > 0 AND id = %d",
						$search,
						$replace,
						$search,
						$itemid
				));
			}

			if ($ret > $total)
				$total = $ret;
		}

		if (!$total)
		{
			return array(
					'success' => false,
					'message' => 'No popup modified' .  (isset($wpdb->lasterror) ? $wpdb->lasterror : '')
			);
		}

		return array(
				'success' => true,
				'message' => sprintf( _n( '%s popup', '%s popups', $total), $total) . ' modified'
		);
	}

	function xml_cdata( $str ) {
	
		if ( ! seems_utf8( $str ) ) {
			$str = utf8_encode( $str );
		}
	
		$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';
	
		return $str;
	}
	
	function replace_data($replace_list, $data)
	{
		foreach($replace_list as $replace)
		{
			$data = str_replace($replace['search'], $replace['replace'], $data);
		}
	
		return $data;
	}
	
	function import_popup($post, $files)
	{
		if (!isset($files['importxml']))
		{
			return array(
					'success' => false,
					'message' => 'No file or invalid file sent.'
			);
		}
	
		if (!empty($files['importxml']['error']))
		{
			$message = 'XML file error.';
	
			switch ($files['importxml']['error']) {
				case UPLOAD_ERR_NO_FILE:
					$message = 'No file sent.';
					break;
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$message = 'Exceeded filesize limit.';
					break;
			}
	
			return array(
					'success' => false,
					'message' => $message
			);
		}
	
		if ($files['importxml']['type'] != 'text/xml')
		{
			return array(
					'success' => false,
					'message' => 'Not an xml file'
			);
		}
	
		add_filter( 'wp_check_filetype_and_ext', 'wonderplugin_popup_wp_check_filetype_and_ext', 10, 4);
	
		$xmlfile = wp_handle_upload($files['importxml'], array(
				'test_form' => false,
				'mimes' => array('xml' => 'text/xml')
		));
	
		remove_filter( 'wp_check_filetype_and_ext', 'wonderplugin_popup_wp_check_filetype_and_ext');
	
		if ( empty($xmlfile) || !empty( $xmlfile['error'] ) ) {
			return array(
					'success' => false,
					'message' => (!empty($xmlfile) && !empty( $xmlfile['error'] )) ? $xmlfile['error']: 'Invalid xml file'
			);
		}
	
		$content = file_get_contents($xmlfile['file']);
	
		$xmlparser = xml_parser_create();
		xml_parse_into_struct($xmlparser, $content, $values, $index);
		xml_parser_free($xmlparser);
	
		if (empty($index) || empty($index['WONDERPLUGINPOPUP']) || empty($index['ID']))
		{
			return array(
					'success' => false,
					'message' => 'Not an exported xml file'
			);
		}
	
		$keepid = (!empty($post['keepid'])) ? true : false;
		$authorid = sanitize_text_field($post['authorid']);
	
		$replace_list = array();
		for ($i = 0; ; $i++)
		{
		if (empty($post['olddomain' . $i]) || empty($post['newdomain' . $i]))
			break;
	
			$replace_list[] = array(
					'search' => str_replace('/', '\\/', sanitize_text_field($post['olddomain' . $i])),
							'replace' => str_replace('/', '\\/', sanitize_text_field($post['newdomain' . $i]))
							);
		}
	
		$import_items = Array();
		foreach($index['ID'] as $key => $val)
		{
		$import_items[] = Array(
				'id' => ($keepid ? $values[$index['ID'][$key]]['value'] : 0),
						'name' => $values[$index['NAME'][$key]]['value'],
						'data' => $this->replace_data($replace_list, $values[$index['DATA'][$key]]['value']),
						'time' => $values[$index['TIME'][$key]]['value'],
						'authorid' => $authorid
				);
		}
	
		if (empty($import_items))
		{
		return array(
			'success' => false,
			'message' => 'No popup found'
			);
		}
	
		global $wpdb;
	
		if (!$this->is_db_table_exists())
			$this->create_db_table();
	
			$table_name = $wpdb->prefix . "wonderplugin_popup";
	
			$total = 0;
			foreach($import_items as $import_item)
			{
			$ret = $wpdb->query($wpdb->prepare(
					"
					INSERT INTO $table_name (id, name, data, time, authorid)
					VALUES (%d, %s, %s, %s, %s) ON DUPLICATE KEY UPDATE
					name=%s, data=%s, time=%s, authorid=%s
					",
					$import_item['id'], $import_item['name'], $import_item['data'], $import_item['time'], $import_item['authorid'],
					$import_item['name'], $import_item['data'], $import_item['time'], $import_item['authorid']
			));
	
					if ($ret)
			$total++;
		}
	
		if (!$total)
		{
					return array(
		'success' => false,
			'message' => 'No popup imported' .  (isset($wpdb->lasterror) ? $wpdb->lasterror : '')
			);
		}
	
			return array(
					'success' => true,
			'message' => sprintf( _n( '%s popup', '%s popups', $total), $total) . ' imported'
			);
	
	}
	
	function export_popup()
	{
	if ( !check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-export') || !isset($_POST['allpopup']) || !isset($_POST['popupid']) || !is_numeric($_POST['popupid']) )
	exit;
	
	$allpopup = sanitize_text_field($_POST['allpopup']);
	$popupid = sanitize_text_field($_POST['popupid']);
	
	if ($allpopup)
		$data = $this->get_list_data(true);
		else
			$data = array($this->get_list_item_data($popupid));
	
			header('Content-Description: File Transfer');
			header("Content-Disposition: attachment; filename=wonderplugin_popup_export.xml");
			header('Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true);
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Pragma: no-cache");
					header("Expires: 0");
					$output = fopen("php://output", "w");
	
			echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";
			echo "<WONDERPLUGINPOPUP>\r\n";
			foreach($data as $row)
					{
					if (empty($row))
					continue;
	
					echo "<ID>" . intval($row["id"]) . "</ID>\r\n";
					echo "<NAME>" . $this->xml_cdata($row["name"]) . "</NAME>\r\n";
					echo "<DATA>" . $this->xml_cdata($row["data"]) . "</DATA>\r\n";
					echo "<TIME>" . $this->xml_cdata($row["time"]) . "</TIME>\r\n";
					echo "<AUTHORID>" . $this->xml_cdata($row["authorid"]) . "</AUTHORID>\r\n";
					}
					echo '</WONDERPLUGINPOPUP>';
	
					fclose($output);
					exit;
	}
	
	function get_list_item_data($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		return $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) , ARRAY_A);
	}
	
	function is_localrecord_db_table_exists() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup_localrecord";
	
		return ( strtolower($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) == strtolower($table_name) );
	}
	
	function create_localrecord_db_table() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup_localrecord";
	
		$charset = '';
		if ( !empty($wpdb -> charset) )
			$charset = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE $wpdb->collate";
	
		$sql = "CREATE TABLE $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		popupid INT(11) DEFAULT 0 NOT NULL,
		data MEDIUMTEXT DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
		) $charset;";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	function init_localrecord_db_table() {
	
		if ( !$this->is_localrecord_db_table_exists() )
		{
			$this->create_localrecord_db_table();
			if ( !$this->is_localrecord_db_table_exists() )
				return false;
		}
	
		return true;
	}
	
	function save_to_local_and_email_notify($id, $data, $savetolocal, $emailnotify, $emailto, $emailsubject, $subscriberemail, $emailautoresponder, $emailautorespondersubject, $emailautorespondercontent) {
		
		if (!is_numeric($id))
		{
			return array(
					"success" => false,
					"message" => "Invalid ID"
			);
		}

		$result = array(
					"success" => true,
			);
		
		if ($savetolocal)
		{
			if ( !$this->init_localrecord_db_table() )
			{
				$result = array(
						"success" => false,
						"message" => "Cannot create the WordPress database table"
				);
			}
			
			global $wpdb;
			$table_name = $wpdb->prefix . "wonderplugin_popup_localrecord";
			
			$ret = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name (popupid, data) VALUES (%d, %s)", $id, json_encode($data)));
			
			if (!$ret)
			{
				$result = array(
						"success" => false,
						"message" => "Cannot update the WordPress database"
				);
			}
		}
		
		if ($emailnotify && is_email($emailto))
		{
			$message = '';
			foreach($data as $key => $value)
				$message .= "\r\n" . $key . ': ' . $value;
			
			add_action( 'phpmailer_init', array($this, 'configure_smtp') );
			
			$ret = wp_mail( $emailto, $emailsubject, $message );
			
			remove_action( 'phpmailer_init', array($this, 'configure_smtp') );
						
			if (!$ret)
			{
				$result['success'] = false;
				$result['message'] = (isset($result['message']) ? ($result['message'] . ' - ') : ''). 'Sending notification email failed';
			}
		}

		if ($emailautoresponder && is_email($subscriberemail))
		{
			$message = $emailautorespondercontent;
			
			add_action( 'phpmailer_init', array($this, 'configure_smtp') );
			
			$ret = wp_mail( $subscriberemail, $emailautorespondersubject, $message );
			
			remove_action( 'phpmailer_init', array($this, 'configure_smtp') );
						
			if (!$ret)
			{
				$result['success'] = false;
				$result['message'] = (isset($result['message']) ? ($result['message'] . ' - ') : ''). 'Sending autoresponder email failed';
			}
		}

		return $result;
		
	}
	
	function configure_smtp($phpmailer)
	{
		$settings = $this->get_settings();
		
		if ($settings['enablesmtp'] && !empty($settings['smtphostname']))
		{
			$phpmailer->isSMTP();
				
			$phpmailer->Host = $settings['smtphostname'];
			$phpmailer->Port = $settings['smtpport'];
				
			if ($settings['smtpsecure'] == 'tls' || $settings['smtpsecure'] == 'ssl')
				$phpmailer->SMTPSecure = $settings['smtpsecure'];
			else
				$phpmailer->SMTPSecure = false;
				
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $settings['smtpusername'];
			$phpmailer->Password = $settings['smtppassword'];
		}
		
		
		$phpmailer->From = $settings['emailfrom'];
		$phpmailer->FromName = $settings['emailfromname'];
	}
	
	function get_localrecord_data($id, $daterange, $customstart, $customend, $numperpage, $page, $nolimit) {

		$calc_range = $this->calc_date_range($daterange, $customstart, $customend);
		$datestart = $calc_range['datestart'];
		$dateend = $calc_range['dateend'];

		$start = strtotime($datestart);
		$end = strtotime($dateend);
		
		$limit = $numperpage;
		$offset = $numperpage * ($page - 1);
		
		if ($start == false || $start == -1 || $end == false || $end == -1)
		{
			return array(
					'success'	=> false,
					'message'	=> 'The date format is invalid'
			);
		}
		
		if ($start > $end)
		{
			return array(
					'success'	=> false,
					'message'	=> 'The end date is earlier than the start date'
			);
		}
				
		if ( !$this->init_localrecord_db_table() )
		{
			return array(
					"success" => false,
					"message" => "Cannot create the WordPress database table"
			);
		}
		
		if ( !$this->is_db_table_exists() )
		{
			return array(
					"success" => false,
					"message" => "Popup data table does not exist"
			);
		}
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		$localrecord_table_name = $wpdb->prefix . "wonderplugin_popup_localrecord";
						
		$start = date("Y-m-d G:i:s", $start);
		$end = date("Y-m-d G:i:s", $end + 24 * 3600);
		
		$count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $localrecord_table_name WHERE popupid = %d", $id) );
		$limitsql = '';
		
		if (!$nolimit)
		{
			if ($count < $offset)
				$offset = 0;
			
			if ($count > $limit)
			{
				if ($offset > 0)
					$limitsql = 'LIMIT ' . $offset . ',' . $limit;
				else
					$limitsql = 'LIMIT '. $limit;
			}
		}
				
		$items = $wpdb->get_results( $wpdb->prepare("SELECT id, time, data FROM $localrecord_table_name WHERE popupid = %d AND time >= %s AND time <= %s ORDER BY time DESC $limitsql", $id, $start, $end), ARRAY_N);
				
		if ( empty($items) )
		{
			return array(
					"success" => false,
					"message" => 'No record found. Please make sure you have enabled the option "Save to the Local Database of this WordPress Website" in the popup editor, Email Service tab.'
			);
		}
		
		$results = array();	
				
		$results['id'] = $id;
		$results['daterange'] = $daterange;
		$results['customstart'] = $customstart;
		$results['customend'] = $customend;
		$results['numperpage'] = $numperpage;
		$results['page'] = $page;
				
		$results['total'] = $count;
		$results['header'] = array('RECORDID', 'TIME');
		$data = json_decode($items[0][2], true);
		foreach($data as $key => $value)
			$results['header'][] = $key;
			
		$results['data'] = array();
		foreach ( $items as $item )
		{		
			$item_data = array('RECORDID' => $item[0], 'TIME' => $item[1]);	
			$data = json_decode($item[2], true);
			foreach($data as $key => $value)
				$item_data[$key] = $value;
			$results['data'][] = $item_data;
		}
				
		return array(
				'success'	=> true,
				'message'	=> 'Success',
				'result'	=> $results
			);
	}
	
	function calc_date_range($daterange, $custom_start, $custom_end) {
		
		$datestart = $custom_start;
		$dateend = $custom_end;
		
		switch ($daterange)
		{
			case 'thismonth':
				$datestart = date('Y-m-01');
				$dateend = date('Y-m-d');
				break;
			case 'lastmonth':
				$datestart = date("Y-m-d", strtotime("first day of previous month"));
				$dateend = date('Y-m-d', strtotime("last day of previous month"));
				break;
			case 'last30':
				$datestart = date("Y-m-d", strtotime("-30 days"));
				$dateend = date('Y-m-d');
				break;
			case 'custom':
				$datestart = $custom_start;
				$dateend = $custom_end;
				break;
		}
		
		return array(
				'datestart' => $datestart,
				'dateend' => $dateend
				);
	}
	
	function export_csv() {

		if ( !check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-localrecord') 
				|| !isset($_POST['wonderplugin-popup-savetocsvsubmit'])
				|| !isset($_POST['wonderplugin-popup-localrecordid'])
				|| !isset($_POST['wonderplugin-popup-localrecorddaterange']) )
			exit;
				
		$id = sanitize_text_field($_POST['wonderplugin-popup-localrecordid']);
		$daterange = sanitize_text_field($_POST['wonderplugin-popup-localrecorddaterange']);
		$customstart = (!empty($_POST['wonderplugin-popup-localrecorddatestart'])) ? sanitize_text_field($_POST['wonderplugin-popup-localrecorddatestart']) : date("Y-m-d", strtotime("-30 days"));
		$customend = (!empty($_POST['wonderplugin-popup-localrecorddateend'])) ? sanitize_text_field($_POST['wonderplugin-popup-localrecorddateend']) : date('Y-m-d');
		$numperpage = sanitize_text_field($_POST['wonderplugin-popup-numperpage']);
		$page = (isset($_POST['wonderplugin-popup-page'])) ? sanitize_text_field($_POST['wonderplugin-popup-page']) : 1;
				
		$ret = $this->get_localrecord_data($id, $daterange, $customstart, $customend, $numperpage, $page, true);
		if (empty($ret) || !$ret['success'] || empty($ret['result']))
		{
			wp_redirect( admin_url('admin.php?page=wonderplugin_popup_show_localrecord') );
			exit;
		}
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=wonderplugin_popup_" . $id . ".csv");
		header("Cache-Control: no-cache, no-store, must-revalidate"); 
		header("Pragma: no-cache"); 
		header("Expires: 0");
		$output = fopen("php://output", "w");
		fputcsv($output, $ret['result']['header']);
		for ($i = 0; $i < count($ret['result']['data']); $i++)
			fputcsv($output, $ret['result']['data'][$i]);
		fclose($output);
		
		exit;
	}
	
	function delete_localrecord_item($id) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup_localrecord";
		
		$ret = $wpdb->query( $wpdb->prepare(
				"
				DELETE FROM $table_name WHERE id=%s
				",
				$id
		) );
		
		return $ret;
	}
	
	function is_analytics_db_table_exists() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup_analytics";
	
		return ( strtolower($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) == strtolower($table_name) );
	}
	
	function create_analytics_db_table() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup_analytics";
	
		$charset = '';
		if ( !empty($wpdb -> charset) )
			$charset = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE $wpdb->collate";
		
		$sql = "CREATE TABLE $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		popupid INT(11) DEFAULT 0 NOT NULL,
		datestamp INT(11) DEFAULT 0 NOT NULL,
		showevent INT(11) DEFAULT 0 NOT NULL,
		actionevent INT(11) DEFAULT 0 NOT NULL,
		closeevent INT(11) DEFAULT 0 NOT NULL,
		cancelevent INT(11) DEFAULT 0 NOT NULL,
		PRIMARY KEY  (id)
		) $charset;";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	function init_analytics_db_table() {
	
		if ( !$this->is_analytics_db_table_exists() )
		{
			$this->create_analytics_db_table();
			if ( !$this->is_analytics_db_table_exists() )
				return false;
		}
	
		return true;
	}
	
	function log_analytics($post) {

		if (empty($post['id']) || !is_numeric($post['id'])  || empty($post['event']))
			return;
		
		$id = sanitize_text_field($post['id']);
		$event = sanitize_text_field($post['event']);
		$datestamp = floor(time() / 24 / 3600);
		
		if ( !$this->init_analytics_db_table() )
		{
			return array(
					"success" => false,
					"message" => "Cannot create analytics table"
			);
		}
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup_analytics";
		
		$ret = null;
		
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE popupid = %d AND datestamp = %d", $id, $datestamp) );
		if ($item_row != null)
		{
			switch ($event)
			{
				case 'show':
					$ret = $wpdb->query( $wpdb->prepare("UPDATE $table_name SET showevent = showevent + 1 WHERE popupid = %d AND datestamp = %d", $id, $datestamp));
					break;
				case 'action':
					$ret = $wpdb->query( $wpdb->prepare("UPDATE $table_name SET actionevent = actionevent + 1 WHERE popupid = %d AND datestamp = %d", $id, $datestamp));
					break;
			}
		}
		else
		{
			switch ($event)
			{
				case 'show':
					$ret = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name (popupid, datestamp, showevent, actionevent) VALUES (%d, %d, 1, 0)", $id, $datestamp));
					break;
				case 'action':
					$ret = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name (popupid, datestamp, showevent, actionevent) VALUES (%d, %d, 1, 1)", $id, $datestamp));
					break;
			}
		}
		
		if (!$ret)
		{
			return array(
					"success" => false,
					"message" => "Cannot update analytics"
			);
		}
		else
		{
			return array(
					"success" => true,
			);
		}
	}
	
	function get_analytics_data($datestart, $dateend) {

		$start = strtotime($datestart);
		$end = strtotime($dateend);
		if ($start == false || $start == -1 || $end == false || $end == -1)
		{
			return array(
					'success'	=> false,
					'message'	=> 'The date format is invalid'
			);
		}
		
		if ($start > $end)
		{
			return array(
					'success'	=> false,
					'message'	=> 'The end date is earlier than the start date'
			);
		}
		
		$start = $start / 24 / 3600;
		$end = $end / 24 / 3600;
		
		if ( !$this->init_analytics_db_table() )
		{
			return array(
					"success" => false,
					"message" => "Cannot create analytics table"
			);
		}
		
		if ( !$this->is_db_table_exists() )
		{
			return array(
					"success" => false,
					"message" => "Popup data table does not exist"
			);
		}
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		$analytics_table_name = $wpdb->prefix . "wonderplugin_popup_analytics";
		
		$items = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
	
		if ( !$items )
		{
			return array(
					"success" => false,
					"message" => "No popup defined"
			);
		}
		
		$results = array();
						
		foreach ( $items as $item )
		{
			
			$data = $wpdb->get_row( $wpdb->prepare("SELECT SUM(showevent), SUM(actionevent) FROM $analytics_table_name WHERE popupid = %d AND datestamp >= %d AND datestamp <= %d", $item['id'], $start, $end), ARRAY_N);
			
			if ($data)
			{
				$itemdata = json_decode($item['data'], true);				
				$results[] = array(
						"popupid"	=> $item['id'],
						"popupname"	=> $item['name'],
						"status"	=> $itemdata['enablelocalanalytics'],
						"showevent"	=> $data[0],
						"actionevent"	=> $data[1]
						);
			}
		}
		
		return array(
				'success'	=> true,
				'data'		=> $results
			);
	}
	
	function generate_body_code($id, $preview) {
				
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		if ( !$this->is_db_table_exists() )
		{
			return '<p>The specified popup does not exist.</p>';
		}
		
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );

		if ($item_row != null)
		{
			$data = $item_row->data;				
			$data = json_decode($data);	

			$sanitizehtmlcontent = get_option( 'wonderplugin_popup_sanitizehtmlcontent', 1 );
			if ($sanitizehtmlcontent == 1)
			{
				add_filter('safe_style_css', 'wonderplugin_popup_css_allow');
				add_filter('wp_kses_allowed_html', 'wonderplugin_popup_tags_allow', 'post');
				
				foreach($data as &$value)
				{
					if ( is_string($value) )
						$value = wp_kses_post($value);
				}
				
				remove_filter('wp_kses_allowed_html', 'wonderplugin_popup_tags_allow', 'post');
				remove_filter('safe_style_css', 'wonderplugin_popup_css_allow');
			}
			
			$bool_options = array('autoclose', 'uniquevideoiframeid', 'loggedinonly', 'status', 'fullscreen', 'overlayclose', 'showclose', 'closeshadow', 'showprivacy', 'showribbon', 'showclosetip', 'showgrecaptcha', 'showemail', 'showname', 'showfirstname', 'showlastname', 'showcompany', 'showphone', 'showzip', 'showaction', 'showcancel', 'cancelastext', 'videoautoplay', 'videoautoclose', 'videomuted', 'videocontrols', 'videonodownload',
				'showterms', 'termsrequired', 'showmessage', 'displayonpageload', 'displayonpagescrollpercent', 'displayonpagescrollpixels', 'displayonpagescrollcssselector', 'displayonuserinactivity', 'displayonclosepage', 'closeafterbutton', 'redirectafterbutton',
				'showprivacyconsent', 'privacyconsentrequired',
				'displayloading', 'displaydetailedmessage',
				'emailautoresponder', 'emailnotify', 'savetolocal', 'mailchimpdoubleoptin', 'icontactdoubleoptin', 'infusionsoftdoubleoptin', 'getresponseautoresponder', 'getresponsev3autoresponder',
				'mailpoet3sendconfirmationemail', 'mailpoet3schedulewelcomeemail',
				'enablegoogleanalytics', 'enablelocalanalytics');
			
			foreach ( $bool_options as $key )
			{				
				if (!isset($data->{$key}))
					$data->{$key} = 0;
			}
			
			$bool_options_1 = array('removeinlinecss');
			foreach ( $bool_options_1 as $key )
			{				
				if (!isset($data->{$key}))
					$data->{$key} = 1;
			}

			if ( ($data->status != 1 && !$preview) || ($data->loggedinonly && !is_user_logged_in()))
			{
				$ret = '';
			}
			else if ( isset($data->publish_status) && ($data->publish_status === 0) )
			{
				$ret = '';
			}
			else
			{
				$csscode = '';

				switch ($data->type)
				{
					case 'embed':
						$csscode .= '#wonderplugin-box-' . $id . ' {display:block;position:relative;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-container {position:relative;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bg {display: none;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-dialog {' .
								'width:' . $data->width . 'px;' .
								'max-width:' . $data->maxwidth . '%;' .
								'}';
						break;
					case 'lightbox':
						$csscode .= '#wonderplugin-box-' . $id . ' {display:none;position:fixed;left:0;top:0;right:0;bottom:0;z-index:9999999;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-container {position:absolute;top:0;left:0;width:100%;height:100%;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bg {' .
								'background-color:' . $data->overlaycolor . ';' .
								'opacity:' . $data->overlayopacity . ';' .
								'}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-dialog {' .
								'width:' . $data->width . 'px;' .
								'max-width:' . $data->maxwidth . '%;' .
								'}';
						break;
					case 'slidein':
						
						$poscss = 'right:0;bottom:0;';
						if (isset($data->slideinposition))
						{
							if ($data->slideinposition == 'bottom-left')
								$poscss = 'left:0;bottom:0;';
							else if ($data->slideinposition == 'bottom')
								$poscss = 'left:0;right:0;bottom:0;margin:0 auto;';
						}
						$csscode .= '#wonderplugin-box-' . $id . ' {display:none;position:fixed;max-width:100%;z-index:9999999;' . $poscss . '}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-container {position:relative;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bg {display: none;}';
						$csscode .= '#wonderplugin-box-' . $id . ' {' .
								'width:' . $data->width . 'px;' .
								'max-width:' . $data->maxwidth . '%;' .
								'}';
						break;
					case 'bar':
						$poscss = '';
						switch ($data->barposition)
						{
							case 'top':
								$poscss = 'top:0;';
								break;
							case 'bottom':
								$poscss = 'bottom:0;';
								break;
						}
						$csscode .= '#wonderplugin-box-' . $id . ' {display:none;position:fixed;width:100%;z-index:9999999;left:0;right:0;' . $poscss . '}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-container {position:relative;}';
						$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bg {display: none;}';
						break;
				}	
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-container {' .
					'padding-top:'  . $data->mintopbottommargin . 'px;' .
					'padding-bottom:' . $data->mintopbottommargin . 'px;' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-content {' .
					'border-radius:' . $data->radius . 'px;' .
					'box-shadow:' . $data->bordershadow . ';' .
					'background-color:' . $data->backgroundcolor . ';' .
					'background-image:url("' . $data->backgroundimage . '");' .
					'background-repeat:' . $data->backgroundimagerepeat . ';' .
					'background-position:' . $data->backgroundimageposition . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-top {' .
					'background-color:' . $data->backgroundtopcolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bottom {' .
					'background-color:' . $data->backgroundbottomcolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-left {' .
					'width:' . $data->leftwidth . '%;' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-right {' .
					'margin:0 0 0 ' . $data->leftwidth . '%;' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-heading {' .
					'color:' . $data->headingcolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-tagline {' .
					'color:' . $data->taglinecolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-description {' .
					'color:' . $data->descriptioncolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bulletedlist {' .
					'color:' . $data->bulletedlistcolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-privacy {' .
					'display:' . (($data->showprivacy) ? 'block' : 'none') . ';' .
					'color:' . $data->privacycolor . ';' .
				'}';
				
				$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-ribbon {' .
					'display:' . (($data->showribbon) ? 'block' : 'none') . ';' .
					$data->ribboncss .
				'}';
				
				if ($data->showcancel && $data->cancelastext)
				{
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-cancel {' .
							'color:' . $data->canceltextcolor . ';' .
						'}';
				}
				
				if ($data->showclose)
				{
					$closebuttoncss = 'display:block;';
					switch($data->closeposition) {
						case 'top-left-outside':
							$closebuttoncss = 'top:-14px;right:auto;bottom:auto;left:-14px;background-color:' . $data->closebackgroundcolor . ';';
							break;
						case 'top-left-inside':
							$closebuttoncss = 'top:0px;right:auto;bottom:auto;left:0px;';
							break;
						case 'top-right-outside':
							$closebuttoncss = 'top:-14px;right:-14px;bottom:auto;left:auto;background-color:' . $data->closebackgroundcolor . ';';
							break;
						default:
							$closebuttoncss = 'top:0px;right:0px;bottom:auto;left:auto;';
					}
					
					if ($data->closeshowshadow && ($data->closeposition == 'top-left-outside' || $data->closeposition == 'top-right-outside'))
						$closebuttoncss .= 'box-shadow:' . $data->closeshadow . ';';
					
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closebutton {' .
						$closebuttoncss .
					'}';
					
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closebutton {' .
						'color:' . $data->closecolor . ';' .
					'}';
					
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closebutton:hover {' .
						'color:' . $data->closehovercolor . ';' .
					'}';
					
					switch ($data->closeposition)
					{
						case 'top-left-inside':
							$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closetip {left:0;} #wonderplugin-box-' . $id . ' .wonderplugin-box-closetip:after {display:block;left:8px;}';
							break;
						case 'top-right-inside':
							$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closetip {right:0;} #wonderplugin-box-' . $id . ' .wonderplugin-box-closetip:after {display:block;right:8px;}';
							break;
						case 'top-left-outside':
							$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closetip {left:0;} #wonderplugin-box-' . $id . ' .wonderplugin-box-closetip:after {display:none;}';
							break;
						case 'top-right-outside':
							$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closetip {right:0;} #wonderplugin-box-' . $id . ' .wonderplugin-box-closetip:after {display:none;}';
							break;				
					}
				}
				else
				{
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closebutton {display:none;}';
				}
				
				$htmlcode = $data->template;
				$htmlcode = str_replace('__LOGO__', esc_url($data->logo), $htmlcode);
				$htmlcode = str_replace('__HEADING__', $data->heading, $htmlcode);
				$htmlcode = str_replace('__TAGLINE__', $data->tagline, $htmlcode);
				$htmlcode = str_replace('__DESCRIPTION__', $data->description, $htmlcode);
				$htmlcode = str_replace('__BULLETEDLIST__', $data->bulletedlist, $htmlcode);
				$htmlcode = str_replace('__PRIVACY__', $data->privacy, $htmlcode);
				$htmlcode = str_replace('__IMAGE__', esc_url($data->image), $htmlcode);
				$htmlcode = str_replace('__RIBBON__', esc_url($data->ribbon), $htmlcode);
				$htmlcode = str_replace('__CLOSETIP__', $data->closetip, $htmlcode);
				
				if (isset($data->customcontent))
					$htmlcode = str_replace('__CUSTOMCONTENT__', $data->customcontent, $htmlcode);
				
				if (empty($data->logo))
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-logo {display:none !important;}';

				if (empty($data->image))
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-image {display:none !important;}';
				
				$videocode = '';

				if (!empty($data->video))
				{
					$videosrc = $data->video;
					if ( strpos( strtolower($videosrc), 'youtube.com') !== false )
					{
						$videosrc .= ( (strpos($videosrc, '?') !== false) ? '&' : '?') . 'enablejsapi=1';

						if (isset($data->videomuted) && $data->videomuted)
						{
							$videosrc .= '&mute=1';
						}
					}	
					else if ( strpos( strtolower($videosrc), 'vimeo.com') !== false )
					{
						$videosrc .= ( (strpos($videosrc, '?') !== false) ? '&' : '?') . 'api=1&player_id=wonderplugin-box-videoiframe' . ((isset($data->uniquevideoiframeid) && $data->uniquevideoiframeid) ? ('-' . $id) : '');
						if (isset($data->videomuted) && $data->videomuted)
						{
							$videosrc .= '&muted=1';
						}
					}
					else if ( strpos( strtolower($videosrc), 'wistia') !== false )
					{
						if (isset($data->videomuted) && $data->videomuted)
						{
							$videosrc .= ( (strpos($videosrc, '?') !== false) ? '&' : '?') . 'muted=true';
						}
					}

					$videocode = '<iframe class="wonderplugin-box-videoiframe" id="wonderplugin-box-videoiframe' . ((isset($data->uniquevideoiframeid) && $data->uniquevideoiframeid) ? ('-' . $id) : '') . '" src="' . esc_url($videosrc) . '" allow="autoplay" frameborder="0" allowfullscreen></iframe>';					
				}
				else if (!empty($data->videohtml5))
				{
					$videocode = '<video src="' . $data->videohtml5 . '"' 
							. ( (isset($data->videomuted) && $data->videomuted) ? ' muted' : '')
							. ( (isset($data->videocontrols) && $data->videocontrols) ? ' controls' : '') 
							. ( (isset($data->videonodownload) && $data->videonodownload) ? ' controlsList="nodownload"' : '') 
							. ' style="width:100%;height:100%;" />';
				}

				$htmlcode = str_replace('__VIDEO__', $videocode, $htmlcode);
				
				$formcode = '<form class="wonderplugin-box-form"';
				
				if ( $data->afteraction == 'redirect' && !empty($data->redirecturl) )
				{
					$formcode .= ' action="' . esc_url($data->redirecturl) . '"';
					if ( $data->redirecturlpassparams == 'passget')
						$formcode .= ' method="get"';
					if ( $data->redirecturlpassparams == 'passpost')
						$formcode .= ' method="post"';
				}
				else if ( $data->afteraction == 'display' && $data->redirectafterbutton && !empty($data->redirectafterbuttonurl) )
				{
					$formcode .= ' action="' . esc_url($data->redirectafterbuttonurl) . '"';
					if ( $data->redirectafterbuttonpassparams == 'passget')
						$formcode .= ' method="get"';
					if ( $data->redirectafterbuttonpassparams == 'passpost')
						$formcode .= ' method="post"';
				}
					
				$formcode .= '>';
				
				if ($data->displayloading && !empty($data->loadingimage))
					$formcode .= '<div class="wonderplugin-box-formloading" style="display:none;"><img src="' . $data->loadingimage . '" /></div>';
				
				$formcode .= '<div class="wonderplugin-box-formmessage"></div>';
				
				$fieldorder = !empty($data->fieldorder) ? $data->fieldorder : 'email,name,firstname,lastname,company,phone,zip,message';
				$fieldorder = explode(',', $fieldorder);

				if (!empty($data->customfields))
				{
					try {
						$customfields = json_decode($data->customfields, true);
					} catch (Exception $e) {}
				}

				foreach($fieldorder as $field)
				{
					if (!empty($customfields) && substr($field, 0, 6) == 'custom')
					{
						$customid = substr($field, 6);
						if (!empty($customfields[$customid]))
						{
							$customitem = $customfields[$customid];
							
							if ($customitem['type'] == 'input')
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-' . $customitem['name']  . '" name="' . $customitem['name'] . '" placeholder="' . $customitem['placeholder'] . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-' . $customitem['name']  . ' { width:' . $customitem['size'] . 'px;' . '}';
							}
							else if ($customitem['type'] == 'select')
							{
								$formcode .= '<div class="wonderplugin-box-select wonderplugin-box-formbefore">';
								$formcode .= '<label class="wonderplugin-box-select-label">' . $customitem['caption'] . '</label>';
								$formcode .= '<select class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-' . $customitem['name']  . '" name="' . $customitem['name'] . '">';
								foreach($customitem['selections'] as $select)
								{
									$formcode .= '<option value="' . $select['value'] . '">' . $select['caption'] . '</option>';	
								}
								$formcode .= '</select>';
								$formcode .= '</div>';
							}
						}
					}

					switch ($field) {
						case 'email':
							if ($data->showemail)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-email" name="'. $data->emailfieldname . '" placeholder="' . $data->email . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-email { width:' . $data->emailinputwidth . 'px;' . '}';
							}
							break;
						case 'name':
							if ($data->showname)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-name" name="'. $data->namefieldname . '" placeholder="' . $data->name . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-name { width:' . $data->nameinputwidth . 'px;' . '}';
							}
							break;
						case 'firstname':
							if ($data->showfirstname)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-firstname" name="'. $data->firstnamefieldname . '" placeholder="' . $data->firstname . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-firstname { width:' . $data->firstnameinputwidth . 'px;' . '}';
							}
							break;
						case 'lastname':
							if ($data->showlastname)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-lastname" name="'. $data->lastnamefieldname . '" placeholder="' . $data->lastname . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-lastname { width:' . $data->lastnameinputwidth . 'px;' . '}';
							}
							break;
						case 'company':
							if ($data->showcompany)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-company" name="'. $data->companyfieldname . '" placeholder="' . $data->company . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-company { width:' . $data->companyinputwidth . 'px;' . '}';
							}
							break;
						case 'phone':
							if ($data->showphone)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-phone" name="'. $data->phonefieldname . '" placeholder="' . $data->phone . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-phone { width:' . $data->phoneinputwidth . 'px;' . '}';
							}
							break;
						case 'zip':
							if ($data->showzip)
							{
								$formcode .= '<input type="text" class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-zip" name="'. $data->zipfieldname . '" placeholder="' . $data->zip . '">';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-zip { width:' . $data->zipinputwidth . 'px;' . '}';
							}
							break;
						case 'message':
							if ($data->showmessage)
							{
								$formcode .= '<textarea class="wonderplugin-box-formdata wonderplugin-box-formrequired wonderplugin-box-formbefore wonderplugin-box-message" name="'. $data->messagefieldname . '" placeholder="' . $data->message . '"></textarea>';
								$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-message { width:' . $data->messageinputwidth . 'px;height:' . $data->messageinputheight . 'px;' . '}';
							}
							break;
					}
				}
				
				if ($data->subscription == 'mailchimp' && !empty($data->mailchimplists) && isset($data->mailchimplistid))
				{
					$mailchimp_lists = json_decode($data->mailchimplists, true);
							
					if (!empty($mailchimp_lists))
					{
						foreach($mailchimp_lists as $list)
						{
							if ($data->mailchimplistid == $list['id'])
							{
								$selected_list = $list;
							}
						}
					}
										
					if (!empty($selected_list) && !empty($selected_list['groups']))
					{
						foreach($selected_list['groups'] as $index => $group)
						{
							$type = isset($data->{'mailchimpgroupoption_' . $index}) ? $data->{'mailchimpgroupoption_' . $index} : $group['type'];
							
							$formcode .= '<div' . (($type == 'hidden') ? ' style="display:none;"' : ' class="wonderplugin-box-formbefore wonderplugin-box-mailchimp-group"') . '>';
							
							if ($type == 'checkboxes')
							{
								$formcode .= '<label class="wonderplugin-box-mailchimp-interest-title">' . $group['title'] . '</label>';
								
								foreach($group['interests'] as $interest)
								{
									$formcode .= '<label class="wonderplugin-box-mailchimp-interest-checkbox"><input type="checkbox" class="wonderplugin-box-formdata" name="MAILCHIMPINTEREST_CHECKBOXES_' .$index . '_' . $interest['id'] . '">' . $interest['name'] . '</label>';
								}
							}
							else if ($type == 'radio')
							{
								$formcode .= '<label class="wonderplugin-box-mailchimp-interest-title">' . $group['title'] . '</label>';
								
								foreach($group['interests'] as $interest)
								{
									$formcode .= '<label class="wonderplugin-box-mailchimp-interest-radio"><input type="radio" class="wonderplugin-box-formdata" name="MAILCHIMPINTEREST_RADIO_' . $index . '" value="' . $interest['id'] . '">' . $interest['name'] . '</label>';
								}
							}
							else if ($type == 'dropdown')
							{
								$formcode .= '<label class="wonderplugin-box-mailchimp-interest-title">' . $group['title'] . '</label>';
								$formcode .= '<select class="wonderplugin-box-formdata wonderplugin-box-mailchimp-interest-dropdown" name="MAILCHIMPINTEREST_DROPDOWN_' . $index . '">';
								foreach($group['interests'] as $interest)
								{
									$formcode .= '<option value="' . $interest['id'] . '">' . $interest['name']  . '</option>';
								}
								$formcode .= '</select>';
							}
							else if ($type == 'hidden')
							{
								if (isset($data->{'mailchimpdefaultgroup_' . $index}) && $data->{'mailchimpdefaultgroup_' . $index} == '1')
								{
									foreach($group['interests'] as $interest)
									{
										if (isset($data->{'mailchimpinterest_' . $index . '_' . $interest['id']}) && $data->{'mailchimpinterest_' . $index . '_' . $interest['id']} == '1')
										{
											$formcode .= '<label><input type="checkbox" checked class="wonderplugin-box-formdata" name="MAILCHIMPINTEREST_CHECKBOXES_' .$index . '_' . $interest['id'] . '">' . $interest['name'] . '</label>';
										}
										
									}
								}
							}	

							$formcode .= '</div>';
						}
					}
				}
								
				
				
				if ($data->showterms)
				{
					$formcode .= '<label class="wonderplugin-box-formbefore wonderplugin-box-label-terms"><input type="checkbox" class="wonderplugin-box-formdata wonderplugin-box-terms wonderplugin-box-formrequired" name="'. $data->termsfieldname . '">' . $data->terms . '</label>';
				}
				
				if ($data->showprivacyconsent)
				{
					$formcode .= '<label class="wonderplugin-box-formbefore wonderplugin-box-label-privacyconsent"><input type="checkbox" class="wonderplugin-box-formdata wonderplugin-box-privacyconsent wonderplugin-box-formrequired" name="'. $data->privacyconsentfieldname . '">' . $data->privacyconsent . '</label>';
				}
								
				if ($data->showgrecaptcha && !empty($data->grecaptchasitekey) && !empty($data->grecaptchasecretkey))
				{
					$formcode .= '<div class="wonderplugin-box-recaptcha-container wonderplugin-box-formbefore"><div class="wonderplugin-box-recaptcha g-recaptcha" data-sitekey="' . $data->grecaptchasitekey . '"></div></div>';
				}
				
				if ($data->showaction)
					$formcode .= '<input type="' . ( ($data->afteraction == 'redirect' && !empty($data->redirecturl)) ? 'submit' : 'button') . '" class="wonderplugin-box-formbefore ' . $data->actioncss . ' wonderplugin-box-action" name="wonderplugin-box-action" value="' . $data->action . '">';
				
				if ($data->showcancel)
				{
					if ($data->cancelastext)
						$formcode .= '<div class="wonderplugin-box-formbefore wonderplugin-box-cancel">' . $data->cancel . '</div>';
					else
						$formcode .= '<input type="button" class="wonderplugin-box-formbefore ' . $data->cancelcss . ' wonderplugin-box-cancel" name="wonderplugin-box-cancel" value="' . $data->cancel . '">';
				}
				
				if ($data->afteraction == 'display')
				{	
					if (!empty($data->afteractionmessage))
						$formcode .= '<div class="wonderplugin-box-formafter wonderplugin-box-afteractionmessage">' . $data->afteractionmessage . '</div>';
					if (!empty($data->afteractionbutton))
						$formcode .= '<input type="' . ( ($data->redirectafterbutton && !empty($data->redirectafterbuttonurl)) ? 'submit' : 'button') . '" class="wonderplugin-box-formafter ' . $data->actioncss . ' wonderplugin-box-afteractionbutton" name="wonderplugin-box-afteractionbutton" value="' . $data->afteractionbutton . '">';
				}
				
				$formcode .= '</form>';
				
				$htmlcode = str_replace('__FORM__', $formcode, $htmlcode);
				
				$dataoptions = '';
				
				$dataoptions .= ' data-type="' . $data->type . '"';
				
				$dataoptions .= ' data-width="' . $data->width . '" data-maxwidth="' . $data->maxwidth . '"';
				if (isset($data->slideinposition))
					$dataoptions .= ' data-slideinposition="' . $data->slideinposition . '"';
				
				if ($data->type == 'bar')
					$dataoptions .= ' data-barposition="' . $data->barposition . '" data-barfloat=' . ((isset($data->barfloat) && $data->barfloat) ? '1' : '0');
				
				if (isset($data->hidebarstyle) && $data->hidebarstyle == 'textbar')
				{
					$dataoptions .= ' data-hidebarstyle="textbar" data-hidebartitle="' . $data->hidebartitle . '" data-hidebarbgcolor="' . $data->hidebarbgcolor . '" data-hidebarcolor="' . $data->hidebarcolor . '" data-hidebarwidth="' . $data->hidebarwidth . '" data-hidebarpos="' . $data->hidebarpos . '"';
					$dataoptions .= ' data-hidebarnotshowafteraction=' . ((isset($data->hidebarnotshowafteraction) && $data->hidebarnotshowafteraction) ? '1' : '0');
				}
				// close on overlay
				if ($data->overlayclose)
					$dataoptions .= ' data-overlayclose=1';
				
				// show close tooltip
				if ($data->showclosetip)
					$dataoptions .= ' data-showclosetip=1';
				
				// retargeting				
				if (isset($data->enableretarget) && !$data->enableretarget)
					$dataoptions .= ' data-enableretarget=0';
				else
					$dataoptions .= ' data-enableretarget=1';
				 
				$dataoptions .= ' data-retargetnoshowaction=' . $data->retargetnoshowaction . ' data-retargetnoshowactionunit="' . $data->retargetnoshowactionunit . '"';
				$dataoptions .= ' data-retargetnoshowclose=' . $data->retargetnoshowclose . ' data-retargetnoshowcloseunit="' . $data->retargetnoshowcloseunit . '"';
				
				// google recaptcha
				if ($data->showgrecaptcha && !empty($data->grecaptchasitekey) && !empty($data->grecaptchasecretkey))
					$dataoptions .= ' data-showgrecaptcha=1';
				
				// engine folder
				$dataoptions .= ' data-pluginfolder="' . WONDERPLUGIN_POPUP_URL . '"';
				
				// video on close
				if ($data->videoautoclose)
					$dataoptions .= ' data-videoautoclose=1';
					
				if ($data->videoautoplay)
					$dataoptions .= ' data-videoautoplay=1';
				
				if ( isset($data->videomuted) && $data->videomuted)
					$dataoptions .= ' data-videomuted=1';

				if ( isset($data->videocontrols) && $data->videocontrols)
					$dataoptions .= ' data-videocontrols=1';

				if ( isset($data->videonodownload) && $data->videonodownload)
					$dataoptions .= ' data-videonodownload=1';
				
				$dataoptions .= ' data-uniquevideoiframeid=' . ((isset($data->uniquevideoiframeid) && $data->uniquevideoiframeid) ? '1' : '0');
				
				// animation
				if ( $data->type != 'embed' )
				{
					$dataoptions .= ' data-retargetnoshowcancel=' . $data->retargetnoshowcancel . ' data-retargetnoshowcancelunit="' . $data->retargetnoshowcancelunit . '"';
					
					$dataoptions .= ' data-inanimation="' . $data->inanimation . '" data-outanimation="' . $data->outanimation . '"';
				
					// display time
					if ($preview)
					{
						$dataoptions .= ' data-displayonpageload=1 data-displaydelay="0"';
					}
					else
					{
						if ($data->displayonpageload)
							$dataoptions .= ' data-displayonpageload=1 data-displaydelay="' . $data->displaydelay . '"';
						
						if ($data->displayonpagescrollpercent)
							$dataoptions .= ' data-displayonpagescrollpercent=1 data-displaypercent="' . $data->displaypercent . '"';
						
						if ($data->displayonpagescrollpixels)
							$dataoptions .= ' data-displayonpagescrollpixels=1 data-displaypixels="' . $data->displaypixels . '"';
						
						if ($data->displayonpagescrollcssselector)
							$dataoptions .= ' data-displayonpagescrollcssselector=1 data-displaycssselector="' . $data->displaycssselector . '"';
						
						if ($data->displayonuserinactivity)
							$dataoptions .= ' data-displayonuserinactivity=1 data-displayinactivity="' . $data->displayinactivity . '"';
						
						if ($data->displayonclosepage)
							$dataoptions .= ' data-displayonclosepage=1 data-displaysensitivity="' . $data->displaysensitivity . '"';
					}

					if ($data->autoclose)
						$dataoptions .= ' data-autoclose=1 data-autoclosedelay="' . $data->autoclosedelay . '"';
					
					// client side rules	
					$dataoptions .= ' data-devicerules=\'' . $data->displaydevicerules . '\'';
				}
				
				// email service
				$dataoptions .= ' data-subscription="' . $data->subscription . '" data-savetolocal=' . ((isset($data->savetolocal) && $data->savetolocal) ? '1' : '0');
				$dataoptions .= ' data-emailnotify=' . ((isset($data->emailnotify) && $data->emailnotify) ? '1' : '0');
				$dataoptions .= ' data-emailautoresponder=' . ((isset($data->emailautoresponder) && $data->emailautoresponder) ? '1' : '0');
				
				// after action
				$dataoptions .= ' data-afteraction="' . $data->afteraction . '"';
				
				if ($data->afteraction == 'display' && !empty($data->afteractionbutton) && $data->closeafterbutton)
					$dataoptions .= ' data-closeafterbutton=1';
				
				// error message
				$dataoptions .= ' data-invalidemailmessage="' . esc_html($data->invalidemailmessage) . '"';
				$dataoptions .= ' data-fieldmissingmessage="' . esc_html($data->fieldmissingmessage) . '"';
				$dataoptions .= ' data-alreadysubscribedmessage="' . esc_html($data->alreadysubscribedmessage) . '"';
				if (isset($data->alreadysubscribedandupdatedmessage))
					$dataoptions .= ' data-alreadysubscribedandupdatedmessage="' . esc_html($data->alreadysubscribedandupdatedmessage) . '"';
				$dataoptions .= ' data-generalerrormessage="' . esc_html($data->generalerrormessage) . '"';
				
				if (isset($data->termsnotcheckedmessage))
					$dataoptions .= ' data-termsnotcheckedmessage="' . esc_html($data->termsnotcheckedmessage) . '"';
				
				if (isset($data->privacyconsentnotcheckedmessage))
					$dataoptions .= ' data-privacyconsentnotcheckedmessage="' . esc_html($data->privacyconsentnotcheckedmessage) . '"';
				
				if ($data->displaydetailedmessage)
					$dataoptions .= ' data-displaydetailedmessage=1';
				
				// google analytics
				if ($data->enablegoogleanalytics && !empty($data->gaid))
					$dataoptions .= ' data-gaid="' . esc_html($data->gaid) . '" data-gaeventcategory="' . esc_html($data->gaeventcategory) . '" data-gaeventlabel="' . esc_html($data->gaeventlabel) . '"';
				
				if ($data->enablelocalanalytics)
					$dataoptions .= ' data-enablelocalanalytics=1';
	
				$csscode .= $data->css;
				
				// fullscreen mode css
				if ($data->fullscreen)
				{
					$csscode .= '@media (max-width: ' . $data->fullscreenwidth . 'px) {';
					
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-bg {' .
								'border-radius: 0;' .
								'box-shadow: none;' .
								'background-color:' . $data->backgroundcolor . ';' .
								'background-image:url("' . $data->backgroundimage . '");' .
								'background-repeat:' . $data->backgroundimagerepeat . ';' .
								'background-position:' . $data->backgroundimageposition . ';' .
								'opacity:1;' .
							'}';
						
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-content {' .
								'border-radius: 0;' .
								'box-shadow: none;' .
								'background-color: transparent;' .
								'background-image: none;' .
							'}';
					
	
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-closebutton {display:none;}';
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-fullscreenclosebutton {display:block;}';
					
					$csscode .= '#wonderplugin-box-' . $id . ' .wonderplugin-box-ribbon {display:none;}';
					
					$csscode .= '}';
				}
				
				$ret = '';
				$csscode = str_replace('POPUPID', $id, $csscode);
				$csscode = str_replace(array("\r", "\n", "\t"), " ", $csscode);

				if ( !isset($data->removeinlinecss) || $data->removeinlinecss)
				{
					$ret .= '<script>function wonderpopup_' . $id . '_appendcss(csscode) {var head=document.head || document.getElementsByTagName("head")[0];var style=document.createElement("style");head.appendChild(style);style.type="text/css";if (style.styleSheet){style.styleSheet.cssText=csscode;} else {style.appendChild(document.createTextNode(csscode));}};</script>';
					$csscode = str_replace(array('\\', '"'), array('\\\\', '\"'), $csscode);
					$ret .= '<script>wonderpopup_' . $id . '_appendcss("' . $csscode  . '");</script>';
				}
				else
				{
					$ret .= '<style>' . $csscode . '</style>';
				}
				
				if (isset($data->customcss) && strlen($data->customcss) > 0)
				{
					$customcss = str_replace("\r", " ", $data->customcss);
					$customcss = str_replace("\n", " ", $customcss);
					$customcss = str_replace("POPUPID", $id, $customcss);
					if ( !isset($data->removeinlinecss) || $data->removeinlinecss)
					{
						$customcss = str_replace(array('\\', '"'), array('\\\\', '\"'), $customcss);
						$ret .= '<script>wonderpopup_' . $id . '_appendcss("' . $customcss  . '");</script>';
					}
					else
					{
						$ret .= '<style>' . $customcss . '</style>';
					}
				}
				
				$ret .= '<div class="wonderplugin-box" id="wonderplugin-box-' . $id . '" data-popupid=' . $id;
				if (isset($data->dataoptions) && strlen($data->dataoptions) > 0)
				{
					$ret .= ' ' . stripslashes($data->dataoptions);
				}
				$ret .= $dataoptions;
				$ret .= '>';
				$ret .= do_shortcode($htmlcode);
				
				if ('C' == 'F')
					$ret .= '<div class="wonderplugin-popup-engine"><a href="https://www.wonderplugin.com/wordpress-popup/" target="_blank" title="'. get_option('wonderplugin-popup-engine')  .'">' . get_option('wonderplugin-popup-engine') . '</a></div>';
								
				$ret .= '</div>';	
				
				if (isset($data->customjs) && strlen($data->customjs) > 0)
				{
					$customjs = str_replace("\r", " ", $data->customjs);
					$customjs = str_replace("\n", " ", $customjs);
					$customjs = str_replace('&lt;',  '<', $customjs);
					$customjs = str_replace('&gt;',  '>', $customjs);
					$customjs = str_replace("POPUPID", $id, $customjs);
					$ret .= '<script>' . $customjs . '</script>';
				}
			}
		}
		else
		{
			$ret = '<div class="wonderplugin-box" id="wonderplugin-box-' . $id . '" data-popupid=' . $id . '><p>The specified popup id does not exist.</p></div>';
		}
		
		return $ret;
	}
	
	function check_lang_rules($id, $data) {

		if ($data->status != 1)
			return false;

		if (!$this->controller->multilingual || empty($data->displaylangrules))
			return true;

		$enable = false;
		
		$rules = json_decode($data->displaylangrules);
		foreach($rules as $rule)
		{
			if ($rule->rule == "alllangs" || $rule->rule == $this->controller->currentlang)
			{
				$enable = ($rule->action == 1);	
				if ($rule->action != 1)
					break;
			}	
		}

		return $enable;
	}

	function check_display_rules($id, $data) {
		
		if ($data->status != 1)
			return false;
			
				
		// page rules
		$enable = false;
		$rules = json_decode($data->displaypagerules);
				
		foreach($rules as $rule)
		{
			$match = false;
			switch ($rule->rule)
			{
				case "allpagesposts":
					$match = true;
					break;
				case "allpages":
					$match = is_page();
					break;
				case "allposts":
					$match = is_single();
					break;
				case "page":
					$match = is_page( $rule->param0 );
					break;
				case "postcategory":
					$match = ( is_category( $rule->param0 ) || in_category( $rule->param0 ) );
					break;
				case "customposttypes":
					$match = is_singular( $rule->param0 );
					break;
				case "homepage":
					$match = is_front_page();
					break;
				case "urlmatch":
					
					$s = empty($_SERVER["HTTPS"]) ? "" : (($_SERVER["HTTPS"] == "on") ? "s" : ""); 
					$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
					$pageurl = strtolower( "http" . $s . "://" . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'] );
					
					if ($rule->param0 == 0)
						$match = (strpos( $pageurl, strtolower($rule->param1) ) !== false);
					else if ($rule->param0 == 1)
						$match = (strpos( $pageurl, strtolower($rule->param1) ) === false);
					else if ($rule->param0 == 2)
						$match = fnmatch($rule->param1, $pageurl);
					break;
			}			
			
			if ($match)
			{
				$enable = ($rule->action == 1);				
				if ($rule->action != 1)
					break;
			}
		}
						
		return $enable;
	}
	
	function add_popup_to_page() {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		if ( !$this->is_db_table_exists() )
			return;
		
		$rows = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
		
		$code = '';
		
		foreach ($rows as $row)
		{
			$data = json_decode($row['data']);
			if ( $this->check_display_rules($row['id'], $data) && $this->check_lang_rules($row['id'], $data))
			{
				$code .= $this->generate_body_code($row['id'], false);
			}
		}
		
		return $code;
	}
	
	function delete_item($id) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		$ret = $wpdb->query( $wpdb->prepare(
				"
				DELETE FROM $table_name WHERE id=%s
				",
				$id
		) );
		
		return $ret;
	}
	
	function trash_item($id) {
	
		return $this->set_item_publish_status($id, 0);
	}
	
	function restore_item($id) {
	
		return $this->set_item_publish_status($id, 1);
	}
	
	function set_item_publish_status($id, $status) {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
	
		$ret = false;
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$data = json_decode($item_row->data, true);
			$data['publish_status'] = $status;
			$data = json_encode($data);
	
			$update_ret = $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET data=%s WHERE id=%d", $data, $id ) );
			if ( $update_ret )
				$ret = true;
		}
	
		return $ret;
	}
	
	function clone_item($id) {
	
		global $wpdb, $user_ID;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		$cloned_id = -1;
		
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$time = current_time('mysql');
			$authorid = $user_ID;
			
			$ret = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO $table_name (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					",
					$item_row->name . " Copy",
					$item_row->data,
					$time,
					$authorid
			) );
				
			if ($ret)
				$cloned_id = $wpdb->insert_id;
		}
	
		return $cloned_id;
	}
	
	function disable_item($id)
	{
		return $this->set_item_status($id, 0);
	}
	
	function enable_item($id)
	{
		return $this->set_item_status($id, 1);
	}
	
	function set_item_status($id, $status) {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
	
		$ret = false;
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$data = json_decode($item_row->data, true);
			$data['status'] = $status;
			$data = json_encode($data);
			
			$update_ret = $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET data=%s WHERE id=%d", $data, $id ) );
			if ( $update_ret )
				$ret = true;
		}
	
		return $ret;
	}
	
	function is_db_table_exists() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
	
		return ( strtolower($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) == strtolower($table_name) );
	}
	
	function is_id_exist($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
	
		$popup_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );		
		return ($popup_row != null);
	}
	
	function create_db_table() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		$charset = '';
		if ( !empty($wpdb -> charset) )
			$charset = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE $wpdb->collate";
	
		$sql = "CREATE TABLE $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		name tinytext DEFAULT '' NOT NULL,
		data MEDIUMTEXT DEFAULT '' NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		authorid tinytext NOT NULL,
		PRIMARY KEY  (id)
		) $charset;";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	function save_item($item) {
		
		global $wpdb, $user_ID;
		
		if ( !$this->is_db_table_exists() )
		{
			$this->create_db_table();
		
			$create_error = "CREATE DB TABLE - ". $wpdb->last_error;
			if ( !$this->is_db_table_exists() )
			{
				return array(
						"success" => false,
						"id" => -1,
						"message" => $create_error
				);
			}
		}
		
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		$id = $item["id"];
		$name = $item["popupname"];
		
		unset($item["id"]);
		$data = json_encode($item);
		
		if ( empty($data) )
		{
			$json_error = "json_encode error";
			if ( function_exists('json_last_error_msg') )
				$json_error .= ' - ' . json_last_error_msg();
			else if ( function_exists('json_last_error') )
				$json_error .= 'code - ' . json_last_error();
		
			return array(
					"success" => false,
					"id" => -1,
					"message" => $json_error
			);
		}
		
		$time = current_time('mysql');
		$authorid = $user_ID;
		
		if ( ($id > 0) && $this->is_id_exist($id) )
		{
			$ret = $wpdb->query( $wpdb->prepare(
					"
					UPDATE $table_name
					SET name=%s, data=%s, time=%s, authorid=%s
					WHERE id=%d
					",
					$name,
					$data,
					$time,
					$authorid,
					$id
			) );
			
			if (!$ret)
			{
				return array(
						"success" => false,
						"id" => $id, 
						"message" => "UPDATE - ". $wpdb->last_error
					);
			}
		}
		else
		{
			$ret = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO $table_name (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					",
					$name,
					$data,
					$time,
					$authorid
			) );
			
			if (!$ret)
			{
				return array(
						"success" => false,
						"id" => -1,
						"message" => "INSERT - " . $wpdb->last_error
				);
			}
			
			$id = $wpdb->insert_id;
		}
		
		return array(
				"success" => true,
				"id" => intval($id),
				"message" => "Popup published!"
		);
	}
	
	function get_list_data() {
		
		if ( !$this->is_db_table_exists() )
			$this->create_db_table();
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
		
		$rows = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
		
		$ret = array();
		
		if ( $rows )
		{
			foreach ( $rows as $row )
			{
				$ret[] = array(
							"id" => $row['id'],
							'name' => $row['name'],
							'data' => $row['data'],
							'time' => $row['time'],
							'authorid' => $row['authorid']
						);
			}
		}
	
		return $ret;
	}
	
	function get_item_data($id)
	{
		if ( !$this->is_db_table_exists() )
			return null;
			
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_popup";
	
		$ret = "";
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$ret = $item_row->data;
		}

		$ret = json_decode($ret, true);
		
		return $ret;
	}
	
	
	function get_settings() {
	
		$userrole = get_option( 'wonderplugin_popup_userrole' );
		if ( $userrole == false )
		{
			update_option( 'wonderplugin_popup_userrole', 'manage_options' );
			$userrole = 'manage_options';
		}
		
		$keepdata = get_option( 'wonderplugin_popup_keepdata', 1 );
		
		$disableupdate = get_option( 'wonderplugin_popup_disableupdate', 0 );
		
		$supportwidget = get_option( 'wonderplugin_popup_supportwidget', 1 );
		
		$addjstofooter = get_option( 'wonderplugin_popup_addjstofooter', 0 );
		
		$ajaxverifynonce = get_option( 'wonderplugin_popup_ajaxverifynonce', 0 );
		
		$sanitizehtmlcontent = get_option( 'wonderplugin_popup_sanitizehtmlcontent', 1 );
		
		$emailfrom = get_option( 'wonderplugin_popup_emailfrom', get_bloginfo('admin_email') );
		
		$emailfromname = get_option( 'wonderplugin_popup_emailfromname', get_bloginfo('name') );
		
		$enablesmtp = get_option( 'wonderplugin_popup_enablesmtp', 0 );
		$smtphostname = get_option( 'wonderplugin_popup_smtphostname', '' );
		$smtpport = get_option( 'wonderplugin_popup_smtpport', 25 );
		$smtpsecure = get_option( 'wonderplugin_popup_smtpsecure', 'no' );
		$smtpusername = get_option( 'wonderplugin_popup_smtpusername', '' );
		$smtppassword = get_option( 'wonderplugin_popup_smtppassword', '' );
		
		$settings = array(
				"userrole" => $userrole,
				"keepdata" => $keepdata,
				"disableupdate" => $disableupdate,
				"supportwidget" => $supportwidget,
				"addjstofooter" => $addjstofooter,
				"ajaxverifynonce" => $ajaxverifynonce,
				"sanitizehtmlcontent" => $sanitizehtmlcontent,
				"emailfrom" => $emailfrom,
				"emailfromname" => $emailfromname,
				"enablesmtp" => $enablesmtp,
				"smtphostname" => $smtphostname,
				"smtpport" => $smtpport,
				"smtpsecure" => $smtpsecure,
				"smtpusername" => $smtpusername,
				"smtppassword" => $smtppassword
				
		);
		
		return $settings;		
	}
	
	function save_settings($options) {
	
		if (!isset($options) || !isset($options['userrole']))
			$userrole = 'manage_options';
		else if ( $options['userrole'] == "Editor")
			$userrole = 'moderate_comments';
		else if ( $options['userrole'] == "Author")
			$userrole = 'upload_files';
		else
			$userrole = 'manage_options';
		update_option( 'wonderplugin_popup_userrole', $userrole );
		
		if (!isset($options) || !isset($options['keepdata']))
			$keepdata = 0;
		else
			$keepdata = 1;
		update_option( 'wonderplugin_popup_keepdata', $keepdata );
		
		if (!isset($options) || !isset($options['disableupdate']))
			$disableupdate = 0;
		else
			$disableupdate = 1;
		update_option( 'wonderplugin_popup_disableupdate', $disableupdate );
		
		if (!isset($options) || !isset($options['supportwidget']))
			$supportwidget = 0;
		else
			$supportwidget = 1;
		update_option( 'wonderplugin_popup_supportwidget', $supportwidget );
		
		if (!isset($options) || !isset($options['addjstofooter']))
			$addjstofooter = 0;
		else
			$addjstofooter = 1;
		update_option( 'wonderplugin_popup_addjstofooter', $addjstofooter );
		
		if (!isset($options) || !isset($options['ajaxverifynonce']))
			$ajaxverifynonce = 0;
		else
			$ajaxverifynonce = 1;
		update_option( 'wonderplugin_popup_ajaxverifynonce', $ajaxverifynonce );
		
		if (!isset($options) || !isset($options['sanitizehtmlcontent']))
			$sanitizehtmlcontent = 0;
		else
			$sanitizehtmlcontent = 1;
		update_option( 'wonderplugin_popup_sanitizehtmlcontent', $sanitizehtmlcontent );
		
		if (!isset($options) || !isset($options['emailfrom']))
			$options['emailfrom'] = get_bloginfo('admin_email');
		update_option( 'wonderplugin_popup_emailfrom', $options['emailfrom'] );
		
		if (!isset($options) || !isset($options['emailfromname']))
			$options['emailfromname'] = get_bloginfo('name');
		update_option( 'wonderplugin_popup_emailfromname', $options['emailfromname'] );
		
		if (!isset($options) || !isset($options['enablesmtp']))
			$enablesmtp = 0;
		else
			$enablesmtp = 1;
		update_option( 'wonderplugin_popup_enablesmtp', $enablesmtp );
		
		if (!isset($options) || !isset($options['smtphostname']))
			$options['smtphostname'] = '';
		update_option( 'wonderplugin_popup_smtphostname', $options['smtphostname'] );
		
		if (!isset($options) || !isset($options['smtpport']))
			$options['smtpport'] = 25;
		update_option( 'wonderplugin_popup_smtpport', $options['smtpport'] );
		
		if (!isset($options) || !isset($options['smtpsecure']))
			$options['smtpsecure'] = 'no';
		update_option( 'wonderplugin_popup_smtpsecure', $options['smtpsecure'] );
		
		if (!isset($options) || !isset($options['smtpusername']))
			$options['smtpusername'] = '';
		update_option( 'wonderplugin_popup_smtpusername', $options['smtpusername'] );
		
		if (!isset($options) || !isset($options['smtppassword']))
			$options['smtppassword'] = '';
		update_option( 'wonderplugin_popup_smtppassword', $options['smtppassword'] );
	}
	
	function get_plugin_info() {
	
		$info = get_option('wonderplugin_popup_information');
		if ($info === false)
			return false;
	
		return unserialize($info);
	}
	
	function save_plugin_info($info) {
	
		update_option( 'wonderplugin_popup_information', serialize($info) );
	}
	
	function check_license($options) {
	
		$ret = array(
			"status" => "empty"
		);
	
		if ( !isset($options) || empty($options['wonderplugin-popup-key']) )
		{
			return $ret;
		}
	
		$key = sanitize_text_field( $options['wonderplugin-popup-key'] );
		if ( empty($key) )
			return $ret;
	
		$update_data = $this->controller->get_update_data('register', $key);
		if( $update_data === false )
		{
			$ret['status'] = 'timeout';
			return $ret;
		}
	
		if ( isset($update_data->key_status) )
			$ret['status'] = $update_data->key_status;
	
		return $ret;
	}
	
	function deregister_license($options) {
	
		$ret = array(
				"status" => "empty"
		);
	
		if ( !isset($options) || empty($options['wonderplugin-popup-key']) )
			return $ret;
	
		$key = sanitize_text_field( $options['wonderplugin-popup-key'] );
		if ( empty($key) )
			return $ret;
	
		$info = $this->get_plugin_info();
		$info->key = '';
		$info->key_status = 'empty';
		$info->key_expire = 0;
		$this->save_plugin_info($info);
	
		$update_data = $this->controller->get_update_data('deregister', $key);
		if ($update_data === false)
		{
			$ret['status'] = 'timeout';
			return $ret;
		}
	
		$ret['status'] = 'success';
	
		return $ret;
	}
}