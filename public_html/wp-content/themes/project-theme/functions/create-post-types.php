<?php

    use App\Helper\CustomPostType;
 
    /**
    * Custom Post Types
    */
 
    function createPostTypes() {
 
        // Heads of Motors
        create_post_type(
            array(
                'name' => 'Works',
                'singular_name' => 'Works',
                'has_archive' => FALSE,
                'rewrite' => array(
                    'slug' => 'works',
                    'with_front' => TRUE
                ),
                'menu_icon' =>  'dashicons-format-image',
                'menu_position' => 5,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                    'revisions'
                ),
            )
        );

 
 
    }
 
    add_action('init', 'createPostTypes');
 
 