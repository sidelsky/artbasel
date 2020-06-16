<?php
/**
 * Template Name: Online Exhibitions
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
</section>
	
<?php
	$args = array(
		'post_type' => 'online-exhibitions',
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
				//$postTitle = get_the_title();
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
		'post_type' => 'online-exhibitions',
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
							//$postTitle = get_the_title();
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