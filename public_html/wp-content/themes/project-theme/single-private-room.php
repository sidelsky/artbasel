<?php

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

<?php  if( have_rows('content') ) : ?>
	<div class="l-site-header__nav">
		<nav class="c-site-nav">
			<ul class="c-site-nav__menu">
				<?php  while ( have_rows('content') ) : the_row(); ?>
					<?php 
						if( get_row_layout() == 'scroll_to_navigation' ):
							$text = get_sub_field('scroll_to_navigation_item');
					?>
							<li class="menu-item"><a href="#<?= $text ?>"><?= $text ?></a></li>

					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
		</nav>			
	</div>
<?php endif; ?>

<?php
/**
 * Hero carousel
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

				//Get Full width Image content
				case 'full_width_image':
					$templateName = 'c-full-width-image';
					break;

				//Get Blockquote content
				case 'blockquote':
					$templateName = 'c-blockquote';
					break;

				//Scroll to navigation
				case 'scroll_to_navigation':
					$templateName = 'c-scroll-to-navigation-ids';
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
 * Footer
 */
include("footer.php"); ?>