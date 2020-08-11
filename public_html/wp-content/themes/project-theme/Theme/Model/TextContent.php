<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class TextContent {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getTextContent() {
       $darkBackground = $this->layout['dark_background'];
       $textContentTitle = $this->layout['text_content_title'];
       $textContentCopy = $this->layout['text_content_copy'];
       $textContentDate = $this->layout['text_content_date'];
       $textContentLink = $this->layout['text_content_link'];
       $textContentLinkDescription = $this->layout['text_content_link_description'];
       $showEnquireButton = $this ->layout['show_enquire_button'];
       //$inquireMultipleEmailAddresses = $this ->layout['inquire_multiple_email_addresses'];
       $idCode = $this ->layout['id_code'];

	return [
      'layoutName' => 'text_content',
      'darkBackground' => $darkBackground,
      'textContentTitle' => $textContentTitle,
      'textContentCopy' => $textContentCopy,
      'textContentDate' => $textContentDate,
      'textContentLink' => $textContentLink,
      'textContentLinkDescription' => $textContentLinkDescription,
      'showEnquireButton' => $showEnquireButton,
      //'inquireMultipleEmailAddresses' => $inquireMultipleEmailAddresses,
      'idCode' => $idCode
	];
   }
}