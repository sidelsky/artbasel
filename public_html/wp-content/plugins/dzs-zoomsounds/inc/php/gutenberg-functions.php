<?php


function dzsap_gutenberg_init() {

  add_action('init', 'dzsap_gutenberg_add_support_block_on_init', 125);
  add_action('admin_footer', 'dzsap_gutenberg_add_support', 500);
  add_action('admin_footer', 'dzsap_gutenberg_add_support', 500);
  add_action('enqueue_block_editor_assets', 'dzsap_gutenberg_admin_enqueue_block_editor_assets', 100);
}

function dzsap_gutenberg_add_support_block_on_init() {
  // -- in init


  // -- add block support on init
  global $dzsap;

  // -- default atrributes gallery
  $atts_gallery = array(
    'dzsap_select_id' => array(
      'type' => 'string',
      'default' => 'default',
    ),
    'examples_con_opened' => array(
      'type' => 'string',
      'default' => '',
    ),
  );


  if (function_exists('register_block_type')) {

    $atts_player = array();



    if(is_array($dzsap->options_item_meta_sanitized)){
      foreach ($dzsap->options_item_meta_sanitized as $opt) {
        $aux = array();

        $aux['type'] = 'string';
        if (isset($opt['type'])) {
          $aux['type'] = $opt['type'];
        }
        if ($aux['type'] == 'select') {
          $aux['type'] = 'string';
        }

        $aux['default'] = '';
        if (isset($opt['default'])) {

          $aux['default'] = $opt['default'];
        }

        // -- sanitizing
        if ($aux['type'] == 'text' || $aux['type'] == 'textarea' || $aux['type'] == 'attach') {

          $atts_player[$opt['name']]['type'] = 'string';
        }


        if ($aux['type'] == 'string') {
          $atts_player[$opt['name']] = $aux;
        }


      }

    }






    // -- register gutenberg
    register_block_type('dzsap/gutenberg-player', array(
      'attributes' => $atts_player,
      'render_callback' => 'dzsap_gutenberg_player_render',
    ));
    register_block_type('dzsap/gutenberg-playlist', array(
      'attributes' => $atts_gallery,
      'render_callback' => 'dzsap_gutenberg_playlist_render',
    ));
  }

}


function dzsap_gutenberg_admin_enqueue_block_editor_assets() {


  // -- enqueue for gutenberg


  if (is_admin()) {
    wp_enqueue_script('dzsap-gutenberg-admin', DZSAP_BASE_URL . 'admin/gutenberg-admin.js');
    DZSZoomSoundsHelper::enqueueMainScrips();
  }
}

function dzsap_gutenberg_player_render($attributes) {
  // -- player render

  $fout = '';

  // -- add block support on init
  global $dzsap;

  if (is_admin()) {
  }




  $attributes['call_from'] = 'dzsap_gutenberg_player_render';
  $fout .= '<div class="gutenberg-dzsap-player-con">' . $dzsap->classView->shortcode_player($attributes);
  $fout .= '</div>';

  return $fout;
}


function dzsap_gutenberg_add_support() {
  // -- this is loaded in admin_footer




  global $post;
  global $dzsap;
  global $current_screen;


//     -- we need to remove gutenberg support if this is avada or wpbakery



  $isWillLoadScript = false;


  // -- disable if it's not gutenberg

  if (dzs_assertIfPageCanHaveGutenbergBlocks()) {
    $isWillLoadScript = true;
  }

  if ($post && $post->post_content && strpos($post->post_content, 'vc_row') !== false) {
    $isWillLoadScript = false;
  }


  if ($isWillLoadScript) {
    wp_enqueue_script('wp-blocks');
    wp_enqueue_script('wp-element');
    wp_enqueue_script('dzsap-gutenberg-player');
    wp_enqueue_script('dzsap-gutenberg-playlist');
  }

}

function dzsap_gutenberg_register_scripts() {
  global $dzsap;

  // -- on init
  if (is_admin() && function_exists('register_block_type')) {











    wp_register_script(
      'dzsap-gutenberg-playlist',
      DZSAP_BASE_URL . ('dist/block_playlist.js'),
      array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor')
    );

    // -- we store this for loading in the footer once all dependencies are loaded
    wp_register_script(
      'dzsap-gutenberg-player',
      DZSAP_BASE_URL . ('dist/block_player.js'),
      array(
        'wp-blocks',
        'wp-element',
        'wp-components',
        'wp-editor',
      )
    );
  }




}

function dzsap_gutenberg_playlist_render($attributes) {
  // -- gallery render
  global $dzsap;

  $fout = '';

  $attributes['id'] = $attributes['dzsap_select_id'];



  if (is_admin()) {
    $attributes['overwrite_only_its'] = array(
      array(
        'source' => 'fake',
        'thumb' => 'https://i.imgur.com/kW6ucoW.jpg',
        'title' => esc_html__('Placeholder', 'dzsap') . ' 1',
        'type' => 'audio',
        'playfrom' => '0',
      ),
      array(
        'source' => 'fake',
        'thumbnail' => 'https://i.imgur.com/kW6ucoW.jpg',
        'thumb' => 'https://i.imgur.com/kW6ucoW.jpg',
        'title' => esc_html__('Placeholder', 'dzsap') . ' 2',
        'type' => 'audio',
        'playfrom' => '0',
      ),
      array(
        'source' => 'fake',
        'thumb' => 'https://i.imgur.com/kW6ucoW.jpg',
        'title' => esc_html__('Placeholder', 'dzsap') . ' 3',
        'type' => 'audio',
        'playfrom' => '0',
      ),
    );


  }



  $fout .= '<div class="gutenberg-dzsap-con">' . $dzsap->classView->shortcode_playlist_main($attributes);


  $fout .= '</div>';
  return $fout;
}

