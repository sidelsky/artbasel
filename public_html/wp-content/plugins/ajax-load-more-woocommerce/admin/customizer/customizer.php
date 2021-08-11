<?php
/**
 * Customizer functionality for WooCommerce.
 *
 * @package ALMWooCommerce
 */

require_once 'class.customizer.php';

/**
 * Register Cusomizer Fields.
 *
 * @param Class $wp_customize The Cusomizer class.
 */
function alm_woocommerce_customizer_register( $wp_customize ) {

	/* Settings */
	$block = 'display: block; padding: 5px 0; opacity: 0.75; font-style: normal;';

	// Add Section.
	$wp_customize->add_section(
		'woocommerce_alm',
		array(
			'title'       => __( 'Ajax Load More [Settings]', 'alm-woocommerce' ),
			'description' => __( 'Edit these global settings to select where Ajax Load More integrates with WooCommerce.', 'alm-woocommerce' ) . '<hr style="margin-top: 15px;" />',
			'priority'    => 90,
			'panel'       => 'woocommerce',
		)
	);

	// Shop Page.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'shop_main',
		array(
			'default'    => false,
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'shop_main',
		array(
			'section'     => 'woocommerce_alm',
			'priority'    => 1,
			'label'       => __( 'Shop Page', 'alm-woocommerce' ),
			// translators: The URL to shop page.
			'description' => sprintf( __( 'Enable Ajax Load More on the main WooCommerce <a href="%s">Shop</a> page.', 'alm-woocommerce' ), get_permalink( wc_get_page_id( 'shop' ) ) ),
			'type'        => 'checkbox',
		)
	);

	// Shop Cache.
	if ( has_action( 'alm_cache_installed' ) ) {
		$wp_customize->add_setting(
			ALM_WOO_PREFIX . 'shop_cache',
			array(
				'default'    => false,
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);
		$wp_customize->add_control(
			ALM_WOO_PREFIX . 'shop_cache',
			array(
				'section'     => 'woocommerce_alm',
				'priority'    => 2,
				'label'       => __( 'Shop Cache', 'alm-woocommerce' ),
				// translators: The URL to the ALM cache.
				'description' => sprintf( __( 'Enable <a href="%s">Cache</a> on main Shop.', 'alm-woocommerce' ), 'admin.php?page=ajax-load-more-cache' ),
				'type'        => 'checkbox',
			)
		);
	}

	// Shop Archive Pages.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'shop_archives',
		array(
			'default'    => false,
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'shop_archives',
		array(
			'section'     => 'woocommerce_alm',
			'priority'    => 2,
			'label'       => __( 'Shop Archives', 'alm-woocommerce' ),
			// translators: Random archive URL.
			'description' => sprintf( __( 'Enable Ajax Load More on shop <a href="%s">archives</a>. <br/><span style="opacity: 0.7;">e.g. Product category and tags</span>', 'alm-woocommerce' ), alm_woo_get_random_product_cat() ),
			'type'        => 'checkbox',
		)
	);

	// Shop Archive Cache.
	if ( has_action( 'alm_cache_installed' ) ) {
		$wp_customize->add_setting(
			ALM_WOO_PREFIX . 'shop_archives_cache',
			array(
				'default'    => false,
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);
		$wp_customize->add_control(
			ALM_WOO_PREFIX . 'shop_archives_cache',
			array(
				'section'     => 'woocommerce_alm',
				'priority'    => 2,
				'label'       => __( 'Shop Archive Cache', 'alm-woocommerce' ),
				// translators: The URL to the ALM cache.
				'description' => sprintf( __( 'Enable <a href="%s">Cache</a> on shop archives.', 'alm-woocommerce' ), 'admin.php?page=ajax-load-more-cache' ),
				'type'        => 'checkbox',
			)
		);
	}

	// Shop Search.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'shop_search',
		array(
			'default'    => false,
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'shop_search',
		array(
			'section'     => 'woocommerce_alm',
			'priority'    => 2,
			'label'       => __( 'Product Search', 'alm-woocommerce' ),
			'description' => __( 'Enable Ajax Load More on product searches.', 'alm-woocommerce' ),
			'type'        => 'checkbox',
		)
	);

	// Container Element.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'container',
		array(
			'default'    => apply_filters( 'alm_woocommerce_container', 'ul.products' ),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'container',
		array(
			'section'     => 'woocommerce_alm',
			'priority'    => 2,
			'label'       => __( 'Container Class', 'alm-woocommerce' ),
			'description' => __( 'Set the target classname of the WooCommerce listing container. You need to update this value if your theme does not follow the standard WooCommerce class structure.', 'alm-woocommerce' ) . '<span style="' . $block . '">' . __( 'Default:', 'alm-woocommerce' ) . ' `ul.products`</span>',
			'input_attrs' => array(
				'placeholder' => apply_filters( 'alm_woocommerce_container', 'ul.products' ),
			),
		)
	);

	// Products Element.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'products',
		array(
			'default'    => apply_filters( 'alm_woocommerce_products', '.product' ),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'products',
		array(
			'section'     => 'woocommerce_alm',
			'priority'    => 2,
			'label'       => __( 'Product Class', 'alm-woocommerce' ),
			'description' => __( 'Set the target classname of the individual WooCommerce products.', 'alm-woocommerce' ) . '<br/><span style="' . $block . '">' . __( 'Default:', 'alm-woocommerce' ) . ' `.product`</span>',
			'input_attrs' => array(
				'placeholder' => apply_filters( 'alm_woocommerce_products', '.product' ),
			),
		)
	);

	// Permalink Struture.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'permalink_structure',
		array(
			'default'    => apply_filters( 'alm_woocommerce_permalink_structure', '' ),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'permalink_structure',
		array(
			'section'     => 'woocommerce_alm',
			'priority'    => 2,
			'label'       => __( 'Permalink Structure', 'alm-woocommerce' ),
			'description' => __( 'Update the pagination permalinks for the shop and archives. This is useful when plugins do not follow the standard WooCommerce permalink structure.', 'alm-woocommerce' ) . '<br/><span style="' . $block . '">' . __( 'Default:', 'alm-woocommerce' ) . ' `page/{page}`</span>',
			'input_attrs' => array(
				'placeholder' => apply_filters( 'alm_woocommerce_permalink_structure', 'page/{page}' ),
			),
		)
	);

	/* Display Settings */

	// Add Section.
	$wp_customize->add_section(
		'woocommerce_alm_display',
		array(
			'title'       => __( 'Ajax Load More [Display]', 'alm-woocommerce' ),
			'description' => __( 'Edit the following Ajax Load More display parameters to create a custom experience for your visitors.', 'alm-woocommerce' ) . '<hr style="margin-top: 15px;" />',
			'priority'    => 90,
			'panel'       => 'woocommerce',
		)
	);

	// Button/Loading Style.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'button_style',
		array(
			'default'    => ALMWooCustomizer::loading_style(),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'button_style',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Button/Loading Style', 'alm-woocommerce' ),
			'description' => __( 'Select an Ajax loading style - choose between a Button or Infinite Scroll..', 'alm-woocommerce' ),
			'type'        => 'select',
			'choices'     => array(
				'default'                 => __( 'Button - Default', 'alm-woocommerce' ),
				'blue'                    => __( 'Button - Blue', 'alm-woocommerce' ),
				'green'                   => __( 'Button - Green', 'alm-woocommerce' ),
				'purple'                  => __( 'Button - Purple', 'alm-woocommerce' ),
				'grey'                    => __( 'Button - Grey', 'alm-woocommerce' ),
				'white'                   => __( 'Button - White', 'alm-woocommerce' ),
				'light-grey'              => __( 'Button - Light Grey', 'alm-woocommerce' ),
				'infinite classic'        => __( 'Infinite Scroll - Classic', 'alm-woocommerce' ),
				'infinite skype'          => __( 'Infinite Scroll - Skype', 'alm-woocommerce' ),
				'infinite ring'           => __( 'Infinite Scroll - Ring', 'alm-woocommerce' ),
				'infinite fading-blocks'  => __( 'Infinite Scroll - Fading Blocks', 'alm-woocommerce' ),
				'infinite fading-circles' => __( 'Infinite Scroll - Fading Circles', 'alm-woocommerce' ),
				'infinite chasing-arrows' => __( 'Infinite Scroll - Chasing Arrows', 'alm-woocommerce' ),
			),
		)
	);

	// Button Label.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'button_label',
		array(
			'default'    => ALMWooCustomizer::default_button_label(),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'button_label',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Button Label', 'alm-woocommerce' ),
			'description' => __( 'The text of the Load More button.', 'alm-woocommerce' ),
			'input_attrs' => array(
				'placeholder' => ALMWooCustomizer::default_button_label(),
			),
		)
	);

	// Button Loading Label.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'button_loading_label',
		array(
			'default'    => ALMWooCustomizer::default_button_loading_label(),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'button_loading_label',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Button Loading Label', 'alm-woocommerce' ),
			'description' => __( 'Update the button label while content is loading.', 'alm-woocommerce' ),
		)
	);

	// Previous Products Button.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'previous_products',
		array(
			'default'    => apply_filters( 'alm_woocommerce_previous_products', __( 'Load Previous Products', 'alm-woocommerce' ) ),
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'previous_products',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Previous Products Button', 'alm-woocommerce' ),
			'description' => __( 'On paged URLs, show a \'Previous Products\' button to load posts in a reverse load more order.', 'alm-woocommerce' ) . '<span style="' . $block . '">' . __( 'Leave empty to not render button.', 'alm-woocommerce' ) . '</span>',
			'input_attrs' => array(
				'placeholder' => apply_filters( 'alm_woocommerce_previous_products', __( 'Load Previous Products', 'alm-woocommerce' ) ),
			),
		)
	);

	// Scroll.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'scroll',
		array(
			'default'    => 'true',
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'scroll',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Scrolling', 'alm-woocommerce' ),
			'description' => __( 'Load products as users scroll the page.', 'alm-woocommerce' ),
			'type'        => 'select',
			'choices'     => array(
				'true'  => __( 'True', 'alm-woocommerce' ),
				'false' => __( 'False', 'alm-woocommerce' ),
			),
		)
	);

	// Scroll Override.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'scroll_override',
		array(
			'default'    => 'true',
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'scroll_override',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Scroll Override', 'alm-woocommerce' ),
			'description' => __( 'Allow scrolling to initiate the loading of posts. If false, users will have to click the \'Load More\' button to begin.', 'alm-woocommerce' ),
			'type'        => 'select',
			'choices'     => array(
				'true'  => __( 'True', 'alm-woocommerce' ),
				'false' => __( 'False', 'alm-woocommerce' ),
			),
		)
	);

	// Scroll Distance.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'scroll_distance',
		array(
			'default'    => 100,
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'scroll_distance',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 1,
			'label'       => __( 'Scroll Distance', 'alm-woocommerce' ),
			'description' => __( 'The distance (in pixels) from the bottom of the screen to trigger a post load.', 'alm-woocommerce' ),
			'type'        => 'number',
			'input_attrs' => array(
				'step' => 10,
			),
		)
	);

	// Back Button.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'controls',
		array(
			'default'    => 'true',
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'controls',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 2,
			'label'       => __( 'Back/Fwd Button', 'alm-woocommerce' ),
			'description' => __( 'Enable navigation between Ajax loaded content using back and forward browser buttons.', 'alm-woocommerce' ),
			'type'        => 'select',
			'choices'     => array(
				'true'  => __( 'True', 'alm-woocommerce' ),
				'false' => __( 'False', 'alm-woocommerce' ),
			),
		)
	);

	// Scrolltop.
	$wp_customize->add_setting(
		ALM_WOO_PREFIX . 'scrolltop',
		array(
			'default'    => 50,
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		)
	);
	$wp_customize->add_control(
		ALM_WOO_PREFIX . 'scrolltop',
		array(
			'section'     => 'woocommerce_alm_display',
			'priority'    => 2,
			'label'       => __( 'Scroll Offset', 'alm-woocommerce' ),
			'description' => __( 'Set the offset top position of the window. The offset determines at which point the URL will update while scrolling through Ajax loaded pages.', 'alm-woocommerce' ),
			'type'        => 'number',
			'input_attrs' => array(
				'step' => 10,
			),
		)
	);
}
add_action( 'customize_register', 'alm_woocommerce_customizer_register' );

