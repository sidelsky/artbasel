<section class="c-vimeo-hero embed-container">

  <?php if( have_rows('vimeo_hero_panel') ): ?>
    <?php while( have_rows('vimeo_hero_panel') ): the_row();

    // Load value.
    $iframe = get_sub_field('vimeo_url');

    // Use preg_match to find iframe src.
    preg_match('/src="(.+?)"/', $iframe, $matches);
    $src = $matches[1];

    // Add extra parameters to src and replace HTML.
    $params = array(
                      'controls'    => 0,
                      'hd'        => 1,
                      'fs'        => 1,
                      'rel'        => 0,
              'modestbranding' => 1,
      				'autoplay' => 1,
              'muted' => 1,
              'loop'=> 1



    );
    $new_src = add_query_arg($params, $src);
    $iframe = str_replace($src, $new_src, $iframe);

    // Add extra attributes to iframe HTML.
    // $attributes = 'class="show-on-desktop hide"';
    $attributes = 'frameborder="0"';
    $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

    // Display customized HTML.
     echo $iframe;

    // Get sub field values.
    $image = get_sub_field('mobile_image');
    $link = get_sub_field('vimeo_hero_link');
    $link_title = get_sub_field('vimeo_hero_link_title');
  ?>
<!-- <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="show-on-mobile hide" /> -->
  <div id="hero">
          <h1><?php the_sub_field('vimeo_hero_title'); ?></h1>
          <p><?php the_sub_field('vimeo_hero_desc'); ?></p>
          <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $link_title ); ?></a>
      </div>
<?php endwhile; ?>
<?php endif; ?>

<script>
var windowWidth = $(window).width();
var $desktopVisible = $('.show-on-desktop');
var $mobileVisible = $('.show-on-mobile');

if (windowWidth <= 540) {
  $desktopVisible.addClass('hide');
  $mobileVisible.removeClass('hide');
} else {
  $desktopVisible.removeClass('hide');
  $mobileVisible.addClass('hide');
}
</script>

<style>
  #hero {
    position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        color: white;
        text-align: center;
  }

  #hero h1 {
    font-size: 345%;
font-family: NewTransport-Medium;
font-weight: 500;
line-height: 1.33;
letter-spacing: .3px;
}

  #hero p {
margin-top: 15px;
font-size: 15px;
line-height: 1.33;
letter-spacing: .3px;
}

#hero a {
font-family: NewTransport-Medium;
font-size: 14px;
font-weight: 500;
line-height: 1.43;
letter-spacing: .3px;
text-align: center;
color: #fff;
padding: 13.5px 60.5px;
display: inline-block;
border-radius: 3px;
border: 1px solid #fff;
background-color: transparent;
transition: all .15s ease-out;
padding: 13.5px 20px;
margin-top: 25px;
width: 200px;
}
#hero a:hover {
  background: white;
  color: black;
}
    .hide {display:none;}
    iframe { border: 0; }
      .embed-container {
          margin-bottom: 30px;
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

      @media screen and (max-width: 540px) {
        .embed-container {
              margin-bottom: 300px;
              overflow: inherit;
        }
        #hero {
          background: #222;
          left: inherit;
          transform: inherit;
          padding: 50px 20px;
          top: 87%;
          width: 100%;
        }
        #hero h1 {
            font-size: 245%;
        }
      }

  </style>


</section>
