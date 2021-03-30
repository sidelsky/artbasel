<?php
/**
 * Template Name: Gallery Exhibitions
 */

use App\Helper\Render;
use Theme\Model\Layout;
use Theme\Model\ViewingRoom;
use Theme\Model\ExhibitionCard;

$render = new Render;
$layout = new Layout;
$viewingRoom = new ViewingRoom;
$exhibitionCard = new ExhibitionCard;

$allLayouts = $layout->getLayout();

include("header.php");
?>

<?php if( !is_front_page() ) : ?>
	<span class="c-works__href-wrap c-works__href-wrap--back c-works__href-wrap--center">
		<svg class="u-icon c-works__icon c-works__icon--back">
			<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>
		</svg>
		<a href="javascript:history.go(-1);" class="c-works__href">Back</a>
	</span>
<?php endif; ?>


<?php

/**
 * Carousel
 */
    $template = 'c-viewing-room-carousel';
    $data = $viewingRoom->getData();
    echo $render->view('Components/' . $template, $data);
?>

<?php if( !is_front_page() ) : ?>

<section class="l-content">
<?php

	foreach($allLayouts as $value) {

			switch ($value['layoutName']) {

				//Get Title break
				case 'title_break':
					$templateName = 'c-title-break';
					$args = [
						'altFontClass' => false,
						'showControls' => true,
						'padding' => true,
						'fullWidth' => true
					];
					break;

				//Get Text content
				case 'text_content':
					$templateName = 'c-text-content';
					break;

				//Get Image content
				case 'image_content':
					$templateName = 'c-image-content';
					break;
	}

			echo $render->view('Components/' . $templateName, $value, $args);
	}

?>



						<?php if( have_rows('content_builder') ):; ?>
								<?php while( have_rows('content_builder') ): the_row(); ?>

													<?php if( get_row_layout() == 'text_iframe_content' ): ?>
														<!-- TEXT BLOCK  -->
														<section class="l-content">
																<article class="l-content__block l-content__block__text-content l-content__block--wide-text">
																	<div class="canvas l-content__block--center l-content__block__text-content">
																		<h2><?php the_sub_field('text_content_title'); ?></h2>
																		<p><?php the_sub_field('text_content_copy'); ?></p>
																	</div>
																</article>
																<div class="l-content__block l-content__block--image-content l-content__block--wide-image">
																	<div class="canvas l-content__block--center">
																		<div class="c-video-player__cover-image">
																			<?php the_sub_field('iframe_html'); ?>
																	</div>
																	</div>
																</div>
														</section><!-- end of row -->

											<?php endif; ?>
									<?php endwhile; ?>
							<?php endif; ?>


</section>

<?php
	$args = array(
		'post_type' => 'gallery-exhibitions',
		'posts_per_page' => 2,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$loop = new WP_Query( $args );

	// Args are passed here --->
	$onlineExhibitionsCardData = [
		'isCarousel' => false
	]

	?>
	<section>
		<div class="c-online-exhibitions">
			<?php
				while ( $loop->have_posts() ) : $loop->the_post();
				$group = get_field('hero');
				$thumbnail = $group['image']['sizes']['medium'];
				$FeaturedThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->ID ), 'medium');
				$alt = $group['alt'];
				$title = $group['subtitle'];
				$url = get_the_permalink();
				$postTitle = get_the_title();
				// <--- Args received here
				include("partials/online-exhibitions-card.php");
				wp_reset_postdata();
				?>

			<?php	endwhile; ?>
		</div>
	</section>


<?php
	/**
	 * Explore title break with Carousel control
	 */
	$template = 'c-title-break';
	$data = $viewingRoom->getData();
	$args = [
		'altFontClass' => false,
		'title' => $themeData['titleBreak']['explore']['title'],
		'showControls' => $themeData['titleBreak']['explore']['controls']
	];
	echo $render->view('Components/' . $template, $data, $args);
	?>


	<?php
	$args = array(
		'post_type' => 'gallery-exhibitions',
		'posts_per_page' => 999,
		'offset' => 2,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$loop = new WP_Query( $args );

	// Args are passed here --->
	$onlineExhibitionsCardData = [
		'isCarousel' => true,
		'isFooterNavigation' => false,
		'altFont' => false
	]

	?>
	<section class="u-l-horizontal-padding--small" style="padding-right: 0">
		<div class="c-online-exhibitions">
			<div class="owl-carousel owl-exhibitions-carousel" data-id="exhibitions-carousel">
				<?php
						while ( $loop->have_posts() ) : $loop->the_post();
							$group = get_field('hero');
							$thumbnail = $group['image']['sizes']['medium'];
							$FeaturedThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->ID ), 'medium');
							$alt = $group['alt'];
							$title = $group['subtitle'];
							$url = get_the_permalink();
							$postTitle = get_the_title();
							// <--- Args received here
							include("partials/online-exhibitions-card.php");
							wp_reset_postdata();
						?>
				<?php	endwhile; ?>
			</div>
		</div>
	</section>

<?php endif; ?>

<?php
/**
 * Email submission
 */
include("partials/ma-email-sub.php"); ?>

<?php
/**
 * Page content
 */
include("partials/page-content.php"); ?>

<?php
/**
 * Footer navigation
 */
$onlineExhibitionsCardData = [
	'isCarousel' => false,
	'isFooterNavigation' => true,
	'altFont' => false
];
include("partials/footer-navigation.php"); ?>

<?php
/**
 * Footer
 */
include("footer.php"); ?>
