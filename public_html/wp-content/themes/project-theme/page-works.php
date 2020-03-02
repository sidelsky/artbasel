<?php
/**
 * Template Name: Works
 */

	use App\Helper\Render;
	$render = new Render;
	include("header.php");
	/* Include login modal */
	include("login.php");

?>

<?php

	$args = array(
		'post_type' => 'works',
		'posts_per_page' => -1,
		'orderby' => 'post_date',
		'order' => 'DEC'
	);

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
			'mediumChinese' => get_field('medium_chinese'),
			'decade' => get_field('decade'),
			'dimensions' => get_field('dimensions'),
			'price' => get_field('price'),
			'priceRange' => get_field('price_range'),
			'link' => get_the_permalink(),
			'sold' => get_field('sold'),
			'ids' => get_field('code_id'),
			'creditLine' => get_field('credit_line'),
		];

	endwhile;
	wp_reset_postdata();

?>


<?php 
/**
 * Hero paralax image
 */
$gallery = get_field('image_gallery', 6);
$images = [];

//print_r($artwork);

if($gallery) : ?>

	<!-- Parallax desktop -->
	<section class="u-section c-paralax-header c-paralax-header--desktop">
		<?php foreach ($gallery as $galleryImage) :  ?>
			<div class="c-header-background-image" style="background-image: url('<?= $galleryImage['sizes']['large']; ?>')">
				<?php
					$front_page_id = '6';
					$currentPost_id = get_the_ID();
					$content = get_post_field('post_content', $front_page_id);
		
					if( is_front_page() || is_page('Works preview') ) { ?>
						 
						<div class="parallax-window__content" id="title">
							<h1 class="c-site-headings  c-site-headings--h1 c-site-headings--h1--hero c-text-align-centre "><?php echo get_the_title( $front_page_id ); ?></h1>
							<h2 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?php echo get_field( 'works_title_chinese', $front_page_id ); ?></h2>
							<?php if($content ) : ?>
								<div class="u-l-container--narrow c-site-headings c-site-headings--h1--small c-site-headings--text-align-center"><?= $content; ?></div>
							<?php endif; ?>

							<span class="c-works__href-wrap c-works__href-wrap--center">
								<a href="#top" class="c-works__href">View all works</a>
								<svg class="u-icon c-works__icon">
									<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>
								</svg>
							</span>
						</div>
						 
					<?php } ?>
			</div>
			<?php endforeach; ?>
	</section>
<?php endif; ?>


<?php 
/**
 * Hero Content carousel
 */
$hero_text_content = get_field('hero_text_content', 6); 
if($hero_text_content) :?>

<section class="u-section c-hero-carousel">
	<div class="u-column u-column--half-width c-hero-carousel--container c-hero-carousel--container--padding">
		<div class="c-hero-carousel--inner-container">
			<h3 class="c-site-headings--h1 c-site-headings--h1--hero-carousel"><?php echo $hero_text_content ?></h3>
		</div>
	</div>
	<div class="u-column--half-width c-hero-carousel--container c-hero-carousel--container--padding">
		<div class="c-hero-carousel--inner-container">
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
						<button id="purchaseBtn_<?= $index ?>" data-id="purchaseBtn" class="c-button c-button--light" <?= $soldMarker = $art['sold'] ? 'disabled' : ''; ?>>Purchase</button>
						<div class="c-works__availability c-works__availability__hero">
							<span class="c-sale-marker <?= $soldMarker = $art['sold'] ? 'c-sale-marker--sold' : 'c-sale-marker--available'; ?>"></span><span><?= $sold = $art['sold'] ? 'Sold' : 'Available'; ?></span>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
/**
 * Hero Content carousel
 */
$fifty_fifty_image= get_field('fifty_fifty_image', 6);
$fifty_fifty_title = get_field('fifty_fifty_title', 6);
$fifty_fifty_text = get_field('fifty_fifty_text', 6);

if($fifty_fifty_image) :?>
	<section class="u-section c-hero-carousel c-hero-carousel--dark-background">
		<div class="u-column u-column--half-width c-hero-carousel--container">
			<div class="c-hero-carousel--image-container" style="background-image: url('<?= $fifty_fifty_image['sizes']['large']; ?>')">

			</div>
		</div>
		<div class="u-column--table u-column--half-width c-hero-carousel--container">
			<div class="c-hero-carousel--inner-container c-hero-carousel--container--padding">
				<h3 class="c-site-headings--h1 c-site-headings--h1--hero-carousel"><?= $fifty_fifty_title ?></h3>
				<?= $fifty_fifty_text ?>
			</div>
		</div>
	</section>
