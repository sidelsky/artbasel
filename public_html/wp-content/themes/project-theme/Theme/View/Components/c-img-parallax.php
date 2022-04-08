<?php if( have_rows('content_builder') ): ?>
    <?php while( have_rows('content_builder') ): the_row(); ?>
        <?php if( get_row_layout() == 'kuula1' ): ?>
          <div class="parallax-window parallax-window__footer" data-parallax="scroll" data-image-src="<?php the_sub_field('kuula_image1'); ?>);"></div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
