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
			<?php the_sub_field('hero-audio-embed'); ?>

			<?php echo do_shortcode(get_field('hero-audio-embed')); ?>
 		<img src="/wp-content/themes/project-theme/assets/build/img/ab/audio.png" />
		</div>

	<div class="hero-artwork">
			<lottie-player id="FirstLottie" src="<?php the_field('hero-single'); ?>"></lottie-player>
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

		</div><!-- end of hero summary -->
	</div><!-- end of hero wrap -->
</div><!-- end of hero -->

	<div id="images">
			<?php if( have_rows('artwork_images') ): ?>
		    <?php while( have_rows('artwork_images') ): the_row(); ?>
			<img src="<?php the_sub_field('images'); ?>" width="100%" height="auto" class="artwork zoooom" alt="<?php echo esc_attr($image['alt']); ?>"  />
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

	<div id="artwork">
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

					<div id="artist" class="left-author">
						<div class="author-wrap">
								<div class="author-img wow zoomIn" data-wow-duration="1.2s" style="visibility: visible;  animation-name: zoomIn;">
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
								<div class="author-img wow zoomIn" data-wow-duration="1.2s" style="visibility: visible;  animation-name: zoomIn;">
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

<!-- scroll event for hero summary fixed sidebar -->

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
    $(document).on("scroll", onScroll);

    //smoothscroll
    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");

        $('a').each(function () {
            $(this).removeClass('active');
        })
        $(this).addClass('active');

        var target = this.hash,
            menu = target;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top+2
        }, 500, 'swing', function () {
            window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
});

function onScroll(event){
    var scrollPos = $(document).scrollTop();
    $('#menu-center a').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('#menu-center ul li a').removeClass("active");
            currLink.addClass("active");
        }
        else{
            currLink.removeClass("active");
        }
    });
}

// artwork audio
// $(window).scroll(function() {
//     if ($(this).scrollTop() < (2950)) {
//         $(".hero-audio").fadeIn('.1s');
//     } else{
//         $(".hero-audio").fadeOut('0');
// 			    };
// });

// artwork summary
$(window).scroll(function() {
    if ($(this).scrollTop() < (3450)) {
				$(".hero-summary").fadeIn('.1s');
    } else{
        $(".hero-summary").fadeOut('0');
			    };
});


</script>


<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-interactivity@latest/dist/lottie-interactivity.min.js"></script>
<script>

//  document.addEventListener('DOMContentLoaded',function(){
//    const player = document.getElementById('firstLottie').getLottie();
//    player.goToAndStop(27,true);
//  })

LottieInteractivity.create({
  player: '#FirstLottie',
	mode: 'scroll',
	actions: [
    {
      visibility: [0, 1],
      type: "loop",
      frames: [0, 110]
    }
  ]
});
</script>
