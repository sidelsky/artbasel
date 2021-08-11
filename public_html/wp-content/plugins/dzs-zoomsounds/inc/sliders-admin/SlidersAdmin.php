<?php


class SlidersAdmin {
  public $targetTaxonomy;
  public $targetPostType;
  function __construct($options = array()){


    $this->targetTaxonomy = $options['target_taxonomy'];
    $this->targetPostType = $options['target_post_type'];
  }

  public function render_sliders(){

    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == $this->targetTaxonomy) {



      global $dzsap;


      $tax = $this->targetTaxonomy;




      wp_enqueue_style('dzs.sliders-admin', DZSAP_BASE_URL . 'inc/sliders-admin/sliders_admin.css');
      wp_enqueue_style('dzs.dzstoggle', DZSAP_BASE_URL . 'libs/dzstoggle/dzstoggle.css');
      wp_enqueue_script('dzs.dzstoggle', DZSAP_BASE_URL . 'libs/dzstoggle/dzstoggle.js');

      DZSZoomSoundsHelper::embedZoomTabsAndAccordions();

      wp_enqueue_script('sliders_admin', DZSAP_BASE_URL . 'admin/sliders_admin.js');
      wp_enqueue_script('dzs.farbtastic', DZSAP_BASE_URL . "libs/farbtastic/farbtastic.js");
      wp_enqueue_style('dzs.farbtastic', DZSAP_BASE_URL . 'libs/farbtastic/farbtastic.css');


      $terms = get_terms($tax, array(
        'hide_empty' => false,
      ));




      $i23 = 0;








      $selectedTermId = null;
      $foundTermObject = null;
      $selected_term_id = '';
      $selected_term_name = '';
      $selected_term_slug = '';
      if (isset($_GET['tag_ID'])) {

        $foundTermObject = get_term($_GET['tag_ID'], $tax);


        if (isset($foundTermObject)) {

          $selected_term_id = $foundTermObject->term_id;
          $selected_term_name = $foundTermObject->name;
          $selected_term_slug = $foundTermObject->slug;
        }


        if (isset($_GET['tag_ID'])) {
          $selectedTermId = $_GET['tag_ID'];





        }
      }


      $term_meta = get_option("taxonomy_$selectedTermId");





      ?>



    <div class="dzsap-sliders-con" data-term_id="<?php echo $selected_term_id; ?>"
         data-term-slug="<?php echo $selected_term_slug; ?>">

      <h3 class="slider-label" style="font-weight: normal">
        <span><?php echo esc_html__("Editing "); ?></span><span
          style="font-weight: bold;"><?php echo $selected_term_name; ?></span> <span class="slider-status empty ">
                <div class="slider-status--inner loading"><i class="fa fa-circle-o-notch fa-spin"  aria-hidden="true"></i> <span
                    class="text-label"><?php echo esc_html__("Saving"); ?></span></div>
            </span>
      </h3>


      <div class="dzsap-slider-items">

      <?php

      if ($selectedTermId) {


        global $dzsap_slidersAdmin_fixHackConfictsForOrder_metaQuery;

        $dzsap_slidersAdmin_fixHackConfictsForOrder_metaQuery = array(
          'relation' => 'OR',
          array(
            'key' => 'dzsap_meta_order_' . $selectedTermId,

            'compare' => 'EXISTS',
          ),
          array(
            'key' => 'dzsap_meta_order_' . $selectedTermId,

            'compare' => 'NOT EXISTS'
          )
        );
        $args = array(
          'post_type' => 'dzsap_items',
          'numberposts' => -1,
          'posts_per_page' => '-1',


          'orderby' => 'meta_value_num',
          'order' => 'ASC',
          'meta_query' => $dzsap_slidersAdmin_fixHackConfictsForOrder_metaQuery,
          'tax_query' => array(
            array(
              'taxonomy' => $tax,
              'field' => 'id',
              'terms' => $selectedTermId
            )
          ),
        );

        $manualItemsQuery = new WP_Query($args);
        $manualItemsQueryPosts = $manualItemsQuery->posts;



        foreach ($manualItemsQueryPosts as $po) {


          echo dzsap_sliders_admin_generate_item($po);


        }

        ?>

        </div>

        <div class="add-btn">
          <i class="fa fa-plus-circle add-btn--icon"></i>
          <div class="add-btn-new button-secondary"><?php echo esc_html__("Create New Item", DZSAP_ID); ?></div>
          <div
            class="add-btn-existing add-btn-existing-media upload-type-audio button-secondary"><?php echo esc_html__("Add From Library", DZSAP_ID); ?></div>
        </div>

        <br>
        <br>


        <div id="tabs-box" class="dzs-tabs  skin-qcre " data-options='{ "design_tabsposition" : "top"
,"design_transition": "fade"
,"design_tabswidth": "default"
,"toggle_breakpoint" : "200"
,"settings_appendWholeContent": "true"
,"toggle_type": "accordion"
}
'>

          <div class="dzs-tab-tobe">
            <div class="tab-menu ">
              <?php
              echo esc_html__("Main Settings", DZSAP_ID);
              ?>
            </div>
            <div class="tab-content tab-content-cat-main">


            </div>
          </div>


          <?php
          foreach ($dzsap->options_slider_categories_lng as $lab => $val) {


            ?>

            <div class="dzs-tab-tobe">
            <div class="tab-menu ">
              <?php
              echo($val);
              ?>
            </div>
            <div class="tab-content tab-content-cat-<?php echo $lab; ?>">


              <table class="form-table custom-form-table sa-category-<?php echo $lab; ?>">
                <tbody>
                <?php
                dzsap_sliders_admin_parse_options($foundTermObject, $lab);
                ?>
                </tbody>

              </table>

            </div>
            </div><?php

          }
          ?>


        </div><!-- end .dzs-tabs -->


        <div class="slidersAdmin--metaArea">
          <div class="feed-con import-folder-con for-feed_mode-import-folder">

            <div class="dzstoggle toggle1" rel="">
              <div class="toggle-title" style=""><?php echo esc_html__('Import folder', DZSAP_ID); ?></div>
              <div class="toggle-content">

                <h4><?php echo esc_html__("Import folder", DZSAP_ID); ?></h4>
                <?php
                $val = '';
                $lab = 'folder_location';
                if (isset($term_meta[$lab])) {
                  $val = $term_meta[$lab];
                }
                ?>


                <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>"
                       name="<?php echo "term_meta[$lab]"; ?>" value="<?php echo $val; ?>"/>
                <div
                  class="sidenote"><?php echo esc_html__("input the location of the folder that is storing the mp3s - for example the location of the zoomsounds plugin folder is ", DZSAP_ID);
                  echo '<strong>' . wp_upload_dir()['basedir'] . '</strong>'; ?></div>
                <div class="button-con align-inside-middle">

                  <button
                    class="button-secondary btn-import-folder"><?php echo esc_html__("Import folder", DZSAP_ID); ?></button>
                  <span class="dzsap-dashicon-preloader dashicons dashicons-update"></span>
                </div>

              </div>
            </div>

            <?php
            if ($dzsap->mainoptions['debug_queries'] === 'on') {
              ?>


              <div class="dzstoggle toggle1" rel="">
                <div class="toggle-title" style=""><?php echo esc_html__('Debug queries', DZSAP_ID); ?></div>
                <div class="toggle-content"><?php
                  if (isset($manualItemsQuery)) {

                    print_rr($manualItemsQuery);
                  } else {
                    print_rr($manualItemsQueryPosts);
                  }
                  ?>>
                </div>
              </div>
              <?php

            }
            ?>
          </div>

          <div class="dzssa--sample-shortcode-area"><h6><?php echo esc_html__('Shortcode sample', DZSAP_ID); ?></h6>
            <pre class="dzssa--sample-shortcode-area--readonly"></pre>
          </div>
        </div>


        <div class="dzsap-sliders">
          <table class="wp-list-table widefat fixed striped tags">
            <thead>
            <tr>


              <th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><a
                  href="<?php echo admin_url('edit-tags.php?taxonomy=' . $this->targetTaxonomy . '&amp;post_type=' . $this->targetPostType . '&amp;orderby=name&amp;order=asc'); ?>"><span>Name</span><span
                    class="sorting-indicator"></span></a></th>


              <th scope="col" id="slug" class="manage-column column-slug sortable desc"><a
                  href="<?php echo admin_url('edit-tags.php?taxonomy=' . $this->targetTaxonomy . '&amp;post_type=' . $this->targetPostType . '&amp;orderby=slug&amp;order=asc'); ?>"><span><?php echo esc_html__("Edit"); ?></span><span
                    class="sorting-indicator"></span></a></th>

              <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><a
                  href="<?php echo admin_url('edit-tags.php?taxonomy=' . $this->targetTaxonomy . '&amp;post_type=' . $this->targetPostType . '&amp;orderby=count&amp;order=asc'); ?>"><span>Count</span><span
                    class="sorting-indicator"></span></a></th>
            </tr>
            </thead>

            <tbody id="the-list" data-wp-lists="list:tag">


            <?php


            foreach ($terms as $tm) {

              ?>


              <tr id="tag-<?php echo $tm->term_id; ?>">

                <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong>
                    <a class="row-title"
                       href="<?php echo site_url(); ?>/wp-admin/term.php?taxonomy=dzsap_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;post_type=dzsap_items&amp;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Ddzsap_sliders%26post_type%3Ddzsap_items"
                       aria-label="“<?php echo $tm->name; ?>” (Edit)"><?php echo $tm->name; ?></a></strong>
                  <br>
                  <div class="hidden" id="inline_<?php echo $tm->term_id; ?>">

                    <div class="name"><?php echo $tm->name; ?></div>
                    <div class="slug"><?php echo $tm->slug; ?></div>
                    <div class="parent">0</div>
                  </div>
                  <div class="row-actions">

                  <span class="edit"><a
                      href="<?php echo site_url(); ?>/wp-admin/term.php?taxonomy=dzsap_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;post_type=dzsap_items&amp;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Ddzsap_sliders%26post_type%3Ddzsap_items"
                      aria-label="Edit “Test 1”">Edit</a> | </span>

                    <span class="delete"><a
                        href="<?php echo admin_url('edit-tags.php?action=delete&amp;taxonomy=dzsap_sliders&amp;tag_ID=' . $tm->term_id . '&amp;_wpnonce=' . wp_create_nonce('delete-tag_' . $tm->term_id) . ''); ?>"
                        class="delete-tag aria-button-if-js" aria-label="Delete “<?php echo $tm->name; ?>”" role="button">Delete</a> | </span><span
                      class="view"><a href="<?php echo site_url(); ?>/audio-sliders/test-1/"
                                      aria-label="View “Test 1” archive">View</a></span></div>
                  <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
                  </button>
                </td>

                <td class="description column-description" data-colname="Description">Edit</td>

                <td class="slug column-slug" data-colname="Slug"><?php echo $tm->count; ?></td>
              </tr>
              <?php
            }
            ?>


            </tbody>


          </table>

        </div>


        </div>


        <?php
      } else {
        // -- start summary page
        echo '</div></div>';
        ?>

        <?php

        if($dzsap->mainoptions['admin_nag_disable_all']!='on'){
          ?>
          <span class="dzs--nag-intro-tooltip--sliders-admin dzstooltip-con js"><span class="tooltip-indicator"><i class="fa fa-info-circle" aria-hidden="true"></i></span><span class="dzstooltip active style-rounded color-dark-light  dims-set transition-slidedown        arrow-left talign-start" style="top: -5px;"><span class="dzstooltip--inner no-wrap width-auto"><?php echo esc_html__("You can create playlists by pressing the Add Playlist button.") ?><br><span class="dzs--nag--hide-all-tips"><input type="checkbox" id="dzs-ajax--hide-tips-checkbox"/> <label for="dzs-ajax--hide-tips-checkbox"><?php echo esc_html__("Hide all tips") ?></label> <button class="button-secondary dzs-ajax--hide-tips"><?php echo esc_html__("Confirm") ?></button></span></span>
<span class="dzs--close-btn"><span class="dashicons dashicons-no"></span></span> </span></span><?php
        }
        ?>


        <form class="import-slider-form" style="display: none;" enctype="multipart/form-data" action="" method="POST">
          <h3><?php echo esc_html__("Loading...") ?></h3>
          <p><input name="dzsap_import_slider_file" type="file" size="10"/></p>
          <button class="button-secondary" type="submit" name="action"
                  value="dzsap_import_slider"><?php echo esc_html__("Import"); ?></button>
          <div class="clear"></div>
          <?php


          ?>
        </form>
        <?php
      }
      ?>
      <div class="feedbacker"><?php echo esc_html__("Loading..."); ?></div><?php
    }
  }
}