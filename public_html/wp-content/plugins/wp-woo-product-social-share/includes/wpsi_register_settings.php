<?php
/**
 * Register Plugin Options Via Settins API
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_register_settings() {

		add_settings_section(
			'wpsi_main_settings_section',
			esc_html__('General Settings','wpsisocialshare'),
			'wpsi_main_settings_description',
			'wpsi_settings_section'
		);

		add_settings_field(
			'wpsi_show_hide_field',
			esc_html__('Enable Sharing Buttons','wpsisocialshare'),
			'wpsi_show_hide_field_setting',
			'wpsi_settings_section',
			'wpsi_main_settings_section'
		);

		add_settings_field(
			'wpsi_buttons_position_field',
			esc_html__('Buttons Positions','wpsisocialshare'),
			'wpsi_buttons_position_field_setting',
			'wpsi_settings_section',
			'wpsi_main_settings_section'
		);

		add_settings_field(
			'wpsi_buttons_style_field',
			esc_html__('Buttons Style','wpsisocialshare'),
			'wpsi_buttons_style_field_setting',
			'wpsi_settings_section',
			'wpsi_main_settings_section'
		);

		add_settings_field(
			'wpsi_buttons_list_field',
			esc_html__('Add Social Buttons','wpsisocialshare'),
			'wpsi_buttons_list_field_setting',
			'wpsi_settings_section',
			'wpsi_main_settings_section'
		);

		add_settings_field(
			'wpsi_buttons_icontext_field',
			esc_html__('Display Type','wpsisocialshare'),
			'wpsi_buttons_icontext_field_setting',
			'wpsi_settings_section',
			'wpsi_main_settings_section'
		);

		register_setting( 'wpsi_settings_group', 'wpsi_register_settings_fields', 'wpsi_main_settings_validate' ) ;
}

function wpsi_main_settings_description(){
    //main description
}