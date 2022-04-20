<?php
/**
 * Admin class
 *
 * @author  YITH
 * @package YITH WooCommerce Ajax Navigation
 * @version 4.0.0
 */

if ( ! defined( 'YITH_WCAN' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCAN_Admin_Premium' ) ) {
	/**
	 * Admin class.
	 * This class manage all the admin features.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCAN_Admin_Premium extends YITH_WCAN_Admin {

		/**
		 * Construct
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function __construct() {
			parent::__construct();

			// updates available tabs.
			add_filter( 'yith_wcan_settings_tabs', array( $this, 'settings_tabs' ) );

			// Add premium options.
			add_filter( 'yith_wcan_panel_general_options', array( $this, 'add_general_options' ) );
			add_filter( 'yith_wcan_panel_preset_options', array( $this, 'add_preset_options' ) );
			add_filter( 'yith_wcan_panel_filter_options', array( $this, 'add_filter_options' ) );
			add_filter( 'yith_wcan_panel_seo_options', array( $this, 'add_seo_options' ) );
			add_filter( 'yith_wcan_panel_legacy_options', array( $this, 'add_legacy_options' ) );
		}

		/* === PANEL METHODS === */

		/**
		 * Add premium plugin options
		 *
		 * @param array $settings List of filter options.
		 * @return array Filtered list of filter options.
		 */
		public function add_general_options( $settings ) {
			$options = $settings['general'];

			$additional_options_batch_1 = array(
				'instant_filter' => array(
					'name'      => _x( 'Filter mode', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose to apply filters in real time using AJAX or whether to show a button to apply all filters', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_instant_filters',
					'type'      => 'yith-field',
					'yith-type' => 'radio',
					'default'   => 'yes',
					'options'   => array(
						'yes' => _x( 'Instant result', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
						'no'  => _x( 'By clicking "Apply filters" button', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					),
				),

				'ajax_filter' => array(
					'name'      => _x( 'Show results', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose whether to load the results on the same page using AJAX or load the results on a new page', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_ajax_filters',
					'type'      => 'yith-field',
					'default'   => 'yes',
					'yith-type' => 'radio',
					'options'   => array(
						'yes' => _x( 'In same page using AJAX', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
						'no'  => _x( 'Reload the page without AJAX', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					),
				),

				'hide_empty_terms' => array(
					'name'      => _x( 'Hide empty terms', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable to hide empty terms from filters section', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_hide_empty_terms',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'hide_out_of_stock' => array(
					'name'      => _x( 'Hide out of stock products', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable to hide "out of stock" products from the results.', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_hide_out_of_stock_products',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),
			);

			$options = yith_wcan_merge_in_array( $options, $additional_options_batch_1, 'general_section_start' );

			$additional_options_batch_2 = array(
				'show_clear_filter' => array(
					'name'      => _x( 'Show "Clear" above each filter', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable to show the "Clear" link above each filter of the preset', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_show_clear_filter',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'show_active_labels' => array(
					'name'      => _x( 'Show active filters as labels', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable to show the active filters as labels. Labels show the current filters selection, and can be used to remove any active filter.', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_show_active_labels',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'active_labels_position' => array(
					'name'      => _x( 'Active filters labels position', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose the default position for Active Filters labels', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_active_labels_position',
					'type'      => 'yith-field',
					'yith-type' => 'radio',
					'default'   => 'before_filters',
					'options'   => array(
						'before_filters'  => _x( 'Before filters', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
						'after_filters'   => _x( 'After filters', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
						'before_products' => _x( 'Above products list<small>When using WooCommerce\'s Gutenberg product blocks, this may not work as expected; in these cases you can place Reset Button anywhere in the page using <code>[yith_wcan_active_filters_labels]</code> shortcode or <code>YITH Mobile Filters Modal Opener</code> block</small>', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					),
					'deps'      => array(
						'ids'    => 'yith_wcan_show_active_labels',
						'values' => 'yes',
					),
				),

				'active_labels_with_titles' => array(
					'name'      => _x( 'Show titles for active filter labels', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable to show labels subdivided by filter, and to show a title for each group', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_active_labels_with_titles',
					'type'      => 'yith-field',
					'default'   => 'yes',
					'yith-type' => 'onoff',
					'deps'      => array(
						'ids'    => 'yith_wcan_show_active_labels',
						'values' => 'yes',
					),
				),

				'scroll_top' => array(
					'name'      => _x( 'Scroll top after filtering', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable this option if you want to scroll to top after filtering.', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_scroll_top',
					'default'   => 'no',
					'type'      => 'yith-field',
					'yith-type' => 'onoff',
				),

				'modal_on_mobile' => array(
					'name'      => _x( 'Show as modal on mobile', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable this option if you want to show filter section as a modal on mobile devices.<small>The modal opener will appear before products. When using WooCommerce\'s Gutenberg product blocks, this may not work as expected. If this is the case, you can place the Modal opener button anywhere in the page using <code>[yith_wcan_mobile_modal_opener]</code> shortcode or <code>YITH Mobile Filters Modal Opener</code> block</small>', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yith_wcan_modal_on_mobile',
					'default'   => 'no',
					'type'      => 'yith-field',
					'yith-type' => 'onoff',
				),
			);

			$options = yith_wcan_merge_in_array( $options, $additional_options_batch_2, 'reset_button_position' );

			// add premium options to existing settings.
			$options['reset_button_position']['options'] = array_merge(
				$options['reset_button_position']['options'],
				array(
					'after_active_labels' => _x( 'Inline with active filters', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
				)
			);

			$settings['general'] = $options;

			return $settings;
		}

		/**
		 * Add premium preset options
		 *
		 * @param array $settings List of preset options.
		 * @return array Filtered list of preset options.
		 */
		public function add_preset_options( $settings ) {
			$settings['preset_layout'] = array(
				'label'   => _x( 'Preset layout', '[Admin] Label in new preset page', 'yith-woocommerce-ajax-navigation' ),
				'type'    => 'radio',
				'options' => YITH_WCAN_Preset_Factory::get_supported_layouts(),
				'desc'  => _x( 'Choose the layout for this filter preset', '[Admin] Label in new preset page', 'yith-woocommerce-ajax-navigation' ),
			);

			return $settings;
		}

		/**
		 * Add premium filter options
		 *
		 * @param array $settings List of filter options.
		 * @return array Filtered list of filter options.
		 */
		public function add_filter_options( $settings ) {
			// add premium settings.
			$additional_options_batch_2 = array(
				'show_search' => array(
					'label'   => _x( 'Show search field', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'onoff',
					'desc'  => _x( 'Enable if you want to show search field inside dropdown', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'price_slider_design' => array(
					'label'   => _x( 'Price slider style', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'radio',
					'options' => array(
						'slider' => _x( 'Show slider', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'fields' => _x( 'Show "From" and "To" fields', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'both'   => _x( 'Show both', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					),
					'desc'  => _x( 'Choose the design for your price slider', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'price_slider_adaptive_limits' => array(
					'label'   => _x( 'Adaptive limits', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'onoff',
					'desc'  => _x( 'Automatically calculate min/max values for the slider, depending on current selection of products.', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'price_slider_min' => array(
					'label'             => _x( 'Slider min value', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'              => 'number',
					'min'               => 0,
					'step'              => 0.01,
					'custom_attributes' => 'data-currency="' . esc_attr( get_woocommerce_currency_symbol() ) . '"',
					'desc'              => _x( 'Set the minimum value for the price slider', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'price_slider_max' => array(
					'label'             => _x( 'Slider max value', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'              => 'number',
					'min'               => 0,
					'step'              => 0.01,
					'custom_attributes' => 'data-currency="' . esc_attr( get_woocommerce_currency_symbol() ) . '"',
					'desc'              => _x( 'Set the maximum value for the price slider', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'price_slider_step' => array(
					'label'             => _x( 'Slider step', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'              => 'number',
					'min'               => 0.01,
					'step'              => 0.01,
					'custom_attributes' => 'data-currency="' . esc_attr( get_woocommerce_currency_symbol() ) . '"',
					'desc'              => _x( 'Set the value for each increment of the price slider', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'order_options'  => array(
					'label'   => _x( 'Order options', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'select-buttons',
					'multiple' => true,
					'class'   => 'wc-enhanced-select',
					'options' => YITH_WCAN_Filter_Factory::get_supported_orders(),
					'desc'  => _x( 'Select sorting options to show', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'price_ranges' => array(
					'label'   => _x( 'Customize price ranges', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'custom',
					'action'  => 'yith_wcan_price_ranges',
				),

				'show_stock_filter' => array(
					'label'   => _x( 'Show stock filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'onoff',
					'desc'  => _x( 'Enable if you want to show "In Stock" filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'show_sale_filter' => array(
					'label'   => _x( 'Show sale filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'onoff',
					'desc'  => _x( 'Enable if you want to show "On Sale" filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'show_featured_filter' => array(
					'label'   => _x( 'Show featured filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'onoff',
					'desc'  => _x( 'Enable if you want to show "Featured" filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'show_toggle' => array(
					'label'   => _x( 'Show as toggle', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'class'   => 'show-toggle',
					'type'    => 'onoff',
					'desc'  => _x( 'Enable if you want to show this filter as a toggle', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'toggle_style' => array(
					'label'   => _x( 'Toggle style', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'class'   => 'toggle-style',
					'type'    => 'radio',
					'options' => array(
						'closed' => _x( 'Closed by default', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'opened' => _x( 'Opened by default', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					),
					'desc'  => _x( 'Choose if the toggle has to be closed or opened by default', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'order_by' => array(
					'label'   => _x( 'Order by', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'select',
					'class'   => 'wc-enhanced-select order-by',
					'options' => array(
						'name' => _x( 'Name', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'slug' => _x( 'Slug', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'count' => _x( 'Term count', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'term_order' => _x( 'Term order', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'include' => _x( 'Drag & drop', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					),
					'desc'  => _x( 'Select the default order for terms of this filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'order' => array(
					'label'   => _x( 'Order type', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'select',
					'class'   => 'wc-enhanced-select',
					'options' => array(
						'asc' => _x( 'ASC', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'desc' => _x( 'DESC', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					),
					'desc'  => _x( 'Select the default order for terms of this filter', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),

				'show_count' => array(
					'label'   => _x( 'Show count of items', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'onoff',
					'desc'  => _x( 'Enable if you want to show how many items are available for each term', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),
			);
			$settings = yith_wcan_merge_in_array( $settings, $additional_options_batch_2, 'terms_options' );

			$additional_options_batch_3 = array(
				'adoptive' => array(
					'label'   => _x( 'Adoptive filtering', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'type'    => 'radio',
					'options' => array(
						'hide' => _x( 'Terms will be hidden', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
						'or'  => _x( 'Terms will be visible, but not clickable', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					),
					'desc' => _x( 'Decide how to manage filter options that show no results when applying filters. Choose to hide them or make them visible (this will show them in lighter grey and not clickable)', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),
			);
			$settings = yith_wcan_merge_in_array( $settings, $additional_options_batch_3, 'relation' );

			// add premium options to existing settings.
			$settings['hierarchical']['options'] = yith_wcan_merge_in_array(
				$settings['hierarchical']['options'],
				array(
					'collapsed' => _x( 'Yes, with terms collapsed', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
					'expanded' => _x( 'Yes, with terms expanded', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' ),
				),
				'parents_only'
			);

			$settings['hierarchical']['options']['open'] = _x( 'Yes, without toggles', '[Admin] Filter edit form', 'yith-woocommerce-ajax-navigation' );

			return $settings;
		}

		/**
		 * Add premium filter options
		 *
		 * @param array $settings List of filter options.
		 * @return array Filtered list of filter options.
		 */
		public function add_seo_options( $settings ) {
			$options = $settings['seo'];

			// add premium options to existing settings.
			$options['change_url']['options'] = yith_wcan_merge_in_array(
				$options['change_url']['options'],
				array(
					'custom' => _x( 'Use plugin customized permalinks', '[ADMIN] Seo settings page', 'yith-woocommerce-ajax-navigation' ),
				),
				'yes',
				'before'
			);

			$settings['seo'] = $options;

			return $settings;
		}

		/**
		 * Add premium plugin options to Legacy tab
		 *
		 * @param array $settings List of legacy options.
		 * @return array Filtered list of legacy options.
		 */
		public function add_legacy_options( $settings ) {
			$options = $settings['legacy'];

			$additional_options_batch_1 = array(
				'scroll_to_top' => array(
					'name'      => _x( 'Scroll to top after filtering', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose whether you want to enable the "Scroll to top" option on Desktop, Mobile, or on both of them', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_scroll_top_mode]',
					'type'      => 'yith-field',
					'default'   => 'menu_order',
					'yith-type' => 'radio',
					'options'   => array(
						'disabled' => _x( 'Disabled', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
						'mobile'   => _x( 'Mobile', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
						'desktop'  => _x( 'Desktop', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
						'both'     => _x( 'Mobile and Desktop', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					),
				),

				'widget_title_selector' => array(
					'name'      => _x( 'Widget title selector', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enter here the CSS selector (class or ID) of the widget title', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_ajax_widget_title_class]',
					'type'      => 'yith-field',
					'yith-type' => 'text',
					'default'   => 'h3.widget-title',
				),

				'widget_container' => array(
					'name'      => _x( 'Widget container', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enter here the CSS selector (class or ID) of the widget container', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_ajax_widget_wrapper_class]',
					'type'      => 'yith-field',
					'yith-type' => 'text',
					'default'   => '.widget',
				),

				'filter_style' => array(
					'name'      => _x( 'Filter style', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose the style of the filter inside widgets', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_ajax_shop_filter_style]',
					'type'      => 'yith-field',
					'default'   => 'standard',
					'yith-type' => 'radio',
					'options'   => array(
						'standard'   => _x( '"x" icon before active filters', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
						'checkboxes' => _x( 'Checkboxes', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					),
				),
			);

			$options = yith_wcan_merge_in_array( $options, $additional_options_batch_1, 'order_by' );

			$additional_options_batch_2 = array(
				'legacy_general_start' => array(
					'name' => _x( 'General options', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'yith_wcan_legacy_general_settings',
				),

				'ajax_loader' => array(
					'name'      => _x( 'Ajax Loader', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose loading icon you want to use for your widget filters', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_ajax_loader]',
					'type'      => 'yith-field',
					'yith-type' => 'text',
					'default'   => YITH_WCAN_URL . 'assets/images/ajax-loader.gif',
				),

				'ajax_price_filter' => array(
					'name'      => _x( 'Filter by price using AJAX', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Filter products via AJAX when using WooCommerce price filter widget', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_ajax_price_filter]',
					'type'      => 'yith-field',
					'default'   => 'yes',
					'yith-type' => 'onoff',
				),

				'price_slider' => array(
					'name'      => _x( 'Use slider for price filtering', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Transform default WooCommerce price filter into a slider', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_ajax_price_filter]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'ajax_price_slider' => array(
					'name'      => _x( 'Filter by price using AJAX slider', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Filter products via AJAX when using WooCommerce price filter widget', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_ajax_price_filter_slider]',
					'type'      => 'yith-field',
					'default'   => 'yes',
					'yith-type' => 'onoff',
				),

				'price_dropdown' => array(
					'name'      => _x( 'Add toggle for price filter widget', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Show price filtering widget as a toggle', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_dropdown_price_filter]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'price_dropdown_style' => array(
					'name'      => _x( 'Chose how to show price filter toggle', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Choose whether to show price filtering widget as an open or closed toggle', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_dropdown_style]',
					'type'      => 'yith-field',
					'default'   => 'open',
					'yith-type' => 'radio',
					'options'   => array(
						'open' => _x( 'Opened', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
						'close' => _x( 'Closed', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					),
				),

				'ajax_shop_pagination' => array(
					'name'      => _x( 'Enable ajax pagination', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Make shop pagination anchors load new page via ajax', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_ajax_shop_pagination]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'shop_pagination_selector' => array(
					'name'      => _x( 'Shop pagination selector', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enter here the CSS selector (class or ID) of the shop pagination', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_ajax_shop_pagination_anchor_class]',
					'type'      => 'yith-field',
					'yith-type' => 'text',
					'default'   => 'a.page-numbers',
				),

				'show_current_categories' => array(
					'name'      => _x( 'Show current categories', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable if you want to show link to current category in the filter, when visiting category page', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_show_current_categories_link]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'show_all_categories' => array(
					'name'      => _x( 'Show "All categories" anchor', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable if you want to show a link to retrieve products from all categories, after a category filter is applied', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_see_all_categories_link]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'all_categories_label' => array(
					'name'      => _x( '"All categories" anchor label', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enter here the text you want to use for "All categories" anchor', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_see_all_categories_link_text]',
					'type'      => 'yith-field',
					'yith-type' => 'text',
					'default'   => _x( 'See all categories', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
				),

				'show_all_tags' => array(
					'name'      => _x( 'Show "All tags" anchor', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enable if you want to show a link to retrieve products from all tags, after a category filter is applied', '[ADMIN] General settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_see_all_tags_link]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'all_tags_label' => array(
					'name'      => _x( '"All tags" anchor label', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Enter here the text you want to use for "All tags" anchor', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_see_all_tags_link_text]',
					'type'      => 'yith-field',
					'yith-type' => 'text',
					'default'   => _x( 'See all tags', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
				),


				'hierarchical_tags' => array(
					'name'      => _x( 'Hierarchical tags', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'desc'      => _x( 'Make product tag taxonomy hierarchical', '[ADMIN] Legacy settings page', 'yith-woocommerce-ajax-navigation' ),
					'id'        => 'yit_wcan_options[yith_wcan_enable_hierarchical_tags_link]',
					'type'      => 'yith-field',
					'default'   => 'no',
					'yith-type' => 'onoff',
				),

				'legacy_general_end' => array(
					'type' => 'sectionend',
					'id' => 'yith_wcan_legacy_general_settings',
				),
			);

			$options = yith_wcan_merge_in_array( $options, $additional_options_batch_2, 'legacy_frontend_end' );

			$settings['legacy'] = $options;

			return $settings;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @param array $tabs Array of available tabs.
		 *
		 * @return   array Filtered array of tabs
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function settings_tabs( $tabs ) {
			unset( $tabs['premium'] );

			$tabs = yith_wcan_merge_in_array(
				$tabs,
				array(
					'customization' => _x( 'Customization', '[Admin] tab name', 'yith-woocommerce-ajax-navigation' ),
				),
				'general'
			);

			return $tabs;
		}

		/**
		 * Prints single item of "Term edit" template
		 *
		 * @param int    $id Current row id.
		 * @param int    $term_id Current term id.
		 * @param string $term_name Current term name.
		 * @param string $term_options Options for current term (it may include label, tooltip, colors, and image).
		 *
		 * @return void
		 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
		 */
		public function filter_term_field( $id, $term_id, $term_name, $term_options = array() ) {
			// just include template, and provide passed terms.
			include( YITH_WCAN_DIR . 'templates/admin/preset-filter-term-advanced.php' );
		}

		/**
		 * Prints "Price Ranges edit" template
		 *
		 * @param array $field Array of options for current template.
		 *
		 * @return void
		 * @author Antonio La Rocca <antonio.larocca@yithemes.com>
		 */
		public function filter_ranges_field( $field ) {
			$id = isset( $field['index'] ) ? $field['index'] : 0;
			$ranges = isset( $field['value'] ) ? $field['value'] : array();

			include( YITH_WCAN_DIR . 'templates/admin/preset-filter-ranges.php' );
		}

		/* === TOOLS === */

		/**
		 * Register available plugin tools
		 *
		 * @param array $tools Available tools.
		 * @return array Filtered array of tools.
		 */
		public function register_tools( $tools ) {
			$tools = parent::register_tools( $tools );

			$additional_tools = array(
				'clear_filter_sessions' => array(
					'name'   => _x( 'Clear Product Filter sessions', '[ADMIN] WooCommerce Tools tab, name of the tool', 'yith-woocommerce-ajax-navigation' ),
					'button' => _x( 'Clear', '[ADMIN] WooCommerce Tools tab, button for the tool', 'yith-woocommerce-ajax-navigation' ),
					'desc'   => _x( 'This will clear all filter sessions on your site. It may be useful if you want to free some space (previously shared sessions won\'t be reachable any longer).', '[ADMIN] WooCommerce Tools tab, description of the tool', 'yith-woocommerce-ajax-navigation' ),
					'callback' => array( YITH_WCAN_Sessions(), 'delete_all' ),
				),
			);

			$tools = array_merge(
				$tools,
				$additional_tools
			);

			return $tools;
		}

		/* === PLUGIN META === */

		/**
		 * Adds action links to plugin row in plugins.php admin page
		 *
		 * @param array  $new_row_meta_args Array of data to filter.
		 * @param array  $plugin_meta       Array of plugin meta.
		 * @param string $plugin_file       Path to init file.
		 * @param array  $plugin_data       Array of plugin data.
		 * @param string $status            Not used.
		 * @param string $init_file         Constant containing plugin int path.
		 *
		 * @return   array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      plugin_row_meta
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WCAN_INIT' ) {
			$new_row_meta_args = parent::plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file );

			if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ) {
				$new_row_meta_args['is_premium'] = true;
			}

			return $new_row_meta_args;
		}

	}
}
