<?php  if( have_rows('content') ) : ?>
	<div class="l-site-header__nav" data-id="site-header-nav" id="header">
		<nav class="c-site-nav">
			<ul class="c-site-nav__menu">
				<?php  while ( have_rows('content') ) : the_row(); ?>
					<?php 
						if( get_row_layout() == 'scroll_to_navigation' ):
							$text = get_sub_field('scroll_to_navigation_item');
					?>
							<li class="menu-item" data-id="scroll-menu-item">
								<a href="#<?= $text ?>"><?= $text ?></a>
							</li>

					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
		</nav>			
	</div>
<?php endif; ?>