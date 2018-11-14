<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 4.0.7
 */
class ACP_Column_User_Name extends AC_Column_User_Name
	implements ACP_Column_SortingInterface {

	public function sorting() {
		return new ACP_Sorting_Model_User_Name( $this );
	}

}
