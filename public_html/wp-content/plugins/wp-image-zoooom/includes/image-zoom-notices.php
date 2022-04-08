<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * ImageZoooom_Notices
 */
class ImageZoooom_Notices {

	var $main            = '';
	var $activation_time = '';
	var $dismiss_notice  = '';
	var $expiration_days = 3;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->set_variables();

		if ( $this->dismiss_notice == 1 ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'wp_ajax_zoooom_notice_dismiss', array( $this, 'notice_dismiss' ) );
	}

	/**
	 * Hooked from 'admin_notices'
	 */
	public function admin_notices() {

		if ( ! $notice = $this->choose_notice() ) {
			return;
		}

		$message = $this->get_message( $notice );

		$this->print_message( $notice, $message );

	}

	/**
	 * Get the options from the database or set them on install or upgrade
	 */
	public function set_variables() {
		$now = time();

		$this->activation_time = get_option( 'zoooom_activation_time', '' );
		$last_version          = get_option( 'zoooom_version', '' );
		$v                     = IMAGE_ZOOM_VERSION;
		$this->dismiss_notice  = get_option( 'zoooom_dismiss_notice', false );

		if ( empty( $this->activation_time ) || version_compare( $last_version, $v, '<' ) ) {
			$this->activation_time = $now;
			update_option( 'zoooom_activation_time', $now );
			update_option( 'zoooom_version', $v );
			update_option( 'zoooom_dismiss_notice', false );
		}

	}

	/**
	 * Choose which notice to be shown
	 */
	public function choose_notice() {
		$now = time();

		$days_passed = ceil( ( $now - $this->activation_time ) / 86400 );

		switch ( $days_passed ) {
			case 1:
				return '1_day';
			case 2:
				return '2_day';
			case 3:
				return '3_day';
			case 4:
			case 5:
			case 6:
			case 7:
				return '7_day';
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
				return '12_day';
		}
	}

	/**
	 * Get the text of the message
	 */
	public function get_message( $notice ) {

		$message    = '';
		$percentage = '40';

		$expiration_date = $this->activation_time + ( $this->expiration_days * 86400 );
		$expiration_date = date( get_option( 'date_format' ), $expiration_date );

		if ( $notice == '12_days' ) {
			$link = 'https://www.silkypress.com/wp-image-zoooom-pro-offer/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
		} else {
			$link = 'https://www.silkypress.com/wp-image-zoooom-pro-special-offer/?a=' . $this->convert_numbers_letters( $this->activation_time ) . '&utm_source=wordpress&utm_campaign=iz_offer&utm_medium=banner';
		}

		$lower_part = sprintf( '<div style="margin-top: 7px;"><a href="%s" target="_blank">%s</a> | <a href="#" class="dismiss_notice"  target="_parent">%s</a></div>', $link, 'Get WP Image Zoooom PRO', 'Dismiss this notice' );

		switch ( $notice ) {
			case '1_day':
				$message = '<div><b>Limited offer ending on ' . $expiration_date . '</b>. ' . $percentage . '% Off from WP Image Zoooom PRO for our WordPress.org users.</div>' . $lower_part;
				break;

			case '2_day':
				$message = '<div><b>Limited offer ending in 1 day (on ' . $expiration_date . ')</b>. ' . $percentage . '% Off from WP Image Zoooom PRO for our WordPress.org users. </div>' . $lower_part;
				break;

			case '3_day':
				$message = '<div><b>Limited offer ending today</b>. ' . $percentage . '% Off from WP Image Zoooom PRO for our WordPress.org users. </div>' . $lower_part;
				break;

			case '7_day':
				$message = '';
				break;

			case '12_day':
				$message = '<div><b>Special Offer</b>: 30% Off from WP Image Zoooom PRO for our WordPress.org users.</div>' . $lower_part;
				break;
		}

		return $message;
	}



	/**
	 * Print the message
	 */
	public function print_message( $option_name = '', $message = '' ) {
		if ( empty( $message ) || empty( $option_name ) ) {
			return;
		}

		?>
			<style type="text/css">
					.zoooom_note{ color: #bc1117; }
					#zoooom_notice { display: block; padding:  }
					#zoooom_notice b { color: #bc1117; }
					#zoooom_notice a { text-decoration: none; font-weight: bold; }
					#zoooom_notice a.dismiss_notice { font-weight: normal; }
			</style>

			<script type='text/javascript'>
				jQuery(function($){
					$(document).on( 'click', '.zoooom_notice .dismiss_notice', function() {

						var data = {
							action: 'zoooom_notice_dismiss',
							option: '<?php echo $option_name; ?>'
						};
						$.post(ajaxurl, data, function(response ) {
							$('#zoooom_notice').fadeOut('slow');
						});
					});
				});
			</script>

			<div id="zoooom_notice" class="updated notice zoooom_notice is-dismissible">
			<p><?php echo $message; ?></p>
			<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php _e( 'Dismiss this notice' ); ?></span>
			</button>
			</div>
		<?php

	}

	function convert_numbers_letters( $text, $from = 'numbers' ) {
		$alphabet = str_split( 'abcdefghij' );
		$numbers  = str_split( '0123456789' );

		if ( $from == 'numbers' ) {
			return str_replace( $numbers, $alphabet, $text );
		} else {
			return str_replace( $alphabet, $numbers, $text );
		}
	}

	/**
	 * Ajax response for `notice_dismiss` action
	 */
	function notice_dismiss() {

		update_option( 'zoooom_dismiss_notice', 1 );

		wp_die();
	}
}


return new ImageZoooom_Notices();
