<?php
/**
 * Button Style
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_buttons_style_field_setting() {

	$options = get_option( 'wpsi_register_settings_fields' ); ?>

	<div class="buttons_style_preview">

		<label for="rounded_radio">
			<input type="radio" id="rounded_radio" name="wpsi_register_settings_fields[wpsi_buttons_style_field]" value="rounded" <?php echo $options['wpsi_buttons_style_field'] == 'rounded' ? 'checked' : "" ?>>
			<img data-toggle="tooltip" data-placement="top" title="Rounded" src="<?php echo plugins_url("/images/icon/rounded.png",WPSI_PLUGIN_URL); ?>">
		</label>

		<label for="square_radio">
			<input type="radio" id="square_radio" name="wpsi_register_settings_fields[wpsi_buttons_style_field]" value="square" <?php echo $options['wpsi_buttons_style_field'] == 'square' ? 'checked' : "" ?>>
			<img data-toggle="tooltip" data-placement="top" title="Squared" src="<?php echo plugins_url("/images/icon/square.png",WPSI_PLUGIN_URL); ?>">
		</label>

		<label for="circle_radio">
			<input type="radio" id="circle_radio" name="wpsi_register_settings_fields[wpsi_buttons_style_field]" value="circle" <?php echo $options['wpsi_buttons_style_field'] == 'circle' ? 'checked' : "" ?>>
			<img data-toggle="tooltip" data-placement="top" title="Circled" src="<?php echo plugins_url("/images/icon/circle.png",WPSI_PLUGIN_URL); ?>">
		</label>

	</div>

<?php }