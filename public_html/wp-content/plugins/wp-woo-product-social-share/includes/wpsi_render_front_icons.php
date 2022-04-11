<?php
add_action( 'wp_enqueue_scripts', 'wpsi_social_addtoany_js' );

// get settings
$wpsi_options = get_option( 'wpsi_register_settings_fields' );
/**
 * Add Front End Buttons Filtering Admin Options
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_social_addtoany_js() {

	global $wpsi_options;

	if ( $wpsi_options['wpsi_show_hide_field'] == 'on' && !is_admin() ) {
    	
		wp_enqueue_script( 'wpsi_addtoany_script' );
		
		wp_enqueue_script( 'wpsi_frontend_js' );
		
		wp_enqueue_style( 'wpsi_front_end' );
	}
}

$scriptJS = '';

if ( $wpsi_options['wpsi_show_hide_field'] == 'on' ) {

	require_once WPSI_PLUGIN_PATH . "images/data/background.php" ;

		switch ( $wpsi_options['wpsi_buttons_position_field'] ) {
			
			case 'wpsi_position_default':
				
				$wpsi_position = 55;
			break;
			
			case 'wpsi_position_apt':
				
				$wpsi_position = 8;
			break;
			
			case 'wpsi_position_bpt':
			
				$wpsi_position = 3;
			break;
			
			case 'wpsi_position_asd':
				
				$wpsi_position = 25;
			break;
			
			case 'wpsi_position_aatcb':
				
				$wpsi_position = 35;
			break;
			
			case 'wpsi_position_bti':
				
				$wpsi_position = 5;
			break;
			
			case 'wpsi_position_ffr':
				
				$scriptJS .= '';
			
			break;
			
			case 'wpsi_position_ffl':
				
				$scriptJS .= '';
			break;
		}

}else{ return; }

global $wpsi_position;

add_action( "woocommerce_single_product_summary", "wpsi_render_social_buttons_front", $wpsi_position );

add_action( "woocommerce_after_single_product_summary", "wpsi_render_social_buttons_front_before_tab", $wpsi_position );

/**
 * Render Buttons
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_render_social_buttons_front(){

	global $wpsi_position, $wpsi_options, $wpsi_backgrounds;

	if ( $wpsi_position == 55 || $wpsi_position == 8 || $wpsi_position == 3 || $wpsi_position == 25 || $wpsi_position == 35 ) {

		$html  = '<div class="wpsi_social_share_buttons not_before_tab row a2a_kit a2a_kit_size_32 a2a_default_style">';
		
		$social_services = explode( ",", $wpsi_options['wpsi_buttons_list_field'] );
		

		foreach ( $social_services as $value ) {
			
			if ( !empty( $value ) ) {

				if ( isset( $wpsi_options['wpsi_buttons_icontext_field'] ) && $wpsi_options['wpsi_buttons_icontext_field'] == 'text_icons' ) {
					
					$text = ucwords( str_replace( "_", " ",$value) );
					
					$color = $wpsi_backgrounds[$value];
					
					$bg   = "style='$color;'";
					
					$class = 'text_only';
				
				}else{
					
					$text = '';
					
					$bg   = '';
					
					$class = 'icons_only';
				}

				if ( isset( $wpsi_options['wpsi_buttons_style_field']) && $wpsi_options['wpsi_buttons_style_field'] == 'square' && $wpsi_options['wpsi_buttons_icontext_field'] == 'icons_only' ) {
					
					echo '<style type="text/css">.a2a_svg, .a2a_count { border-radius: 0 !important; }</style>';
				
				}elseif( isset( $wpsi_options['wpsi_buttons_style_field']) && $wpsi_options['wpsi_buttons_style_field'] == 'circle'  && $wpsi_options['wpsi_buttons_icontext_field'] == 'icons_only' ){
					
					echo '<style type="text/css">.a2a_svg, .a2a_count { border-radius: 100% !important; }</style>';
				}

				$html .= "<a $bg class='a2a_button_$value $class col-xs-6 col-md-6 col-lg-6'>".esc_attr($text)."</a>";
			}
		}
		
		$html .= '</div>';
		
		echo $html;
	}
}


/**
 * Before Tab Render Buttons
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_render_social_buttons_front_before_tab(){
	
	global $wpsi_position, $wpsi_options, $wpsi_backgrounds;

	if ( $wpsi_position == 5 ) {
		
		$html  = '<div class="wpsi_social_share_buttons before_tab row a2a_kit a2a_kit_size_32 a2a_default_style">';
		
		$social_services = explode( ",", $wpsi_options['wpsi_buttons_list_field'] );

		foreach ( $social_services as $value ) {
			
			if ( !empty( $value ) ) {

				if ( isset( $wpsi_options['wpsi_buttons_icontext_field'] ) && $wpsi_options['wpsi_buttons_icontext_field'] == 'text_icons' ) {
					
					$text = ucwords( str_replace("_", " ", $value ) );
					
					$color = $wpsi_backgrounds[$value];
					
					$bg   = "style='$color ;'";
					
					$class = 'text_only';
				
				}else{
					
					$text = '';
					
					$bg   = '';
					
					$class = 'icons_only';
				}

				if ( isset( $wpsi_options['wpsi_buttons_style_field']) && $wpsi_options['wpsi_buttons_style_field'] == 'square' && $wpsi_options['wpsi_buttons_icontext_field'] == 'icons_only' ) {
					
					echo '<style type="text/css">.a2a_svg, .a2a_count { border-radius: 0 !important; }</style>';
				
				}elseif( isset( $wpsi_options['wpsi_buttons_style_field']) && $wpsi_options['wpsi_buttons_style_field'] == 'circle'  && $wpsi_options['wpsi_buttons_icontext_field'] == 'icons_only' ){
					
					echo '<style type="text/css">.a2a_svg, .a2a_count { border-radius: 100% !important; }</style>';
				}

				$html .= "<a $bg class='a2a_button_$value $class col-xs-6 col-md-6 col-lg-3'>".esc_attr($text)."</a>";
			}
		}

		$html .= '</div>';

		echo $html;
	}
}