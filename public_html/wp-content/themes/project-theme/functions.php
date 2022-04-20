<?php

use App\Helper\CustomTaxonomy;
use App\Helper\Enqueues;
use App\Helper\Reset;
use App\Helper\DisableComments;
use App\Helper\Utilities;
use App\Helper\ProjectConfig;

$theme = wp_get_theme();
$versionNumber = $theme->get( 'Version' );

//Featured Image
add_theme_support( 'post-thumbnails' );

//Reset Wordpress (removes redundant scripts etc.)
    add_action('init', 'resetWordpressDefaults');
    function resetWordpressDefaults()
    {
        // DisableComments::disableAllComments();
        // Reset::resetWordpressDefaults();
    }

    //Enqueue scripts and styles
    add_action('wp_enqueue_scripts', 'enqueueScriptsAndStyles');
    function enqueueScriptsAndStyles()
    {
        new Enqueues();
    }

    //Custom taxonomies
    //Set all of the taxonomies
    function customTaxonomies() {
        $ad_type = create_custom_taxonomy(
            $args = [
                'name' => 'Collection',
                'singular_name' => 'Collection',
                'slug' => 'collection'
            ]
        );
        $custom_posts = array(
            'post_name' => 'works'
        );
        register_taxonomy($args['slug'], $custom_posts['post_name'], $ad_type['args']);
    }
    
    add_action('init', 'customTaxonomies');

    //Taxonomies
    // add_action('init', 'createTaxonomies');
    // function createTaxonomies()
    // {   
    //     //Collection
    //     $collectionTax = CustomTaxonomy::createTaxonomy("Collection", "Collection", "collection");
    //     register_taxonomy("event_type", array("works"), $collectionTax["args"]);
    // }

    //Project configuration - menus, image crops etc.
    add_action('init', 'projectConfig');
    function projectConfig()
    {
        // ProjectConfig::ProjectConfig();
    }

    /**
    * Require all functions within the functions folder
    */
    function getFunctions()
    {

        $folder = '/functions/*.php';
        $files = glob(dirname(__FILE__) . $folder);

        foreach( $files as $file ) {
            require_once( $file );
        }

    }

    getFunctions();