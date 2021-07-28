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

    //artbasel taxonomies
function custom_post_type() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Art Basel', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Art Basel', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Art Basel', 'twentytwenty' ),
        'all_items'           => __( 'All Art Works', 'twentytwenty' ),
        'view_item'           => __( 'View Art Work', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Art Work', 'twentytwenty' ),
        'add_new'             => __( 'Add New Art Work', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Art Work', 'twentytwenty' ),
        'update_item'         => __( 'Update Art Work', 'twentytwenty' ),
        'search_items'        => __( 'Search Art Work', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );

// Set other options for Custom Post Type

    $args = array(
        'label'               => __( 'artbasel', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => array( 'Art Works' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'taxonomies'          => array('topics', 'category' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
    );

    // Registering your Custom Post Type
    register_post_type( 'artbasel', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action( 'init', 'custom_post_type', 0 );


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

    /**
    * rename woocommerce checkout pages - https://www.hongkiat.com/blog/price-request-catalog-woocommerce/


add_filter( 'woocommerce_free_price_html', 'hide_free_price_notice');
add_filter( 'woocommerce_variable_free_price_html', 'hide_free_price_notice' );
add_filter( 'woocommerce_variation_free_price_html', 'hide_free_price_notice' );

function hide_free_price_notice( $price ) {
   return '';
}





add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );

function woo_custom_cart_button_text() {
   return __( 'Add to Wishlist', 'woocommerce' );
}

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_cart_button_text' );

function woocommerce_button_proceed_to_checkout() {
  $checkout_url = WC()->cart->get_checkout_url();
?>
<a href="<?php echo $checkout_url; ?>" class="checkout-button button alt wc-forward"><?php _e( 'Demander des prix', 'woocommerce' ); ?></a>
<?php
}
add_filter( 'woocommerce_order_button_text', create_function( '', 'return "Send Inquiry";' ) ); */


/**
 * Register our widgetized areas for woocommerce
  *
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Art based left sidebar',
		'id'            => 'ab_left',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

  register_sidebar( array(
		'name'          => 'Art based right sidebar',
		'id'            => 'ab_right',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );

/* remove single product tabs */

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['description'] );          // Remove the description tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab
    return $tabs;
}

/* add link to product hero for single pages */

function customText(){
  global $product;
  echo '<ul class="buttons"><li><a href="#chat" class="whatsapp">Live Chat</a></li>';
  echo '<li><a href="#inquire" class="inquire">Inquire</a></li></ul>';
  echo '<ul class="anchor"><li><a href="#details" class="details">Details & Features</a></li>';
  echo '<li><a href="#artwork" class="artist">About the artwork</a></li>';
  echo '<li><a href="#artist" class="artist">About the artist</a></li></ul>';
}
add_action( 'woocommerce_single_product_summary','customText',25);

// do same for ACF fields

add_action( 'woocommerce_single_product_summary', 'view_acf_field_for_single_product', 5 );

function view_acf_field_for_single_product(){
 if (function_exists('the_field')){
   the_field('hero-single-content');
  }
}
