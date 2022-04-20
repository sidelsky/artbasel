<?php
 
    /**
    * Create Custom Post Type
    */
 
    function create_post_type( $args ) {
 
        //If we've set a single and plural title
        if ($args['singular_name'] && $args['name']) {
 
            $title_slug = sanitize_title($args['name']);
 
            register_post_type($title_slug,
 
            array(
                'labels' => array(
                    'name' => __($args['name'], $title_slug),
                    'singular_name' => __($args['singular_name'], $title_slug),
                    'add_new' => __('Add New', $title_slug),
                    'add_new_item' => __('Add New ' . $args['singular_name'], $title_slug),
                    'edit' => __('Edit', $title_slug),
                    'edit_item' => __('Edit ' . $args['singular_name'], $title_slug),
                    'new_item' => __('New ' . $args['singular_name'], $title_slug),
                    'view' => __('View ' . $args['singular_name'], $title_slug),
                    'view_item' => __('View ' . $args['singular_name'], $title_slug),
                    'search_items' => __('Search ' . $args['name'], $title_slug),
                    'not_found' => __('No ' . $args['name'] . ' found', $title_slug),
                    'not_found_in_trash' => __('No ' . $args['name'] . ' found in Trash', $title_slug)
                ),
                'public' => true,
                'hierarchical' => true,
                'has_archive' => $args['has_archive'],
                'rewrite' => $args['rewrite'],
                'menu_icon' =>  $args['menu_icon'],
                'menu_position' => $args['menu_position'],
                'supports' => $args['supports'],
                'taxonomies' => array(),
                'can_export' => true
                )
            );
 
        }
 
    }
 
 
 
?>