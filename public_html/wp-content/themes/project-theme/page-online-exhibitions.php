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

			$templateName = NULL;
			
			switch ($value['layoutName']) {
				
				//Get Title break
				case 'title_break':
					$templateName = 'c-title-break';
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

			echo $render->view('Components/' . $templateName, $value);
	}

?>
</section>
	
<?php
	$args = array(
		'post_type' => 'online-exhibitions',
		'posts_per_page' => 2,
		'orderby' => 'post_date',
		'order' => 'DEC'
	);
	$loop = new WP_Query( $args );

	// Args are passed here --->
	$onlineExhibitionsCardData = [
		'isCarousel' => false
	]

	?>
	<section class="u-l-horizontal-padding--small">
		<div class="c-online-exhibitions">
			<?php 
				while ( $loop->have_posts() ) : $loop->the_post();
				$group = get_field('hero');
				$thumbnail = $group['image']['sizes']['medium'];
				$alt = $group['alt'];
				$fieldTitle = $group['title'];
				$fieldSubTitle = $group['subtitle'];
				$postTitle = get_the_title();
				$url = get_the_permalink();
				// <--- Args received here
				include("partials/online-exhibitions-card.php");
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
		'orderby' => 'post_date',
		'order' => 'DEC'
	);
	$loop = new WP_Query( $args );

	// Args are passed here --->
	$onlineExhibitionsCardData = [
		'isCarousel' => true
	]

	?>
	<section class="u-l-horizontal-padding--small">
		<div class="c-online-exhibitions">
			<div class="owl-carousel owl-exhibitions-carousel" data-id="exhibitions-carousel">
				<?php 	
						while ( $loop->have_posts() ) : $loop->the_post();
							$group = get_field('hero');
							$thumbnail = $group['image']['sizes']['medium'];
							$alt = $group['alt'];
							$fieldTitle = $group['title'];
							$fieldSubTitle = $group['subtitle'];
							$postTitle = get_the_title();
							$url = get_the_permalink();
							// <--- Args received here
							include("partials/online-exhibitions-card.php");
						?>
				<?php	endwhile; ?>
			</div>
		</div>
	</section>

<?php
//End if is front()
	endif;
?>

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
include("partials/footer-navigation.php"); ?>
	 
<?php
/**
 * Footer
 */
include("footer.php"); ?>