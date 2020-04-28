<?php

/**
 * Template Name: Viewing room template
 */

use App\Helper\Render;
use Theme\Model\LayoutVr;

$render = new Render;
$layout = new LayoutVr;

$allLayouts = $layout->getLayout();

include("header.php"); 

$term_id = get_field('collection');
$args =[
	'post_type' => 'works',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'post_date',
	'order' => 'DEC',
	'tax_query' => [
		[
			'taxonomy' => 'collection',
			'field'    => 'term_id',
			'terms'    => $term_id
		]
	]
];

$loop = new WP_Query($args);
$artwork = [];
while ( $loop->have_posts() ) : $loop->the_post();

	$artwork[] = [
		'key' => get_the_id(),
		'title' => get_the_title(),
		'subPostTitle' => get_field('sub_post_title'),
		'surname' => get_field('surname'),
		'fullName' => get_field('full_name'),
		'image' => get_the_post_thumbnail_url(),
		'date' => get_field('date'),
		'description' => get_field('description'),
		'medium' => get_field('medium'),
		'mediumText' => get_field('medium_free_text'),
		'decade' => get_field('decade'),
		'dimensions' => get_field('dimensions'),
		'price' => get_field('price'),
		'priceRange' => get_field('price_range'),
		'link' => get_the_permalink(),
		'sold' => get_field('sold'),
		'ids' => get_field('code_id'),
		'creditLine' => get_field('credit_line'),
		'hidePurchaseButton' => get_field('hide_purchase_button')
	];

endwhile;
wp_reset_postdata();
?>

<?php
/**
 * Hero paralax image
 */
$hero = get_field('hero');
if($hero) : ?>

	<span class="c-works__href-wrap c-works__href-wrap--back c-works__href-wrap--center">
		<svg class="u-icon c-works__icon c-works__icon--back">
			<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>
		</svg>
		<a href="/" class="c-works__href">Back</a>
	</span>

	<section class="u-section c-paralax-header c-paralax-header--desktop">
		<div class="c-header-background-image" style="background-image: url('<?= $hero['image']['sizes']['large']; ?>')">
		<span class="c-header-background-image__shading" style="background-color: rgba(0,0,0,<?= get_field('image_shading_cover') ?>);"></span>
			<div class="parallax-window__content" data-id="title">
				<h1 class="c-site-headings  c-site-headings--h1 c-site-headings--h1--hero c-text-align-centre "><?= $hero['title'] ?></h1>
				<h2 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?= $hero['subtitle'] ?></h2>
					<?php if( get_field('view_all_works_link') ) : ?>
					<span class="c-works__href-wrap c-works__href-wrap--center">
						<a href="#top" class="c-works__href">View all works</a>
						<svg class="u-icon c-works__icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>
						</svg>
					</span>
					<?php endif; ?>
			</div>
		</div>
	</section>
<?php else : ?>
	<?php _e( 'Oops, please add a Hero image here.' ); ?>
<?php endif; ?>

<?php 
/**
 * Show email field at the Top
 */
if( get_field('show_artist_inquiry_email') == "top") {
	include("partials/ma-email-sub-vr.php"); 
} ?>

<?php 
/**
 * Hero Content carousel
 */
