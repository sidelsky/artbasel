<?php
/**
 * OrderBy filter class
 *
 * Offers method specific to Ajax Order By filter
 *
 * @author  YITH
 * @package YITH WooCommerce Ajax Product FIlter
 * @version 4.0.0
 */

if ( ! defined( 'YITH_WCAN' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCAN_Filter_Orderby' ) ) {
	/**
	 * OrderBy Filter Handling
	 *
	 * @since 1.0.0
	 */
	class YITH_WCAN_Filter_Orderby extends YITH_WCAN_Filter {

		/**
		 * Filter type
		 *
		 * @var string
		 */
		protected $type = 'orderby';

		/**
		 * Method that will output content of the filter on frontend
		 *
		 * @return string Template for current filter
		 */
		public function render() {
			$atts = array(
				'filter' => $this,
				'preset' => $this->get_preset(),
			);

			return yith_wcan_get_template( 'filters/filter-orderby.php', $atts, false );
		}

		/**
		 * Checks if we're currently sorting by a specific order
		 *
		 * @param string $order Order to check.
		 *
		 * @return bool Whether products are sorted by specified order
		 */
		public function is_order_active( $order ) {
			return YITH_WCAN_Query()->is_ordered_by( $order );
		}

		/* === ORDER BY OPTIONS === */

		/**
		 * Retrieve an array of sorting options for Order by filter
		 *
		 * @param string $context Context of the operation.
		 * @return array Array of sorting options
		 */
		public function get_formatted_order_options( $context = 'view' ) {
			$supported_orders = YITH_WCAN_Filter_Factory::get_supported_orders();
			$selected_orders  = $this->get_order_options( $context );
			$res              = array();

			if ( ! $selected_orders || ! $supported_orders ) {
				return $res;
			}

			foreach ( $selected_orders as $order ) {
				if ( ! isset( $supported_orders[ $order ] ) ) {
					continue;
				}

				$res[ $order ] = $supported_orders[ $order ];
			}

			return $res;
		}
	}
}
