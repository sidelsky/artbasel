<?php
/**
 * Button List settings
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_buttons_list_field_setting() {

	$options = get_option( 'wpsi_register_settings_fields' ); ?>

	<input type="hidden" name="wpsi_register_settings_fields[wpsi_buttons_list_field]" id="wpsi_buttons_list_field_values" value="<?php echo $options['wpsi_buttons_list_field']; ?>">
	
	<ul id="selected">
		
		<section class="selected_container">
			
		<?php
		
			$selected_services = explode( ",", $options['wpsi_buttons_list_field'] );

			require_once WPSI_PLUGIN_PATH . "/images/data/template.php" ;

			foreach ( $selected_services as $value ) {
				
				if ( !empty( $value ) ) {
					
					echo $templates[$value];
				}
			}

		?>
		
		</section>
		
		<button id="social_service_toggle_btn" class="button" data-toggle="modal" data-target="#myModal"><?php echo esc_html__('Add Social Service +','wpsisocialshare'); ?></button>
	
	</ul>

<?php require_once WPSI_PLUGIN_INC . "/wpsi_modal_template.php"; }