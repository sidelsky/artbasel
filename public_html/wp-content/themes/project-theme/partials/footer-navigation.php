<?php

   if( isset($onlineExhibitionsCardData) && is_array($onlineExhibitionsCardData) ){

   $args = $onlineExhibitionsCardData;

   } else {
      $args = [];
   }

   $defaults = [
      'isCarousel' => null,
      'isFooterNavigation' => false,
      'altFont' => false
   ];

   $args = wp_parse_args( $args, $defaults );

   // This variable to item
   $isCarousel = $args['isCarousel'];
   $isFooterNavigation = $args['isFooterNavigation'];
	$altFont = $args['altFont'];
	
?>

<section class="<?= $isFooterNavigation ? 'u-l-horizontal-padding--small' : 'c-online-exhibitions--padding-bottom' ?> u-l-horizontal-padding--top-small background-color--dark c-online-exhibitions--border-bottom">
	<div class="c-online-exhibitions">
		<?php
		
		$pageRows = get_field('footer_navigation');
		$optionRows = get_field('footer_navigation', 'option');

		if( $pageRows ) {
			$rows = $pageRows;
		} else {
			$rows = $optionRows;
		}
		
		foreach($rows as $index => $row) {
			$artwork_title = $row['footer_navigation_artwork_title'];
			$title = $row['footer_navigation_title'];
			$thumbnail = $row['footer_navigation_image']['url'];
			$alt = $row['footer_navigation_image']['alt'];
			$url = $row['footer_navigation_link']['url'];
			include(dirname(__FILE__) . '/online-exhibitions-card.php');
		}
		?>
	</div>
</section>