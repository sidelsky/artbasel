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
	
<section class="u-l-horizontal-padding--small">
	<div class="c-online-exhibitions">
		<?php
			/**
			 * Exhibitions
			 */
			function lessThan($index, $value) {
				return $index < $value;
			}
			$template = 'c-exhibition-card';
			$data = $exhibitionCard->getData();
			$args = [
				'operator' => lessThan,
				'index' => 2,
				'altFontClass' => true,
				'smallClass' => null
			];
			echo $render->view('Components/' . $template, $data, $args);
		?>
	</div>

	<div class="c-online-exhibitions">
		<div class="owl-carousel owl-exhibitions-carousel" data-id="exhibitions-carousel">
			<?php
			/**
			 * Exhibitions
			 */
			function greaterThan($index, $value) {
				return $index > $value;
			}
			$template = 'c-exhibition-card';
			$data = $exhibitionCard->getData();
			$args = [
				'operator' => greaterThan,
				'index' => 2,
				'altFontClass' => true,
				'smallClass' => true
			];
			echo $render->view('Components/' . $template, $data, $args);
			?> 
		</div>
	</div>
</section>

<?php
/**
 * Title break with Carousel control
 */
    $template = 'c-title-break';
    $data = $viewingRoom->getData();
    $args = [
		'altFontClass' => true,
		'title' => $themeData['titleBreak']['title'],
		'showControls' => true
		];
    echo $render->view('Components/' . $template, $data, $args);
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