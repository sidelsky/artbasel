<?php if( have_rows('content_builder') ): ?>
    <?php while( have_rows('content_builder') ): the_row(); ?>
        <?php if( get_row_layout() == 'kuula1' ): ?>
          <div class="parallax" style="background-image: url('<?php the_sub_field('kuula_image1'); ?>');"></div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<style>
.parallax {

  /* Set a specific height */
  min-height: 750px;

  /* Create the parallax scrolling effect */
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  margin-bottom: 70px;
}
</style>