$hero_text_content = get_field('mini_carousel_text'); 
if($hero_text_content) :?>
	<section class="u-section c-hero-carousel">
		<div class="u-column u-column--half-width c-hero-carousel--container c-hero-carousel--container--padding">
			<blockquote class="c-hero-carousel--inner-container">
				<h3 class="c-site-headings--h1 c-site-headings--h1--hero-carousel"><?php echo $hero_text_content ?></h3>
			</blockquote>
		</div>
		<div class="u-column--half-width c-hero-carousel--container c-hero-carousel--container--padding">
			<div class="c-hero-carousel--inner-container">
			
			<div class="pre-loader">
				<div class="lds-dual-ring"></div>
				<div class="pre-loader__text">Loading carousel...</div>
			</div>

				<div class="owl-carousel owl-carousel-home owl-theme">
					<?php
						/**
						 * Get Works content for mini carousel
						 */
						foreach($artwork as $index => $art):
						?>
						<article class="c-works__hero-card">
							<figure class="c-works__hero-figure">
								<a href="<?= $art['link']; ?>">
									<img src="<?= $art['image']; ?>" alt="<?= $art['title']; ?>" class="c-works__hero-image">
								</a>
							</figure> 
							<div class="c-works__hero-credit-line"><?= $art['creditLine']; ?></div>
							
							<?php if( $art['sold'] == 'sold' ) {
								$availabilityMarker = 'c-sale-marker--sold';
								$availabilityTitle = 'Sold';
							} elseif( $art['sold'] == 'hold' ) {
								$availabilityMarker = 'c-sale-marker--hold';
								$availabilityTitle = 'Hold';
							} else {
								$availabilityMarker = 'c-sale-marker--available';
								$availabilityTitle = 'Available';
							} ?>

						<?php if( $art['sold'] == !NULL ) : ?>	
							<div class="c-works__availability c-works__availability__hero">
								<span class="c-sale-marker <?= $availabilityMarker ?>"></span><span><?= $availabilityTitle ?></span>
							</div>
						<?php endif; ?>
							
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php 
/**
 * Hero Content Fifty Fifty and Kuula VR
 */
$fiftyFifty = get_field('fifty_fifty');
$kuulaVr = $fiftyFifty['fifty_fifty_kuula'];
$kuulaImage = $fiftyFifty['kuula_image']['sizes']['large'];

if( $kuulaImage && $kuulaVr ) : ?>
	<section class="u-section">
		<div class="c-kuula">
			<button class="c-kuula__button" id="touchButton" onclick="touchFunction()">
				<svg class="c-kuula__touch-icon">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-touch" viewBox="0 0 32 32"></use>
				</svg>
			</button>
			<div class="c-kuula__image" style="background-image: url('<?= $kuulaImage ?>')" id="coverVR"></div>
			<?= $kuulaVr ?>
		</div>
</section>
<?php endif; ?>

<?php 
/** 
 * Image /Video content
 * */
if( $fiftyFifty['fifty_fifty_image']['sizes']['large'] || $fiftyFifty['fifty_fifty_video'] ) : ?>
	<section class="l-content">

		<?php
		/**
		 * Text content
		 */
		if( $fiftyFifty['fifty_fifty_title'] && $fiftyFifty['fifty_fifty_content'] ) :
		?>
			<article class="l-content__block l-content__block--dark-background">
				<div class="canvas l-content__block--center l-content__block--text-content">
					<div>
						<h2 class="l-content__block--title"><?= $fiftyFifty['fifty_fifty_title'] ?></h2>
						<div class="l-content__block--body-text"><?= $fiftyFifty['fifty_fifty_content'] ?></div>	
					</div>
				</div>
			</article>
		<?php endif; ?>

		<?php if( !$fiftyFifty['fifty_fifty_video']) : ?>
			<article class="l-content__block" style="background-image: url('<?= $fiftyFifty['fifty_fifty_image']['sizes']['large'] ?>')"></article>
		<?php endif; ?>
		<?php if( $fiftyFifty['fifty_fifty_video']) : ?>
			<article class="l-content__block l-content__block--dark-background">
				<div class="canvas l-content__block--center">
					<button class="c-video-player__button" onclick="playFunction()" id="playButton">
						<svg class="c-video-player__play-icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-play" viewBox="0 0 32 32"></use>
						</svg>
					</button>
					<div class="c-video-player__cover-image" id="cover" style="background-image: url('<?= $fiftyFifty['fifty_fifty_image']['sizes']['large'] ?>')"></div>	
					<div class="u-video-aspect-ratio u-video-aspect-ratio--full-width">
						<?= $fiftyFifty['fifty_fifty_video'] ?>
					</div>
				</div>
			</article>
		<?php endif; ?>

	</section>
<?php endif; ?>

<?php
/**
 * Flexible Content Bulider
 */
//if( !empty($renderContent) ) :
?>
	<section class="l-content" id="top">
		<?php
		foreach($allLayouts as $value) {

				$templateName;
				
				switch ($value['layoutName']) {
					
					// Get Text content
					case 'text_content':
						$templateName = 'c-text-content';
					break;
					
					// Get Image content
					case 'image_content':
						$templateName = 'c-image-content';
					break;

					// Get Image content
					case 'works_content':
						$templateName = 'c-works-content';
					break;
				
			}
				$renderContent = $render->view('Components/' . $templateName, $value);
				echo $renderContent;
		}

	?>
	</section>
