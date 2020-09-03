<?php

/**
 * Template Name: Parallax Template
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
$top_spacing_phone = get_field('top_spacing_phone');
$top_spacing_tablet = get_field('top_spacing_tablet');
$top_spacing_desktop = get_field('top_spacing_desktop');
$top_spacing_desktop_large = get_field('top_spacing_desktop_large');
$parallax_hero_image_shading = get_field('parallax_hero_image_shading');

$parallax_hero_image_height_phone = get_field('parallax_hero_image_height');
$parallax_hero_image_height_tablet = get_field('parallax_hero_image_height_tablet');
$parallax_hero_image_height_desktop = get_field('parallax_hero_image_height_desktop');

$parallax_second_hero_image_height = get_field('parallax_second_hero_image_height');

$parallaxVideo = get_field('parallax_video');

$speed = 1;

// echo $top_spacing_phone;
// echo $top_spacing_tablet;
// echo $top_spacing_desktop;
// echo $top_spacing_desktop_large;

?>

<style>

		/* Phone */ 
	.spacing-top {
		top: <?= $top_spacing_phone ?>px;
		}
	
	.hero-height {
		height: <?= $parallax_hero_image_height_phone ?>px
	}

@media (min-width:375px) { 
	/* Tablet */ 
	.spacing-top {
			top: <?= $top_spacing_tablet ?>px;
		}

	.hero-height {
		height: <?= $parallax_hero_image_height_tablet ?>px
	}
}
 
@media (min-width:678px) { 
	/* Desktop */ 
	.spacing-top {
			top: <?= $top_spacing_desktop ?>px;
		}

	.hero-height {
		height: <?= $parallax_hero_image_height_desktop ?>px
	}
}
 


}

</style>

	<section class="c-parallax-hero rellax-wrapper" style="background-color: rgba(0,0,0,<?= $parallax_hero_image_shading ?>)">
		<div class="c-parallax-hero__content-wrap spacing-top">
			
			<?php 
			/**
			 * Title, copy & Thumbnails
			 */
			if( have_rows('parallax_thumbnails')): ?>
				<div class="u-section u-l-vertical-padding--margin-40">
					<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">

					<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
						<h1 class="c-site-headings c-site-headings--h1--sub c-parallax-hero__h1">
							<?= $parallax_hero_title ?>
						</h1>
					</div>

					<p class="c-parallax-hero__h2"><?= $parallax_introduction ?></p>

						<?php if( $parallaxVideo['parallax_video_vimeo'] ) { ?>
							<ul class="c-parallax-hero__thumbnails">
								<?php 
								while( have_rows('parallax_thumbnails')): the_row(); 
								$thumbnail = get_sub_field('parallax_thumbnail');
								$speed++;
								?>
								<li class="c-parallax-hero__thumbnail" >
									<figure>
										<img src="<?= $thumbnail['url'] ?>" alt="<?= $thumbnail['url'] ?>">
										<figcaption><?= $thumbnail['caption'] ?></figcaption>
									</figure>
								</li>
								<?php endwhile; ?>
							</ul>
						<?php } ?>

					</div>
				</div>
			<?php endif; ?>
		<!-- END -->
		</div>

		<figure>
			<div class="parallax-window parallax-window__hero hero-height" data-parallax="scroll" natural-height="2048" data-image-src="<?php echo esc_url($parallax_hero_image['url']); ?>"></div>
		</figure>
		
	</section>

 
	<?php if( !$parallaxVideo['parallax_video_vimeo'] ) { ?>
		<section class="u-section u-l-vertical-padding--margin-40" style="padding: 20px 20px 15px">
			<div class="u-l-container --full-width ">
				<ul class="c-parallax-hero__thumbnails">
					<?php 
					while( have_rows('parallax_thumbnails')): the_row(); 
					$thumbnail = get_sub_field('parallax_thumbnail');
					$speed++;
					?>
					<li class="c-parallax-hero__thumbnail" >
						<figure>
							<img src="<?= $thumbnail['url'] ?>" alt="<?= $thumbnail['url'] ?>">
							<figcaption><?= $thumbnail['caption'] ?></figcaption>
						</figure>
					</li>
					<?php endwhile; ?>
				</ul>
			</div>
		</section>
	<?php } ?>


<?php
/**
 * Video
 */

