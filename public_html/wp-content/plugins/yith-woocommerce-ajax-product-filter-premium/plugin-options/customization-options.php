<?php
/**
 * Customization options
 *
 * @author  YITH
 * @package YITH WooCommerce Ajax Product Filter
 * @version 4.0.0
 */

$default_accent_color = apply_filters( 'yith_wcan_default_accent_color', '#A7144C' );

return apply_filters(
	'yith_wcan_panel_customization_options',
	array(
		'customization' => array(
			'customization_section_start' => array(
				'name' => _x( 'Customization', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'yith_wcan_customization_settings',
			),

			'filters_title' => array(
				'name'      => _x( 'Filters area title', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'desc'      => _x( 'Enter a title to identify the “AJAX filter Preset” section', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_filters_title',
				'type'      => 'yith-field',
				'yith-type' => 'text',
				'default'   => '',
			),

			'filters_colors' => array(
				'name'      => _x( 'Filters area colors', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_filters_colors',
				'type'      => 'yith-field',
				'yith-type' => 'multi-colorpicker',
				'colorpickers' => array(
					array(
						'name' => _x( 'Titles', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
						'id'   => 'titles',
						'default' => '#333333',
					),
					array(
						'name' => _x( 'Background', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
						'id'   => 'background',
						'default' => '#FFFFFF',
					),
					array(
						'name' => _x( 'Accent color', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
						'id'   => 'accent',
						'default' => $default_accent_color,
					),
				),
			),

			'filters_style' => array(
				'name'      => _x( 'Options style', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'desc'      => _x( 'Choose which preset of style options you\'d like to apply to your filters', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_filters_style',
				'type'      => 'yith-field',
				'default'   => 'default',
				'yith-type' => 'radio',
				'options'   => array(
					'default' => _x( 'Theme style', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'custom'  => _x( 'Custom style', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				),
			),

			'color_swatches_style' => array(
				'name'      => _x( 'Color swatch style', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'desc'      => _x( 'Choose the style for color thumbnails', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_color_swatches_style',
				'type'      => 'yith-field',
				'default'   => 'round',
				'yith-type' => 'radio',
				'options'   => array(
					'round' => _x( 'Rounded', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'square'  => _x( 'Square', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				),
			),

			'color_swatches_size' => array(
				'name'      => _x( 'Color swatch size', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'desc'      => _x( 'The size for color thumbnails', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_color_swatches_size',
				'type'      => 'yith-field',
				'default'   => '30',
				'yith-type' => 'number',
				'min'       => '5',
				'max'       => '200',
			),

			'labels_style' => array(
				'name'         => _x( 'Labels style color', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'           => 'yith_wcan_labels_style',
				'type'         => 'yith-field',
				'yith-type'    => 'multi-colorpicker',
				'colorpickers' => array(
					array(
						array(
							'name' => _x( 'Background', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
							'id'   => 'background',
							'default' => '#FFFFFF',
						),
						array(
							'name' => _x( 'Background Hover', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
							'id'   => 'background_hover',
							'default' => $default_accent_color,
						),
						array(
							'name' => _x( 'Background Active', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
							'id'   => 'background_active',
							'default' => $default_accent_color,
						),
					),
					array(
						array(
							'name' => _x( 'Text', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
							'id'   => 'text',
							'default' => '#434343',
						),
						array(
							'name' => _x( 'Text Hover', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
							'id'   => 'text_hover',
							'default' => '#FFFFFF',
						),
						array(
							'name' => _x( 'Text Active', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
							'id'   => 'text_active',
							'default' => '#FFFFFF',
						),
					),
				),
			),

			'anchors_style' => array(
				'name'      => _x( 'Textual terms color', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_anchors_style',
				'type'      => 'yith-field',
				'yith-type' => 'multi-colorpicker',
				'colorpickers' => array(
					array(
						'name' => _x( 'Text', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
						'id'   => 'text',
						'default' => '#434343',
					),
					array(
						'name' => _x( 'Text hover', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
						'id'   => 'text_hover',
						'default' => $default_accent_color,
					),
					array(
						'name' => _x( 'Text active', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
						'id'   => 'text_active',
						'default' => $default_accent_color,
					),
				),
			),

			'ajax_loader_style' => array(
				'name'      => _x( 'AJAX loader', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'desc'      => _x( 'Choose the style for AJAX loader icon', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_ajax_loader_style',
				'type'      => 'yith-field',
				'default'   => 'default',
				'yith-type' => 'radio',
				'options'   => array(
					'default' => _x( 'Use default loader', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
					'custom'  => _x( 'Upload custom loader', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				),
			),

			'ajax_loader_custom_icon' => array(
				'name'      => _x( 'Custom AJAX loader', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'desc'      => _x( 'Upload an icon you\'d like to use as AJAX Loader (suggested 50px x 50px)', '[ADMIN] Customization settings page', 'yith-woocommerce-ajax-navigation' ),
				'id'        => 'yith_wcan_ajax_loader_custom_icon',
				'default'   => '',
				'type'      => 'yith-field',
				'yith-type' => 'upload',
				'deps'      => array(
					'id'    => 'yith_wcan_ajax_loader_style',
					'value' => 'custom',
				),
			),

			'customization_section_end' => array(
				'type' => 'sectionend',
				'id' => 'yith_wcan_customization_settings',
			),

		),
	)
);
