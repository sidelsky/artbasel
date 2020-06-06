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

<section class="l-content ">
	<div class="u-l-container u-l-container--padding">
	<?php

		foreach($allLayouts as $value) {

				$templateName = NULL;
				
				switch ($value['layoutName']) {
					
					//Get Title break
					case 'title_break':
						$templateName = 'c-title-break';
						$args = [
							'altFontClass' => false,
							'showControls' => false,
							'padding' => false
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
						
					//Get Art Fairs
					case 'art_fairs':
						$templateName = 'c-art-fairs';
						break;
		}

				echo $render->view('Components/' . $templateName, $value, $args);
		}

	?>
	</div>
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