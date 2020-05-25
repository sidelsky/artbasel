<?php
/**
 * Template Name: Private Sales Sub
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

				//Wider text content
				case 'wider_text-content':
					$templateName = 'c-wider-text-content';
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
 * Email submission
 */
include("partials/ma-email-sub.php"); ?>

 
<?php
/**
 * Footer navigation
 */
include("partials/footer-navigation.php"); ?>


<?php 
	//Fetch users and set our boolean to true per default
	$users = get_field('associated_users');
	$show_content = true;
	if ( !is_user_logged_in() && $users) {
		//If the visitor isn't logged in and we have users they should not be able to see the content
		$show_content = false;
	}elseif( is_user_logged_in() && $users ){
		//Okay so they're logged in and we have users we need to check against
		$current_user = wp_get_current_user();
		//assume they should not see it (just in case)
		$show_content = false;
		foreach( $users as $user ){
			//Loop through each user array from the ACF field
			if( $user['ID'] == $current_user->ID ){
				//Alright they are in the clear! Lets set our boolean to true and break early!
				$show_content = true;
				break;
			}
			
		}
	}
 ?>

<?php if( $show_content ){ ?>

<!-- All content goes in here -->

<?php } ?>

	 
<?php
/**
 * Footer
 */
include("footer.php"); ?>