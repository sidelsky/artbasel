<?php

/**
 * Template Name: Parallax alpha template
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
 * Parallax
 */

	$parallax_hero_image = get_field('parallax_hero_image');
	$parallax_hero_title = get_field('parallax_hero_title');
	$parallax_introduction = get_field('parallax_introduction');
	
?>

	<section class="c-parallax-hero">
		<div data-rellax data-rellax-container>
			<img class="" src="<?php echo esc_url($parallax_hero_image['url']); ?>" alt="<?php echo esc_attr($parallax_hero_image['alt']); ?>" />
		</div>
		<div class="header-content-wrap">
			<div>
				<h1 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?= $parallax_hero_title ?></h1>
			</div>
		</div>
	</section>


<!-- <section class="c-parallax-hero">
	<div class="c-parallax-hero__container">

		<div>
			<h1 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?= $parallax_hero_title ?></h1>
		</div>

		<div class="c-parallax-hero__content-wrap">
			<div>
				<img class="" src="<?php echo esc_url($parallax_hero_image['url']); ?>" alt="<?php echo esc_attr($parallax_hero_image['alt']); ?>" />
			</div>
		</div>

	</div>
</section>
  -->

<section class="l-content">
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
		'id' => 12,
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