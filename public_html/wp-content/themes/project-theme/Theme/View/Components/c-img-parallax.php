<?php if( have_rows('content_builder') ): ?>
<section class="parallax">
  <?php while( have_rows('content_builder') ): the_row(); ?>
        <?php if( get_row_layout() == 'kuula1' ): ?>
          <div class="parallax" style="background-image: url('<?php the_sub_field('kuula_image1'); ?>');"></div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
</section>
