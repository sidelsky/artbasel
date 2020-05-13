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