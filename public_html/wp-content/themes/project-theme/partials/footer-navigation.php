<section class="u-l-horizontal-padding--small u-l-horizontal-padding--top-small background-color--dark">
	<div class="c-online-exhibitions c-online-exhibitions--border-bottom">
		<?php
		$rows = get_field('footer_navigation', 'option');
		// Args are passed here --->
		$onlineExhibitionsCardData = [
			'isCarousel' => false,
			'isFooterNavigation' => true,
			'altFont' => false
		];
		foreach($rows as $index => $row) {
			$thumbnail = $row['footer_navigation_image']['url'];
			$alt = $row['footer_navigation_image']['alt'];
			$title = $row['footer_navigation_title'];
			$url = $row['footer_navigation_link']['url'];
			include(dirname(__FILE__) . '/online-exhibitions-card.php');
		}
		?>
	</div>
</section>