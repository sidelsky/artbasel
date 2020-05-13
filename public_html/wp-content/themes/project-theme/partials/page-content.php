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