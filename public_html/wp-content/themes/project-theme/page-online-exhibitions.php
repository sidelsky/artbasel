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
	/**
	 * Exhibitions card
	 */
	function lessThan($index, $value) {
		return $index < $value;
	}
	$template = 'c-exhibition-card';
	$data = $exhibitionCard->getData();
	$args = [
		'operator' => lessThan,
		'index' => 2,
		'isCarousel' => false,
		'smallClass' => false
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
		'title' => $themeData['titleBreak']['explore']['title'],
		'showControls' => $themeData['titleBreak']['explore']['controls']
	];
	echo $render->view('Components/' . $template, $data, $args);
	?>

	<?php
	/**
	 * Exhibitions card
	 */
	function greaterThan($index, $value) {
		return $index > $value;
	}
	$template = 'c-exhibition-card';
	$data = $exhibitionCard->getData();
	$args = [
		'operator' => greaterThan,
		'index' => 2,
		'isCarousel' => true,
		'smallClass' => true
	];
	echo $render->view('Components/' . $template, $data, $args);
	?> 

<?php 
/**
 * End if is front()
 */
endif; ?>

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