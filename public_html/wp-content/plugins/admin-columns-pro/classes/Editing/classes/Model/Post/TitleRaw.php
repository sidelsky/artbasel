<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Editing_Model_Post_TitleRaw extends ACP_Editing_Model {

	public function get_edit_value( $id ) {
		return get_post_field( 'post_title', $id );
	}

	public function save( $id, $value ) {
		$this->strategy->update( $id, array( 'post_title' => $value ) );
	}

}
