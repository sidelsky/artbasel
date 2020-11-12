<section class="c-vimeo-hero embed-container">

  <?php if( have_rows('vimeo_hero_panel') ): ?>
    <?php while( have_rows('vimeo_hero_panel') ): the_row();

 
  // Load value.
  $iframe = get_field('vimeo_url');

  // Use preg_match to find iframe src.
  preg_match('/src="(.+?)"/', $iframe, $matches);
  $src = $matches[1];

  // Add extra parameters to src and replicate HTML.
  $params = array(
    'controls'    => 0,
    'hd'        => 1,
    'fs'        => 1,
    'rel'        => 0,
    'modestbranding' => 1,
    'autoplay' => 1
  );
  $new_src = add_query_arg($params, $src);
  $iframe = str_replace($src, $new_src, $iframe);

  // Add extra attributes to iframe HTML.
  $attributes = 'frameborder="0"';
  $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

  // Display customized HTML.
  echo $iframe;
  // Get sub field values.
  $image = get_sub_field('image');
  $link = get_sub_field('link');

  ?>
  <div id="hero">
      <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
      <div class="content">
          <?php the_sub_field('caption'); ?>
          <a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
      </div>
  </div>
<?php endwhile; ?>
<?php endif; ?>


  <style>
      .embed-container {
          position: relative;
          padding-bottom: 56.25%;
          overflow: hidden;
          max-width: 100%;
          height: auto;
      }

      .embed-container iframe,
      .embed-container object,
      .embed-container embed {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
      }
  </style>


<h1>vimeo_hero_title<h1>
<p>vimeo_hero_desc</p>
<p><a href="vimeo_hero_link">vimeo_hero_link_title</a></p>


</section>
