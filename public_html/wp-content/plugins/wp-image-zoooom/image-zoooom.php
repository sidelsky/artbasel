<?php
/**
 * Plugin Name: WP Image Zoom
 * Plugin URI: https://wordpress.org/plugins/wp-image-zoooom/
 * Description: Add zoom effect over the an image, whether it is an image in a post/page or the featured image of a product in a WooCommerce shop
 * Version: 1.47.1
 * Author: SilkyPress
 * Author URI: https://www.silkypress.com
 * License: GPL2
 *
 * Text Domain: wp-image-zoooom
 * Domain Path: /languages/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 5.6 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ImageZoooom' ) ) :
	/**
	 * Main ImageZoooom Class
	 *
	 * @class ImageZoooom
	 */
	final class ImageZoooom {
		public $version             = '1.47.1';
		public $theme               = '';
		protected static $_instance = null;


		/**
		 * Main ImageZoooom Instance
		 *
		 * Ensures only one instance of ImageZoooom is loaded or can be loaded
		 *
		 * @static
		 * @return ImageZoooom - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
		}

		/**
		 * Image Zoooom Constructor
		 *
		 * @access public
		 * @return ImageZoooom
		 */
		public function __construct() {
			global $_wp_theme_features;

			define( 'IMAGE_ZOOM_FILE', __FILE__ );
			define( 'IMAGE_ZOOM_URL', plugins_url( '/', __FILE__ ) );
			define( 'IMAGE_ZOOM_PATH', plugin_dir_path( __FILE__ ) );
			define( 'IMAGE_ZOOM_VERSION', $this->version );

			$this->theme = strtolower( get_template() );
			include_once 'includes/settings.php';

			if ( is_admin() ) {
				$this->load_plugin_textdomain();
				include_once 'includes/admin-side.php';
				new ImageZoooom_Admin();
			}
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );

			include_once 'includes/class-iz-compatibilities.php';
		}

		/**
		 * Show the javascripts in the front-end
		 * Hooked to template_redirect in $this->__construct()
		 *
		 * @access public
		 */
		public function template_redirect() {

			$general = $this->get_option_general();

			if ( isset( $general['enable_mobile'] ) && empty( $general['enable_mobile'] ) && wp_is_mobile() ) {
				return false;
			}

			// Adjust the zoom to WooCommerce 3.0.+
			if ( $general['enable_woocommerce'] && class_exists( 'woocommerce' ) && version_compare( WC_VERSION, '3.0', '>' ) ) {
				remove_theme_support( 'wc-product-gallery-zoom' );
				// remove_theme_support( 'wc-product-gallery-lightbox' );
				add_theme_support( 'wc-product-gallery-slider' );

				if ( $this->theme( 'kiddy' ) || ( $this->theme( 'flatsome' ) && ! get_theme_mod( 'product_gallery_woocommerce' ) ) ) {
					remove_theme_support( 'wc-product-gallery-slider' );
				}

				if ( $this->theme( 'thegem' ) ) {
					remove_action( 'thegem_woocommerce_single_product_left', 'thegem_woocommerce_single_product_gallery', 5 );
					add_action( 'thegem_woocommerce_single_product_left', 'woocommerce_show_product_images', 20 );
				}

				if ( $this->theme( 'enfold' ) ) {
					add_action( 'wp_head', 'IZ_Compatibilities::wc3gallery_css', 40 );
				}
			}

			add_filter( 'woocommerce_single_product_image_html', array( $this, 'woocommerce_single_product_image_html' ) );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'woocommerce_single_product_image_thumbnail_html' ) );

			add_filter( 'woocommerce_single_product_image_html', array( $this, 'remove_prettyPhoto' ) );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'remove_prettyPhoto' ) );

			add_filter( 'the_content', array( $this, 'find_bigger_image' ), 40 );

			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

			add_filter( 'wp_calculate_image_srcset', array( $this, 'wp_calculate_image_srcset' ), 40, 5 );
		}

		/**
		 * If the full image isn't in the srcset, then add it
		 */
		function wp_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
			if ( ! isset( $image_meta['width'] ) ) {
				return $sources;
			}
			if ( ! is_array( $sources ) ) {
				$sources = array();
			}
			if ( isset( $sources[ $image_meta['width'] ] ) ) {
				return $sources;
			}

			if ( is_array( $size_array ) && count( $size_array ) == 2
			&& isset( $size_array[1] ) && isset( $image_meta['height'] )
			&& $size_array[1] > 0
			&& isset( $image_meta['width'] ) && $image_meta['width'] > 0 ) {

				$ratio = $size_array[0] * $image_meta['height'] / $size_array[1] / $image_meta['width'];
				if ( $ratio > 1.03 || $ratio < 0.97 ) {
					return $sources;
				}
			}

			$url                             = str_replace( wp_basename( $image_src ), wp_basename( $image_meta['file'] ), $image_src );
			$sources[ $image_meta['width'] ] = array(
				'url'        => $url,
				'descriptor' => 'w',
				'value'      => $image_meta['width'],
			);
			return $sources;
		}

		/**
		 * Add data-thumbnail-src to the main product image
		 */
		function woocommerce_single_product_image_html( $content ) {
			if ( ! strstr( $content, 'attachment-shop_single' ) ) {
				$content = preg_replace( '/ class="([^"]+)" alt="/i', ' class="attachment-shop_single $1" alt="', $content );
			}
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'shop_thumbnail' );

			if ( ! isset( $thumbnail[0] ) ) {
				return $content;
			}

			$thumbnail_data = ' data-thumbnail-src="' . $thumbnail[0] . '"';

			$content = str_replace( ' title="', $thumbnail_data . ' title="', $content );

			return $content;
		}

		/**
		 * Force the WooCommerce to use the "src" attribute
		 */
		function woocommerce_single_product_image_thumbnail_html( $content ) {
			$content = str_replace( 'class="attachment-shop_single size-shop_single"', 'class="attachment-shop_thumbnail size-shop_thumbnail"', $content );

			if ( ! strstr( $content, 'attachment-shop_thumbnail' ) ) {
				$content = str_replace( ' class="', ' class="attachment-shop_thumbnail ', $content );
			}

			if ( strstr( $content, 'attachment-shop_single' ) ) {
				$content = str_replace( 'attachment-shop_single', '', $content );
			}

			// Fix for the 2.8.6+ Virtue theme, see https://wordpress.org/support/topic/woocommerce_single_product_image_html-filter/
			if ( $this->theme( 'virtue' ) ) {
				$content = str_replace( 'attachment-shop_thumbnail  wp-post-image', 'attachment-shop_single  wp-post-image', $content );
			}
			return $content;
		}

		/**
		 * Remove the lightbox
		 */
		function remove_prettyPhoto( $content ) {
			$replace = array( 'data-rel="prettyPhoto"', 'data-rel="lightbox"', 'data-rel="prettyPhoto[product-gallery]"', 'data-rel="lightbox[product-gallery]"', 'data-rel="prettyPhoto[]"' );

			return str_replace( $replace, 'data-rel="zoomImage"', $content );
		}


		/**
		 * Find bigger image if class="zoooom" and there is no srcset
		 *
		 * Note: the srcset is not be set if for some reason
		 *      the _wp_attachment_metadata for the image is not present
		 */
		function find_bigger_image( $content ) {
			if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
				return $content;
			}

			foreach ( $matches[0] as $image ) {
				// the image has to have the class "zoooom"
				if ( false === strpos( $image, 'zoooom' ) ) {
					continue;
				}
				// the image was tagged to skip this step
				if ( false !== strpos( $image, 'skip-data-zoom-image' ) ) {
					continue;
				}
				// the image does not have the srcset
				if ( false !== strpos( $image, ' srcset=' ) ) {
					continue;
				}
				// the image has an "-300x400.jpg" type ending
				if ( 0 == preg_match( '@ src="([^"]+)(-[0-9]+x[0-9]+).(jpg|png|gif)"@', $image ) ) {
					continue;
				}

				// link the full-sized image to the data-zoom-image attribute
				$full_image      = preg_replace( '@^(.*) src="(.*)(-[0-9]+x[0-9]+).(jpg|png|gif)"(.*)$@', '$2.$4', $image );
				$full_image_attr = ' data-zoom-image="' . $full_image . '"';
				$full_image_img  = str_replace( ' src=', $full_image_attr . ' src=', $image );
				$content         = str_replace( $image, $full_image_img, $content );
			}

			return $content;
		}



		/**
		 * Enqueue the jquery.image_zoom.js
		 * Hooked to wp_enqueue_scripts in $this->template_redirect
		 *
		 * @access public
		 */
		public function wp_enqueue_scripts() {
			$v      = IMAGE_ZOOM_VERSION;
			$url    = IMAGE_ZOOM_URL;
			$prefix = '.min';

			// Load the jquery.image_zoom.js
			wp_register_script( 'image_zoooom', $url . 'assets/js/jquery.image_zoom' . $prefix . '.js', array( 'jquery' ), $v, false );
			wp_enqueue_script( 'image_zoooom' );

			// Load the image_zoom-init.js
			wp_register_script( 'image_zoooom-init', $url . 'assets/js/image_zoom-init.js', array( 'jquery' ), $v, false );
			wp_localize_script( 'image_zoooom-init', 'IZ', $this->get_localize_vars() );
			wp_enqueue_script( 'image_zoooom-init' );

			// Remove the prettyPhoto
			if ( $this->woocommerce_is_active() && function_exists( 'is_product' ) && is_product() ) {
				wp_dequeue_script( 'prettyPhoto' );
				wp_dequeue_script( 'prettyPhoto-init' );
			}

			if ( $this->theme( 'sovereign' ) ) {
				wp_enqueue_script( 'prettyPhoto' );
				wp_enqueue_script( 'prettyPhoto-init' );
			}
		}

		function get_localize_vars() {
			$general = $this->get_option_general();
			$options = $this->get_options_for_zoom();

			$default = array(
				'with_woocommerce'    => '1',
				'exchange_thumbnails' => '1',
				'woo_categories'      => ( isset( $general['woo_cat'] ) && $general['woo_cat'] == 1 ) ? '1' : '0',
				'enable_mobile'       => $general['enable_mobile'],
				'options'             => $options,
				'woo_slider'          => '0',
			);

			if ( class_exists( 'woocommerce' ) && version_compare( WC_VERSION, '3.0', '>' ) && current_theme_supports( 'wc-product-gallery-slider' ) ) {
				$default['woo_slider'] = 1;
			}

			$with_woocommerce = true;
			if ( ! $this->woocommerce_is_active() ) {
				$default['with_woocommerce'] = '0';
			}

			if ( ! function_exists( 'is_product' ) || ! is_product() ) {
				$default['with_woocommerce'] = '0';
			}

			if ( isset( $general['enable_woocommerce'] ) && empty( $general['enable_woocommerce'] ) ) {
				$default['with_woocommerce'] = '0';
			}

			if ( isset( $general['exchange_thumbnails'] ) && empty( $general['exchange_thumbnails'] ) ) {
				$default['exchange_thumbnails'] = '0';
			}

			return $default;
		}

		function get_options_for_zoom() {
			$i = get_option( 'zoooom_settings' );
			$o = array();

			if ( ! $i ) {
				return array();
			}

			switch ( $i['lensShape'] ) {
				case 'none':
					$o = array(
						'zoomType'     => 'inner',
						'cursor'       => $i['cursorType'],
						'easingAmount' => $i['zwEasing'],
					);
					break;
				case 'square':
				case 'round':
					$o = array(
						'lensShape'    => $i['lensShape'],
						'zoomType'     => 'lens',
						'lensSize'     => $i['lensSize'],
						'borderSize'   => $i['borderThickness'],
						'borderColour' => $i['borderColor'],
						'cursor'       => $i['cursorType'],
						'lensFadeIn'   => $i['lensFade'],
						'lensFadeOut'  => $i['lensFade'],
					);
					if ( $i['tint'] == true ) {
						$o['tint']        = 'true';
						$o['tintColour']  = $i['tintColor'];
						$o['tintOpacity'] = $i['tintOpacity'];
					}

					break;
				case 'square':
					break;
				case 'zoom_window':
					$o = array(
						'lensShape'         => 'square',
						'lensSize'          => $i['lensSize'],
						'lensBorderSize'    => $i['borderThickness'],
						'lensBorderColour'  => $i['borderColor'],
						'borderRadius'      => $i['zwBorderRadius'],
						'cursor'            => $i['cursorType'],
						'zoomWindowWidth'   => $i['zwWidth'],
						'zoomWindowHeight'  => $i['zwHeight'],
						'zoomWindowOffsetx' => $i['zwPadding'],
						'borderSize'        => $i['zwBorderThickness'],
						'borderColour'      => $i['zwBorderColor'],
						'zoomWindowShadow'  => $i['zwShadow'],
						'lensFadeIn'        => $i['lensFade'],
						'lensFadeOut'       => $i['lensFade'],
						'zoomWindowFadeIn'  => $i['zwFade'],
						'zoomWindowFadeOut' => $i['zwFade'],
						'easingAmount'      => $i['zwEasing'],
					);

					if ( $i['tint'] == true ) {
						$o['tint']        = 'true';
						$o['tintColour']  = $i['tintColor'];
						$o['tintOpacity'] = $i['tintOpacity'];
					}

					break;
			}
			return $o;
		}



		/** Helper function ****************************************/

		public function theme( $string ) {
			$string = strtolower( $string );
			if ( empty( $this->theme ) ) {
				$this->theme = strtolower( get_template() );
			}
			if ( strpos( $this->theme, $string ) !== false ) {
				return true;
			}

			return false;
		}


		/**
		 * Check if WooCommerce is activated
		 *
		 * @access public
		 * @return bool
		 */
		public function woocommerce_is_active() {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return true;
			}
			return false;
		}

		public function get_option_general() {
			$general = get_option( 'zoooom_general' );

			if ( ! isset( $general['enable_woocommerce'] ) ) {
				$general['enable_woocommerce'] = true;
			}

			if ( ! isset( $general['exchange_thumbnails'] ) ) {
				$general['exchange_thumbnails'] = true;
			}

			if ( ! isset( $general['enable_mobile'] ) ) {
				$general['enable_mobile'] = false;
			}

			if ( ! isset( $general['woo_cat'] ) ) {
				$general['woo_cat'] = false;
			}

			if ( ! $this->woocommerce_is_active() ) {
				$general['woo_cat'] = false;
			}

			return $general;
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'wp-image-zoooom', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}



	}

endif;

/**
 * Returns the main instance of ImageZoooom
 *
 * @return ImageZoooom
 */
function ImageZoooom() {
	return ImageZoooom::instance();
}

ImageZoooom();

/**
 *  * Plugin action link to Settings page
 *  */
function wp_image_zoooom_plugin_action_links( $links ) {

	$settings_link = '<a href="admin.php?page=zoooom_settings">' .
		esc_html( __( 'Settings', 'wp-image-zoooom' ) ) . '</a>';

	return array_merge( array( $settings_link ), $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wp_image_zoooom_plugin_action_links' );

if ( ! function_exists( 'x_disable_wp_image_srcset' ) ) :
	function x_disable_wp_image_srcset() {
		return true;
	}
endif;
