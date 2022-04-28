<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
class WonderPlugin_Popup_Service {

	private $controller;
	
	function __construct($controller) {
				
		$this->controller = $controller;
	}
	
	function subscribe($post) {
		
		if ( empty($post['action']) || $post['action'] != 'wonderplugin_popup_subscribe'
				|| empty($post['id']) || !is_numeric($post['id'])
				|| empty($post['service'])
				|| empty($post['data']) || !is_array($post['data']) || count($post['data']) <= 0)
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	1,
					'message'	=> 'Error: invalid parameter or no email service defined'
			);
		}

		$id = sanitize_text_field($post['id']);
		$service = sanitize_text_field($post['service']);
		$data = array();
		foreach($post['data'] as $key => $value)
			$data[sanitize_text_field($key)] = sanitize_text_field($value);
		
		$options = $this->controller->get_item_data($id);
		if ( empty($options) )
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	2,
					'message'	=> 'Error: the specified popup id does not exist'
			);
		}
				
		if ( $options['showgrecaptcha'] && !empty($options['grecaptchasitekey']) && !empty($options['grecaptchasecretkey']) )
		{
			if (!isset($data['g-recaptcha-response']) || !$this->check_g_recaptcha($options['grecaptchasecretkey'], $data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']))
			{
				return array(
						'success'	=> 	false,
						'errorcode'	=> 	-3,
						'message'	=> 'Error: The reCAPTCHA was not entered correctly. Please try it again.'
				);
			}
		}
		
		$subscriberemail = ( $options['showemail'] && array_key_exists($options['emailfieldname'], $data) ) ? strtolower($data[$options['emailfieldname']]) : '';
		
		if ($options['savetolocal'] || ($options['emailnotify'] && is_email($options['emailto'])) || ($options['emailautoresponder'] && is_email($subscriberemail)) )
		{
			unset($data['g-recaptcha-response']);
			$ret = $this->controller->save_to_local_and_email_notify($id, $data, $options['savetolocal'], 
				$options['emailnotify'], $options['emailto'], $options['emailsubject'],
				$subscriberemail, $options['emailautoresponder'], $options['emailautorespondersubject'], $options['emailautorespondercontent']);
			
			if ($service == 'noservice')
				return $ret;
		}
		
		if ($service == 'noservice')
		{
			return array(
					'success'	=> true
			);
		}
		
		if ( !$options['showemail'] || !array_key_exists($options['emailfieldname'], $data) )
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	4,
					'message'	=> 	'Error: no email address'
			);
		}
		
		if ( $service == 'mailchimp' )
		{
			return $this->mailchimp_subscribe($id, $data, $options);
		}
		else if ( $service == 'getresponse' )
		{
			return $this->getresponse_subscribe($id, $data, $options);
		}
		else if ( $service == 'getresponsev3' )
		{
			return $this->getresponsev3_subscribe($id, $data, $options);
		}
		else if ( $service == 'campaignmonitor' )
		{
			return $this->campaignmonitor_subscribe($id, $data, $options);
		}
		else if ( $service == 'constantcontact' )
		{
			return $this->constantcontact_subscribe($id, $data, $options);
		}
		else if ( $service == 'icontact' )
		{
			return $this->icontact_subscribe($id, $data, $options);
		}
		else if ( $service == 'activecampaign' )
		{
			return $this->activecampaign_subscribe($id, $data, $options);
		}
		else if ( $service == 'mailpoet' )
		{
			return $this->mailpoet_subscribe($id, $data, $options);
		}
		else if ( $service == 'mailpoet3' )
		{
			return $this->mailpoet3_subscribe($id, $data, $options);
		}
		else if ( $service == 'infusionsoft' )
		{
			return $this->infusionsoft_subscribe($id, $data, $options);
		}
	}
	
	function icontact_subscribe($id, $data, $options)
	{
		if ($options['subscription'] != 'icontact' || empty($options['icontactusername']) || empty($options['icontactappid']) || empty($options['icontactapppassword']) || empty($options['icontactlistid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$username = $options['icontactusername'];
		$appid = $options['icontactappid'];
		$apppassword = $options['icontactapppassword'];
		$listid = $options['icontactlistid'];
		
		$email = strtolower($data[$options['emailfieldname']]);
		
		$firstname = null;
		$lastname = null;
		
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$names = explode(' ', $data[$options['namefieldname']]);
			$firstname = $names[0];
			$lastname = ( count($names) > 1 ) ? array_pop($names) : null;
		}
		
		if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			$firstname = $data[$options['firstnamefieldname']];
		
		if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			$lastname =  $data[$options['lastnamefieldname']];
		
		$postalcode = ( $options['showzip'] && array_key_exists($options['zipfieldname'], $data) ) ? $data[$options['zipfieldname']] : null;
		$phone = ( $options['showphone'] && array_key_exists($options['phonefieldname'], $data) ) ? $data[$options['phonefieldname']] : null;
		$business = ( $options['showcompany'] && array_key_exists($options['companyfieldname'], $data) ) ? $data[$options['companyfieldname']] : null;
		
		require_once('icontact/lib/iContactApi.php');
		
		iContactApi::getInstance()->setConfig(array(
				'appId'       => $appid,
				'apiPassword' => $apppassword,
				'apiUsername' => $username
		));
		
		$oiContact = iContactApi::getInstance();
		
		try {
			
			$contact_response = $oiContact->addContact($email, 'normal', null, $firstname, $lastname, null, null, null, null, null, $postalcode, $phone, null, $business);				
			if (!empty($contact_response) && is_object($contact_response) && isset($contact_response->contactId))
			{

				$check_response = $oiContact->getSubscription($contact_response->contactId, $listid);				
				if ( $check_response && is_object($check_response) && !empty($check_response->subscriptions) )
				{
					return array(
							'success'	=> false,
							'errorcode'	=> -1,
							'message'	=> 'The contact alreasy exists in the list'
					);
				}
				else
				{		
					if ( isset($options['icontactdoubleoptin']) && $options['icontactdoubleoptin'] && !empty($options['icontactmessageid']))	
					{	
						$icontactmessageid = $options['icontactmessageid'];
						$status = 'pending';
					}
					else
					{
						$icontactmessageid = '';
						$status = 'normal';
					}
					
					
					$sub_response = $oiContact->subscribeContactToList($contact_response->contactId, $listid, $status, $icontactmessageid);		
					if ( !empty($sub_response) )
					{
						return array(
								'success'	=> true
							);
					}
					else
					{
						return array(
								'success'	=> false,
								'errorcode'	=> 7,
								'message'	=> 'Error subscribe contact to the list'
							);
					}
				}
			}
			else
			{
				return array(
						'success'	=> false,
						'errorcode'	=> 7,
						'message'	=> 'Error add contact to iContact'
					);
			}
		} catch (Exception $e) {
		
			$errors = $oiContact->getErrors();		
			$message = ($errors && is_array($errors) && is_string($errors[0])) ? $errors[0] : $e->getMessage();
			return array(
						'success'	=> false,
						'errorcode'	=> 7,
						'message'	=> $message
					);
		}
	}
	
	function constantcontact_subscribe($id, $data, $options)
	{
		if ($options['subscription'] != 'constantcontact' || empty($options['constantcontactapikey']) || empty($options['constantcontactaccesstoken']) || empty($options['constantcontactlistid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$apikey = $options['constantcontactapikey'];
		$accesstoken = $options['constantcontactaccesstoken'];
		$listid = $options['constantcontactlistid'];
				
		$email = strtolower($data[$options['emailfieldname']]);
		unset($data[$options['emailfieldname']]);
		
		// check whether email exist
		$already_existed = false;
		$existing_results = array();
		
		$apicheckurl = 'https://api.constantcontact.com/v2/contacts?api_key=' . $apikey . '&email=' . urlencode($email);
		$args = array(
				'headers' 	=> array(
						'Authorization' => 'Bearer ' . $accesstoken
				)
		);
		
		$raw_response = wp_remote_get( $apicheckurl, $args );
		if ( !is_wp_error( $raw_response ) )
		{
			if ( isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200 )
			{
				if ( isset($raw_response['body']) )
				{
					$response_body = json_decode($raw_response['body'], true);
					
					if ( isset($response_body['results']) && count($response_body['results']) > 0)
					{
						$already_existed = true;
						$existing_results = $response_body['results'];
					}
				}
			}
		}
		
		// update if already existed
		if ( $already_existed )
		{
			foreach($existing_results as $result)
			{
				$foundemail = false;
				
				foreach($result['email_addresses'] as $address)
				{
					if ($address['email_address'] == $email)
					{
						$foundemail = true;
						break;
					}
				}
				
				if ($foundemail)
				{
					$body = $result; 
					
					$foundlist = false;
					
					foreach($result['lists'] as $list)
					{
						if ($list['id'] == $listid)
						{
							$foundlist = true;
							break;
						}
					}
					
					if (!$foundlist)
						$body['lists'][] = array( 'id' 	=> $listid );
					
					$apiurl = 'https://api.constantcontact.com/v2/contacts/' . $result['id'] . '?action_by=ACTION_BY_VISITOR&api_key=' . $apikey;
					
					$args = array(
							'headers' 	=> array(
									'Authorization' => 'Bearer ' . $accesstoken,
									'Content-Type'	=> 'application/json'
							),
							'method'	=> 'PUT'
					);
				}
			}
		}
		
		$already_existed = !empty($body);
		
		if ( !$already_existed )
		{
			$body = array(
					'email_addresses' 	=> array(
							array( 'email_address'	=> $email )
					),
					'lists'				=> array(
							array( 'id' 	=> $listid )
					)
			);
			
			$apiurl = 'https://api.constantcontact.com/v2/contacts?action_by=ACTION_BY_VISITOR&api_key=' . $apikey;
			
			$args = array(
					'headers' 	=> array(
							'Authorization' => 'Bearer ' . $accesstoken,
							'Content-Type'	=> 'application/json'
					),
					'method'	=> 'POST'
			);
		}
		
		if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
		{
			$body['first_name'] = $data[$options['firstnamefieldname']];
			unset($data[$options['firstnamefieldname']]);
		}
		
		if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
		{
			$body['last_name'] = $data[$options['lastnamefieldname']];
			unset($data[$options['lastnamefieldname']]);
		}
		
		if ( $options['showcompany'] && array_key_exists($options['companyfieldname'], $data) )
		{
			$body['company_name'] = $data[$options['companyfieldname']];
			unset($data[$options['companyfieldname']]);
		}
		
		$constantcontact_params = array('cell_phone', 'company_name', 'fax', 'home_phone', 'job_title', 'prefix_name', 'source', 'work_phone');
		foreach($data as $key => $value)
		{
			if ( in_array($key, $constantcontact_params) )
				$body[$key] = $value;
		}
		
		$args['body'] = json_encode($body);
		
		$raw_response = wp_remote_post( $apiurl, $args );
		if ( !is_wp_error( $raw_response ) )
		{			
			if ( isset($raw_response['response']['code']) && ($raw_response['response']['code'] == 200 || $raw_response['response']['code'] == 201) )
			{
				return array(
						'success'	=> true,
						'updated'	=> ($already_existed ? true : false)
				);
			}
			else if ( isset($raw_response['response']['code']) )
			{
				if ( isset($raw_response['body']) )
				{
					$response_body = json_decode($raw_response['body'], true);	
					$message = isset($response_body[0]['error_message']) ? $response_body[0]['error_message'] : '';
					return array(
							'success'	=> false,
							'errorcode'	=> ( $raw_response['response']['code'] == 409) ? -1 : 6,
							'message'	=> $message
					);
				}
				else
				{
					$message = isset($raw_response['response']['message']) ? $raw_response['response']['message'] : '';
					return array(
							'success'	=> false,
							'errorcode'	=> ( $raw_response['response']['code'] == 409) ? -1 : 6,
							'message'	=> $message
					);
				}
			}
			else
			{
				return array(
						'success'	=> false,
						'errorcode'	=> 7,
						'message'	=> 'Error connect to Constant Contact service'
				);
			}
		}
	}
	
	function campaignmonitor_subscribe($id, $data, $options)
	{
		
		if ($options['subscription'] != 'campaignmonitor' || empty($options['campaignmonitorapikey']) || empty($options['campaignmonitorlistid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$apikey = $options['campaignmonitorapikey'];
		$listid = $options['campaignmonitorlistid'];
		
		$email = strtolower($data[$options['emailfieldname']]);
		unset($data[$options['emailfieldname']]);
		
		$name = '';
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$name = $data[$options['namefieldname']];
			unset($data[$options['namefieldname']]);
		}
		else
		{
			if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			{
				$name .= $data[$options['firstnamefieldname']];
				unset($data[$options['firstnamefieldname']]);
			}
				
			if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			{
				$name .= ( !empty($name) ? ' ' : '') . $data[$options['lastnamefieldname']];
				unset($data[$options['lastnamefieldname']]);
			}
		}
		
		$args = array(
				'EmailAddress' => $email
			);
		
		if (!empty($name))
			$args['Name'] = $name;
		
		if (!empty($data))
		{
			$customs = array();
			foreach($data as $key => $value)
			{
				$customs[] = array(
						'Key'		=> $key,
						'Value'	=> $value
				);
			}
			$args['CustomFields'] = $customs;
		}
		
		require_once 'campaignmonitor/csrest_subscribers.php';
		
		$wrap = new CS_REST_Subscribers( $listid, array('api_key' => $apikey));
		
		$result = $wrap->add($args);
		
		if (isset($result->http_status_code) && $result->http_status_code == 201)
		{
			return array(
					'success'	=> true
			);
		}
		else if (isset($result->response) && isset($result->response->Code) && isset($result->response->Message))
		{
			return array(
					'success'	=> false,
					'errorcode'	=> (($result->response->Code == 208) ? -2 : 6),
					'message'	=> $result->response->Message
			);
		}
		else
		{
			return array(
					'success'	=> false,
					'errorcode'	=> 7,
					'message'	=> 'Error connect to Campaign Monitor service'
			);
		}
	}
	
	function getresponsev3_subscribe($id, $data, $options)
	{
		if ($options['subscription'] != 'getresponsev3' || empty($options['getresponsev3apikey']) || empty($options['getresponsev3campaignid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$email = strtolower($data[$options['emailfieldname']]);
		unset($data[$options['emailfieldname']]);
		
		$name = '';
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$name = $data[$options['namefieldname']];
			unset($data[$options['namefieldname']]);
		}
		else
		{
			if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			{
				$name .= $data[$options['firstnamefieldname']];
				unset($data[$options['firstnamefieldname']]);
			}
			
			if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			{
				$name .= ( !empty($name) ? ' ' : '') . $data[$options['lastnamefieldname']];
				unset($data[$options['lastnamefieldname']]);
			}
		}
			
		$apikey = $options['getresponsev3apikey'];
		$apiurl = 'https://api.getresponse.com/v3/contacts';

		$bodyargs = array(
			'campaign'  => array(
				'campaignId' => $options['getresponsev3campaignid']
			),
			'email'		=> $email
		);
	
		if (isset($options['getresponsev3autoresponder']) && $options['getresponsev3autoresponder'])
			$bodyargs['dayOfCycle'] = 0;
		
		if (!empty($name))
			$bodyargs['name'] = $name;
		
		if (!empty($data))
		{
			$customs = array();
			foreach($data as $key => $value)
			{
				$customs[] = array(
						'name'		=> $key,
						'content'	=> $value
						);
			}
			$bodyargs['customFieldValues'] = $customs;
		}

		$args = array(
			'method'	=> 'POST',
			'headers' => array(
				'X-Auth-Token' 	=> 'api-key ' . $apikey,
				'Content-Type'	=> 'application/json'
			),
			'body'		=> json_encode($bodyargs)
		);

		$raw_response = wp_remote_post( $apiurl, $args);

		if ( !empty($raw_response) && isset($raw_response['response']['code']) && isset($raw_response['response']['code']) == '202')
		{
			return array(
				'success'	=> true
			);
		}
		else if ( !empty($raw_response) && isset($raw_response['response']['code']) && isset($raw_response['response']['code']) == '409')
		{
			return 	array( 
				'success'	=> 	false, 
				'errorcode'	=> 	-1,
				'message'	=> 	'Error: the email address has already subscribed.'
			);
		}
		else if ( isset($raw_response['response']['code']) )
		{
			return array(
					'success'	=> false,
					'errorcode'	=> $raw_response['response']['code'],
					'message'	=> isset($raw_response['response']['message']) ? $raw_response['response']['message'] : ''

			);
		}
		else
		{
			return array(
					'success'	=> false,
					'errorcode'	=> 	7,
					'message'	=> 'Error connect to GetResponse service'
			);
		}
	}

	function getresponse_subscribe($id, $data, $options)
	{
		require_once 'getresponse/jsonRPCClient.php';
		
		if ($options['subscription'] != 'getresponse' || empty($options['getresponseapikey']) || empty($options['getresponsecampaignid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$email = strtolower($data[$options['emailfieldname']]);
		unset($data[$options['emailfieldname']]);
		
		$name = '';
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$name = $data[$options['namefieldname']];
			unset($data[$options['namefieldname']]);
		}
		else
		{
			if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			{
				$name .= $data[$options['firstnamefieldname']];
				unset($data[$options['firstnamefieldname']]);
			}
			
			if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			{
				$name .= ( !empty($name) ? ' ' : '') . $data[$options['lastnamefieldname']];
				unset($data[$options['lastnamefieldname']]);
			}
		}
			
		$apikey = $options['getresponseapikey'];
		$campaignid = $options['getresponsecampaignid'];
		
		$apiurl = 'https://api2.getresponse.com';
		
		$client = new jsonRPCClient($apiurl);
		
		$args = array(
				'campaign'  => $campaignid,
				'email'		=> $email
			);
		
		if (isset($options['getresponseautoresponder']) && $options['getresponseautoresponder'])
			$args['cycle_day'] = 0;
		
		if (!empty($name))
			$args['name'] = $name;
		
		if (!empty($data))
		{
			$customs = array();
			foreach($data as $key => $value)
			{
				$customs[] = array(
						'name'		=> $key,
						'content'	=> $value
						);
			}
			$args['customs'] = $customs;
		}
		
		try {
				
			$result = $client->add_contact(
				$apikey,
				$args
			);

			if (isset($result) && isset($result['queued']) && $result['queued'] == 1)
			{
				return array(
						'success'	=> true
				);
			}
			else if ( isset($result) && isset($result['code']) && isset($result['message']) )
			{
				return array(
						'success'	=> false,
						'errorcode'	=> (($result['code'] == -1) ? -1 : 6),
						'message'	=> $result['message']
				);
			}
			else
			{
				return array(
						'success'	=> false,
						'errorcode'	=> 	7,
						'message'	=> 'Error connect to GetResponse service'
				);
			}
		
		} catch (Exception $e) {
		
			return array(
					'success'	=> false,
					'errorcode'	=> 7,
					'message'	=> $e->getMessage()
			);
		}
	}
	
	function mailchimp_subscribe($id, $data, $options)
	{
		if ($options['subscription'] != 'mailchimp' || empty($options['mailchimpapikey']) || empty($options['mailchimplistid']))
		{
			return array( 
					'success'	=> 	false, 
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$email = strtolower($data[$options['emailfieldname']]);
		unset($data[$options['emailfieldname']]);
			
		$apikey = $options['mailchimpapikey'];
		$listid = $options['mailchimplistid'];
		$parts = explode('-', $apikey);
		if ( count($parts) <= 1)
		{
			return array( 
					'success'	=> 	false, 
					'errorcode'	=> 	5,
					'message'	=> 	'Error: invalid email service configuration'
			);
		}	
		$server = end( $parts );

		
		$apiurl = 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $listid . '/members/' . md5($email);
		$args = array(
				'headers' 	=> 	array(
						'Authorization' => 'Basic ' . base64_encode( 'user:' . $apikey )
				)
		);
		$raw_response = wp_remote_get( $apiurl, $args );
		if ( !is_wp_error( $raw_response ) && isset($raw_response['body']) && isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200)
		{	
			$response = json_decode($raw_response['body'], true);	
			if ( isset($response['status']) && $response['status'] == 'subscribed' )
			{
				return 	array( 
						'success'	=> 	false, 
						'errorcode'	=> 	-1,
						'message'	=> 	'Error: the email address has already subscribed.'
				);
			}
			else if ( isset($response['status']) && $response['status'] == 'pending' )
			{
				return 	array(
						'success'	=> 	false,
						'errorcode'	=> 	-2,
						'message'	=> 	'Error: the email address has already subscribed. You must confirm your email address before we can send you. Please check your email and follow the instructions.'
				);
			}
		}
		
		$apiurl = 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $listid . '/members/';
		$status = (isset($options['mailchimpdoubleoptin']) && $options['mailchimpdoubleoptin']) ? 'pending': 'subscribed';
		$body = array(
					'email_address'		=> $email,
					'status'			=> $status
				);
		
		$interests = array();
		foreach($data as $key => $value)
		{
			if (strpos($key, 'MAILCHIMPINTEREST_') === 0)
			{
				$items = explode('_', $key);
				if (count($items) > 2)
				{
					if ($items[1] == 'CHECKBOXES' && $value == 'on')
					{
						$interests[array_pop($items)] = true;
					}
					else if ($items[1] == 'RADIO' || $items[1] == 'DROPDOWN')
					{
						$interests[$value] = true;
					}
				}
				unset($data[$key]);
			}
		}
		
		if ( !empty($data) )
			$body['merge_fields'] = $data;
		
		if ( !empty($interests) )
			$body['interests'] = $interests;
		
		$args = array(
				'method'    => 'POST',
				'headers' 	=> 	array(
						'Authorization' => 'Basic ' . base64_encode( 'user:' . $apikey )
				),
				'body'		=>	json_encode($body)
		);
		
		$raw_response = wp_remote_request( $apiurl, $args );
		if ( !is_wp_error( $raw_response ) && isset($raw_response['body']) )
		{						
			if ( isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200 )
			{
				return array(
					'success'	=> 	true
				);
			}
			else
			{
				return array(
					'success'	=> 	false,
					'errorcode'	=> 	$raw_response['response']['code'],
					'message'	=> 	$raw_response['body']
				);
			}			
		}	
		else
		{
			return 	array(
					'success'	=> 	false,
					'errorcode'	=> 	7,
					'message'	=> 	'Error: unable to connect to email service'
			);
		}
	}
	
	function service_connect($data) {
		
		if ($data['service'] == 'mailchimp')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->mailchimp_getlists($data['servicekey']);
			}
		}
		else if ($data['service'] == 'getresponse')
		{
			if ($data['serviceaction'] == 'getcampaigns')
			{
				return $this->getresponse_getcampaigns($data['servicekey']);
			}
		}
		else if ($data['service'] == 'getresponsev3')
		{
			if ($data['serviceaction'] == 'getcampaigns')
			{
				return $this->getresponsev3_getcampaigns($data['servicekey']);
			}
		}
		else if ($data['service'] == 'campaignmonitor')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->campaignmonitor_getlists($data['servicekey'], $data['clientid']);
			}
		}
		else if ($data['service'] == 'constantcontact')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->constantcontact_getlists($data['servicekey'], $data['accesstoken']);
			}
		}
		else if ($data['service'] == 'icontact')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->icontact_getlists($data['username'], $data['appid'], $data['apppassword']);
			}
		}
		else if ($data['service'] == 'activecampaign')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->activecampaign_getlists($data['apiurl'], $data['apikey']);
			}
		}
		else if ($data['service'] == 'mailpoet')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->mailpoet_getlists();
			}
		}
		else if ($data['service'] == 'mailpoet3')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->mailpoet3_getlists();
			}
		}
		else if ($data['service'] == 'infusionsoft')
		{
			if ($data['serviceaction'] == 'getlists')
			{
				return $this->infusionsoft_getlists($data['subdomain'], $data['apikey']);
			}
		}
	}
	
	function icontact_getlists($username, $appid, $apppassword)
	{
		if ( empty($username) || empty($appid) || empty($apppassword) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'The Username, Application ID or Application Password is invalid.'
			);
		}
		
		require_once('icontact/lib/iContactApi.php');
		
		iContactApi::getInstance()->setConfig(array(
				'appId'       => $appid,
				'apiPassword' => $apppassword,
				'apiUsername' => $username
		));
		
		$oiContact = iContactApi::getInstance();
		
		try {
			$response = $oiContact->getLists();
			if ( $response && is_array($response) )
			{
				if ( is_object($response[0]) && isset($response[0]->listId) )
				{
					$lists = array();
					
					foreach ($response as $value)
					{
						$lists[] = array(
								'id'	=> $value->listId,
								'name'	=> str_replace(array("'", '"'), '', $value->name),
								'welcomeMessageId' => (!empty($value->welcomeMessageId) ? $value->welcomeMessageId: '')
						);
					}
					
					if (count($lists) <= 0)
					{
						return array(
								'success'	=> false,
								'message'	=> 'No list defined in iContact'
						);
					}
					else
					{
						return array(
								'success'	=> true,
								'data'		=> json_encode($lists)
						);
					}
				}
				else if (is_string($response[0]))
				{
					return array(
							'success'	=> false,
							'message'	=> $response[0]
					);
				}
			}
			
		} catch (Exception $e) { 

			$errors = $oiContact->getErrors();			
			$message = ($errors && is_array($errors) && is_string($errors[0])) ? $errors[0] : $e->getMessage();
			return array(
					'success'	=> false,
					'message'	=> $message
			);
		}
	}
	
	function constantcontact_getlists($apikey, $accesstoken)
	{
		if ( empty($apikey) || empty($accesstoken) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'The API Key or Access Token is invalid.'
			);
		}
		
		$apiurl = 'https://api.constantcontact.com/v2/lists?api_key=' . $apikey;
				
		$args = array(
				'headers' => array(
						'Authorization' => 'Bearer ' . $accesstoken
				)
		);
		
		$raw_response = wp_remote_request( $apiurl, $args );
				
		if ( !is_wp_error( $raw_response ) )
		{
			if ( isset($raw_response['response']['code']) && isset($raw_response['body']) )
			{
				if ( $raw_response['response']['code'] == 200 )
				{
					$lists= array();
					$items = json_decode($raw_response['body'], true);
					foreach ($items as $item)
					{
						$lists[] = array(
								'id'	=> $item['id'],
								'name'	=> str_replace(array("'", '"'), '', $item['name'])
						);
					}
					
					if (count($lists) <= 0)
					{
						return array(
								'success'	=> false,
								'message'	=> 'No list defined in Constant Contact'
						);
					}
					else
					{
						return array(
								'success'	=> true,
								'data'		=> json_encode($lists)
						);
					}
				}
				else
				{
					$data = json_decode($raw_response['body'], true);
					if ( isset($data[0]['error_message']) )
					{
						return array(
								'success'	=> false,
								'message'	=> $data[0]['error_message']
						);
					}
				}
			}
		}
		else
		{
			return array(
					'success'	=> false,
					'message'	=> 'Error connect to Constant Contact Co service'
			);
		}
	}
	
	function campaignmonitor_getlists($apikey, $clientid)
	{	
		if ( empty($apikey) || empty($clientid) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'The API Key or client ID is invalid.'
			);
		}
		
		require_once 'campaignmonitor/csrest_clients.php';
		
		$wrap = new CS_REST_Clients( $clientid, array('api_key' => $apikey));
		
		$result = $wrap->get_lists();
		
		if (isset($result->http_status_code) && $result->http_status_code == 200 && isset($result->response) && is_array($result->response))
		{
			$lists= array();
			
			foreach( $result->response as $list)
			{
				$lists[] = array(
						'id'	=> $list->ListID,
						'name'	=> str_replace(array("'", '"'), '', $list->Name)
					);
			}
			
			if (count($lists) <= 0)
			{
				return array(
						'success'	=> false,
						'message'	=> 'No list defined in Campaign Monitor'
				);
			}
			else
			{
				return array(
						'success'	=> true,
						'data'		=> json_encode($lists)
				);
			}
		}
		else if (isset($result->response) && isset($result->response->Message))
		{
			return array(
					'success'	=> false,
					'message'	=> $result->response->Message
			);
		}
		else
		{
			return array(
					'success'	=> false,
					'message'	=> 'Error connect to Campaign Monitor service'
			);
		}
	}
	
	function getresponsev3_getcampaigns($apikey)
	{
		if ( empty($apikey) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'Please enter your API key.'
			);
		}
		
		$apiurl = 'https://api.getresponse.com/v3/campaigns';
				
		$args = array(
				'headers' => array(
						'X-Auth-Token' => 'api-key ' . $apikey
				)
		);
		
		$raw_response = wp_remote_request( $apiurl, $args );
				
		if ( !is_wp_error( $raw_response ) )
		{
			if ( isset($raw_response['response']['code']) && isset($raw_response['body']) )
			{
				if ( $raw_response['response']['code'] == 200 )
				{
					$lists= array();
					$items = json_decode($raw_response['body'], true);
					foreach ($items as $item)
					{
						$lists[] = array(
								'id'	=> $item['campaignId'],
								'name'	=> str_replace(array("'", '"'), '', $item['name'])
						);
					}
					
					if (count($lists) <= 0)
					{
						return array(
								'success'	=> false,
								'message'	=> 'No list defined in GetResponse'
						);
					}
					else
					{
						return array(
								'success'	=> true,
								'data'		=> json_encode($lists)
						);
					}
				}
				else
				{
					$data = json_decode($raw_response['body'], true);
					if ( isset($data[0]['message']) )
					{
						return array(
								'success'	=> false,
								'message'	=> $data[0]['message']
						);
					}
				}
			}
		}
		else
		{
			return array(
					'success'	=> false,
					'message'	=> 'Error connect to GetResponse service'
			);
		}
	}

	function getresponse_getcampaigns($apikey)
	{
		require_once 'getresponse/jsonRPCClient.php';
		
		if ( empty($apikey) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'The API key is invalid.'
			);
		}
		
		$apiurl = 'https://api2.getresponse.com';
		
		$client = new jsonRPCClient($apiurl);
		
		try {
			
			$campaigns = $client->get_campaigns( $apikey );
			
			$lists = array();
			foreach( $campaigns as $key => $campaign )
			{
				$lists[] = array(
						'id'	=> $key,
						'name'	=> str_replace(array("'", '"'), '', $campaign['name'])
				);
			}
			
			if (count($lists) <= 0)
			{
				return array(
						'success'	=> false,
						'message'	=> 'No list defined in GetResponse'
				);
			}
			else
			{
				return array(
						'success'	=> true,
						'data'		=> json_encode($lists)
				);
			}
		
		} catch (Exception $e) {
						
			return array(
					'success'	=> false,
					'message'	=> $e->getMessage()
			);
		}
		
	}
	
	function mailchimp_getgroupinterests($apikey, $list_id, $group_id)
	{
		$group_interests = array();
		
		$parts = explode('-', $apikey);
		if ( count($parts) <= 1)
			return $group_interests;
		
		$server = end( $parts );
		$apiurl = 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $list_id . '/interest-categories/' . $group_id . '/interests';
		
		$args = array(
				'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( 'user:' . $apikey )
				)
		);
		
		$raw_response = wp_remote_request( $apiurl, $args );
		if ( !is_wp_error( $raw_response ) )
		{
			if ( isset($raw_response['body']) )
			{
				$data = json_decode($raw_response['body'], true);
					
				if (isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200)
				{
					if ( isset($data['interests']) )
					{
						foreach($data['interests'] as $interest)
						{
							$group_interests[] = array(
									'id'	=> $interest['id'],
									'name'	=> str_replace(array("'", '"'), '', $interest['name']),
									'display_order'	=> $interest['display_order']
							);
						}
				
						return $group_interests;
					}
				}
			}
		}
	}
	
	function mailchimp_getgroups($apikey, $list_id)
	{
		$groups = array();
		
		$parts = explode('-', $apikey);
		if ( count($parts) <= 1)
			return $groups;
		
		$server = end( $parts );
		$apiurl = 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $list_id . '/interest-categories';
		
		$args = array(
				'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( 'user:' . $apikey )
				)
		);
		
		$raw_response = wp_remote_request( $apiurl, $args );
		if ( !is_wp_error( $raw_response ) )
		{
			if ( isset($raw_response['body']) )
			{
				$data = json_decode($raw_response['body'], true);
					
				if (isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200)
				{
					if ( isset($data['categories']) )
					{
						foreach($data['categories'] as $category)
						{
							$groups[] = array(
									'id'	=> $category['id'],
									'title'	=> $category['title'],
									'display_order'	=> $category['display_order'],
									'type' 	=> $category['type'],
									'interests' => array()
							);
						}

						foreach($groups as &$group)
						{
							$group['interests'] = $this->mailchimp_getgroupinterests($apikey, $list_id, $group['id']);
						}
						
						return $groups;
					}
				}
			}
		}
	}
	
	function mailchimp_getlists($apikey)
	{
		$parts = explode('-', $apikey);
		if ( count($parts) <= 1)
		{
			return array(
					'success'	=> false,
					'message'	=> 'The API key is invalid.'
			);
		}
		
		$server = end( $parts );
		$apiurl = 'https://' . $server . '.api.mailchimp.com/3.0/lists?offset=0&count=1000';
		
		$args = array(
				'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( 'user:' . $apikey )
				)
		);
		
		$raw_response = wp_remote_request( $apiurl, $args );
		if ( !is_wp_error( $raw_response ) )
		{
			if ( isset($raw_response['body']) )
			{
				$data = json_decode($raw_response['body'], true);
			
				if (isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200)
				{
					$lists = array();					
					if ( isset($data['lists']) )
					{
						foreach($data['lists'] as $list)
						{
							$lists[] = array(
								'id'	=> $list['id'],
								'name'	=> str_replace(array("'", '"'), '', $list['name']),
								'groups' => array()
							);
						}
					}
					
					if (count($lists) <= 0)
					{
						return array(
								'success'	=> false,
								'message'	=> 'No list defined in MailChimp'
						);
					}
					else
					{
						// get groups of each list
						foreach($lists as &$list)
						{
							$list['groups'] = $this->mailchimp_getgroups($apikey, $list['id']);
						}
						
						return array(
							'success'	=> true,
							'data'		=> json_encode($lists)
						);
					}
				}
				else
				{
					return array(
						'success'	=> false,
						'message'	=> (isset($data['title']) ? $data['title'] : '') . ': ' . (isset($data['detail']) ? $data['detail'] : '')
					);
				}
			}
		}
	}
	
	function activecampaign_subscribe($id, $data, $options) 
	{
		if ($options['subscription'] != 'activecampaign' || empty($options['activecampaignapiurl']) || empty($options['activecampaignapikey']) || empty($options['activecampaignlistid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$apiurl = $options['activecampaignapiurl'];
		$apikey = $options['activecampaignapikey'];
		$listid = $options['activecampaignlistid'];
		
		$args = array(
				'api_key'      	=> $apikey,
				'api_action'   	=> 'contact_add',
				'api_output'   	=> 'json'
		);
		
		$query = '';
		foreach( $args as $key => $value )
		{
			$query .= $key . '=' . urlencode($value) . '&';
		}
		$query = rtrim($query, '& ');
		
		$apiurl = rtrim($apiurl, '/ ') . '/admin/api.php?' . $query;
		
		
		$email = strtolower($data[$options['emailfieldname']]);
		
		$firstname = null;
		$lastname = null;
		
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$names = explode(' ', $data[$options['namefieldname']]);
			$firstname = $names[0];
			$lastname = ( count($names) > 1 ) ? array_pop($names) : null;
		}
		
		if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			$firstname = $data[$options['firstnamefieldname']];
		
		if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			$lastname =  $data[$options['lastnamefieldname']];
		
		$phone = ( $options['showphone'] && array_key_exists($options['phonefieldname'], $data) ) ? $data[$options['phonefieldname']] : null;
		$orgname = ( $options['showcompany'] && array_key_exists($options['companyfieldname'], $data) ) ? $data[$options['companyfieldname']] : null;
				
		$body = array(
				'email'													=> $email,
				'p[' . $options['activecampaignlistid'] . ']'			=> $options['activecampaignlistid'],
				'status[' . $options['activecampaignlistid'] . ']'		=> 1
		);
		
		if (!empty($firstname))
			$body['first_name'] = $firstname;
		
		if (!empty($lastname))
			$body['last_name'] = $lastname;
		
		if (!empty($phone))
			$body['phone'] = $phone;
		
		if (!empty($orgname))
			$body['orgname'] = $orgname;

		if (!empty($options['activecampaignformid']))
			$body['form'] = $options['activecampaignformid'];
		
		$args = array(
				'method'	=> 'POST',
				'body'		=> $body
		);
		
		$raw_response = wp_remote_post( $apiurl, $args );
		
		if ( !is_wp_error( $raw_response ) && isset($raw_response['body']) && isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200)
		{
			$response = json_decode($raw_response['body'], true);
			if ( isset($response['result_code']) ) 
			{
				if ( $response['result_code'] == 1 )
				{
					return array(
							'success'	=> 	true
					);
				}
				else
				{
					return 	array(
							'success'	=> 	false,
							'errorcode'	=> 	-2,
							'message'	=> 	(isset($response['result_message']) ? $response['result_message'] : '')
					);
				}
			}
			else
			{
				return array(
						'success'	=> 	false,
						'errorcode'	=> 	6,
						'message'	=> 	'Error: bad request to email service'
				);
			}
		}
		else
		{
			return 	array(
					'success'	=> 	false,
					'errorcode'	=> 	7,
					'message'	=> 	'Error: unable to connect to email service'
			);
		}
	}
	
	function activecampaign_getlists($apiurl, $apikey)
	{	
		if ( empty($apiurl) || empty($apikey) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'The API URL or key is invalid.'
			);
		}
				
		$args = array(
				'api_key'      	=> $apikey,
				'api_action'   	=> 'list_list',
				'api_output'   	=> 'json',
				'ids'			=> 'all',
				'full'			=> 0
		);
		
		$query = '';
		foreach( $args as $key => $value ) 
		{
			$query .= $key . '=' . urlencode($value) . '&';
		}
		$query = rtrim($query, '& ');
		
		$apiurl = rtrim($apiurl, '/ ') . '/admin/api.php?' . $query;
				
		$raw_response = wp_remote_get( $apiurl );
		if ( !is_wp_error( $raw_response ) )
		{							
			if ( isset($raw_response['body']) )
			{
				$data = json_decode($raw_response['body'], true);		
									
				if (!empty($data) && isset($data['result_code']) &&  $data['result_code'] == 1 && isset($raw_response['response']['code']) && $raw_response['response']['code'] == 200)
				{
					unset($data['result_code']);
					unset($data['result_message']);
					unset($data['result_output']);
					
					$lists = array();
					foreach ($data as $list)
					{
						if (is_array($list) && isset($list['id']) && isset($list['name']))
						{
							$lists[] = array(
									'id'	=> $list['id'],
									'name'	=> str_replace(array("'", '"'), '', $list['name'])
							);
						}
					}

					if (count($lists) <= 0)
					{
						return array(
								'success'	=> false,
								'message'	=> 'No list defined in Active Campaign'
						);
					}
					else
					{
						return array(
								'success'	=> true,
								'data'		=> json_encode($lists)
						);
					}
				}
				else
				{
					$message = '';
					
					if (!empty($data) && isset($data['result_message']) )
						$message = $data['result_message'];
					else if (strpos($raw_response['body'], 'Not Found') !== false)
						$message = 'The API Access URL you entered can not be found.';
						
					return array(
							'success'	=> false,
							'message'	=> $message
					);
				}

			}
		}
	}
	
	function mailpoet_subscribe($id, $data, $options)
	{
		if( !class_exists( 'WYSIJA' ) )
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: MailPoet is not installed or activated'
			);
		}
		
		if( empty($options['mailpoetlistid']) )
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: No MailPoet list selected'
			);
		}
				
		$email = strtolower($data[$options['emailfieldname']]);
		
		$firstname = null;
		$lastname = null;
		
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$names = explode(' ', $data[$options['namefieldname']]);
			$firstname = $names[0];
			$lastname = ( count($names) > 1 ) ? array_pop($names) : null;
		}
		
		if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			$firstname = $data[$options['firstnamefieldname']];
		
		if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			$lastname =  $data[$options['lastnamefieldname']];
		
		$user_data = array(
				'email' => $email
			);

		if (!empty($firstname))
			$user_data['firstname'] = $firstname;
		
		if (!empty($firstname))
			$user_data['lastname'] = $lastname;
		
		$data_subscriber = array(
				'user' => $user_data,
				'user_list' => array('list_ids' => array($options['mailpoetlistid']) )
		);
		
		$helper_user = WYSIJA::get('user','helper');
		$result = $helper_user->addSubscriber($data_subscriber);
		
		return array(
			'success'	=> 	true
		);
	}
	
	function mailpoet_getlists()
	{
		if( !class_exists( 'WYSIJA' ) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'MailPoet is not installed or activated'
			);
		}
		
		$model_list = WYSIJA::get('list', 'model');
		$mailpoet_lists = $model_list->get(array('name', 'list_id'), array('is_enabled' => 1));
		
		$lists = array();
		foreach ($mailpoet_lists as $list)
		{
			if (is_array($list) && isset($list['list_id']) && isset($list['name']))
			{
				$lists[] = array(
						'id'	=> $list['list_id'],
						'name'	=> str_replace(array("'", '"'), '', $list['name'])
				);
			}
		}
			
		if (count($lists) <= 0)
		{
			return array(
					'success'	=> false,
					'message'	=> 'No list defined in MailPoet'
			);
		}
		else
		{
			return array(
					'success'	=> true,
					'data'		=> json_encode($lists)
			);
		}
	}
	
	function mailpoet3_subscribe($id, $data, $options)
	{
		if( !is_plugin_active('mailpoet/mailpoet.php') )
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: MailPoet 3 is not installed or activated'
			);
		}
	
		if( empty($options['mailpoet3listid']) )
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: No MailPoet 3 list selected'
			);
		}
	
		$email = strtolower($data[$options['emailfieldname']]);
	
		$firstname = null;
		$lastname = null;
	
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$names = explode(' ', $data[$options['namefieldname']]);
			$firstname = $names[0];
			$lastname = ( count($names) > 1 ) ? array_pop($names) : null;
		}
	
		if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			$firstname = $data[$options['firstnamefieldname']];
	
		if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			$lastname =  $data[$options['lastnamefieldname']];
	
		$user_data = array(
				'email' => $email
		);
	
		if (!empty($firstname))
			$user_data['first_name'] = $firstname;
	
		if (!empty($firstname))
			$user_data['last_name'] = $lastname;
	
		$list = array($options['mailpoet3listid']);
		
		$options = array(
				'send_confirmation_email' => ((isset($options['mailpoet3sendconfirmationemail']) && $options['mailpoet3sendconfirmationemail']) ? true: false),
				'schedule_welcome_email' => ((isset($options['mailpoet3schedulewelcomeemail']) && $options['mailpoet3schedulewelcomeemail']) ? true: false),
		);
	
		require_once('mailpoet3/mailpoet3Api.php');
		
		// check already exist
		$subscriber_data = array();
		try {
			$subscriber_data = mailpoet3_getsubscriber($email);
		} catch(Exception $exception) {}

		// already exist
		if (!empty($subscriber_data))
		{
			try {

				mailpoet3_subscribetolists($email, $list, $options); 

				return array(
					'success'	=> 	true
				);
			} catch(Exception $exception) {
				
				return array(
					'success'	=> false,
					'message'	=> $exception->getMessage()
				);
			}
		}
		
		// new subscriber
		try {
			
			$subscriber = mailpoet3_addsubscriber($user_data, $list, $options);
						
			return array(
				'success'	=> 	true
			);
		} catch(Exception $exception) {
			
			$ret = array(
				'success'	=> false,
				'message'	=> $exception->getMessage()
			);

			if ($ret['message'] == 'This subscriber already exists.')
			{
				$ret['errorcode'] = -1;
			}

			return $ret;
		}
	}
	
	function mailpoet3_getlists()
	{
		if( !is_plugin_active('mailpoet/mailpoet.php') )
		{
			return array(
					'success'	=> false,
					'message'	=> 'MailPoet 3 is not installed or activated'
			);
		}
	
		require_once('mailpoet3/mailpoet3Api.php');
		
		$subscription_lists = mailpoet3_getlists();
			
		$lists = array();
		foreach ($subscription_lists as $list)
		{
			if (is_array($list) && isset($list['id']) && isset($list['name']))
			{
				$lists[] = array(
						'id'	=> $list['id'],
						'name'	=> str_replace(array("'", '"'), '', $list['name'])
				);
			}
		}
			
		if (count($lists) <= 0)
		{
			return array(
					'success'	=> false,
					'message'	=> 'No list defined in MailPoet 3'
			);
		}
		else
		{
			return array(
					'success'	=> true,
					'data'		=> json_encode($lists)
			);
		}
	}
	
	function infusionsoft_subscribe($id, $data, $options)
	{
		if ($options['subscription'] != 'infusionsoft' || empty($options['infusionsoftsubdomain']) || empty($options['infusionsoftapikey']) || empty($options['infusionsoftlistid']))
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	3,
					'message'	=> 	'Error: no email service configured'
			);
		}
		
		$subdomain = $options['infusionsoftsubdomain'];
		$apikey = $options['infusionsoftapikey'];
		$tagid = $options['infusionsoftlistid'];
		
		$sendemail = false;
		$templateid = '';
		if ( isset($options['infusionsoftdoubleoptin']) && $options['infusionsoftdoubleoptin'] && !empty($options['infusionsofttemplateid']))
		{
			$sendemail = true;
			$templateid = $options['infusionsofttemplateid'];
		}
		
		$email = strtolower($data[$options['emailfieldname']]);
		
		$firstname = null;
		$lastname = null;
		
		if ( $options['showname'] && array_key_exists($options['namefieldname'], $data) )
		{
			$names = explode(' ', $data[$options['namefieldname']]);
			$firstname = $names[0];
			$lastname = ( count($names) > 1 ) ? array_pop($names) : null;
		}
		
		if ( $options['showfirstname'] && array_key_exists($options['firstnamefieldname'], $data) )
			$firstname = $data[$options['firstnamefieldname']];
		
		if ( $options['showlastname'] && array_key_exists($options['lastnamefieldname'], $data) )
			$lastname =  $data[$options['lastnamefieldname']];
		
		$phone = ( $options['showphone'] && array_key_exists($options['phonefieldname'], $data) ) ? $data[$options['phonefieldname']] : null;
		$orgname = ( $options['showcompany'] && array_key_exists($options['companyfieldname'], $data) ) ? $data[$options['companyfieldname']] : null;
		
		$user = array(
					'Email' => $email
				);
		
		if (!empty($firstname))
			$user['FirstName'] = $firstname;
		
		if (!empty($lastname))
			$user['LastName'] = $lastname;
		
		if (!empty($phone))
			$user['Phone1'] = $phone;
		
		if (!empty($orgname))
			$user['Company'] = $orgname;
		
		require_once('infusionsoft/infusionsoftApi.php');
		
		$infusionsoft = new InfusionsoftAPI($subdomain, $apikey);
		
		return $infusionsoft->add_contact($user, $tagid, $sendemail, $templateid);
	}
	
	function infusionsoft_getlists($subdomain, $apikey)
	{
		if ( empty($subdomain) || empty($apikey) )
		{
			return array(
					'success'	=> false,
					'message'	=> 'The subdomain or the API key is invalid.'
			);
		}
		
		require_once('infusionsoft/infusionsoftApi.php');
		
		$infusionsoft = new InfusionsoftAPI($subdomain, $apikey);
		
		$tags = $infusionsoft->get_taglist();

		if (!empty($tags) && is_array($tags))
		{
			$lists = array();
			foreach ($tags as $list)
			{
				if (is_array($list) && isset($list['Id']) && isset($list['GroupName']))
				{
					$lists[] = array(
							'id'	=> $list['Id'],
							'name'	=> str_replace(array("'", '"'), '', $list['GroupName'])
					);
				}
			}

			if (count($lists) <= 0)
			{
				return array(
						'success'	=> false,
						'message'	=> 'No list defined in Infusionsoft'
				);
			}
			else
			{
				return array(
						'success'	=> true,
						'data'		=> json_encode($lists)
				);
			}
		}
		else
		{
			return array(
					'success'	=> 	false,
					'errorcode'	=> 	6,
					'message'	=> 	'Error: Cannot connect to infusionsoft service. Please make sure the subdomain and API key are correct.'
			);
		}
	}
	
	function check_g_recaptcha($gkey, $gresponse, $ipaddress)
	{
		try {
	
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			$data = array(
					'secret'   => $gkey,
					'response' => $gresponse,
					'remoteip' => $ipaddress
			);
	
			$options = array(
					'http' => array(
							'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
							'method'  => 'POST',
							'content' => http_build_query($data)
					)
			);
	
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			return json_decode($result)->success;
		}
		catch (Exception $e) {
			return null;
		}
	}
}