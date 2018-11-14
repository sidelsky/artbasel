<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Settings_Column_Gravatar extends AC_Settings_Column_Image {

	public function format( $value, $original_value ) {
		return ac_helper()->image->get_image( $value, $this->get_size_args(), true );
	}

}
