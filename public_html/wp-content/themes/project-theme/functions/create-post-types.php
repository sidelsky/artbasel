<?php

    use App\Helper\CustomPostType;
 
    /**
    * Custom Post Types
    */
 
    function createPostTypes() {
 
        // Works
        create_post_type([
			    'name' => 'Works',
               'singular_name' => 'Works',
               'has_archive' => FALSE,
               'rewrite' => array(
                    'slug' => 'works',
                    'with_front' => TRUE
					),
					'menu_icon' =>  'dashicons-format-image',
					'menu_position' => 5,
					'supports' => [
						'title',
						'editor',
						'thumbnail',
						'excerpt',
						'revisions'
					],
		  ]);

                // Works
        	create_post_type([
                'name' => 'Private room',
                'singular_name' => 'Private room',
                'has_archive' => true,
                'rewrite' => array(
                    'slug' => 'private-sales/private-room',
                    'with_front' => true
                ),
                'menu_icon' =>  'dashicons-admin-network',
                'menu_position' => 6,
                'supports' => [
                    'title',
                    'revisions'
						],
					]);


				}
 
    add_action('init', 'createPostTypes');
 
 