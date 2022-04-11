<?php if( have_rows('content_builder') ): ?>
    <?php while( have_rows('content_builder') ): the_row(); ?>
        <?php if( get_row_layout() == 'anchor' ): ?>
                   <h2 class="l-content__block__title" >
										 <?php the_sub_field('anchor_title'); ?>
									 </h2>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
