<?php
/**
 * Template Name: Art Basel HOME
 */

include("header-artbasel.php");
?>

<!-- hero -->
<div id="hero">
	<?php if( have_rows('hero-home') ): ?>
    <?php while( have_rows('hero-home') ): the_row();
?>
	<div id="hero-wrap">

<iframe src="https://player.vimeo.com/video/<?php the_sub_field('video-hero'); ?>?dnt=1&app_id=122963&controls=0&hd=1&fs=1&rel=0&modestbranding=1&autoplay=1&muted=1&loop=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen frameborder="0"></iframe>

	</div>
	<div id="hero-text">
			<p class="wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;"><?php the_sub_field('date-hero'); ?></p>
			<h2 class="wow fadeInUp" data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeInUp;"><?php the_sub_field('title-hero'); ?></h2>
			 <p class="wow fadeInUp" data-wow-delay="1.2s"data-wow-duration="1s"  style="visibility: visible; animation-delay: 1.2s; animation-name: fadeInUp;"><a href="<?php the_sub_field('link-hero'); ?>" class="play"></a><?php the_sub_field('cta-hero'); ?></p>

			</div>
</div>
<?php endwhile; ?>
<?php endif; ?>
</div>

<!-- featured -->
<div id="featured">
	<?php if( get_field('featured') ) : ?>
		<div class="featured-inner">
			<h2 class="wow fadeInUp" data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeInUp;"><?php the_field('featured'); ?></h2>
		</div>
	 <?php endif; ?>
	</div>

<!-- triple col image feature -->

<div id="carousel">
	<div id="carousel-wrap">
	<div class="left">
		<?php if( have_rows('left') ): ?>
	    <?php while( have_rows('left') ): the_row(); ?>
				<h2 class="wow fadeInLeft slow"  data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeInLeft;"><?php the_sub_field('title'); ?></h2>
				<div class="wow fadeInLeft slow" data-wow-delay=".1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.25s;  animation-name: fadeInLeft;"><p><?php the_sub_field('desc'); ?></p></div>
				<div class="cta is-desktop wow fadeInLeft slow" data-wow-delay=".3s" data-wow-duration="2s" style="visibility: visible; animation-delay: 0.4s;  animation-name: fadeInLeft;">
					<a href="<?php the_sub_field('link'); ?>" >
					  View details
					</a> 
			</div>

				<div class="cta is-mobile">
					<a href="<?php the_sub_field('link'); ?>">
					   Explore
					</a> 
			</div>
<?php endwhile; ?>
<?php endif; ?>
</div>
	<div class="middle wow fadeIn"  data-wow-delay=".1s" data-wow-duration="1s" style="visibility: visible; animation-delay: 0.1s;  animation-name: fadeIn;">
		<!-- show images -->
		<?php if( have_rows('middle') ): $i = 0; ?>
	    <?php while( have_rows('middle') ): the_row(); $i++; ?>
				<div id="block-<?php echo $i; ?>"><img src="<?php the_sub_field('image'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>"  /></div>
				<?php endwhile; ?>
			<?php endif; ?>
				<!-- end show images -->
	</div>
	<div class="right sidebar stickyside  wow fadeInUp"  data-wow-delay="1s" data-wow-duration="1.5s" style="visibility: visible; animation-delay: 1s;  animation-name: fadeInUp;" id="sticky-contents">
	<ul><!-- show images again but as thumbnails -->
		<?php if( have_rows('middle') ): $i = 0; ?>
	    <?php while( have_rows('middle') ): the_row(); $i++; ?>
			<li class="<?php echo $i; ?>"><a href="#block-<?php echo $i; ?>" class="smoothScroll active">
				<img src="<?php the_sub_field('image'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</a>
					</li>
		<?php endwhile; ?>
		<?php endif; ?>
</ul><!-- END show images again but as thumbnails -->
	</div>
</div>
</div>

<div id="product-content">
		<div id="product-wrap" class="slow wow fadeInUp"  data-wow-delay=".05s" data-wow-duration="2s" style="visibility: visible; animation-delay: .05s;  animation-name: fadeInUp;">
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
						<div class="product-message"><!-- product liked etc -->
							<?php echo do_shortcode('[shop_messages]'); ?>
						</div>

						<?php echo do_shortcode('[products per_page="20" columns="4" show_catalog_ordering="yes" orderby="rand"  pagination="true"]'); ?>


					</div>
					<div class="filter-right">
						<?php if ( is_active_sidebar( 'ab_right' ) ) : ?>
							<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
								<?php dynamic_sidebar( 'ab_right' ); ?>
							</div><!-- #primary-sidebar -->
						<?php endif; ?>
					</div>
				</div>
</div>



<div id="twin-content">
		<?php if( have_rows('twin_content') ): ?>
	    <?php while( have_rows('twin_content') ): the_row();
	?>
		<div id="twin-wrap">
				<div class="title slow wow fadeInLeft"  data-wow-delay=".0" data-wow-duration="2s" style="visibility: visible; animation-delay: 0;  animation-name: fadeInLeft;"><h2><?php the_sub_field('twin-title'); ?></h2>	</div>
				<div class="desc slow wow fadeInRight"  data-wow-delay=".4s" data-wow-duration="2s" style="visibility: visible; animation-delay: .4s;  animation-name: fadeInRight;"><p><?php the_sub_field('twin-desc'); ?></p>	</div>
	</div>
	<?php endwhile; ?>
	<?php endif; ?>
</div>

<?php if( get_field('video') ): ?>
	<div id="video-content">
			<div id="video-wrap">
					<div class="video slow wow fadeInUp" data-wow-duration="2s" style="visibility: visible; animation-name: fadeInUp;"><?php the_field('video'); ?></div>
					<div class="video-desc"><h3><?php the_field('video_desc'); ?></h3></div>
					</div>
	</div>
<?php endif; ?>

<?php if( get_field('image_content1') ): ?>

<div id="triple-content">

		<div id="triple-wrap">
			<div class="wow fadeInUp" data-wow-delay="0" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0;  animation-name: fadeInUp;">
				<div class="img">
					<img src="<?php the_field('image_content1'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</div>
				<div class="caption-ab"><p><?php the_field('image_1_caption'); ?></p></div>
			</div>

			<div  class="wow fadeInUp" data-wow-delay=".2s" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0.2s;  animation-name: fadeInUp;">
				<div class="img">
					<img src="<?php the_field('image_content2'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</div>
				<div class="caption-ab"><p><?php the_field('image_2_caption'); ?></p></div>
				</div>

			<div  class="wow fadeInUp" data-wow-delay=".4s" data-wow-duration="1.4s" style="visibility: visible; animation-delay: 0.4s;  animation-name: fadeInUp;">
					<div class="img">
						<img src="<?php the_field('image_content3'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
					</div>
					<div class="caption-ab"><p><?php the_field('image_3_caption'); ?></p></div>
				</div>
	</div>
</div>

<?php endif; ?>

<!-- <div class="slow wow fadeInLeft"  data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-delay: 1s;  animation-name: fadeInUp;"> -->
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

<!-- </div> -->