if( $parallaxVideo ): ?>

	<?php if( $parallaxVideo['parallax_video_vimeo'] ) { ?>

		<section class="u-section u-l-vertical-padding--margin-40">
			<div class="u-l-container --full-width ">

				<div class="c-video-player--centered " id="video" data-id="video">

					<button class="c-video-player__button" data-id='playBtn'>
						<svg class="c-video-player__play-icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
						</svg>
					</button>

					<figure class="c-video-player__cover-image" role="img" aria-label="<?= esc_attr( $parallaxVideo['parallax_video_image_cover']['alt'] ); ?>" style="background-image: url('<?= $parallaxVideo['parallax_video_image_cover']['url'] ?>')" data-id='cover'></figure>	
					
					<div data-id="vimeo-content" class="u-video-aspect-ratio">
						<?= $parallaxVideo['parallax_video_vimeo'] ?>
					</div>

				</div>

			</div>
		</section>
	<?php } ?>

<?php endif; ?>


	<?php 
	/**  
	 * Second hero image
	*/
	$parallax_second_hero_image = get_field('parallax_second_hero_image');

	if( $parallax_second_hero_image ) { ?>

		<section class="u-section u-l-vertical-padding--margin-40">
			<div class="u-l-container--full-width ">
				<figure>
				<div class="parallax-window parallax-window__second-hero" <?= $parallax_second_hero_image_height ? 'style="height:' . $parallax_second_hero_image_height . 'px' . '"' : '' ?> data-parallax="scroll" data-image-src="<?php echo esc_url($parallax_second_hero_image['url']); ?>"></div>
			</figure>
			</div>
		</section>

	<?php } ?>


	<section class="l-content">
	<?php
	/*
	* Magnifing glass carousel
	*/ 
	
	// Check value exists.
	if( have_rows('parallax_layout_builder') ):

		// Loop through rows.
		while ( have_rows('parallax_layout_builder') ) : the_row();
		$show_inquire_button = get_sub_field('show_inquire_button');
		$show_magnifying_glass = get_sub_field('show_magnifying_glass');

		 // Case: Paragraph layout.
        if( get_row_layout() == 'magnify_carousel' ):
            $rows = get_sub_field('magnify_carousel_item');
				// Do something...
				
				echo '<section class="u-section u-l-vertical-padding--margin-40">';
					echo '<div class="u-l-container ala">';

					echo '<div id="prev-slide" class="c-online-exhibitions__btn-prev"></div>';
					echo '<div id="next-slide" class="c-online-exhibitions__btn-next"></div>';
					
						if( $rows ) {

							echo '<div class="owl-carousel owl-carousel-magnify owl-theme">';
								foreach( $rows as $row ) {

									$image = $row['magnify_carousel_image']['sizes']['large'];
									$alt = $row['magnify_carousel_image']['alt'];
									$image_magnify = $row['magnify_carousel_image']['sizes']['large'];
									$caption = $row['magnify_carousel_image']['caption'];
									$video = $row['magnify_carousel_video'];

									// Check if magnifying glass is active
									if ( $show_magnifying_glass ) {
										$zoom = 'zoom';
									}


									if ( $video ) {
										
										echo '<div class="c-video-player--centered " id="video" data-id="video">';
											echo '<button class="c-video-player__button" data-id="playBtn"><svg class="c-video-player__play-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use></svg></button>';
											echo '<figure class="c-video-player__cover-image" role="img" aria-label="' . esc_attr( $alt ) . '" style="background-image: url('. $image .')" data-id="cover"></figure>';
											echo '<div data-id="vimeo-content" class="u-video-aspect-ratio">' . $video . '</div>';
										echo '</div>';
														
									} else {
										echo '<figure class="c-magnifying-zoom">';
											echo '<img src="' . $image . '" class="' . $zoom . ' c-magnifying-zoom__image" data-magnify-src="' . $image_magnify . '">';
											echo '<figcaption class="caption">' . $caption . '</figcaption>';
										echo '</figure>';
									}

								}
							echo '</div>';
							
							if( $show_inquire_button ) {
								$message =  'I am interested in learning more about this piece. Please send me further details about this artwork, pricing, and availability.';
								$idCode = get_the_title();
								echo '<button class="cta-button" data-id="inquire-button" data-message-value="' . $message . '" data-id-code="' . $idCode . '">Inquire</button>';
							}

						}

					echo '</div>';
				echo '</section>';

			// Case: Download layout.
			elseif( get_row_layout() == 'blockquote' ): 
				$blockquote = get_sub_field('blockquote');
				// Do something...

				echo '<section class="u-section u-l-vertical-padding--margin-40 c-parallax-hero__blockquote">';
					echo '<div class="u-l-container u-l-horizontal-padding">';
						echo '<article class="c-hero-carousel--inner-container">';
							echo '<h3 class="c-site-headings--h1 c-site-headings--h1--hero-carousel">' . $blockquote . '</h3>';
						echo '</article>';
					echo '</div>';
				echo '</section>';


			// Case: Text content.
			elseif( get_row_layout() == 'image_content' ): 
				$image_content = get_sub_field('image_content');
				//Do something...

					echo '<div class="l-content__block l-content__block--image-content l-content__block--wide-image">';
						echo '<div class="canvas l-content__block--center">';
							echo '<figure role="img" aria-label="' . esc_attr( $image_content['alt'] ) . '" class="c-video-player__cover-image" style="background-image: url(' . $image_content['url'] . ')"></figure>';
						echo '</div>';
					echo '</div>';

				// Case: Text content.
				elseif( get_row_layout() == 'text_content' ): 
					$text_content_title = get_sub_field('text_content_title');
					$text_content_copy = get_sub_field('text_content_copy');
					$text_content_link = get_sub_field('text_content_link')['url'];
					$text_content_target = get_sub_field('text_content_link')['target'];
					$text_content_link_title = get_sub_field('text_content_link_title');
					// Do something...

					echo '<article class="l-content__block l-content__block__text-content l-content__block--wide-text">';
						echo '<div class="canvas l-content__block--center l-content__block__text-content">';
							echo '<div class="l-content__block__min-width-text">';
								echo '<h2 class="l-content__block__title">' . $text_content_title . '</h2>';

								echo '<div class="l-content__block__body-text">'. $text_content_copy .'</div>';

								if( $text_content_link_title || $text_content_link ) {
								echo '<span class="c-works__href-wrap c-works__href-wrap--center l-content__block--link">';
               				echo '<a href="' . $text_content_link . '" target="' . $text_content_target . '" class="c-works__href">' . $text_content_link_title . '</a>';

										echo ' <svg class="u-icon c-works__icon">';
											echo '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-black" viewBox="0 0 32 32"></use>';
										echo '</svg>';
										
									echo '</span>';
									}

							echo '</div>';
						echo '</div>';
					echo '</article>';

        endif;

		// End loop.
		endwhile;

	endif;
	?>

