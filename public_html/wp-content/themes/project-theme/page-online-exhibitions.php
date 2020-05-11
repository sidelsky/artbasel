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
					$rows = get_field('online_exhibitions');
					foreach($rows as $index => $row) {
						if($index < 2) {
							echo '<div class="c-online_exhibitions__block" style="background-image: url('. $row['online_exhibitions_image']['url'] .')">';
								echo '<div class="canvas">';
									echo '<div class="c-online_exhibitions__content">';
										echo '<h3 class="c-online_exhibitions__artist">'. $row['online_exhibitions_artist'] .'</h3>';
										echo '<h4 class="c-online_exhibitions__title">'. $row['online_exhibitions_title'] .'</h4>';
											echo '<span class="c-works__href-wrap c-online_exhibitions__href">';
												echo '<a href="'. $row['online_exhibitions_link']['url'] .'" class="c-works__href">Explore now</a>';
												echo '<svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>';
											echo '</span>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						}
					}
				?>
				</div>
				<div class="c-online_exhibitions">
					<div class="owl-carousel owl-exhibitions-carousel" data-id="exhibitions-carousel">
						<?php		
							foreach($rows as $index => $row) {
								if($index > 1) {
									echo '<div class="c-online_exhibitions__block--small" style="background-image: url('. $row['online_exhibitions_image']['url'] .')">';
										echo '<div class="canvas">';
										echo '<div class="c-online_exhibitions__content">';
											echo '<h3 class="c-online_exhibitions__artist">'. $row['online_exhibitions_artist'] .'</h3>';
											echo '<h4 class="c-online_exhibitions__title">'. $row['online_exhibitions_title'] .'</h4>';
												echo '<span class="c-works__href-wrap c-online_exhibitions__href">';
													echo '<a href="'. $row['online_exhibitions_link']['url'] .'" class="c-works__href">Explore now</a>';
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
		<!-- Carousel navigation -->
		<section class="u-l-container--full-width u-l-horizontal-padding--medium u-l-vertical-padding--small c-online_exhibitions__btn-title-wrap">
			<div class="l-content__title-break c-online_exhibitions__btn-title">Explore</div>
				<div id="MyNavs" class="c-online_exhibitions__btn-container">
					<div id="prev-slide" class="c-online_exhibitions__btn-prev">
							<svg class="c-online_exhibitions__btn-icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-chevron" viewBox="0 0 32 32"></use>
						</svg>
					</div>  
					<div id="next-slide" class="c-online_exhibitions__btn-next">
						<svg class="c-online_exhibitions__btn-icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-chevron" viewBox="0 0 32 32"></use>
						</svg>
					</div>
				</div>
		</section>

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
