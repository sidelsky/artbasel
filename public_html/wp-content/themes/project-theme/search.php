<?php
/**
 * Search result page.
 */

 get_header( 'artbasel' ); ?>

 <div id="product-wrap">
   <div class="filter-left">
     <?php if ( is_active_sidebar( 'ab_left' ) ) : ?>
       <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">

         <?php echo do_shortcode('[yith_wcan_filters slug="default-preset"]'); ?>

         <?php dynamic_sidebar( 'ab_left' ); ?>
       </div><!-- #primary-sidebar -->
     <?php endif; ?>
   </div>

  		<div class="product-main">
 					<h1 class="page-title"> <?php echo $wp_query->found_posts; ?>
						<?php _e( 'Search results found for', 'locale' ); ?>: "<?php the_search_query(); ?>"
					</h1>


   <div class="woocommerce columns-4">
          <ul class="products columns-4">
  				<?php if ( have_posts() ) { ?>

  						<?php while ( have_posts() ) {
  							the_post(); ?>

              <li class="product wow fadeInUp" data-wow-delay="0" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0;  animation-name: fadeInUp;">
                  	<a href="<?php echo esc_url(get_the_permalink()); ?>">
                      <div class="thumbnail">
                        <?php echo get_the_post_thumbnail( $page->ID, 'thumbnail' ); ?>
                      </div>
                      <div class="artist">Artist Attribute</div>
                      <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                      <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
                      <?php echo wc_price( $price ); ?>
                      <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]');?>
                   </a>
              </li>

  						<?php } ?>

        </ul>
    </div>

<!-- no search results -->

				<?php } else {
          echo '<div id="product-wrap">';
          echo '<div class="filter-left"></div>';
           echo '<div class="product-main">';
           echo 'No results found';
           echo '</div>';
           echo '</div>';
          echo '</div>';
         include('product-searchform.php');
        }?>

  	</div>
    <div class="filter-right">
      <?php if ( is_active_sidebar( 'ab_right' ) ) : ?>
        <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
          <?php dynamic_sidebar( 'ab_right' ); ?>
        </div><!-- #primary-sidebar -->
        <a href="/art-basel-home#product-wrap" class="artworks">Back to All artworks</a>
      <?php endif; ?>

    </div>

	</div><!-- end product contain -->


  <?php
	/**
	 * Email submission
	 */
	include("partials/artbasel-email-sub.php"); ?>


<?php
get_footer( 'artbasel' );?>
