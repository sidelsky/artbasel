<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Filtering_Model_Post_ChildPages extends ACP_Filtering_Model {

	public function filter_by_description( $where ) {
		global $wpdb;

		$where .= $wpdb->prepare( " AND {$wpdb->posts}.ID IN (
		                SELECT DISTINCT {$wpdb->posts}.post_parent
                        FROM {$wpdb->posts} 
                        WHERE {$wpdb->posts}.post_parent > 1
                            AND {$wpdb->posts}.post_status = 'publish'
                            AND {$wpdb->posts}.post_type = %s
                   )", $this->column->get_post_type() );

		return $where;
	}

	public function get_filtering_vars( $vars ) {
		add_filter( 'posts_where', array( $this, 'filter_by_description' ) );

		return $vars;
	}

	public function get_filtering_data() {
		return array(
			'options' => array(
				'has_child_page' => 'Has Child Pages',
			),
		);
	}

}
