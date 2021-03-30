<?php

namespace Theme\Model;
use App\WordPress\WordPress;

class text_iframe_content {

   public function __construct($layout)
   {
	   $this->layout = $layout;
   }

   public function getText_iframe_content() {
      $text_iframe_content = $this->layout['text_iframe_content'];
      $text_content_title = $this->layout['text_content_title'];
      $text_content_copy = $this->layout['text_content_copy'];
      $iframe_html = $this->layout['iframe_html'];

	return [
		'layoutName' => 'text_iframe_content',
      'text_content_title' => $text_content_title,
      'text_content_copy' => $text_content_copy,
      'iframe_html' => $iframe_html,
	];
   }
}
