<?php

/**
 * Template Name: Art Basel template
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
?>

<?php
/**
 * Hero carousel
 */
    $template = 'c-viewing-room-carousel';
    $data = $viewingRoom->getData();
    $args = [
		'altFontClass' => false
		];
    echo $render->view('Components/' . $template, $data, $args);
?>

<section class="l-content u-l-vertical-padding--top-only">
<?php

	foreach($allLayouts as $value) {
			
			switch ($value['layoutName']) {
				
				//Get Title break
				case 'title_break':
					$templateName = 'c-title-break';
					$args = [
						'altFontClass' => false
         		];
					break;

				//Get Text content
				case 'text_content':
               $templateName = 'c-text-content';
               $args = [
						'altFontClass' => false
         		];
					break;

				//Get Image content
				case 'image_content':
					$templateName = 'c-image-content';
					break;

				//Get Full width Image content
				case 'full_width_image':
					$templateName = 'c-full-width-image';
					 $args = [
						'fullWidth' => true
         		];
					break;

				//Get Blockquote content
				case 'blockquote':
					$args = [
						'altFontClass' => false
         		];
					$templateName = 'c-blockquote';
					break;

				//Wider text content
				case 'wider_text_content':
					$templateName = 'c-wider-text-content';
					$args = [
						'altFontClass' => false
         		];
					break;

				//Scroll to navigation
				case 'scroll_to_navigation':
					$templateName = 'c-scroll-to-navigation-ids';
					break;

				//Video centered
				case 'video_centered':
					$templateName = 'c-video-centered';
					break;

				//Video centered
				case 'two_images_module':
					$templateName = 'c-two-images';
					break;


				}

			echo $render->view('Components/' . $templateName, $value, $args);
	}

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
 * Footer navigation
 */
$onlineExhibitionsCardData = [
	'isCarousel' => false,
	'isFooterNavigation' => false,
	'altFont' => false,
];
include("partials/footer-navigation.php"); ?>
	 
<?php
/**
 * Footer
 */
include("footer.php"); ?>