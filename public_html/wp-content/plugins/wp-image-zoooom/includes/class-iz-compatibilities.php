<?php
/**
 * IZ_Compatibilities. Compatibilities with other themes or plugins.
 *
 * @package WP_Image_Zoooom
 */

defined( 'ABSPATH' ) || exit;

/**
 * IZ_Compatibilities class.
 */
class IZ_Compatibilities {

	/**
	 * Initiate the class.
	 */
	public static function init() {
		add_action( 'wp_head', 'IZ_Compatibilities::wp_head', 40 );
		add_action( 'vc_after_init', 'IZ_Compatibilities::vc_after_init' );
		add_action( 'after_setup_theme', 'IZ_Compatibilities::after_setup_theme' );
		add_action( 'init', 'IZ_Compatibilities::admin_side' );
	}


	/**
	 * CSS modifications.
	 */
	public static function wp_head() {
		$theme = strtolower( get_template() );

		$opt                       = get_option( 'zoooom_general' );
		$opt['enable_woocommerce'] = isset( $opt['enable_woocommerce'] ) ? $opt['enable_woocommerce'] : true;

		$style = '';

		// These themes add a wrapper on the whole page with index higher than the zoom.
		$wrapper_themes = array(
			array(
				'rule'   => '.wrapper { z-index: 40 !important; }',
				'themes' => array( 'bridge', 'nouveau', 'stockholm', 'tactile', 'vigor', 'homa', 'hudsonwp', 'borderland', 'moose' ),
			),
			array(
				'rule'   => '.qodef-wrapper { z-index: 200 !important; }',
				'themes' => array( 'kloe', 'startit', 'kudos', 'moments', 'ayro', 'suprema', 'ultima', 'geko', 'target', 'coney', 'aton', 'ukiyo', 'zenit', 'mixtape', 'scribbler', 'alecta', 'cityrama', 'bazaar' ),
			),
			array(
				'rule'   => '.edgtf-wrapper { z-index: 40 !important; }',
				'themes' => array( 'quadric', 'oxides', 'kvadrat', 'magazinevibe', 'kolumn', 'skyetheme', 'conall', 'dorianwp', 'node', 'ratio', 'escher', 'fair', 'assemble', 'any', 'walker', 'freestyle', 'shuffle', 'vangard', 'fuzion', 'crimson', 'cozy', 'xpo', 'onschedule', 'illustrator', 'oberon', 'fluid', 'barista', 'kamera', 'revolver', 'baker', 'rebellion', 'goodwish', 'maison', 'silverscreen', 'sovereign', 'atmosphere', 'dekko', 'objektiv', 'okami', 'coyote', 'bumblebee', 'blaze', 'mediadesk', 'penumbra', 'pxlz', 'gastrobar', 'aalto', 'dishup', 'voevod', 'orkan', 'fierce', 'grayson', 'hyperon', 'pintsandcrafts', 'haar', 'polyphonic', 'offbeat', 'hereford', 'kvell', 'sarto', 'journo', 'cinerama', 'ottar', 'playerx', 'kenozoik', 'elaine', 'entropia', 'tetsuo', 'bitpal', 'tahoe', 'urbango', 'smilte', 'neralbo', 'galatia', 'mintus', 'manon' ),
			),
			array(
				'rule'   => '.edge-wrapper { z-index: 40 !important; }',
				'themes' => array( 'dieter', 'anders', 'adorn', 'creedence', 'noizzy' ),
			),
			array(
				'rule'   => '.edgt-wrapper { z-index: 40 !important; }',
				'themes' => array( 'shade', 'eldritch', 'morsel', 'educator', 'milieu' ),
			),
			array(
				'rule'   => '.sidebar-menu-push { z-index: 40 !important; }',
				'themes' => array( 'artcore' ),
			),
			array(
				'rule'   => '.eltdf-wrapper { z-index: 40 !important; }',
				'themes' => array( 'readanddigest', 'tomasdaisy', 'virtuoso', 'blu', 'superfood', 'ambient', 'koto', 'azaleawp', 'all4home', 'mrseo', 'vibez', 'sweettooth', 'halogen', 'vino', 'ion', 'satine', 'nightshade', 'esmarts', 'makoto', 'mane', 'imogen', 'yvette', 'gourmand', 'sceon', 'calla', 'corretto', 'allston' ),
			),
			array(
				'rule'   => '.eltd-wrapper { z-index: 40 !important; }',
				'themes' => array( 'woly', 'averly', 'search-and-go', 'flow', 'kreate', 'allure', 'chandelier', 'malmo', 'minnesota', 'newsroom', 'kendall', 'savory', 'creator', 'awake', 'diorama', 'medipoint', 'audrey', 'findme', 'april', 'bizfinder', 'bjorn', 'trackstore', 'albergo', 'vakker', 'tamashi', 'bonvoyage' ),
			),
			// Next three rules are to the Mikado-Themes.
			array(
				'rule'   => '.wrapper {z-index: 20 !important; }',
				'themes' => array( 'mikado1', 'onyx', 'hornet', 'burst' ),
			),
			array(
				'rule'   => '.mkdf-wrapper {z-index: 20 !important; }',
				'themes' => array( 'chillnews', 'deploy', 'piquant', 'optimizewp', 'wellspring', 'siennawp', 'hashmag', 'voyagewp', 'gotravel', 'verdict', 'mediclinic', 'iacademy', 'newsflash', 'evently', 'cortex', 'roam', 'lumiere', 'aviana', 'zuhaus', 'staffscout', 'kastell', 'fivestar', 'janeandmark', 'neva', 'klippe', 'rosebud', 'endurer', 'wanderers', 'anwalt', 'equine', 'verdure', 'brewski', 'curly', 'fiorello', 'bardwp', 'lilo', 'gluck', 'dotwork', 'eola', 'cocco', 'housemed', 'ande', 'foton', 'overton', 'kanna', 'attika', 'backpacktraveller' ),
			),
			array(
				'rule'   => '.mkd-wrapper {z-index: 20 !important; }',
				'themes' => array( 'libero', 'discussionwp', 'hue', 'medigroup', 'newshub', 'affinity', 'hotspot', 'industrialist', 'pinata', 'cornerstone', 'connectwp', 'opportunity', 'highrise', 'anahata', 'hoshi', 'fleur', 'sparks', 'topfit', 'depot', 'trophy', 'motorepair', 'citycruise', 'indigo', 'servicemaster', 'lister', 'renovator', 'ecologist', 'buro', 'cyberstore', 'appetito', 'grillandchow', 'baumeister', 'kalos', 'fuego', 'entre' ),
			),

			array(
				'rule'   => '#boxed { z-index: 840 !important; }',
				'themes' => array( 'salient' ),
			),
		);

		foreach ( $wrapper_themes as $_v ) {
			if ( in_array( $theme, $_v['themes'], true ) ) {
				$style .= $_v['rule'];
			}
		}

		/**
		 * TheGem theme, WooCommerce product gallery.
		 */
		if ( strpos( $theme, 'thegem' ) !== false && $opt['enable_woocommerce'] && class_exists( 'woocommerce' ) && version_compare( WC_VERSION, '3.0', '>' ) ) {
			$style .= '.single-product div.product .woocommerce-product-gallery .attachment-shop_thumbnail {width: 100%;height: 100%;}';
			$style .= '.single-product div.product .woocommerce-product-gallery .flex-control-thumbs {margin: 0;padding: 0;margin-top: 10px;}';
			$style .= '.single-product div.product .woocommerce-product-gallery .flex-control-thumbs::before {content: "";display: table;}';
			$style .= '.single-product div.product .woocommerce-product-gallery.woocommerce-product-gallery--columns-4 .flex-control-thumbs li {width: 24.2857142857%;float: left;}';
			$style .= '.single-product div.product .woocommerce-product-gallery .flex-control-thumbs li {list-style: none;margin-bottom: 1.618em;cursor: pointer;}';
		}

		/**
		 * Brooklyn theme, WooCommerce product gallery.
		 */
		if ( strpos( $theme, 'brooklyn' ) !== false && $opt['enable_woocommerce'] && class_exists( 'woocommerce' ) && version_compare( WC_VERSION, '3.0', '>' ) ) {
			$style .= '.woocommerce div.product div.images .woocommerce-product-gallery__wrapper { -webkit-box-pack: start; -ms-flex-pack: start; justify-content: start; }';
		}

		/**
		 * LearnPress plugin.
		 */
		if ( defined( 'LP_PLUGIN_FILE' ) ) {
			$style .= 'body.content-item-only .learn-press-content-item-only { z-index: 990; } .single-lp_course #wpadminbar{z-index:900;}';
		}

		/**
		 * Image Hotspot plugin.
		 */
		if ( class_exists( 'WP_Image_Hotspot' ) ) {
			$style .= '.point_style.ihotspot_tooltop_html {z-index: 1003}';
		}

		/**
		 * Elementor Page Builder plugin.
		 */
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$style .= '.dialog-lightbox-widget-content[style] { top: 0 !important; left: 0 !important;}';
		}

