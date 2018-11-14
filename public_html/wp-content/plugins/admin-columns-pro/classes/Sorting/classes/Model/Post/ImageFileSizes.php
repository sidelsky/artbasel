<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Sorting_Model_Post_ImageFileSizes extends ACP_Sorting_Model {

	public function get_sorting_vars() {
		$this->set_data_type( 'numeric' );

		$ids = array();

		foreach ( $this->strategy->get_results() as $id ) {
			$ids[ $id ] = array_sum( $this->column->get_raw_value( $id ) );
		}

		return array(
			'ids' => $this->sort( $ids ),
		);
	}

}
