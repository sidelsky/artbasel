<?php

use Elementor\Plugin;

class DZSAP_Elementor {
  function __construct($dzsap) {
    $this->dzsap = $dzsap;
    // Add Plugin actions
    add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
    add_action('elementor/controls/controls_registered', [$this, 'init_controls']);

    add_action('elementor/preview/enqueue_styles', function () {
      DZSZoomSoundsHelper::enqueueMainScrips();
      wp_enqueue_script('dzsap-elementor-preview-refresh-dzsap', DZSAP_BASE_URL . 'inc/php/compatibilities/elementor-preview-refresh-dzsap.js');
    });
  }

  function init_widgets() {

    include_once(DZSAP_BASE_PATH . 'inc/php/compatibilities/elementor/DZSAP_Elementor_Widget.php');
    Plugin::instance()->widgets_manager->register_widget_type(new DZSAP_Elementor_Widget());

    include_once(DZSAP_BASE_PATH . 'inc/php/compatibilities/elementor/DZSAP_Elementor_Playlist_Widget.php');
    Plugin::instance()->widgets_manager->register_widget_type(new DZSAP_Elementor_Playlist_Widget());
  }

  function init_controls() {

  }
}