/**
 * Customizer scripts.
 */
function alm_woocommerce_customizer_add_scripts() {
	?>
	<script type="text/javascript">

		jQuery( document ).ready( function( $ ) {

			// Redirect user to main 'shop' page
			wp.customize.section( 'woocommerce_alm', function( section ) {
				section.expanded.bind( function( isExpanded ) {
					if ( isExpanded ) {
						wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>' );
					}
				});
			});

			// Redirect user to main 'shop' page
			wp.customize.section( 'woocommerce_alm_display', function( section ) {
				section.expanded.bind( function( isExpanded ) {
					if ( isExpanded ) {
						wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>' );
					}
				});
			});

			// Redirect user to archive page when updated
			wp.customize('alm_woo_shop_archives', function (value) {
				value.bind(function (state) {
					wp.customize.previewer.previewUrl.set( '<?php echo esc_js( alm_woo_get_random_product_cat() ); ?>' );
				});
			});


			// Button Labels
			// Hide if loading type is infinite
			wp.customize('alm_woo_button_style', function (value) {
				var alm_woo_button_style = wp.customize.instance('alm_woo_button_style').get();
				if(alm_woo_button_style.indexOf('infinite') !== -1){
					setTimeout(function(){
						wp.customize.control('alm_woo_button_label').toggle(false);
						wp.customize.control('alm_woo_button_loading_label').toggle(false);
					}, 1000);
				}
				value.bind(function (state) {
					if(state.indexOf('infinite') !== -1){
						wp.customize.control('alm_woo_button_label').toggle(false);
						wp.customize.control('alm_woo_button_loading_label').toggle(false);
					} else {
						wp.customize.control('alm_woo_button_label').toggle(true);
						wp.customize.control('alm_woo_button_loading_label').toggle(true);
					}
				});
			});


			// Scrolling
			// Hide Override & Distance if scroll false
			wp.customize('alm_woo_scroll', function (value) {
				var scrollVal = wp.customize.instance('alm_woo_scroll').get();
				if(scrollVal === 'false'){
					setTimeout(function(){
						wp.customize.control('alm_woo_scroll_override').toggle(false);
						wp.customize.control('alm_woo_scroll_distance').toggle(false);
					}, 1000);
				}
				value.bind(function (state) {
					if(state === 'false'){
						wp.customize.control('alm_woo_scroll_override').toggle(false);
						wp.customize.control('alm_woo_scroll_distance').toggle(false);
					} else {
						wp.customize.control('alm_woo_scroll_override').toggle(true);
						wp.customize.control('alm_woo_scroll_distance').toggle(true);
					}
				});
			});

			<?php if ( has_action( 'alm_cache_installed' ) ) { ?>
			// Cache
			// Hide Cache if Shop Page false
			wp.customize('alm_woo_shop_main', function (value) {
				var shop_main = wp.customize.instance('alm_woo_shop_main').get();
				if(shop_main !== '1'){
					setTimeout(function(){
						wp.customize.control('alm_woo_shop_cache').toggle(false);
					}, 1000);
				}
				value.bind(function (state) {
					console.log(state);
					if(!state){
						wp.customize.control('alm_woo_shop_cache').toggle(false);
					} else {
						wp.customize.control('alm_woo_shop_cache').toggle(true);
					}
				});
			});


			// Hide Cache if Shop Archive false
			wp.customize('alm_woo_shop_archives', function (value) {
				var shop_main = wp.customize.instance('alm_woo_shop_archives').get();
				if(shop_main !== '1'){
					setTimeout(function(){
						wp.customize.control('alm_woo_shop_archives_cache').toggle(false);
					}, 1000);
				}
				value.bind(function (state) {
					console.log(state);
					if(!state){
						wp.customize.control('alm_woo_shop_archives_cache').toggle(false);
					} else {
						wp.customize.control('alm_woo_shop_archives_cache').toggle(true);
					}
				});
			});
			<?php } ?>

		});
	</script>
	<?php
}
add_action( 'customize_controls_print_scripts', 'alm_woocommerce_customizer_add_scripts', 30 );

/**
 * Get random product category when a user adjusts the `Shop Archives` customizer setting.
 */
function alm_woo_get_random_product_cat() {

	$args  = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
	);
	$terms = get_terms( $args );

	if ( $terms ) {
		// Category.
		$index     = array_rand( $terms, 1 );
		$term      = $terms[ $index ];
		$term_link = get_term_link( $term );
		return $term_link;
	} else {
		// Tags.
		$args['taxonomy'] = 'product_tag';
		$terms            = get_terms( $args );
		if ( $terms ) {
			$index     = array_rand( $terms, 1 );
			$term      = $terms[ $index ];
			$term_link = get_term_link( $term );
			return $term_link;
		}
	}

}
