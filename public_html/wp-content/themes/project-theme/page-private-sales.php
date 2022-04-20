<?php
/**
 * Template Name: Private Sales
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
    $args = [
		'altFontClass' => true
	];
    echo $render->view('Components/' . $template, $data, $args);
?>

	<?php
	/**
	 * Explore title break with Carousel control
	 */
		$template = 'c-title-break';
		$data = $viewingRoom->getData();
		$args = [
			'altFontClass' => true,
			'title' => $themeData['titleBreak']['newWorks']['title'],
			'showControls' => $themeData['titleBreak']['newWorks']['controls']
			];
		echo $render->view('Components/' . $template, $data, $args);
	?>


<?php
	$args = array(
		'post_type' => 'private-sales',
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
	<section class="u-l-horizontal-padding--small">
		<div class="c-online-exhibitions">
			<?php 
				while ( $loop->have_posts() ) : $loop->the_post();

				$rows = get_field('current_viewing_rooms');
				$thumbnail = $rows[0]['current_viewing_room_image']['sizes']['medium'];
            $alt = $rows[0]['current_viewing_room_image']['alt'];
				$fieldTitle = $rows[0]['current_viewing_room_title'];
				$fieldSubTitle = $rows[0]['current_viewing_room_title'];
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
			'altFontClass' => true,
			'title' => $themeData['titleBreak']['explore']['title'],
			'showControls' => $themeData['titleBreak']['explore']['controls']
			];
		echo $render->view('Components/' . $template, $data, $args);
	?>

<?php
	$args = array(
		'post_type' => 'private-sales',
		'posts_per_page' => 999,
		'offset' => 2, 
		'orderby' => 'menu_order',
		'order' => 'ASC'
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
						$rows = get_field('current_viewing_rooms');
						$thumbnail = $rows[0]['current_viewing_room_image']['sizes']['medium'];
						$alt = $rows[0]['current_viewing_room_image']['alt'];
						$fieldTitle = $rows[0]['current_viewing_room_title'];
						$fieldSubTitle = $rows[0]['current_viewing_room_title'];
						$postTitle = get_the_title();
						$url = get_the_permalink();
						// <--- Args received here
						include("partials/online-exhibitions-card.php");
					?>
				<?php	endwhile; ?>
			</div>
		</div>
	</section>

<section class="l-content">
<?php

	foreach($allLayouts as $value) {

			$templateName = NULL;
			
			switch ($value['layoutName']) {
				
				//Get Title break
				case 'title_break':
					$templateName = 'c-title-break';
					$args = [
						'altFontClass' => true
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
/**
 * Footer navigation
 */
include("partials/footer-navigation.php"); ?>
	 
<?php
/**
 * Footer
 */
include("footer.php"); ?>