<?php endif; ?>
 
<section class="u-section" id="top">
	<div class="u-l-container--center">
		<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
			<div class="c-works">
				<div class="c-works__list">
				<?php
					/**
					 * Get Works content for list of works
					 */
					foreach($artwork as $i => $artworks):
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
						<?php if(!$artworks['sold']) : ?>
						<div class="c-works__price"><span><?= $artworks['price']; ?></span></div>
						<?php endif; ?>
						<div class="c-works__availability">
							<span class="c-sale-marker <?= $soldMarker = $artworks['sold'] ? 'c-sale-marker--sold' : 'c-sale-marker--available'; ?>"></span><span><?= $sold = $artworks['sold'] ? 'Sold' : 'Available'; ?></span>
						</div>
						<button id="ListPurchaseBtn_<?= $i ?>" data-id="ListPurchaseBtn" class="c-button c-button--light" <?= $soldMarker = $artworks['sold'] ? 'disabled' : ''; ?>>Purchase</button>
					</article>

				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- <div id="app"></div> -->
</section>

<?php 
/**
 * Footer paralax image
 */

$gallery = get_field('footer_gallery', 6);
$images = [];

if($gallery) : ?>

	<!-- Parallax desktop -->
	<section class="u-section c-paralax-header c-paralax-header--desktop">
		<div>
			<?php foreach ($gallery as $galleryImage) :  ?>
				<div class="parallax-window parallax-window__footer" data-parallax="scroll" data-image-src="<?php echo $galleryImage['sizes']['large']; ?>"></div>
			<?php endforeach; ?>
		</div>
	</section>

<?php endif; ?>

<?php 
/**
 * Footer content
 */
?>
<section class="u-section">
	<div class="u-l-container--center">
		<div class="u-l-container u-l-container--shallow u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
			<div class="s-content c-works__footer c-works__footer__hr">
			<?php
				// query for the about page
				$your_query = new WP_Query( 'pagename=works-list-footer' );
				// "loop" through query (even though it's just one page) 
				while ( $your_query->have_posts() ) : $your_query->the_post();
					the_content();
				endwhile;
				// reset post data (important!)
				wp_reset_postdata();
			?>
			</div>
		</div>
	</div>
</section>

<?php
	/**
	 * Purchase modal
	 */
	foreach($artwork as $index => $artworks): ?>
	<div id="ListPurchaseModal_<?= $index ?>" class="modal">
		<div class="modal-content">
			<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>
			<span class="close">&times;</span>
			<?= do_shortcode('[gravityform id="5" title="false" description="false" ajax="true" field_values="form_msg=I would like to buy ' . $artworks['fullName'] .', ' . $artworks['title'] . '. \nPlease contact me to finalize the purchase details.&id_code=' . $artworks['ids'] . '"]'); ?>
			<small>*By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it's afiliated companies.</small>
		</div>
	</div>
<?php endforeach; ?>

<?php
	/**
	 * Purchase modal
	 */
	foreach($artwork as $index => $art): ?>
	<div id="purchaseModal_<?= $index ?>" class="modal">
		<div class="modal-content">
			<svg class="c-header__icon"><use xlink:href="#shape-hauserwirth-logo"></use></svg>
			<span class="close">&times;</span>
			<?= do_shortcode('[gravityform id="5" title="false" description="false" ajax="true" field_values="form_msg=I would like to buy ' . $art['fullName'] .', ' . $art['title'] . '. \nPlease contact me to finalize the purchase details.&id_code=' . $art['ids'] . '"]'); ?>
			<small>*By submiting your email address, you consent to receive our Newsleter. Your consent is revocable at any time by clicking the unsubscribe link in our Newsleter. The Newsletter is sent in accordance with our Privacy Policy and to advertise products and services of Hauser &amp; Wirth Ltd. and it's afiliated companies.</small>
		</div>
	</div>
<?php endforeach; ?>

<?php include("footer.php"); ?>