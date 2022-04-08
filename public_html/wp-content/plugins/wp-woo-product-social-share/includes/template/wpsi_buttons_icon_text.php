<?php
/**
 * Button Type Settings
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_buttons_icontext_field_setting() {

		$options = get_option('wpsi_register_settings_fields');

		$select_fields = array(

			"icons_only" => esc_html__("Icons Only",'wpsisocialshare'),
			"text_icons" => esc_html__("Text With Icons",'wpsisocialshare'),
		);
?>
		<select name="wpsi_register_settings_fields[wpsi_buttons_icontext_field]" id="wpsi_buttons_icontext_field">
			
			<?php
			
				foreach ( $select_fields as $key => $value ) {
					
					echo "<option value=$key";
					
					if( isset( $options['wpsi_buttons_icontext_field'] ) && $key == $options['wpsi_buttons_icontext_field'] ){
					
						echo " selected";
					}
					
					echo ">".esc_attr($value)."</option>";
				}
			?>
		</select>
<?php }