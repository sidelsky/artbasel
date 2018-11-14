<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Editing_Model_Post_Title extends ACP_Editing_Model_Post_TitleRaw {

	public function get_view_settings() {
		$settings = parent::get_view_settings();

		$settings['js']['selector'] = 'a.row-title';

		return $settings;
	}

}