		/**
		 * No active zoom in Elementor, WPBakery, Divi active editors.
		 */
		$zoom_class_in_editor = ' { content: "\f179     ' . __( 'Zoom applied to the image. Check on the frontend', 'wp-image-zoooom' ) . '"; ' . 
			'position: absolute; margin-top: 12px; text-align: right; background-color: white; line-height: 1.4em; left: 5%; ' .
			'padding: 0 10px 6px; font-family: dashicons; font-size: 0.9em; font-style: italic; z-index: 20; }';
	
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$style .= 'body.elementor-editor-active .zoooom::before' . $zoom_class_in_editor;
		}
	
		if ( defined( 'WPB_VC_VERSION' ) ) {
			$style .= '.vc_editor.compose-mode .zoooom::before' . $zoom_class_in_editor;
		}

		if ( strpos( $theme, 'divi' ) !== false ) {
			$style .= 'body.et_pb_pagebuilder_layout.et-fb .zoooom::before' . $zoom_class_in_editor;
		}

		$type = current_theme_supports( 'html5', 'style' ) ? '' : ' type="text/css"';
		if ( ! empty( $style ) ) {
			echo '<style' . $type . '>' . $style . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}


	/**
	 * CSS for themes that remove the WooCommerce wc-product-gallery-slider CSS.
	 */
	public static function wc3gallery_css() {

		$style = '.woocommerce div.product div.images{margin-bottom:2em}.woocommerce div.product div.images img{display:block;width:100%;height:auto;box-shadow:none}.woocommerce div.product div.images div.thumbnails{padding-top:1em}.woocommerce div.product div.images.woocommerce-product-gallery{position:relative}.woocommerce div.product div.images .woocommerce-product-gallery__wrapper{-webkit-transition:all cubic-bezier(.795,-.035,0,1) .5s;transition:all cubic-bezier(.795,-.035,0,1) .5s;margin:0;padding:0}.woocommerce div.product div.images .woocommerce-product-gallery__wrapper .zoomImg{background-color:#fff;opacity:0}.woocommerce div.product div.images .woocommerce-product-gallery__image--placeholder{border:1px solid #f2f2f2}.woocommerce div.product div.images .woocommerce-product-gallery__image:nth-child(n+2){width:25%;display:inline-block}.woocommerce div.product div.images .woocommerce-product-gallery__trigger{position:absolute;top:.5em;right:.5em;font-size:2em;z-index:9;width:36px;height:36px;background:#fff;text-indent:-9999px;border-radius:100%;box-sizing:content-box}.woocommerce div.product div.images .woocommerce-product-gallery__trigger::before{content:"";display:block;width:10px;height:10px;border:2px solid #000;border-radius:100%;position:absolute;top:9px;left:9px;box-sizing:content-box}.woocommerce div.product div.images .woocommerce-product-gallery__trigger::after{content:"";display:block;width:2px;height:8px;background:#000;border-radius:6px;position:absolute;top:19px;left:22px;-webkit-transform:rotate(-45deg);-ms-transform:rotate(-45deg);transform:rotate(-45deg);box-sizing:content-box}.woocommerce div.product div.images .flex-control-thumbs{overflow:hidden;zoom:1;margin:0;padding:0}.woocommerce div.product div.images .flex-control-thumbs li{width:25%;float:left;margin:0;list-style:none}.woocommerce div.product div.images .flex-control-thumbs li img{cursor:pointer;opacity:.5;margin:0}.woocommerce div.product div.images .flex-control-thumbs li img.flex-active,.woocommerce div.product div.images .flex-control-thumbs li img:hover{opacity:1}.woocommerce div.product .woocommerce-product-gallery--columns-3 .flex-control-thumbs li:nth-child(3n+1){clear:left}.woocommerce div.product .woocommerce-product-gallery--columns-4 .flex-control-thumbs li:nth-child(4n+1){clear:left}.woocommerce div.product .woocommerce-product-gallery--columns-5 .flex-control-thumbs li:nth-child(5n+1){clear:left}';

		$type = current_theme_supports( 'html5', 'style' ) ? '' : ' type="text/css"';
		echo '<style' . $type . '>' . $style . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * Adjust hooks after the theme loaded.
	 */
	public static function after_setup_theme() {
		$theme = strtolower( get_template() );

		$opt                       = get_option( 'zoooom_general' );
		$opt['enable_woocommerce'] = isset( $opt['enable_woocommerce'] ) ? $opt['enable_woocommerce'] : true;

		if ( strpos( $theme, 'enfold' ) !== false && $opt['enable_woocommerce'] && class_exists( 'woocommerce' ) && version_compare( WC_VERSION, '3.0', '>' ) ) {
			remove_action( 'woocommerce_product_thumbnails', 'avia_product_gallery_thumbnail_opener', 19 );
			remove_action( 'woocommerce_product_thumbnails', 'avia_close_div', 21 );
			remove_filter( 'woocommerce_single_product_image_thumbnail_html', 'avia_woocommerce_gallery_thumbnail_description', 10, 4 );
		}


		// Disable the Lazy Loading functionality for the LiteSpeed Cache plugin.
		if ( defined( 'LSWCP_PLUGIN_URL' ) ) {
			do_action( 'litespeed_conf_force', 'media-lazy', false );
		}
	}


	/**
	 * Add zoom option in the vc_single_image shortcode in WPBakery
	 */
	public static function vc_after_init() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return false;
		}
		$param = WPBMap::getParam( 'vc_single_image', 'style' );
		if ( is_array( $param ) ) {
			$param['value'][ __( 'WP Image Zoooom', 'wp-image-zoooom' ) ] = 'zoooom';
			vc_update_shortcode_param( 'vc_single_image', $param );
		}
	}


	/**
	 * Admin side modifications.
	 */
	public static function admin_side() {
		if ( ! is_admin() ) return;
		if ( strpos( strtolower( get_template() ), 'enfold' ) !== false ) {
			add_theme_support( 'avia_template_builder_custom_css' );
		}
	}
}

IZ_Compatibilities::init();
