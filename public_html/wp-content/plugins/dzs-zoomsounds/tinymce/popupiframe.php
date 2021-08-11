<?php
// some total cache vars that needs to be like this

function dzsap_shortcode_builder() {
  global $dzsap;


  $sample_data_installed = false;
  if ($dzsap->sample_data && is_array($dzsap->sample_data)) {
    $sample_data_installed = true;
  }


  $ids = '';


  if(isset($dzsap->sample_data['media'])){
  for ($i = 0; $i < count($dzsap->sample_data['media']); $i++) {

    if ($i > 0) {
      $ids .= ',';
    }


    $ids .= $dzsap->sample_data['media'][$i];
  }
  }



  if(isset($dzsap->sample_data['media'][0])){
    ?><div class="dzsap-feed--media-0"><?php echo $dzsap->sample_data['media'][0]; ?></div><?php
  }
  if(isset($ids)){
    ?><div class="dzsap-feed--media-ids"><?php echo $ids; ?></div><?php
  }
  ?>

  <div class="wrap <?php
  if ($sample_data_installed) {
    echo 'sample-data-installed';
  }
  ?>">
    <h6><strong>ZoomSounds <?php echo esc_html__("Playlist", 'dzsap'); ?></strong> <em><?php echo esc_html__("shortcode generator", 'dzsap'); ?></em></h6>

    <div class="dzstoggle " rel="">
      <div class="toggle-title" style=""><?php echo esc_html__('Import sample data', 'dzsap'); ?></div>
      <div class="toggle-content">


        <p><?php echo esc_html__("You can generate an example from the preview", 'dzsap'); ?></p>

        <?php
        if ($sample_data_installed === false) {
          echo '<p><button id="" class="button-secondary insert-sample-tracks">Insert Sample Data</button></p>';
        } else {

          echo '<p><button id="" class="button-secondary remove-sample-tracks">Remove Sample Data</button></p>';
        }
        ?>


        <div class="dzspb_lay_row shortcode-generator-cols">

          <div class="dzspb_layb_one_third">
            <?php
            echo '<img class="fullwidth" src="' . DZSAP_BASE_URL . 'tinymce/img/sg1.png"/>';
            ?>
            <h3><?php echo esc_html__('Player with Wave and Comments'); ?></h3>
            <p>
              <button class="button-primary sg-1"<?php
              if ($sample_data_installed === false) {
                echo 'disabled';
              }
              ?>><?php echo esc_html__('Insert Shortcode'); ?></button>
            </p>
            <p
              class="sidenote sidenote-for-sample-data-not-installed"><?php echo esc_html__('Install sample data first, to generate this example'); ?></p>
          </div>
          <div class="dzspb_layb_one_third">
            <?php
            echo '<img  class="fullwidth" src="' . DZSAP_BASE_URL . 'tinymce/img/sg2.png"/>';
            ?>

            <h3><?php echo esc_html__('Bottom Player with Grid Display'); ?></h3>
            <p>
              <button class="button-primary sg-2"<?php
              if ($sample_data_installed === false) {
                echo 'disabled';
              }
              ?>><?php echo esc_html__('Insert Shortcode'); ?></button>
            </p>
            <p
              class="sidenote sidenote-for-sample-data-not-installed"><?php echo esc_html__('Install sample data first, to generate this example'); ?></p>
          </div>
          <div class="dzspb_layb_one_third">
            <?php
            echo '<img class="fullwidth" src="' . DZSAP_BASE_URL . 'tinymce/img/sg3.png"/>';
            ?>

            <h3><?php echo esc_html__('Audio Player with Custom Skin'); ?></h3>
            <p>
              <button class="button-primary sg-3"<?php
              if ($sample_data_installed === false) {
                echo 'disabled';
              }
              ?>><?php echo esc_html__('Insert Shortcode'); ?></button>
            </p>
            <p
              class="sidenote sidenote-for-sample-data-not-installed"><?php echo esc_html__('Install sample data first, to generate this example'); ?></p>
          </div>
        </div>


      </div>
    </div>
    <div class="clear"></div>

    <div class="flex-hr-nice-container">
    <div>
    </div>
    <div class="niceHr-label">
    <?php echo esc_html__("OR", 'dzsap'); ?>
    </div>
    <div>
    </div>
</div>


    <div class="sc-menu">
      <div class="setting type_any">
        <h3><?php echo esc_html__("Select a Playlist to Insert", 'dzsap'); ?></h3>
        <select class="styleme" name="dzsap_selectid">
          <?php

          $dzsap->db_read_mainitems();


          if ($dzsap->mainoptions['playlists_mode'] == 'normal') {

            foreach ($dzsap->mainitems as $mainitem) {

$term_id = '';

if(isset($mainitem['term_id'])){
  $term_id = $mainitem['term_id'];
}

              echo '<option value="' . $mainitem['value'] . '"';
              if($term_id){
                echo ' data-term_id="' . $term_id . '"';
              }
              echo '>' . $mainitem['label'] . '</option>';
            }
          } else {
            foreach ($dzsap->mainitems as $mainitem) {
              echo '<option>' . ($mainitem['settings']['id']) . '</option>';
            }
          }
          ?>
        </select>
        <div class="sidenote"><?php echo esc_html__('Quick edit the gallery - ', 'dzsap'); ?> <a class="ultibox-item-delegated" id="sg_gallery_edit_link" data-source="" data-type="iframe" href="#"><?php echo esc_html__('here', 'dzsap'); ?></a></div>


    <div class="dzstoggle " rel="">
      <div class="toggle-title" style=""><h6><?php echo esc_html__('Force sizes', 'dzsap'); ?></h6></div>
      <div class="toggle-content">


      <div class="setting type_any">
        <h4><?php echo esc_html__("Force Width", 'dzsap'); ?></h4>
        <input class="textinput" name="width"/>
      </div>


      <div class="setting type_any">
        <h4><?php echo esc_html__("Force Height", 'dzsap'); ?></h4>
        <input class="textinput" name="height"/>
      </div>
</div>
</div>
      <div class="clear"></div>
      <br/>
      

    </div>


    <div class="shortcode-output"></div>

    <div class="bottom-right-buttons">

      <button id=""
              class="button-secondary insert-sample-library"><?php echo esc_html__("One Click Install Example"); ?></button>
      <span style="font-size: 11px; opacity: 0.5;"><?php echo esc_html__("OR", 'dzsvg'); ?></span>
      <button id="insert_tests" class="button-primary insert-tests"><?php echo esc_html__("Insert Gallery"); ?></button>
    </div>


    <div id="import-sample-lib" class="show-in-ultibox">
      <?php

      echo '<h3>' . esc_html__("Import Demo", 'dzsap') . '</h3>';


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/img/sample_gallery_1.jpg',
        'title' => 'Sample Gallery',
        'demo-slug' => 'sample-gallery-1',
      );

      dzsap_generate_example_lib_item($args);


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/img/sample_grid_style_1.jpg',
        'title' => 'Grid Style 1',
        'demo-slug' => 'sample_grid_style_1',
      );

      dzsap_generate_example_lib_item($args);


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/img/sample_soundcloud_gallery_just_thumbs.jpg',
        'title' => 'Soundcloud Thumbnail Grid',
        'demo-slug' => 'sample_soundcloud_gallery_just_thumbs',
      );


      dzsap_generate_example_lib_item($args);


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/img/sample_player_with_buttons.jpg',
        'title' => 'Player with Buttons',
        'demo-slug' => 'sample_player_with_buttons',
      );


      dzsap_generate_example_lib_item($args);


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/sampledata_img/single_player_wrapper.jpg',
        'title' => 'Single player with rectangle',
        'demo-slug' => 'single_player_wrapper',
      );


      dzsap_generate_example_lib_item($args);


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/sampledata_img/single_wave_and_single_jtap.jpg',
        'title' => 'Two players',
        'demo-slug' => 'single_wave_and_single_jtap',
      );


      dzsap_generate_example_lib_item($args);


      $lab = 'small_play_and_pause';


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/sampledata_img/' . $lab . '.jpg',
        'title' => 'Small play and pause controls',
        'demo-slug' => $lab,
      );


      dzsap_generate_example_lib_item($args);


      $lab = 'consecutive_player';


      $args = array(
        'featured_image' => DZSAP_BASE_URL . 'assets/sampledata_img/' . $lab . '.jpg',
        'title' => 'Consecutive player',
        'demo-slug' => $lab,
      );


      dzsap_generate_example_lib_item($args);


      ?>
    </div>


  </div><?php
}


