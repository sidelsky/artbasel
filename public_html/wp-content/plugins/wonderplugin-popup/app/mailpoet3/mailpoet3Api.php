<?php

if ( ! defined( 'ABSPATH' ) )
	exit;
	
function mailpoet3_addsubscriber($user_data, $lists, $options) {
		
	return \MailPoet\API\API::MP('v1')->addSubscriber($user_data, $lists, $options);
}

function mailpoet3_getlists() {
		
	return \MailPoet\API\API::MP('v1')->getLists();
}

function mailpoet3_getsubscriber($email) {

	return \MailPoet\API\API::MP('v1')->getSubscriber($email);
}

function mailpoet3_subscribetolists($email, $lists, $options) {

	return \MailPoet\API\API::MP('v1')->subscribeToLists($email, $lists, $options); 
}