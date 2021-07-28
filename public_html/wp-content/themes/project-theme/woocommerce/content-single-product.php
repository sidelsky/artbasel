<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
// do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

<div id="hero-single">
	<div id="hero-single-wrap">
		<div class="hero-audio">
			<?php the_field('hero-audio'); ?>audio player ID here
		</div>

	<div class="hero-artwork">
		<?php the_field('hero-single'); ?>
	</div>

	<div class="hero-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>

	share icons here

		</div><!-- end of hero summary -->
	</div><!-- end of hero wrap -->
</div><!-- end of hero -->

	<div id="images">
			<?php if( have_rows('artwork_images') ): ?>
		    <?php while( have_rows('artwork_images') ): the_row(); ?>
			<img src="<?php the_sub_field('images'); ?>" width="100%" height="auto" class="artwork" alt="<?php echo esc_attr($image['alt']); ?>"  />
		<?php endwhile; ?>
		<?php endif; ?>
	</div>

	<div id="video">
		<div id="video-wrap">
			<?php if( have_rows('artwork_video') ): ?>
		    <?php while( have_rows('artwork_video') ): the_row(); ?>
				<div class="video"><?php the_sub_field('video'); ?></div>
			<?php endwhile; ?>
			<?php endif; ?>
				</div>
	</div>

<div id="details-wrap">
	<div id="details">

		<?php if( get_field('details_heading') ) : ?>
			<h2><?php the_field('details_heading'); ?></h2>
	 <?php endif; ?>

		<?php if( have_rows('details_description') ): ?>
	    <?php while( have_rows('details_description') ): the_row(); ?>
			<div class="title"><?php the_sub_field('details_title'); ?></div>
			<div class="desc"><?php the_sub_field('details_content'); ?></div>
		<?php endwhile; ?>
		<?php endif; ?>

	</div>

	<div id="about">
		<?php if( get_field('about_heading') ) : ?>
			<h2><?php the_field('about_heading'); ?></h2>
	 <?php endif; ?>

			<?php if( get_field('about_artwork_description') ) : ?>
					<div class="title"><?php the_field('about_artwork_description'); ?></div>
				<?php endif; ?>
	</div>
</div>

<?php if( have_rows('content_panels') ): ?>
    <?php while( have_rows('content_panels') ): the_row(); ?>
        <?php if( get_row_layout() == 'left_img' ):
					$image = get_sub_field('left-image');
					?>

					<div id="author" class="left-author">
						<div class="author-wrap">
								<div class="author-img">
										<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
									</div>
									<div class="author-content">
										<h2><?php the_sub_field('left_heading'); ?></h2>
										<p><?php the_sub_field('left_copy'); ?></p>
									</div>
						</div>
					</div>

        <?php elseif( get_row_layout() == 'right_img' ):
            $image = get_sub_field('right-image');
            ?>

						<div class="right-author">
							<div class="author-wrap">
								<div class="author-content">
									<h2><?php the_sub_field('right_heading'); ?></h2>
									<p><?php the_sub_field('right_copy'); ?></p>
								</div>
								<div class="author-img">
									<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
								</div>
							</div>
						</div>

					<?php elseif( get_row_layout() == 'quote' ): ?>

						<div id="quote" style="background-color: <?php the_sub_field('colorpicker'); ?>">
							<div id="quote-wrap">
						<?php the_sub_field('quote_copy'); ?>
						<br /><br />
						<div class="author-name">
							<?php the_sub_field('quote_author'); ?>
							</div>
					</div>
				</div>

					<?php elseif( get_row_layout() == 'small_print' ): ?>

						<div id="small-print">
							<?php the_sub_field('small_print'); ?>
					</div>

        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>



	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 25
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php  ( 'woocommerce_after_single_product' ); ?>
