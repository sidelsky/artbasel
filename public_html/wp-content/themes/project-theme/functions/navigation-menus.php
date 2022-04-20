<?php
    /**
     * Register menus
     */
    function register_menus()
    {
		register_nav_menus([
            'primary-navigation' => __('Primary Navigation'),
            'secondary-navigation' => __('Secondary Navigation'),
            'tertiary-navigation' => __('Tertiary Navigation'),
            'quaternary-navigation' => __('Quaternary Navigation'),
            'quinary-navigation' => __('Quinary Navigation'),
            'senary-navigation' => __('Senary Navigation'),
            'septenary-navigation' => __('Septenary Navigation'),
            'octonary-navigation' => __('Octonary Navigation'),
            'nonary-navigation' => __('Nonary Navigation'),
            'denary-navigation' => __('Denary Navigation')
        ]);
	}
	add_action('init', 'register_menus');