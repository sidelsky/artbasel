<?php
/**
 * Template Name: Art Basel HOME
 */

use App\Helper\Render;
use Theme\Model\Layout;
// use Theme\Model\ViewingRoom;
// use Theme\Model\ExhibitionCard;

$render = new Render;
$layout = new Layout;
// $viewingRoom = new ViewingRoom;
// $exhibitionCard = new ExhibitionCard;

$allLayouts = $layout->getLayout();

include("header-artbasel.php");
?>

<!-- hero -->
<div id="hero">
	<?php if( have_rows('hero-home') ): ?>
    <?php while( have_rows('hero-home') ): the_row();

// Get sub field values.
			 $image = get_sub_field('image-hero');
?>
	<div id="hero-wrap" style="background: url('<?php echo esc_url( $image['url'] ); ?>');  background-size: cover;">
			<p><?php the_sub_field('date-hero'); ?></p>
			<h2><?php the_sub_field('title-hero'); ?></h2>
			 <p><a href="<?php the_sub_field('link-hero'); ?>" class="play"></a><?php the_sub_field('cta-hero'); ?></p>
			</div>
</div>
<?php endwhile; ?>
<?php endif; ?>
</div>

<!-- featured -->
<div id="featured">
	<?php if( get_field('featured') ) : ?>
			<h2 class="hideme"><?php the_field('featured'); ?></h2>
 <?php endif; ?>
</div>

<!-- triple col image feature -->

<div id="carousel">
	<div id="carousel-wrap">
	<div class="left">
		<?php if( have_rows('left') ): ?>
	    <?php while( have_rows('left') ): the_row(); ?>
				<h2><?php the_sub_field('title'); ?></h2>
				<p><?php the_sub_field('desc'); ?></p>
				<div class="cta is-desktop">
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
	<div class="middle">
		<!-- show images -->
		<?php if( have_rows('middle') ): $i = 0; ?>
	    <?php while( have_rows('middle') ): the_row(); $i++; ?>
				<div id="block-<?php echo $i; ?>"><img src="<?php the_sub_field('image'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>"  /></div>
				<?php endwhile; ?>
			<?php endif; ?>
				<!-- end show images -->
	</div>
	<div class="right sidebar stickyside" id="sticky-contents">
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
						<div class="product-message"><!-- product liked etc -->
							<?php echo do_shortcode('[shop_messages]'); ?>
						</div>

						<?php echo do_shortcode('[products per_page="8" columns="4" show_catalog_ordering="yes" orderby="rand"]'); ?>
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
				<div class="title"><h2><?php the_sub_field('twin-title'); ?></h2>	</div>
				<div class="desc"><p><?php the_sub_field('twin-desc'); ?></p>	</div>
	</div>
	<?php endwhile; ?>
	<?php endif; ?>
</div>

<div id="video-content">
		<div id="video-wrap">
				<div class="video"><?php the_field('video'); ?></div>
				<div class="video-desc"><h3><?php the_field('video_desc'); ?></h3></div>
				</div>
</div>

<div id="triple-content">

		<div id="triple-wrap">
			<div>
				<div class="img">
					<img src="<?php the_field('image_content1'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</div>
				<div class="caption-ab"><p><?php the_field('image_1_caption'); ?></p></div>
			</div>

			<div>
				<div class="img">
					<img src="<?php the_field('image_content2'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
				</div>
				<div class="caption-ab"><p><?php the_field('image_2_caption'); ?></p></div>
				</div>

				<div>
					<div class="img">
						<img src="<?php the_field('image_content3'); ?>" width="100%" height="auto" class="middle-img" alt="<?php echo esc_attr($image['alt']); ?>" />
					</div>
					<div class="caption-ab"><p><?php the_field('image_3_caption'); ?></p></div>
				</div>
	</div>
</div>



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
