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
            'rewrite' => [
					'slug' => 'works',
					 'with_front' => TRUE
					],
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

			// Online exhibitions
        	create_post_type([
				'name' => 'Online exhibitions',
				'singular_name' => 'Online exhibition',
				'has_archive' => false,
				'rewrite' => [
					//'slug' => 'private-sales/private-room',
					'with_front' => false
				],
				'menu_icon' =>  'dashicons-format-gallery',
				'menu_position' => 5,
				'supports' => [
					'title',
					'thumbnail',
					'editor',
					'revisions'
				],
			]);

			// Art fairs
        	create_post_type([
				'name' => 'Art fairs',
				'singular_name' => 'Art fairs',
				'has_archive' => false,
				'rewrite' => [
					//'slug' => 'private-sales/private-room',
					'with_front' => false
				],
				'menu_icon' =>  'dashicons-images-alt2',
				'menu_position' => 5,
				'supports' => [
					'title',
					'thumbnail',
					'editor',
					'revisions'
				],
			]);

			// Gallery Exhibitions
        	create_post_type([
				'name' => 'Gallery exhibitions',
				'singular_name' => 'Gallery exhibitions',
				'has_archive' => false,
				'rewrite' => [
					//'slug' => 'private-sales/private-room',
					'with_front' => false
				],
				'menu_icon' =>  'dashicons-images-alt',
				'menu_position' => 5,
				'supports' => [
					'title',
					'thumbnail',
					'editor',
					'revisions'
				],
			]);

			// On view
        	// create_post_type([
			// 	'name' => 'On view',
			// 	'singular_name' => 'On view',
			// 	'has_archive' => false,
			// 	'rewrite' => [
			// 	//'slug' => 'private-sales/private-rooms',
			// 	'with_front' => false
			// 	],
			// 	'menu_icon' =>  'dashicons-admin-site-alt2',
			// 	'menu_position' => 5,
			// 	'supports' => [
			// 		'title',
			// 		'thumbnail',
			// 		'revisions'
			// 	],
			// ]);


		   // Private sales
        	// create_post_type([
			// 	'name' => 'Private sales',
			// 	'singular_name' => 'Private sale',
			// 	'has_archive' => false,
			// 	'rewrite' => [
			// 		//'slug' => 'private-sales/private-rooms',
			// 		'with_front' => false
			// 	],
			// 	'menu_icon' =>  'dashicons-welcome-view-site',
			// 	'menu_position' => 5,
			// 	'supports' => [
			// 		'title',
			// 		'thumbnail',
			// 		'revisions'
			// 	],
			// ]);

         // Private rooms
        	// create_post_type([
			// 	'name' => 'Private rooms',
			// 	'singular_name' => 'Private room',
			// 	'has_archive' => true,
			// 	'rewrite' => [
			// 	//'slug' => 'private-sales/private-rooms',
			// 	'with_front' => false
			// 	],
			// 	'menu_icon' =>  'dashicons-hidden',
			// 	'menu_position' => 5,
			// 	'supports' => [
			// 		'title',
			// 		'thumbnail',
			// 		'revisions'
			// 	],
			// ]);





				}
 
    add_action('init', 'createPostTypes');