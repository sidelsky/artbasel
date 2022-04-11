<?php
/**
 * Buttons Position
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_buttons_position_field_setting() {

	$options = get_option( 'wpsi_register_settings_fields' );
	
	$select_fields = array(
		"wpsi_position_default" => esc_html__("Default",'wpsisocialshare'),
		"wpsi_position_apt" => esc_html__("After Product Title",'wpsisocialshare'),
		"wpsi_position_bpt" => esc_html__("Before Product Title",'wpsisocialshare'),
		"wpsi_position_asd" => esc_html__("After Short Description",'wpsisocialshare'),
		"wpsi_position_aatcb" => esc_html__("After Add To Cart Button",'wpsisocialshare'),
		"wpsi_position_bti" => esc_html__("Before Tab Information",'wpsisocialshare')
	);
?>
    <select name="wpsi_register_settings_fields[wpsi_buttons_position_field]" id="wpsi_buttons_position">

	<?php
		
		foreach ( $select_fields as $key => $value ) {
			
			echo "<option value=$key";
			
			if( isset( $options['wpsi_buttons_position_field'] ) && $key == $options['wpsi_buttons_position_field'] ){
			
				echo " selected";
			}
			
			echo ">".esc_attr($value)."</option>";
		}
	?>

    </select>

<?php }