<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Editing_Model_User_Registered extends ACP_Editing_Model {

	public function get_view_settings() {
		return array(
			'type' => 'date_time',
		);
	}

	public function save( $id, $value ) {
		wp_update_user( array(
			'ID'              => $id,
			'user_registered' => $value,
		) );
	}

}
