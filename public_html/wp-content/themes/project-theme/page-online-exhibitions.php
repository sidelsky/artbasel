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
	
			<section class="u-l-horizontal-padding--small">
				<div class="c-online_exhibitions">
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
							'smallClass' => null
						];
						echo $render->view('Components/' . $template, $data, $args);
					?>
				</div>

				<div class="c-online_exhibitions">
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
							'smallClass' => '--small'
						];
						echo $render->view('Components/' . $template, $data, $args);
						?> 
					</div>
				</div>
			</div>
		</section>
		<!-- Carousel navigation -->
		<?php include("partials/carousel-prev-next-navigation.php"); ?>

	<?php endif; ?>

	<?php include("partials/ma-email-sub.php"); ?>

	<?php 
	/**
	 * Footer content
	 */
	$content = get_post()->post_content;
	if( !empty($content) ):
	?>
	<div class="u-l-horizontal-padding--small u-l-vertical-padding u-l-vertical-padding--small background-color--light">
		<div class="s-content c-footer-content">
			<?php 
				if ( have_posts() ) : 
					while ( have_posts() ) : the_post(); 
						the_content();
					endwhile; 
				endif; 
			?>
		</div>
	</div>
	<?php endif; ?>


<section class="u-l-horizontal-padding--small u-l-horizontal-padding--top-small background-color--dark">
	<div class="c-online_exhibitions c-online_exhibitions--border-bottom">
		<?php
			$rows = get_field('footer_navigation');
			foreach($rows as $index => $row) {
				echo '<div class="c-online_exhibitions__block--navigation" style="background-image: url('. $row['footer_navigation_image']['url'] .')">';
					echo '<div class="canvas">';
						echo '<div class="c-online_exhibitions__content">';
							echo '<h3 class="c-online_exhibitions__title">'. $row['footer_navigation_title'] .'</h3>';

								echo '<span class="c-works__href-wrap c-online_exhibitions__href">';
									if($row['footer_navigation_link'] ) {
										echo '<a href="'. $row['footer_navigation_link']['url'] .'" class="c-works__href">'. $row['footer_navigation_link_title'] .'</a>';
										echo '<svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>';
									} else {
										echo '<span class="c-works__href">'. $row['footer_navigation_link_title'] .'</span>';
									}
								echo '</span>';
								
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</section>
	 
<?php include("footer.php"); ?>