</section>

<?php
		/**
		 * Inquire form
		 */
			$template = 'c-inquire-form';
			$data = $inquireForm->getInquireForm();
			//args can overwrite $data
			$args = [ 
				'id' => 7,
				'title' => false,
				'description' => false,
				'ajax' => true
				];
			echo $render->view('Components/' . $template, $data, $args);
		?>



<?php 
/**
 * Footer paralax image
 */
$footerParallaxImage = get_field('parallax_footer_image');
if($footerParallaxImage) : ?>
	<div class="parallax-window parallax-window__footer" data-parallax="scroll" data-image-src="<?= $footerParallaxImage['sizes']['large']; ?>"></div>
<?php endif; ?>

<?php 
	/**
	 * Artist recommendations
	 */
	$recommendations = get_field('artist_recommendations'); 
	$recommendationsTitle = $recommendations[artist_recommendations_title];
	$recommendationsSubtitle = $recommendations[artist_recommendations_subtitle];
	$recommendationsContent = $recommendations[artist_recommendations_content];
	$recommendationsSpotify = $recommendations[artist_recommendations_spotify];
?>
<?php if( $recommendationsTitle ) : ?>
	<section class="u-section background-color--dark">
			<div class="u-l-container u-l-container--center">
				<div class="u-l-container u-l-horizontal-padding u-l-vertical-padding--bottom">
					<div class="c-recommendations">
						<h3 class="c-recommendations__title"><?= $recommendationsTitle ?></h3>
						<div class="c-recommendations__sub-title"><?= $recommendationsSubtitle ?></div>
						<div class="c-recommendations__content-block">
							<div class="c-recommendations__column"><?= $recommendationsContent ?></div>
							<div class="c-recommendations__column"><?= $recommendationsSpotify ?></div>
						</div>
					</div>
				</div>
			</div>
	</section>
<?php endif; ?>

<?php
/**
 * Footer
 */
include("footer.php"); ?>



