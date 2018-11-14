<?php
/**
 * ACF Options page
 */
if( function_exists('acf_add_options_page') ) {
 
    acf_add_options_page([
        'page_title'    => 'Site Options',
        'menu_title'    => 'Site Options',
        'menu_slug'     => 'theme-general-settings',
        'icon_url'      => 'dashicons-welcome-view-site',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ]);

    // acf_add_options_sub_page([
    //     'page_title'     => 'Header Settings',
    //     'menu_title'    => 'Header',
    //     'parent_slug'    => 'theme-general-settings',
    // ]);

    // acf_add_options_sub_page([
    //     'page_title'     => 'Footer Settings',
    //     'menu_title'    => 'Footer',
    //     'parent_slug'    => 'theme-general-settings',
    // ]);

}
