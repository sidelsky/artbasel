<?php
/**
 * Template Name: Art fairs
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


<span class="c-works__href-wrap c-works__href-wrap--back c-works__href-wrap--center">
	<svg class="u-icon c-works__icon c-works__icon--back">
		<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>
	</svg>
	<a href="javascript:history.go(-1)" class="c-works__href">Back</a>
</span>


<?php

/**
 * Carousel
 */
    $template = 'c-viewing-room-carousel';
    $data = $viewingRoom->getData();
    echo $render->view('Components/' . $template, $data);
?>


	<?php

		foreach($allLayouts as $value) {
				
				switch ($value['layoutName']) {
					
					//Get Title break
					case 'title_break':
						$templateName = 'c-title-break';
						$args = [
							'altFontClass' => false,
							'showControls' => false,
							'padding' => true,
							'fullWidth' => false
						];
						break;

					//Get Full width Image content
					case 'full_width_image':
						$templateName = 'c-full-width-image';
						$args = [
							'fullWidth' => false
						];
						break;
					
					//Wider text content
					case 'wider_text_content':
						$templateName = 'c-wider-text-content';
						$args = [
							'altFontClass' => false,
							'alignLeft' => false
         			];
						break;
						
					//Get Art Fairs
					case 'art_fairs':
						$templateName = 'c-art-fairs';
						break;
		}

				echo $render->view('Components/' . $templateName, $value, $args);
		}

	?>
	 
<?php
/**
 * Footer
 */
include("footer.php"); ?>