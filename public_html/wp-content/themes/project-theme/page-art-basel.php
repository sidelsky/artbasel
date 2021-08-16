<?php
/**
 * Template Name: Art Basel HOME
 */

include("header-artbasel.php");
?>

<!-- <container id="fullpage"> -->
<!-- hero -->
<section id="hero" class="hero">
	<?php if( have_rows('hero-home') ): ?>
    <?php while( have_rows('hero-home') ): the_row();
?>

<div class="hero-inner">
	<div class="hero-wrap">
		<iframe src="https://player.vimeo.com/video/<?php the_sub_field('video-hero'); ?>?dnt=1&app_id=122963&controls=0&hd=1&fs=1&rel=0&modestbranding=1&autoplay=1&muted=1&loop=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen frameborder="0"></iframe>
	</div>

	<div class="hero-text">
			<p class="wow fadeIn" style="visibility: visible; animation-name: fadeIn;"><?php the_sub_field('date-hero'); ?></p>
			<h2 class="wow fadeIn" data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeIn;"><?php the_sub_field('title-hero'); ?></h2>
			 <p class="wow fadeIn" data-wow-delay="1.2s"data-wow-duration="1s"  style="visibility: visible; animation-delay: 1.2s; animation-name: fadeIn;">
  <?php echo do_shortcode('[popup_trigger id="9828"][/popup_trigger]');  ?><?php the_sub_field('cta-hero'); ?></p>
</div>
</div>

<?php endwhile; ?>
<?php endif; ?>
</section>

<!-- featured -->
<section id="featured">
	<?php if( get_field('featured') ) : ?>
		<div class="featured-inner">
			<h2 class="wow fadeIn" data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeIn;"><?php the_field('featured'); ?></h2>
		</div>
	 <?php endif; ?>
	</section>

<!-- triple col image feature -->

<section id="carousel" class="fp-auto-height">
	<div id="carousel-wrap">
	<div class="left">
		<?php if( have_rows('left') ): ?>
	    <?php while( have_rows('left') ): the_row(); ?>
				<h2 class="wow fadeIn slow"  data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeInt;"><?php the_sub_field('title'); ?></h2>
				<div class="wow fadeIn slow" data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.25s;  animation-name: fadeIn;"><p><?php the_sub_field('desc'); ?></p></div>
				<div class="cta is-desktop wow fadeIn slow" data-wow-delay=".3s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.4s;  animation-name: fadeIn;"><a href="<?php the_sub_field('link'); ?>">Explore</a></div>

				<div class="cta is-mobile"><a href="<?php the_sub_field('link'); ?>">Explore</a></div>
<?php endwhile; ?>
<?php endif; ?>
</div>
	<div class="middle wow fadeIn"  data-wow-delay=".1s" data-wow-duration="1s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeIn;">
		<!-- show images -->
		<?php if( have_rows('middle') ): $i = 0; ?>
	    <?php while( have_rows('middle') ): the_row(); $i++; ?>
				<div id="block-<?php echo $i; ?>">
					<div class="slide">
					<lottie-player id="Lottie-<?php echo $i; ?>" src="<?php the_sub_field('lottie'); ?>" style="width: 95%; "></lottie-player>
				</div>
				</div>
				<?php endwhile; ?>
			<?php endif; ?>
				<!-- end show images -->
	</div>
	<div class="right sidebar stickyside  wow fadeIn"  data-wow-delay="1s" data-wow-duration="1.5s" style="visibility: visible; animation-delay: 1s;  animation-name: fadeIn;" id="sticky-contents">
	<!-- <ul> show images again but as thumbnails
		<//?php if( have_rows('right') ): $i = 0; ?>
	    <//?php while( have_rows('right') ): the_row(); $i++; ?>
			<li class="<?php echo $i; ?>"><a href="#block-<?php echo $i; ?>" class="smoothScroll active">
				<img src="<?php the_sub_field('image'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</a>
					</li>
		<//?php endwhile; ?>
		<//?php endif; ?>
</ul> END show images again but as thumbnails -->
	</div>
</div><!-- end carousel wrap-->
</section><!-- end carousel -->

<!-- </container>  END full page wrapper for sticky scroll https://codepen.io/Nidor/pen/gZJPWd -->
<div id="product-content">
		<div id="product-wrap">
					<div class="filter-left">
						<h3 class"leftcol">All artworks</h3>
						<?php if ( is_active_sidebar( 'ab_left' ) ) : ?>
							<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">

								<?php echo do_shortcode('[yith_wcan_filters slug="default-preset"]'); ?>

								<?php dynamic_sidebar( 'ab_left' ); ?>
							</div><!-- #primary-sidebar -->
						<?php endif; ?>
					</div>
					<div class="product-main">
						<!-- <div class="product-message">
							<// ?php echo do_shortcode('[shop_messages]'); ?>
						</div> -->

						<?php echo do_shortcode('[products per_page="20" columns="4" show_catalog_ordering="yes" orderby="rand"  pagination="true"]'); ?>

						<!-- <// ?php echo do_shortcode('[ajax_load_more id="ajax" post_type="product" posts_per_page="20" taxonomy="product_cat" taxonomy_terms="artworks" taxonomy_operator="IN" scroll_container="#product-wrap" button_label="Show more" button_loading_label="Loading artworks" button_done_label="All artworks shown"]'); ?> -->

					</div>
					<div class="filter-right">
						<?php if ( is_active_sidebar( 'ab_right' ) ) : ?>
							<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
								<?php dynamic_sidebar( 'ab_right' ); ?>
							</div><!-- #primary-sidebar -->
						<?php endif; ?>
					</div>
				</div><!-- end product-wrap -->
