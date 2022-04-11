<?php
/**
 * Styles & Scripts For Backend Settings Page
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_enqueue_assets(){
	
	if(is_admin()){
		if ( get_current_screen()->id == 'toplevel_page_wpsi-social-share' ) {
			
			wp_enqueue_style( "wpsi_css", plugins_url( "images/css/wpsi-style.css", WPSI_PLUGIN_URL ), true );
			
			wp_enqueue_style( "wpsi_css_for_icons", plugins_url( "images/css/wpsi-css-for-icons.css", WPSI_PLUGIN_URL ), false );
			
			wp_enqueue_style( "wpsi_icons", plugins_url( "images/css/icons.26.svg.css", WPSI_PLUGIN_URL ), true );
			
			wp_enqueue_style( "select2_css", plugins_url( "images/css/select2.min.css", WPSI_PLUGIN_URL), true );
			
			wp_enqueue_script( "select2_js", plugins_url( "images/js/select2.min.js", WPSI_PLUGIN_URL ), true );
			
			wp_enqueue_script( "wpsi_popper_js", plugins_url( "images/js/popper.min.js", WPSI_PLUGIN_URL ), true );
			
			wp_enqueue_script( "wpsi_bootstrap_js", plugins_url( "images/js/bootstrap.min.js", WPSI_PLUGIN_URL ),array('jquery'), true );
			
			wp_enqueue_script( "wpsi_app", plugins_url( "images/js/wpsi-app.js", WPSI_PLUGIN_URL ), array( 'jquery', 'wp-color-picker', 'wpsi_bootstrap_js' ), true );
		}
	}
}


add_action( 'wp_enqueue_scripts', 'wpsi_social_icons_front_js' );
/**
 * Styles & Scripts For Front End Buttons Content
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_social_icons_front_js() {
    
     if(!is_admin()){
	    wp_register_script( 'wpsi_addtoany_script', plugins_url( "images/js/wpsi-front-page.js", WPSI_PLUGIN_URL ), true );
	    
	    wp_register_script( "wpsi_frontend_js", plugins_url( "images/js/wpsi-front-script.js", WPSI_PLUGIN_URL ), array( 'jquery' ), true );
	    
	    wp_register_style( "wpsi_front_end", plugins_url( "images/css/wpsi-front-style.css", WPSI_PLUGIN_URL ), true );
	    
	    wp_enqueue_style( "wpsi_css_front_icons", plugins_url( "images/css/wpsi-css-for-icons.css", WPSI_PLUGIN_URL ), true );
	 }
}