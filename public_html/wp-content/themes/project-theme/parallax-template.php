<?php

/**
 * Template Name: Parallax template
 * Template Post Type: online-exhibitions
 */

use App\Helper\Render;
use Theme\Model\Layout;
use Theme\Model\ViewingRoom;
use Theme\Model\InquireForm;

$render = new Render;
$layout = new Layout;
$viewingRoom = new ViewingRoom;
$inquireForm = new InquireForm;

$allLayouts = $layout->getLayout();

include("header.php"); 

/**
 * Parallax
 */
$parallax_hero_image = get_field('parallax_hero_image');
$parallax_hero_title = get_field('parallax_hero_title');
$parallax_introduction = get_field('parallax_introduction');

$speed = 1;

?>

	<section class="c-parallax-hero rellax-wrapper">
		<div class="c-parallax-hero__content-wrap">
			
			<?php
			/**
			 * Title
			 */
			?>
			<div class="u-section u-l-vertical-padding--margin-40">
				<div class="u-l-container--shallow">
					<h1 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center rellax" data-rellax-speed="2" data-rellax-percentage="0.5"><?= $parallax_hero_title ?></h1>
				</div>
			</div>

			<?php
			/**
			 * Subtitle
			 */
			?>
			<div class="u-section u-l-vertical-padding--margin-40">
				<div class="u-l-container--shallow">
					<p class="c-parallax-hero__h2 rellax" data-rellax-speed="4" data-rellax-percentage="0.5"><?= $parallax_introduction ?></p>
				</div>
			</div>

			<?php 
			/**
			 * Thumbnails
			 */
			if( have_rows('parallax_thumbnails')): ?>
				<div class="u-section u-l-vertical-padding--margin-40">
					<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
						<ul class="c-parallax-hero__thumbnails">
							<?php 
							while( have_rows('parallax_thumbnails')): the_row(); 
							$thumbnail = get_sub_field('parallax_thumbnail');
							$speed++;
							?>
							<li class="c-parallax-hero__thumbnail rellax" data-rellax-speed="<?= $speed ?>" data-rellax-percentage=".5" >
								<figure>
									<span>
										<img src="<?= $thumbnail['url'] ?>" alt="<?= $thumbnail['url'] ?>">
									</span>
									<figcaption><?= $thumbnail['caption'] ?></figcaption>
								</figure>
							</li>
							<?php endwhile; ?>
						</ul>
					</div>
				</div>
			<?php endif; ?>

			<?php 
			/**
			 * Video
			 */
			if( have_rows('parallax_video') ): ?>
				<?php while( have_rows('parallax_video') ): the_row(); 

					// Get sub field values.
					$parallax_video_vimeo = get_sub_field('parallax_video_vimeo');
					$parallax_video_image_cover = get_sub_field('parallax_video_image_cover');
					?>

					<section class="u-section u-l-vertical-padding--margin-40">
						<div class="u-l-container">

							<div class="c-video-player--centered rellax" id="video" data-rellax-speed="5" data-rellax-percentage="0.20" data-id="video">

								<button class="c-video-player__button" data-id='playBtn'>
									<svg class="c-video-player__play-icon">
										<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
									</svg>
								</button>

								<figure class="c-video-player__cover-image" role="img" aria-label="<?= esc_attr( $parallax_video_image_cover['alt'] ); ?>" style="background-image: url('<?= $parallax_video_image_cover['url'] ?>')" data-id='cover'></figure>	
								
								<div data-id="vimeo-content" class="u-video-aspect-ratio">
									<?= $parallax_video_vimeo ?>
								</div>

							</div>

						</div>
					</section>

				<?php endwhile; ?>
			<?php endif; ?>

		<!-- END -->
		</div>
		<img class="c-parallax-hero__hero-image rellax" data-rellax-speed="-15" src="<?php echo esc_url($parallax_hero_image['url']); ?>" alt="<?php echo esc_attr($parallax_hero_image['alt']); ?>" />
	</section>

	<?php
	/*
	* Magnifing glass carousel
	*/ 

	// Check value exists.
	if( have_rows('parallax_layout_builder') ):

		// Loop through rows.
		while ( have_rows('parallax_layout_builder') ) : the_row();

		?>
		<?php

			// Case: Magnify carousel
			if( get_row_layout() == 'magnify_carousel' ) { 
				$rows = get_sub_field('magnify_carousel_item');

				echo '<section class="u-section u-l-vertical-padding--margin-40">';
					echo '<div class="u-l-container ala">';

						echo '<div id="prev-slide" class="c-online-exhibitions__btn-prev"></div>';
						echo '<div id="next-slide" class="c-online-exhibitions__btn-next"></div>';

					if( $rows ) {
						echo '<div class="owl-carousel owl-carousel-magnify owl-theme">';
							foreach( $rows as $row ) {

							$image = $row['magnify_carousel_image']['sizes']['large'];
							$image_magnify = $row['magnify_carousel_image']['sizes']['2048x2048'];
							$caption = $row['magnify_carousel_image']['caption'];

							echo '<figure class="c-magnifying-zoom">';
								echo '<img src="' . $image . '" class="zoom c-magnifying-zoom__image" data-magnify-src="' . $image_magnify . '">';
								echo '<figcaption class="caption">' . $caption . '</figcaption>';
							echo '</figure>';
							}
						echo '</div>';
					}

					echo '</div>';
				echo '</section>';

				?>
			
			<?php } ?>


		<?php	
		// End loop.
		endwhile;

	endif;
	?>


 


<?php
/**
 * Footer
 */
include("footer.php"); ?>