</div><!-- end product-contain -->



<div id="twin-content">
		<?php if( have_rows('twin_content') ): ?>
	    <?php while( have_rows('twin_content') ): the_row();
	?>
		<div id="twin-wrap">
				<div class="title slow wow fadeIn"  data-wow-delay=".0" data-wow-duration="2s" style="visibility: visible; animation-delay: 0;  animation-name: fadeIn;"><h2><?php the_sub_field('twin-title'); ?></h2>	</div>
				<div class="desc slow wow fadeInRight"  data-wow-delay=".4s" data-wow-duration="2s" style="visibility: visible; animation-delay: .4s;  animation-name: fadeInRight;"><p><?php the_sub_field('twin-desc'); ?></p>	</div>
	</div>
	<?php endwhile; ?>
	<?php endif; ?>
</div>

<?php if( get_field('video') ): ?>
	<div id="video-content">
			<div id="video-wrap">
					<div class="video slow wow fadeIn" data-wow-duration="2s" style="visibility: visible; animation-name: fadeIn;"><?php the_field('video'); ?></div>
					<div class="video-desc"><h3><?php the_field('video_desc'); ?></h3></div>
					</div>
	</div>
<?php endif; ?>

<?php if( get_field('image_content1') ): ?>

<div id="triple-content">

		<div id="triple-wrap">
			<div class="wow fadeIn" data-wow-delay="0" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0;  animation-name: fadeIn;">
				<div class="img">
					<img src="<?php the_field('image_content1'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</div>
				<div class="caption-ab"><p><?php the_field('image_1_caption'); ?></p></div>
			</div>

			<div  class="wow fadeIn" data-wow-delay=".2s" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0.2s;  animation-name: fadeIn;">
				<div class="img">
					<img src="<?php the_field('image_content2'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</div>
				<div class="caption-ab"><p><?php the_field('image_2_caption'); ?></p></div>
				</div>

			<div  class="wow fadeIn" data-wow-delay=".4s" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0.4s;  animation-name: fadeIn;">
					<div class="img">
						<img src="<?php the_field('image_content3'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
					</div>
					<div class="caption-ab"><p><?php the_field('image_3_caption'); ?></p></div>
				</div>
	</div>
</div>

<?php endif; ?>

<!-- <div class="slow wow fadeIn"  data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 1s;  animation-name: fadeIn;"> -->
<?php
/**
 * Email submission
 */
include("partials/artbasel-email-sub.php"); ?>


<?php
/**
 * Footer
 */
include("footer-artbasel.php"); ?>

<!-- for page scroll animation -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.4/jquery.fullPage.min.js"></script> -->

 <!-- <script>
 $(document).ready(function() {
		 // Activate fullpage.js -
		 // https://github.com/alvarotrigo/fullPage.js#usage
		 $('#fullpage').fullpage({
			 	scrollBar: true,
			 	navigation: false,
				loopBottom: false,
			 	sectionSelector: 'section',
				fitToSection: false,
			});
});
 </script> -->

 <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
 <script src="https://unpkg.com/@lottiefiles/lottie-interactivity@latest/dist/lottie-interactivity.min.js"></script>

 <script>

 LottieInteractivity.create({
   player: "#Lottie-1",
   mode:"scroll",
   actions: [
       {
         visibility: [0, 1.0],
         type: "seek",
         frames: [0, 130]
       }
     ]
 });

 LottieInteractivity.create({
   player: "#Lottie-2",
   mode:"scroll",
   actions: [
       {
         visibility: [0, 1.0],
         type: "seek",
         frames: [0, 130]
       }
     ]
 });

 LottieInteractivity.create({
	 player: "#Lottie-3",
	 mode:"scroll",
	 actions: [
			 {
				 visibility: [0, 1.0],
				 type: "seek",
				 frames: [0, 130]
			 }
		 ]
 });

 LottieInteractivity.create({
	 player: "#Lottie-4",
	 mode:"scroll",
	 actions: [
			 {
				 visibility: [0, 1.0],
				 type: "seek",
				 frames: [0, 130]
			 }
		 ]
 });

 LottieInteractivity.create({
	 player: "#Lottie-5",
	 mode:"scroll",
	 actions: [
			 {
				 visibility: [0, 1.0],
				 type: "seek",
				 frames: [0, 130]
			 }
		 ]
 });

 LottieInteractivity.create({
	 player: "#Lottie-6",
	 mode:"scroll",
	 actions: [
			 {
				 visibility: [0, 1.0],
				 type: "seek",
				 frames: [0, 130]
			 }
		 ]
 });

 </script>
