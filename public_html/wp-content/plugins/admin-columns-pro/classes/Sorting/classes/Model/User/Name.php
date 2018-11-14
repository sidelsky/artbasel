<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Sorting_Model_User_Name extends ACP_Sorting_Model {

	public function get_sorting_vars() {

		$first = uniqid();
		$last = uniqid();

		$vars = array(
			'meta_query' => array(
				$first => array(
					'key'     => 'first_name',
					'value'   => '',
					'compare' => '!=',
				),
				$last  => array(
					'key'     => 'last_name',
					'value'   => '',
					'compare' => '!=',
				),
			),
			'orderby'    => $first . ' ' . $last,
		);

		if ( acp_sorting()->show_all_results() ) {

			$vars['meta_query'] = array(
				array(
					'relation' => 'OR',
					$first     => array(
						'key'     => 'first_name',
						'compare' => 'EXISTS',
					),
					array(
						'key'     => 'first_name',
						'compare' => 'NOT EXISTS',
					),
				),
				array(
					'relation' => 'OR',
					$last     => array(
						'key'     => 'last_name',
						'compare' => 'EXISTS',
					),
					array(
						'key'     => 'last_name',
						'compare' => 'NOT EXISTS',
					),
				),
			);
		}

		return $vars;
	}

}
