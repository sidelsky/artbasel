<?php


if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}


class DZSAP_Elementor_Widget extends \Elementor\Widget_Base {

  protected function _register_controls() {

    global $dzsap;


    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Content', DZSAP_ID),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );
    $this->generateControlsForCategory('main');
    $this->end_controls_section();

    $this->start_controls_section(
      'counters',
      [
        'label' => esc_html__('Counters', DZSAP_ID),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );
    $this->generateControlsForCategory('counters');
    $this->end_controls_section();

    $this->start_controls_section(
      'misc',
      [
        'label' => esc_html__('Miscellanous', DZSAP_ID),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );
    $this->generateControlsForCategory('misc');
    $this->end_controls_section();

  }

  private function generateControlsForCategory($seekedCategory) {
    global $dzsap;

    $optionsItemMeta = $dzsap->options_item_meta;


    $sourceKey = null;
    foreach ($optionsItemMeta as $key => $configOption) {
      if (isset($configOption['name']) && $configOption['name'] == DZSAP_META_OPTION_PREFIX . 'item_source') {
        $sourceKey = $key;
      }
    }
    if ($sourceKey) {

      array_splice($optionsItemMeta, $sourceKey + 1, 0, array(array
      (
        'name' => DZSAP_META_OPTION_PREFIX . 'item_replace_source',
        'type' => 'text',
        'category' => 'main',
        'title' => esc_html__('Custom Source Uri', DZSAP_ID),
        'sidenote' => esc_html__('if you place anything here, it will replace the source field', DZSAP_ID),
      )));
    }

    foreach ($optionsItemMeta as $key => $configOption) {

      if (isset($configOption['category']) && $configOption['category'] === $seekedCategory) {

        if (!dzs_is_option_for_this($configOption, 'elementor')) {
          continue;
        }

        $controlName = $configOption['name'];


        $placeholder = '';
        $default = '';

        if (isset($configOption['default'])) {
          $default = $configOption['default'];
        }
        if (isset($configOption['default'])) {
          $placeholder = $configOption['default'];
        }
        $controlArgs = [
          'label' => $configOption['title'],
          'placeholder' => $placeholder,
          'default' => $default,
        ];

        if ($configOption['type'] === 'text') {
          $controlArgs['type'] = \Elementor\Controls_Manager::TEXT;
        }
        if ($configOption['type'] === 'textarea') {
          $controlArgs['type'] = \Elementor\Controls_Manager::TEXTAREA;
          if (isset($configOption['extra_type']) && $configOption['extra_type'] === 'WYSIWYG') {
            $controlArgs['type'] = \Elementor\Controls_Manager::WYSIWYG;
          }
        }
        if ($configOption['type'] === 'attach') {
          $controlArgs['type'] = \Elementor\Controls_Manager::MEDIA;

          if (isset($configOption['upload_type']) && $configOption['upload_type'] == 'audio') {
            $controlArgs['media_type'] = 'audio';
          }
          unset($controlArgs['default']);
        }
        if ($configOption['type'] === 'select') {
          $controlArgs['type'] = \Elementor\Controls_Manager::SELECT;


          if (!isset($configOption['options'])) {
            if (isset($configOption['choices'])) {
              $configOption['options'] = $configOption['choices'];
            }
          }

          if (isset($configOption['options'])) {

            if (isset($configOption['extra_type']) && $configOption['extra_type'] === 'switcher') {
              $controlArgs['type'] = \Elementor\Controls_Manager::SWITCHER;
              $controlArgs['label_on'] = esc_html__('Enable', DZSAP_ID);
              $controlArgs['return_value'] = 'on';
            } else {
              $controlArgs['options'] = $this->mapChoicesToFlatArray($configOption['options']);
            }

          }
        }

        $controlArgs['label_block'] = true;

        $this->add_control(
          $controlName,
          $controlArgs
        );
      }
    }
  }

  private function mapChoicesToFlatArray($choices) {
    $flatArray = array();
    foreach ($choices as $label => $choice) {
      $flatArray[$choice['value']] = $choice['label'];
    }
    return $flatArray;
  }

  public function get_name() {
    return 'zoomsounds_player';
  }

  public function get_title() {
    return esc_html__('ZoomSounds Player', DZSAP_ID);
  }

  public function get_icon() {
    return 'eicon-headphones';
  }

  public function get_categories() {
    return ['general'];
  }

  private function mapForDzsapKeys($settings) {
    $newSettings = array();

    foreach ($settings as $key => $setting) {
      $newKey = str_replace('dzsap_meta_', '', $key);
      $newSettings[$newKey] = $setting;
    }

    return $newSettings;
  }

  protected function render() {
    global $dzsap;

    $settings = $this->get_settings_for_display();


    $playerSettings = $this->mapForDzsapKeys($settings);
    if (isset($playerSettings['item_source']['url'])) {

      $playerSettings['linktomediafile'] = $playerSettings['item_source']['id'];
      $playerSettings['wpPlayerPostId'] = $playerSettings['item_source']['id'];
      $playerSettings['item_source'] = $playerSettings['item_source']['url'];
    }


    echo '<div class="dzsap--elementor-widget">';


    echo $dzsap->classView->shortcode_player($playerSettings);

    echo '</div>';

  }
}