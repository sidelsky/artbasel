<?php

/**
 * Template Name: Online Exhibitions
 */

use App\Helper\Render;
use Theme\Model\Layout;
use Theme\Model\ViewingRoom;

$render = new Render;
$layout = new Layout;
$viewingRoom = new ViewingRoom;

$allLayouts = $layout->getLayout();

include("header.php"); 
include("login.php");
?>

<?php
/**
 * Viewing room
 */
    $template = 'c-viewing-room-carousel';
    $data = $viewingRoom->getData();
    echo $render->view('Components/' . $template, $data);
?>

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
	 
	<section class="u-l-horizontal-padding--small">
		<div class="online_exhibitions">
			<?php
				$rows = get_field('online_exhibitions');

				foreach($rows as $index => $row) {
					if($index < 2) {
						echo '<div class="online_exhibitions__block" style="background-image: url('. $row['online_exhibitions_image']['url'] .')">';
							echo '<div class="canvas">';
								echo '<div class="online_exhibitions__content">';
									echo '<h3 class="online_exhibitions__artist">'. $row['online_exhibitions_artist'] .'</h3>';
									echo '<h4 class="online_exhibitions__title">'. $row['online_exhibitions_title'] .'</h4>';
										echo '<span class="c-works__href-wrap online_exhibitions__href">';
											echo '<a href="'. $row['online_exhibitions_link'] .'" class="c-works__href">Explore now</a>';
											echo '<svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>';
										echo '</span>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
				}
			?>
			</div>
			<div class="online_exhibitions">
				<div class="owl-carousel owl-exhibitions-carousel">
					<?php		
						foreach($rows as $index => $row) {
							if($index > 1) {
								echo '<div class="online_exhibitions__block--small" style="background-image: url('. $row['online_exhibitions_image']['url'] .')">';
									echo '<div class="canvas">';
									echo '<div class="online_exhibitions__content">';
										echo '<h3 class="online_exhibitions__artist">'. $row['online_exhibitions_artist'] .'</h3>';
										echo '<h4 class="online_exhibitions__title">'. $row['online_exhibitions_title'] .'</h4>';
											echo '<span class="c-works__href-wrap online_exhibitions__href">';
												echo '<a href="'. $row['online_exhibitions_link'] .'" class="c-works__href">Explore now</a>';
												echo '<svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>';
											echo '</span>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						}
					?> 
				</div>

			</div>
		</div>
	</section>

	<section class="u-l-container--full-width u-l-horizontal-padding--large u-l-vertical-padding--small online_exhibitions__btn-title-wrap">
		<div class="l-content__title-break online_exhibitions__btn-title">Explore</div>
			<div id="MyNavs" class="online_exhibitions__btn-container">
				<div id="prev-slide" class="online_exhibitions__btn-prev">
						<svg class="online_exhibitions__btn-icon">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-chevron" viewBox="0 0 32 32"></use>
					</svg>
				</div>  
				<div id="next-slide" class="online_exhibitions__btn-next">
					<svg class="online_exhibitions__btn-icon">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-chevron" viewBox="0 0 32 32"></use>
					</svg>
				</div>
			</div>
	</section>


	<?php include("partials/ma-email-sub.php"); ?>
	 
<?php include("footer.php"); ?>
