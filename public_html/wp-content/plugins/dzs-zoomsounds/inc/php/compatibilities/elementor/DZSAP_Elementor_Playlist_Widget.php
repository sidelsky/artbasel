<?php


if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}


class DZSAP_Elementor_Playlist_Widget extends \Elementor\Widget_Base {

  public static $slug = 'elementor-zoomsounds-playlist';
  public static $controlsId = 'elementor-zoomsounds-playlist';
  protected function _register_controls() {

    global $dzsap;




    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Content', DZSAP_ID),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );


    $dzsap->db_read_mainitems();
    $arrOptions = array();
    if ($dzsap->mainoptions['playlists_mode'] == 'normal') {

      foreach ($dzsap->mainitems as $mainitem) {
        $arrOptions[$mainitem['value']] = $mainitem['label'];
      }
    } else {
      foreach ($dzsap->mainitems as $mainitem) {
        $arrOptions[$mainitem['settings']['id']] = $mainitem['settings']['id'];
      }
    }


    $controlArgs = [
      'label' => esc_html__('Select a playlist', DZSAP_ID),
    ];
    $controlArgs['type'] = \Elementor\Controls_Manager::SELECT;
    $controlArgs['options'] = $arrOptions;



    $this->add_control(
      'playlist_id',
      $controlArgs
    );;



    $this->end_controls_section();


  }


  public function get_name() {
    return 'zoomsounds_playlist';
  }

  public function get_title() {
    return esc_html__('ZoomSounds Playlist', DZSAP_ID);
  }

  public function get_icon() {
    return 'eicon-headphones';
  }

  public function get_categories() {
    return ['general'];
  }

  protected function render() {
    global $dzsap;

    $settings = $this->get_settings_for_display();


    echo '<div class="dzsap-playlist--elementor-widget">';




    echo $dzsap->classView->shortcode_playlist_main($settings);

    echo '</div>';

  }
}