$dzsap_example_lib_index = 0;
function dzsap_generate_example_lib_item($pargs) {
  global $dzsap_example_lib_index, $dzsap;

  $margs = array(
    'featured_image' => '',
    'title' => '',
    'demo-slug' => '',
  );


  $margs = array_merge($margs, $pargs);

  if ($dzsap_example_lib_index % 3 == 0) {
    echo '<div class="dzs-row">';

  }


  ?>
  <div class="dzs-col-md-4">
  <div class="lib-item <?php


  if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {

  } else {

    echo ' dzstooltip-con';

    echo ' disabled';
  }


  ?>" data-demo="<?php echo $margs['demo-slug']; ?>"><?php


    if ($dzsap->mainoptions['dzsap_purchase_code_binded'] == 'on') {

    } else {

      ?>
      <div class=" dzstooltip skin-black arrow-bottom align-left">
        <?php echo esc_html__("You need to activate zoomsounds with purchase code before importing demos");
        ?>
      </div>
      <?php
    }


    ?>
    <i class="fa  fa-lock lock-icon"></i>
    <div class="loading-overlay">
      <i class="fa fa-spin fa-circle-o-notch loading-icon"></i>
    </div>
    <div class="divimage" style="background-image:url(<?php echo $margs['featured_image']; ?>); "></div>
    <h5><?php echo $margs['title'];; ?></h5>

  </div>

  </div><?php


  if ($dzsap_example_lib_index % 3 == 2) {

    echo '</div>';
  }


  $dzsap_example_lib_index++;


}