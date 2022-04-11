<?php
/**
 * Show Hide Field Settings
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_show_hide_field_setting() {

	$options = get_option( 'wpsi_register_settings_fields' ); ?>

    <label class="switch">
    	
    	<input type="checkbox" name="wpsi_register_settings_fields[wpsi_show_hide_field]" <?php checked( 'on', $options['wpsi_show_hide_field'], true ); ?> />
		
		<span class="slider"></span>
	
	</label>

<?php }