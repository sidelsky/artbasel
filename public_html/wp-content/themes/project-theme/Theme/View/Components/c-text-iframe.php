  <section class="l-content">
      <article class="l-content__block l-content__block__text-content l-content__block--wide-text">
        <div class="canvas l-content__block--center l-content__block__text-content">
          <h2><?php the_sub_field('text_content_title'); ?></h2>
          <p><?php the_sub_field('text_content_copy'); ?></p>
        </div>
      </article>
      <div class="l-content__block l-content__block--image-content l-content__block--wide-image">
        <div class="canvas l-content__block--center">
          <div class="c-video-player__cover-image">
            <?php the_sub_field('iframe_html'); ?>
        </div>
        </div>
      </div>
  </section><!-- end of row -->
