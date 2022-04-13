<?php if( have_rows('content_builder') ): ?>
    <?php while( have_rows('content_builder') ): the_row(); ?>
        <?php if( get_row_layout() == 'anchor' ): ?>
					<section class="u-section u-l-vertical-padding--margin-40 anchor-wrap">
						<div class="u-l-container--shallow u-l-horizontal-padding--medium l-content__block__text-content--wide ">
							<div class="l-content__block__text-content l-content__block__body-text">
                   <h2 class="l-content__block__title" >
										 <?php the_sub_field('anchor_title'); ?>
									 </h2>
								 </div>
								 </div>
								 <div class="anchor">
									 <?php if( have_rows('anchor_link') ): ?>
 									 <ul class="link">
									 	<?php while ( have_rows('anchor_link') ) : the_row(); ?>
									    <li>
													<a href="#<?php the_sub_field('url'); ?>">
														<?php the_sub_field('title'); ?>
													</a>
											 </li>
												    <?php endwhile; ?>
												    </ul>
												<?php endif; ?>
											</div>
						</section>
	   		<?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>