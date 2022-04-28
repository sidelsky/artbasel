<?php

if ( ! defined( 'ABSPATH' ) )
	exit;
	
require_once ABSPATH . '/wp-includes/class-IXR.php';
require_once ABSPATH . '/wp-includes/class-wp-http-ixr-client.php';

class InfusionsoftAPI {
	
	private $subdomain;
	private $apikey;
	
	function __construct($subdomain, $apikey) {
	
		$this->subdomain = $subdomain;
		$this->apikey = $apikey;
	}
	
	function add_contact($user, $tagid, $sendemail, $templateid) {
		
		$apiclient = new WP_HTTP_IXR_Client( 'https://' . $this->subdomain . '.infusionsoft.com/api/xmlrpc' );
		if (!$apiclient)
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	6,
					'message'	=> 	'Error: Can not connect to Infusionsoft service'
					);
		}
		
		$query = $apiclient->query('ContactService.findByEmail', $this->apikey , $user['Email'], array('Id'));
		if (!$query)
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	6,
					'message'	=> 	'Error when connect to Infusionsoft and find by email'
					);
		}
		
		$result = $apiclient->getResponse();
		if (is_array($result) && count($result) > 0)
		{
			return 	array( 
						'success'	=> 	false, 
						'errorcode'	=> 	-1,
						'message'	=> 	'Error: the email address has already added.'
				);
		}
		
		$query = $apiclient->query('ContactService.add', $this->apikey , $user);		
		if (!$query)
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	6,
					'message'	=> 	'Error: Connect to Infusionsoft and add contact'
			);
		}
		
		$contactid = $apiclient->getResponse();

		$query = $apiclient->query('APIEmailService.optIn', $this->apikey , $user['Email'], 'Opt-in through WonderPlugin PopUp');
		if (!$query)
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	6,
					'message'	=> 	'Error: Connect to Infusionsoft and opt-in'
			);
		}
		
		$query = $apiclient->query('ContactService.addToGroup', $this->apikey , $contactid, $tagid);			
		if (!$query)
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	6,
					'message'	=> 	'Error: Connect to Infusionsoft and add to group'
			);
		}
		
		if ($sendemail && !empty($templateid))
		{
			$query = $apiclient->query('APIEmailService.sendEmail', $this->apikey , array($contactid), $templateid);
			if (!$query)
			{
				$result = $apiclient->getResponse();
				$error_msg = (is_array($result) && isset($result['faultString'])) ? $result['faultString'] : 'Send template email';
				return array(
						'success'	=> 	false,
						'errorcode'	=> 	8,
						'message'	=> 	'Error: ' . $error_msg
				);
			}
		}
		
		return array(
				'success'	=> 	true
				);
	}
	
	function get_taglist() {
		
		$apiclient = new WP_HTTP_IXR_Client( 'https://' . $this->subdomain . '.infusionsoft.com/api/xmlrpc' );		
		if (!$apiclient)
			return false;
		
		$query = $apiclient->query('DataService.query', $this->apikey , 'ContactGroup', 1000, 0, array('Id' => '%'), array('Id','GroupName'));		
		if (!$query)
			return false;
		
		return $apiclient->getResponse();
	}
}