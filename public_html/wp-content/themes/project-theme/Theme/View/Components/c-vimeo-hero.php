<section class="c-vimeo-hero embed-container">
  <?php

  // Load value.
  $iframe = get_field('vimeo_hero');

  // Use preg_match to find iframe src.
  preg_match('/src="(.+?)"/', $iframe, $matches);
  $src = $matches[1];

  // Add extra parameters to src and replcae HTML.
  $params = array(
      'controls'  => 0,
      'hd'        => 1,
      'autohide'  => 0
  );
  $new_src = add_query_arg($params, $src);
  $iframe = str_replace($src, $new_src, $iframe);

  // Add extra attributes to iframe HTML.
  $attributes = 'frameborder="0"';
  $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

  // Display customized HTML.
  echo $iframe;
  ?>

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
</section>
