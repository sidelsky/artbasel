<?php
/**
 * Template Name: Private Sales Sub
 */
use App\Helper\Render;
use Theme\Model\Layout;
use Theme\Model\ViewingRoom;

$render = new Render;
$layout = new Layout;
$viewingRoom = new ViewingRoom;

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

<section class="l-content u-l-vertical-padding--top-only">
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
               $args = [
						'altFontClass' => true
         		];
					break;

				//Get Image content
				case 'image_content':
					$templateName = 'c-image-content';
					break;

				//Get Image content
				case 'full_width_image':
					$templateName = 'c-full-width-image';
					break;

				//Get Image content
				case 'blockquote':
					$templateName = 'c-blockquote';
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