<?php //endif; ?>

<?php 
/**
 * Get a list of Works if no layout builder active
 */
if( empty($renderContent) ) : ?>
	<section class="u-section" id="top">
		<div class="u-l-container--center">
			<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
				<div class="c-works">
					<div class="c-works__list">
					<?php
						/**
						 * Get Works content for list of works
						 */
						foreach($artwork as $index => $artworks):
						?>
						<article class="c-works__card"">
							<figure class="c-works__figure">
								<a href="<?= $artworks['link']; ?>">
									<img src="<?= $artworks['image']; ?>" alt="<?= $artworks['title']; ?>" class="c-works__image">
								</a>
							</figure> 
							<a href="<?= $artworks['link']; ?>">
								<h2 class="c-works__title"><?= $artworks['title']; ?></h2>
							</a>
							<div class="c-works__name"><?= $artworks['fullName']; ?></div>
							<div class="c-works__date"><?= $artworks['date']; ?></div>
							<div class="c-works__medium"><?= $artworks['mediumText']; ?></div>
							
							<?php if($artworks['sold'] === 'available') : ?>
								<div class="c-works__price"><span><?= $artworks['price']; ?></span></div>
							<?php endif; ?>

							<?php if( $artworks['sold'] == 'sold' ) {
								$availabilityMarker = 'c-sale-marker--sold';
								$availabilityTitle = 'Sold';
							} elseif( $artworks['sold'] == 'hold' ) {
								$availabilityMarker = 'c-sale-marker--hold';
								$availabilityTitle = 'Hold';
							} else {
								$availabilityMarker = 'c-sale-marker--available';
								$availabilityTitle = 'Available';
							} ?>

							<?php if( $artworks['sold'] == !NULL ) : ?>		
								<div class="c-works__availability">		
									<span class="c-sale-marker <?= $availabilityMarker ?>"></span><span><?= $availabilityTitle ?></span>
								</div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php 
/**
 * Show email field at the Bottom
 */
if( get_field('show_artist_inquiry_email') == "bottom") {
	include("partials/ma-email-sub-vr.php"); 
} ?>

<?php 
/**
 * Footer paralax image
 */
$footerParallaxImage = get_field('footer_parallax_image');
if($footerParallaxImage) : ?>
	<div class="parallax-window parallax-window__footer" data-natural-height="1400" data-parallax="scroll" data-image-src="<?= $footerParallaxImage['sizes']['large']; ?>"></div>
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
 * Footer content
 */
$content = get_post()->post_content;
if( !empty($content) ):
?>
	<section class="u-section">
		<div class="u-l-container--center">
			<div class="u-l-container u-l-container--shallow u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
				<div class="s-content c-works__footer c-works__footer__hr">
				<?php 
					if ( have_posts() ) : 
						while ( have_posts() ) : the_post(); 
							the_content();
						endwhile; 
					endif; 
					?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if( $fiftyFifty['fifty_fifty_video'] ) : ?>
	<script src="https://player.vimeo.com/api/player.js"></script>
	<script>
		/** Video */
		var iframe = document.querySelector('iframe');
		var player = new Vimeo.Player(iframe);
		var playButton = document.getElementById('playButton');
		var cover = document.getElementById('cover');

		function playFunction() {
			player.play();
		}

		player.on('play', function() {
			cover.style.display = "none";
			playButton.style.display = "none";
		});

		player.on('pause', function() {
			cover.style.display = "block";
			playButton.style.display = "block";
		});
	</script>
<?php endif; ?>

<?php if( $kuulaImage && $kuulaVr ) : ?>
	<script>
		/** VR */
		var touchButton = document.getElementById('touchButton');
		var touchCover = document.getElementById('coverVR');

		function touchFunction() {
			console.log('click');
			touchButton.style.display = "none";
			touchCover.style.display = "none";
		}
	</script>
<?php endif; ?>


<?php include("footer.php"); ?>