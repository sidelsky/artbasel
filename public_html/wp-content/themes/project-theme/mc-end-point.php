<?php

/*
	Template Name: Endpoint
*/
// load in mailchimp library
include('mailchimp.php');

// namespace defined in MailChimp.php
use \DrewM\MailChimp\MailChimp;

// connect to mailchimp
$MailChimp = new MailChimp('c235a48c71ada782e6e45a4b8c842049-us14'); // put your API key here
$list = '84804d8fbd'; // put your list ID here
$email = $_GET['EMAIL']; // Get email address from form
$id = md5(strtolower($email)); // Encrypt the email address

// setup th merge fields
$mergeFields = array(
   // *** YOUR FIELDS GO HERE ***
   'EMAIL'=>$_GET['EMAIL'],
   //'FNAME'=>$_GET['FNAME'],
   'WPRESS' => $_GET['WPRESS']
);

// remove empty merge fields
$mergeFields = array_filter($mergeFields);

$result = $MailChimp->put("lists/$list/members/$id", array(
   'email_address'     => $email,
   'status'            => 'subscribed',
   'merge_fields'      => $mergeFields,
   'update_existing'   => true, // YES, update old subscribers!
));
echo json_encode($result);
