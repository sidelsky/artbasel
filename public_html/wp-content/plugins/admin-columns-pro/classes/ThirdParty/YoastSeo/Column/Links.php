<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_ThirdParty_YoastSeo_Column_Links extends ACP_ThirdParty_YoastSeo_Column {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'wpseo-links' );
	}

}
