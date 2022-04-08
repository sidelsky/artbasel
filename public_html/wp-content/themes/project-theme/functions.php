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
  echo '<ul class="buttons"><li><a href="https://wa.me/+447384525201" class="whatsapp">Live Chat</a></li>';
  echo '<li class="inquire">';
  echo do_shortcode('[popup_trigger id="9837"]Email Inquiry[/popup_trigger]');
  echo '</li></ul>';
  echo '<ul class="anchor"><li><a href="#details" class="details">Details & Features</a></li>';
  echo '<li><a href="#artwork" class="artist">About the artwork</a></li>';
  echo '<li><a href="#artist" class="artist">About the artist</a></li></ul>';
  echo '<div class="tools">';
  echo do_shortcode('[yith_wcwl_add_to_wishlist]');
  echo do_shortcode('[popup_trigger id="9817"]<img src="/wp-content/themes/project-theme/assets/build/img/ab/share.svg" width="18" height="16" alt="share" />[/popup_trigger]');
  echo '</div>';

}
add_action( 'woocommerce_single_product_summary','customText',25);

// do same for ACF fields

add_action( 'woocommerce_single_product_summary', 'view_acf_field_for_single_product', 5 );

function view_acf_field_for_single_product(){
 if (function_exists('the_field')){
   the_field('hero-single-content');
  }
}

// Change WC searchform placeholder text

add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );
function woo_custom_product_searchform( $form ) {

 $form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '">
 <div>
 <label class="screen-reader-text" for="s">' . __( 'Search', 'woocommerce' ) . '</label>
 <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search', 'woocommerce' ) . '" />
 <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', 'woocommerce' ) .'" />
 <input type="hidden" name="post_type" value="product" />
 </div>
 </form>';

 return $form;

}



/**
 * shop image wrapper for zoom, and attribute for artist name
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // remove product thumbnail and title from the shop loop
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

    // add the product thumbnail and title back in with custom structure
    add_action( 'woocommerce_before_shop_loop_item_title', 'sls_woocommerce_template_loop_product_thumbnail', 4 );

    function sls_woocommerce_template_loop_product_thumbnail() {
       echo '<div class="thumbnail" title="'.get_the_title().'" href="'. get_the_permalink() . '">'.woocommerce_get_product_thumbnail().'</div>';
    }
}



function add_cat_title_shop_loop(){

	$terms = get_the_terms( get_the_ID(), 'pa' );
	if ( $terms && ! is_wp_error( $terms ) ) {
			if ( ! empty( $terms ) ) { ?>
				<p class="category-title-loop-product"><?php echo $terms[0]->name; ?></p>
		<?php }
	}

}
add_action( 'woocommerce_before_shop_loop_item_title', 'add_cat_title_shop_loop', 50 );

add_action('woocommerce_before_shop_loop_item_title', 'display_custom_product_attributes_on_loop', 5 );
function display_custom_product_attributes_on_loop() {
    global $product;

    $value1 = $product->get_attribute('artists');

    if ( ! empty($value1) ) {

        echo '<div class="artist"><p>';

        $attributes = array(); // Initializing

        if ( ! empty($value1) ) {
            $attributes[] = __("") . ' ' . $value1;
        }

        echo implode( '<br>', $attributes ) . '</p></div>';
    }
}

/* yith ajax filtering */

if( ! function_exists( 'yith_wcan_content_selector' ) ){
 function yith_wcan_content_selector( $selector ){
 $selector = '#product-content';
 return $selector;
   }
 add_filter( 'yith_wcan_content_selector', 'yith_wcan_content_selector' );
}
