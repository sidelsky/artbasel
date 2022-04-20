<?php
/**
 * Zoom admin settings page
 */

defined( 'ABSPATH' ) || exit;


/**
 * ImageZoooom_Admin
 */
class ImageZoooom_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( strtolower( get_template() ) === 'enfold' ) {
			add_theme_support( 'avia_template_builder_custom_css' );
		}

		require_once 'frm/class-form-fields.php';
		require_once 'frm/premium-tooltips.php';
		require_once 'frm/warnings.php';

		add_action( 'admin_menu', 'ImageZoooom_Admin::admin_menu' );
		add_action( 'admin_enqueue_scripts', 'ImageZoooom_Admin::admin_enqueue_scripts' );
		add_action( 'admin_head', 'ImageZoooom_Admin::iz_add_tinymce_button' );
		add_action( 'admin_head', 'ImageZoooom_Admin::gutenberg_style' );
		add_action( 'enqueue_block_editor_assets', 'ImageZoooom_Admin::enqueue_block_editor_assets' );

		self::warnings();
	}

	/**
	 * Add menu items
	 */
	public static function admin_menu() {
		add_menu_page(
			__( 'WP Image Zoom', 'wp-image-zoooom' ),
			__( 'WP Image Zoom', 'wp-image-zoooom' ),
			'administrator',
			'zoooom_settings',
			'ImageZoooom_Admin::admin_settings_page',
			IMAGE_ZOOM_URL . 'assets/images/icon.svg'
		);
	}

	/**
	 * Load the javascript and css scripts
	 */
	public static function admin_enqueue_scripts( $hook ) {
		if ( 'toplevel_page_zoooom_settings' !== $hook ) {
			return false;
		}

		$url     = IMAGE_ZOOM_URL . 'assets/';
		$frm_url = IMAGE_ZOOM_URL . 'includes/frm/assets/';
		$v       = IMAGE_ZOOM_VERSION;
		$min     = defined( SCRIPT_DEBUG ) && SCRIPT_DEBUG ? '' : '.min';

		// Register the assets
		wp_register_script( 'bootstrap', $frm_url . 'bootstrap.min.js', array( 'jquery' ), $v, true );
		wp_register_script( 'image_zoooom', $url . 'js/jquery.image_zoom' . $min . '.js', array( 'jquery' ), $v, true );
		wp_register_script( 'zoooom-settings', $url . 'js/image_zoom.settings.js', array( 'image_zoooom' ), $v, true );
		wp_register_style( 'bootstrap', $frm_url . 'bootstrap.min.css', array(), $v );
		wp_register_style( 'zoooom', $url . 'css/style' . $min . '.css', array(), $v );

		// Enqueue the assets
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'image_zoooom' );
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'zoooom' );
		if ( ! isset( $_GET['tab'] ) || 'settings' === $_GET['tab'] ) {
			wp_enqueue_script( 'zoooom-settings' );
		}
	}

	/**
	 * Add "with Image Zoom" style for the Image block in Gutenberg
	 *
	 * @access public
	 */
	public static function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'gutenberg-zoom-style',
			IMAGE_ZOOM_URL . 'assets/js/gutenberg-zoom-style.js',
			array( 'wp-blocks', 'wp-dom' ),
			filemtime( IMAGE_ZOOM_PATH . '/assets/js/gutenberg-zoom-style.js' ),
			true
		);
	}

	/**
	 * Output the admin page
	 *
	 * @access public
	 */
	public static function admin_settings_page() {

		// Get the tabs.
		$tabs = array(
			'general'  => __( 'General Settings', 'wp-image-zoooom' ),
			'settings' => __( 'Zoom Settings', 'wp-image-zoooom' ),
		);

		$tab_current     = ( isset( $_GET['tab'] ) && isset( $tabs[$_GET['tab']] ) ) ? $_GET['tab'] : 'settings';
		$options_current = ( 'settings' === $tab_current ) ? 'zoooom_settings' : 'zoooom_general';

		// Get the field settings.
		$settings_all   = wp_image_zoooom_settings( 'settings' );
		$values_current = get_option( $options_current, array() );

		// Filter settings only for this section.
		foreach ( $settings_all as $_key => $_value ) {
			if ( $_value['section'] !== $tab_current ) {
				unset( $settings_all[ $_key ] );
			}
		}

		if ( class_exists( 'woocommerce' ) && version_compare( WC_VERSION, '3.0', '>' ) ) {
			unset( $settings_all['exchange_thumbnails'] );
		}

		// Configure the form class.
		$form = new \SilkyPressFrm\Form_Fields( $settings_all );
		$form->add_setting( 'tooltip_img', plugins_url( '/', IMAGE_ZOOM_FILE ) . 'assets/images/question_mark.svg' );
		$form->add_setting( 'section', $tab_current );
		$form->add_setting( 'label_class', 'settings' === $tab_current ? 'col-sm-5' : 'col-sm-6' );
		$form->set_current_values( $values_current );

		// The settings were saved.
		if ( ! empty( $_POST ) ) {
			check_admin_referer( $options_current );

			if ( current_user_can( 'manage_options' ) ) {

				$values_post_sanitized = $form->validate( $_POST );

				$form->set_current_values( $values_post_sanitized );

				if ( update_option( $options_current, $values_post_sanitized ) ) {
					$form->add_message( 'success', '<b>' . __( 'Your settings have been saved.' ) . '</b>' );
				}
			}
		}

		// Premium tooltips.
		$message = __( 'Only available in <a href="%1$s" target="_blank">PRO version</a>', 'wp-image-zoooom' );
		$message = wp_kses(
			$message,
			array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			)
		);
		$message = sprintf( $message, 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=wmsc_free&utm_medium=banner' );
		new SilkyPress_PremiumTooltips( $message );

		$messages = $form->render_messages();

		include_once 'template-' . $tab_current . '.php';

		include_once 'right_columns.php';
	}

	/**
	 * Add a button to the TinyMCE toolbar
	 *
	 * @access public
	 */
	public static function iz_add_tinymce_button() {
		global $typenow;

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		$allowed_types = array( 'post', 'page' );

		if ( defined( 'LEARNDASH_VERSION' ) ) {
			$learndash_types = array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz', 'sfwd-certificates', 'sfwd-assignment' );
			$allowed_types   = array_merge( $allowed_types, $learndash_types );

		}
		/*
		if( ! in_array( $typenow, $allowed_types ) )
			return;
		 */

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wplister-templates' ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) != 'true' ) {
			return;
		}

		add_filter( 'mce_external_plugins', 'ImageZoooom_Admin::iz_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'ImageZoooom_Admin::iz_register_tinymce_button' );
	}

	/**
	 * Register the plugin with the TinyMCE plugins manager
	 *
	 * @access public
	 */
	public static function iz_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['image_zoom_button'] = IMAGE_ZOOM_URL . 'assets/js/tinyMCE-button.js';
		return $plugin_array;
	}

	/**
	 * Register the button with the TinyMCE manager
	 */
	public static function iz_register_tinymce_button( $buttons ) {
		array_push( $buttons, 'image_zoom_button' );
		return $buttons;
	}


	/**
	 * Image style in the Gutenberg editor
	 */
	public static function gutenberg_style() {
		echo '<style type="text/css">
				.wp-block-image.is-style-zoooom .components-resizable-box__container::before,
				.wp-block-image.zoooom .components-resizable-box__container::before {
                    content: "\f179     ' . __( 'Zoom applied to the image. Check on the frontend', 'wp-image-zoooom' ) . '";
                    position: absolute;
                    margin-top: 12px;
                    text-align: right;
                    background-color: white;
					line-height: 1.4em;
					left: 5%;
                    padding: 0 10px 6px;
                    font-family: dashicons;
                    font-size: 0.9em;
                    font-style: italic;
                    z-index: 20;
                }
            </style>';
	}


	/**
	 * Show admin warnings
	 */
	public static function warnings() {

		$allowed_actions = array(
			'iz_dismiss_ajax_product_filters',
			'iz_dismiss_jetpack',
			'iz_dismiss_bwp_minify',
			'iz_dismiss_avada',
			'iz_dismiss_shopkeeper',
			'iz_dismiss_bridge',
			'iz_dismiss_wooswipe',
			'iz_dismiss_avada_woo_gallery',
			'iz_dismiss_flatsome_theme',
			'iz_dismiss_smart_image_resize',
			'iz_dismiss_gallery_video',
			'iz_dismiss_woo_product_gallery_slider',
		);

		$w = new SilkyPress_Warnings( $allowed_actions );

		if ( ! $w->is_url( 'zoooom_settings' ) ) {
			return;
		}

		// Warning about AJAX product filter plugins
		self::iz_dismiss_ajax_product_filters( $w );

		// Check if Jetpack Photon module is active
		if ( defined( 'JETPACK__VERSION' ) ) {
			$message = sprintf( __('Under certain situations the <a href="%1$s">Lazy Loading</a> functionality from Jetpack can interfere with the image zooming. If you\'re expriencing issues with the zoom, please try deactivating this option. Since WordPress 5.5 the lazy-loading technique is used by default on all the website\'s images, also when the Lazy Loading option from Jetpack is disabled.', 'wp-image-zoooom' ), admin_url( 'admin.php?page=jetpack#/performance' ) );
			$w->add_notice( 'iz_dismiss_jetpack', $message );
		}

		// Warning about BWF settings
		if ( is_plugin_active( 'bwp-minify/bwp-minify.php' ) ) {
			$message = sprintf( __( '<b>If the zoom does not show up</b> on your website, it could be because you need to add the "image_zoooom-init" and the "image_zoooom" to the "Scripts to NOT minify" option in the BWP Minify settings, as shown in <a href="%1$s" target="_blank">this screenshot</a>.', 'wp-image-zoooom' ), 'https://www.silkypress.com/wp-content/uploads/2016/09/image-zoom-bwp.png' );
			$w->add_notice( 'iz_dismiss_bwp_minify', $message );
		}

		// Check if the Avada theme is active
		if ( strpos( strtolower( get_template() ), 'avada' ) !== false && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$flexslider_url = 'https://woocommerce.com/flexslider/';
			$pro_url        = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
			$message        = sprintf( __( 'The WP Image Zoom plugin <b>will not work</b> on the WooCommerce products gallery with the Avada theme. The Avada theme changes entirely the default WooCommerce gallery with the <a href="%1$s" target="_blank">Flexslider gallery</a> and the zoom plugin does not support the Flexslider gallery. Please check the <a href="%2$s" target="_blank">PRO version</a> of the plugin for compatibility with the Flexslider gallery.', 'wp-image-zoooom' ), $flexslider_url, $pro_url );
			$w->add_notice( 'iz_dismiss_avada', $message );
		}

		// Check if the Shopkeeper theme is active
		if ( strpos( strtolower( get_template() ), 'shopkeeper' ) !== false && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$pro_url = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
			$message = sprintf( __( 'The WP Image Zoom plugin <b>will not work</b> on the WooCommerce products gallery with the Shopkeeper theme. The Shopkeeper theme changes entirely the default WooCommerce gallery with a custom made gallery not supported by the free version of the WP Image Zoom plugin. Please check the <a href="%1$s" target="_blank">PRO version</a> of the plugin for compatibility with the Shopkeeper\'s gallery.', 'wp-image-zoooom' ), $pro_url );
			$w->add_notice( 'iz_dismiss_shopkeeper', $message, 'updated settings-error notice is-dismissible' );
		}

		// Check if the Bridge theme is active
		if ( strpos( strtolower( get_template() ), 'bridge' ) !== false && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$message = sprintf( __( 'The <b>Bridge</b> theme replaces the default WooCommerce product gallery with its own. The <b>WP Image Zoom</b> plugin will not work with this replaced gallery. But if you set the "Enable Default WooCommerce Product Gallery Features" option to "Yes" on the <a href="%1$s">%2$s</a> page, then the zoom will work as expected on the product gallery.', 'wp-image-zoooom' ), admin_url( 'admin.php?page=qode_theme_menu_tab_woocommerce' ), 'WP Admin -> Qode Options -> WooCommerce' );
			// Note: This works for Bridge 16.7, but not for Bridge 14.1
			$w->add_notice( 'iz_dismiss_bridge', $message, 'updated settings-error notice is-dismissible' );
		}

		// Warning about WooSwipe plugin
		if ( is_plugin_active( 'wooswipe/wooswipe.php' ) ) {
			$pro_url      = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
			$wooswipe_url = 'https://wordpress.org/plugins/wooswipe/';
			$message      = sprintf( __( 'WP Image Zoom plugin is <b>not compatible with the <a href="%1$s">WooSwipe WooCommerce Gallery</a> plugin</b>. You can try the zoom plugin with the default WooCommerce gallery by deactivating the WooSwipe plugin. Alternatively, you can upgrade to the WP Image Zoom Pro version, where the issue with the WooSwipe plugin is fixed.', 'wp-image-zoooom' ), $wooswipe_url, $pro_url );
			$w->add_notice( 'iz_dismiss_wooswipe', $message );
		}

		// Check if the Avada theme is active and the WooCommerce plugin installed
		if ( strpos( strtolower( get_template() ), 'avada' ) !== false && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$avada_options = get_option( 'fusion_options' );
			if ( isset( $avada_options['disable_woo_gallery'] ) && $avada_options['disable_woo_gallery'] == '1' ) {
				$message = sprintf( __( '<b>The zoom will work</b> on the WooCommerce products images only when having the <b>"Avada\'s WooCommerce Product Gallery Slider"</b> option <b>turned off</b> on the <a href="%1$s">%2$s</a> page', 'wp-image-zoooom' ), admin_url( 'themes.php?page=avada_options' ), 'WP Admin -> Avada -> Theme Options -> WooCommerce -> General WooCommerce' );
				$w->add_notice( 'iz_dismiss_avada_woo_gallery', $message );
			}
		}

        // Warning about Flatsome theme
        if ( strpos( strtolower(get_template()), 'flatsome') !== false && $w->is_url('zoooom_settings')) {
            $message = sprintf( __( 'The Flatsome theme replaces the default WooCommerce gallery with a Flickity carousel, which leads to the effects that it zooms only on the first image. Please check <a href="%1$s" target="_blank">this article</a> for a solution to the issue.', 'wp-image-zoooom' ), 'https://www.silkypress.com/wp-image-zoom/zoom-woocommerce-gallery-flatsome-theme/' );
            $w->add_notice( 'iz_dismiss_flatsome_theme', $message );
        }

		// Warning about Smart Image Resize plugin.
		if ( is_plugin_active( 'smart-image-resize/plugpix-smart-image-resize.php' ) && $w->is_url( 'zoooom_settings' ) ) {
			$message = __( 'The <b>Smart Image Resize for WooCommerce</b> plugin modifies the WooCommerce product images, which will result in a distorted zoom lens on those images. Unfortunately, if you plan to use the <b>WP Image Zoom</b> plugin on the WooCommerce product images, then, you\'ll need to deactivate the <b>Smart Image Resize</b> plugin.', 'wp-image-zoooom' );
			$w->add_notice( 'iz_dismiss_smart_image_resize', $message );
		}

		// Warning about Additional Variation Images Gallery for WooCommerce plugin.
		if ( is_plugin_active( 'woo-variation-gallery/woo-variation-gallery.php' ) && $w->is_url( 'zoooom_settings' ) ) {
			$slick_url = 'http://kenwheeler.github.io/slick/';
			$variation_plugin_url = 'https://wordpress.org/plugins/woo-variation-gallery/';
			$pro_url        = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
			$message      = sprintf( __( 'The <a href="%1$s" target="_blank">Additional Variation Images Gallery for WooCommerce</a> plugin replaces the default WooCommerce gallery with a <a href="%2$s" target="_blank">Slick carousel</a> gallery. Unfortunately the <b>WP Image Zoom</b> plugin will not work with the <b>Slick carousel</b> gallery. You can try the zoom plugin with the default WooCommerce gallery by deactivating the <b>Additional Variation Images Gallery for WooCommerce</b> plugin. Alternatively, you can upgrade to the <a href="%3$s">WP Image Zoom Pro</a> version, which is compatible with the <b>Slick carousel</b> gallery, therefore compatible with the <b>Additional Variation Images Gallery for WooCommerce</b> plugin.', 'wp-image-zoooom' ), $variation_plugin_url, $slick_url, $pro_url );
			$w->add_notice( 'iz_dismiss_woo_variation', $message );
		}

		// Warning about Additional Variation Images Gallery for WooCommerce plugin.
		if ( is_plugin_active( 'product-video-gallery-slider-for-woocommerce/product-video-gallery-slider-for-woocommerce.php' ) && $w->is_url( 'zoooom_settings' ) ) {
			$slick_url = 'http://kenwheeler.github.io/slick/';
			$gallery_plugin_url = 'https://wordpress.org/plugins/product-video-gallery-slider-for-woocommerce/';
			$pro_url        = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
			$message      = sprintf( __( 'The <a href="%1$s" target="_blank">WooCommerce Product Video Gallery</a> plugin replaces the default WooCommerce gallery with a <a href="%2$s" target="_blank">Slick carousel</a> gallery. Unfortunately the <b>WP Image Zoom</b> plugin will not work with the <b>Slick carousel</b> gallery. You can try the zoom plugin with the default WooCommerce gallery by deactivating the <b>WooCommerce Product Video Gallery</b> plugin. Alternatively, you can upgrade to the <a href="%3$s">WP Image Zoom Pro</a> version, which is compatible with the <b>Slick carousel</b> gallery, therefore compatible with the <b>WooCommerce Product Video Gallery</b> plugin.', 'wp-image-zoooom' ), $gallery_plugin_url, $slick_url, $pro_url );
			$w->add_notice( 'iz_dismiss_gallery_video', $message );
		}

		// Warning about the Product Gallery Slider for WooCommerce plugin.
		if ( is_plugin_active( 'woo-product-gallery-slider/woo-product-gallery-slider.php' ) && $w->is_url( 'zoooom_settings' ) ) {
			$gallery_plugin_url = 'https://wordpress.org/plugins/woo-product-gallery-slider/';
			$message      = sprintf( __( 'The <a href="%1$s" target="_blank">Product Gallery Slider for WooCommerce</a> plugin replaces the default WooCommerce gallery with a custom implementation, which, unfortunately, is not compatible with the <b>WP Image Zoom</b> plugin. You can try the image zoom plugin with the default WooCommerce gallery by deactivating the <b>Product Gallery Slider for WooCommerce</b> plugin.', 'wp-image-zoooom' ), $gallery_plugin_url );
			$w->add_notice( 'iz_dismiss_woo_product_gallery_slider', $message );
		}

		$w->show_warnings();
	}


	/**
	 * Warning about AJAX product filter plugins
	 */
	public static function iz_dismiss_ajax_product_filters( $w ) {
		$continue = false;

		$general = get_option( 'zoooom_general' );
		if ( isset( $_POST['tab'] ) ) {
			$general['woo_cat'] = ( isset( $_POST['woo_cat'] ) ) ? true : false;
		}
		if ( ! isset( $general['woo_cat'] ) || $general['woo_cat'] != true ) {
			return false;
		}

		if ( is_plugin_active( 'woocommerce-ajax-filters/woocommerce-filters.php' ) ) {
			$continue = true;
		}
		if ( is_plugin_active( 'load-more-products-for-woocommerce/load-more-products.php' ) ) {
			$continue = true;
		}
		if ( is_plugin_active( 'wc-ajax-product-filter/wcapf.php' ) ) {
			$continue = true;
		}

		if ( ! $continue ) {
			return false;
		}

		$article_url = 'https://www.silkypress.com/wp-image-zoom/zoom-woocommerce-category-page-ajax/';
		$message     = sprintf( __( 'You are using the zoom on WooCommerce shop pages in combination with a plugin that loads more products with AJAX (a product filter plugin or a "load more" products plugin). You\'ll notice that the zoom isn\'t applied after new products are loaded with AJAX. Please read <a href="%1$s" target="_blank">this article for a solution</a>.', 'wp-image-zoooom' ), $article_url );

		$w->add_notice( 'iz_dismiss_ajax_product_filters', $message );
	}
}
