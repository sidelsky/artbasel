<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_ThirdParty_YoastSeo_Filtering_Title extends ACP_Filtering_Model {

	public function get_filtering_data() {

		return array(
			'options' => array(
				'default_title' => __( 'Has Default Title', 'codepress-admin-columns' ),
				'custom_title'  => __( 'Has Custom SEO Title', 'codepress-admin-columns' ),
			),
		);
	}

	public function get_filtering_vars( $vars ) {
		$is_default = ( 'default_title' === $this->get_filter_value() );

		$vars['meta_query']['seo_title'] = array(
			'key'     => $this->get_meta_key(),
			'value'   => '',
			'compare' => $is_default ? '' : '!=',
		);

		if ( $is_default ) {
			$vars['meta_query']['seo_title']['relation'] = 'OR';
			$vars['meta_query']['seo_title'] = array(
				'key'     => $this->get_meta_key(),
				'compare' => 'NOT EXISTS',
			);
		}

		return $vars;
	}

	public function get_meta_key() {
		return '_yoast_wpseo_title';
	}

}
