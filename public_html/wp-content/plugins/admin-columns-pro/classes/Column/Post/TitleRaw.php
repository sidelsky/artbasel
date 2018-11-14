<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 4.0
 */
class ACP_Column_Post_TitleRaw extends AC_Column_Post_TitleRaw
	implements ACP_Column_SortingInterface, ACP_Column_EditingInterface {

	public function sorting() {
		$model = new ACP_Sorting_Model( $this );
		$model->set_orderby( 'title' );

		return $model;
	}

	public function editing() {
		return new ACP_Editing_Model_Post_TitleRaw( $this );
	}

}
