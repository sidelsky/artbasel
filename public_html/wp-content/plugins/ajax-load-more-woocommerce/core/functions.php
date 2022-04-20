<?php
/**
 * Is cache ALM enabled on shop main page.
 *
 * @since 1.1
 */
function alm_woo_is_shop_cache() {
	if ( ! alm_woo_is_shop_enabled() ) {
		return false;
	}
	$active = ( null === get_option( ALM_WOO_PREFIX . 'shop_cache' ) || empty( get_option( ALM_WOO_PREFIX . 'shop_cache' ) ) ) ? false : true;
	if ( ! $active ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Is cache ALM enabled on shop main page.
 *
 * @since 1.1
 */
function alm_woo_is_shop_archive_cache() {
	if ( ! alm_woo_is_shop_archive_enabled() ) {
		return false;
	}

	$active = ( null === get_option( ALM_WOO_PREFIX . 'shop_archives_cache' ) || empty( get_option( ALM_WOO_PREFIX . 'shop_archives_cache' ) ) ) ? false : true;
	if ( ! $active ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Is ALM enabled on shop main page.
 *
 * @since 1.0
 */
function alm_woo_is_shop_enabled(){
	$show = ( null === get_option( ALM_WOO_PREFIX . 'shop_main' ) || empty( get_option( ALM_WOO_PREFIX . 'shop_main' ) ) ) ? false : true;
	if ( is_shop() && ! $show ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Is ALM enabled on shop archive pages.
 *
 * @since 1.0
 */
function alm_woo_is_shop_archive_enabled() {
	$show = ( null === get_option( ALM_WOO_PREFIX . 'shop_archives' ) || empty( get_option( ALM_WOO_PREFIX . 'shop_archives' ) ) ) ? false : true;
	if ( ( is_product_category() || is_product_tag() ) && ! $show ) {
		return false;
	} else {
		return true;
	}

}
/**
 * Is ALM enabled for shop searches.
 *
 * @since 1.0
 */
function alm_woo_is_shop_search_enabled() {
	$show = ( null === get_option( ALM_WOO_PREFIX . 'shop_search' ) || empty( get_option( ALM_WOO_PREFIX . 'shop_search' ) ) ) ? false : true;
	if ( ( is_search() && is_post_type_archive( 'product' ) ) && ! $show ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Is WooCommerce activated and on a WooCommerce page.
 *
 * @since 1.0
 */
function alm_is_woo_archive() {
	if ( alm_is_woo_activated() && function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Get default WooCommerce props.
 *
 * @param string $type string Prop name.
 * @param string $default string Default value.
 * @since 1.0
 */
function alm_woo_get_loop_prop( $type = '', $default = '' ) {
	if ( function_exists( 'wc_get_loop_prop' ) && ! empty( $type ) ) {
		$prop  = wc_get_loop_prop( $type );
		$value = ( $prop ) ? $prop : $default;
		return $value;
	}
}

/**
 * Hide the woocommerce pagination on ALM pages.
 *
 * @since 1.0
 */
function alm_woo_hide_pagination() {
	$hide_pagination = apply_filters( 'alm_woocommerce_hide_pagination', true );
	$classname       = apply_filters( 'alm_woocommerce_pagination_class', '.woocommerce-pagination' );
	return $classname && $hide_pagination ? $classname . '{display:none !important;}' : '';
}

/**
 * Hide the woocommerce orderby filter on ALM pages.
 *
 * @since 1.0
 */
function alm_woo_hide_orderby() {
	$hide_ordering = apply_filters( 'alm_woocommerce_hide_orderby', false );
	$classname     = apply_filters( 'alm_woocommerce_orderby_class', 'woocommerce-ordering' );
	return $hide_ordering ? '.' . $classname . '{display:none !important;}' : '';
}
