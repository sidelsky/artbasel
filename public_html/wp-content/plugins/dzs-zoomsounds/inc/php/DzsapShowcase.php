<?php

class DzsapShowcase {

  /**
   * DzsapShowcase constructor.
   * @param DZSAudioPlayer $dzsap
   */
  function __construct($dzsap) {

    $this->dzsap = $dzsap;


    add_shortcode(DZSAP_ZOOMSOUNDS_ACRONYM . '_showcase_audio_cats', array($this, 'view_shortcode_audioCats'));
    add_shortcode(DZSAP_ZOOMSOUNDS_ACRONYM . '_showcase', array($this, 'view_shortcode_showcase'));


  }

  function view_shortcode_audioCats() {

    $items = array();

    $fout = '';


    if (dzs_get_query_arg(dzs_curr_url(), 'term_id')) {

      echo '<div><a href="' . remove_query_arg('term_id', dzs_curr_url()) . '">&#8592; ' . esc_html__('Back', DZSAP_ID) . '</a></div><br>';


      $term = get_term(dzs_get_query_arg(dzs_curr_url(), 'term_id'));
      $postType = '';
      $taxonomyName = $term->taxonomy;

      if ($taxonomyName == DZSAP_REGISTER_POST_TYPE_CATEGORY) {
        $postType = DZSAP_REGISTER_POST_TYPE_NAME;
      }


      $fout .= do_shortcode('[zoomsounds_showcase feed_from="' . $postType . '"   style="player" count="50" cat="' . $term->slug . '" ]');
    } else {

      $audioCats = get_terms(array(
        'taxonomy' => DZSAP_REGISTER_POST_TYPE_CATEGORY,
        'hide_empty' => false,
      ));
      foreach ($audioCats as $audioCat) {

        $audioCatId = $audioCat->term_id;


        $term_meta = get_option("taxonomy_$audioCatId");


        array_push($items, DzsapShowcase::sanitizeAudioCatToDzsViewLayout($audioCat, $term_meta));
      }
      include_once DZSAP_BASE_PATH . 'inc/php/dzs-shared/view_layout.php';
      $fout .= dzs_view_layoutStyle(array(), $items, DZSAP_BASE_URL);
    }

    return $fout;
  }

  /**
   * @param WP_Term $term
   * @param $term_meta
   */
  static function sanitizeAudioCatToDzsViewLayout($term, $term_meta) {

    global $wp;

    $item = array();

    $item['title'] = $term->name;


    $item['subtitle'] = $term->count . ' ' . esc_html__('songs', DZSAP_ID);
    $item['featuredUri'] = add_query_arg('term_id', $term->term_id, home_url($wp->request));
    $item['thumbnailImageUrl'] = wp_get_attachment_image_url($term_meta['term_image'], array(300, 300));


    return $item;
  }


  /**
   *   [zoomsounds_showcase feed_from="audio_items" ids="1,2,3"]
   * , example2 : [zoomsounds_showcase feed_from="audio_items" ids="" style="widget_player" orderby="likes"  order="DESC" count="5" style_widget_player_show_likes="on"]
   * @param array $pargs
   * @return string
   */
  function view_shortcode_showcase($pargs = array()) {

    global $dzsapp;


    $fout = '';


    $dzsap = $this->dzsap;
    include_once DZSAP_BASE_PATH . 'inc/php/query/query-filters.php';


    $fout = '';
    $shortcodeOptions = array(
      'feed_from' => 'audio_items', // -- audio_items ( Audio Items ) or products ( WooCommerce )
      'cat' => '', // -- input a category slug
      'paged' => '', // -- the page number
      'slideshow_time' => '100', // -- slideshow number in seconds for
      'count' => '5', // -- number of items per page
      'orderby' => 'date', // -- date, likes, views
      'author' => '', // -- author id
      'ids' => '', // -- select some ids for example 1,2,50 / ids="1,2,3"
      'style' => 'scroller', // -- style - "playlist" or "widget" "player" or "scroller" or "featured_slider"
      'order' => 'DESC', // -- DESC ( descending ) or ASC
      'scroller_per_row' => '3', // -- number of rows per page
      'play_in_footer' => 'off', // -- date, likes, views
    );


    if (!is_array($pargs)) {
      $pargs = array();
    }
    $shortcodeOptions = array_merge($shortcodeOptions, $pargs);


    $zoomsoundsIts = array();
    if ($shortcodeOptions['feed_from']) {


      if ($shortcodeOptions['ids']) {

      }

      $wpQueryArgs = array();
      $wpQueryArgs['posts_per_page'] = '-1';
      $wpQueryArgs['post_type'] = 'any';
      $wpQueryArgs['post_status'] = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash');


      if ($shortcodeOptions['count']) {
        $wpQueryArgs['posts_per_page'] = $shortcodeOptions['count'];
      }


      if ($shortcodeOptions['feed_from'] == 'audio_items' || $shortcodeOptions['feed_from'] == DZSAP_REGISTER_POST_TYPE_NAME) {
        $wpQueryArgs['post_type'] = DZSAP_REGISTER_POST_TYPE_NAME;
      }


      if ($shortcodeOptions['ids']) {
        $wpQueryArgs['post__in'] = explode(',', $shortcodeOptions['ids']);
      }


      if ($shortcodeOptions['author']) {
        $wpQueryArgs['author'] = $shortcodeOptions['author'];
      }

      $argsForAdjust = DZSZoomSoundsHelper::query_adjustForFilters($shortcodeOptions);
      $wpQueryArgs = dzs_query_adjustFilterSearch($wpQueryArgs, $argsForAdjust);
      $query = new WP_Query($wpQueryArgs);


      /**
       * posts retrieved
       */


      $zoomsoundsIts = DZSZoomSoundsHelper::transform_to_array_for_parse($query->posts, $shortcodeOptions);


      DZSZoomSoundsHelper::sort_zoomsoundsItems($zoomsoundsIts, $shortcodeOptions);

      if ($shortcodeOptions['count']) {
        $zoomsoundsIts = array_slice($zoomsoundsIts, 0, intval($shortcodeOptions['count']));
      }


      // -----------------
      /**
       * start VIEW
       */

      $fout .= '<div class="dzsap-showcase-con dzsap-showcase-con--style-' . $shortcodeOptions['style'] . '">';

      if ($shortcodeOptions['style'] == DZSAP_SHOWCASE_STYLE_TYPES['PLAYER']) {


        $shortcodePlayerAtts = array();


        foreach ($zoomsoundsIts as $zoomsoundsIt) {
          $fout .= $dzsap->classView->shortcode_player($zoomsoundsIt);
        }
        DZSZoomSoundsHelper::enqueueAudioPlayerShowcase();
      }

      if ($shortcodeOptions['style'] == DZSAP_SHOWCASE_STYLE_TYPES['PLAYLIST']) {

        $args = array(
          'ids' => '1'
        , 'embedded_in_zoombox' => 'off'
        , 'embedded' => 'off'
        , 'db' => 'main'
        );


        $gallery_id = DZSAP_VIEW_SHOWCASE_PLAYLIST_ID;

        $fout .= '[zoomsounds id="' . $gallery_id . '" embedded="' . $args['embedded'] . '" extra_classes="from-wc-album" for_embed_ids="' . $shortcodeOptions['ids'] . '"]';


        if ($dzsap->mainoptions['playlists_mode'] == 'normal') {
          $tax = DZSAP_TAXONOMY_NAME_SLIDERS;
          $reference_term = get_term_by('slug', $gallery_id, $tax);
          $selected_term_id = $reference_term->term_id;
          $term_meta = get_option("taxonomy_$selected_term_id");
        }


        $dzsap->classView->get_its_settings($zoomsoundsIts, $shortcodeOptions, $term_meta, $selected_term_id);


        $dzsap->front_scripts();


        $dzsap->sliders_index++;


        $i = 0;
        $k = 0;
        $id = DZSAP_VIEW_SHOWCASE_PLAYLIST_ID;
        if (isset($shortcodeOptions['id'])) {
          $id = $shortcodeOptions['id'];
        }


        // TODO: legacy, but new ?


        if ($dzsap->mainoptions['playlists_mode'] == 'legacy') {
          for ($i = 0; $i < count($dzsap->mainitems); $i++) {
            if ((isset($id)) && ($id == $dzsap->mainitems[$i]['settings']['id']))
              $k = $i;
          }
        }


        $enable_likes = 'off';

        $enable_views = 'off';
        $enable_downloads_counter = 'off';


        $zoomsoundsIts = array_reverse($zoomsoundsIts);

        foreach ($zoomsoundsIts as $it) {


          // -- this is settings item .. we don't do nothing
          if (isset($it['design_menu_state'])) {
            continue;
          }

          $po = get_post($it['id']);


          $desc = ' ';
          $title = ' ';
          $title = $po->post_title;
          $desc = $po->post_title;

          $src = $it['source'];


          if ($dzsap->mainoptions['try_to_hide_url'] == 'on') {


            $nonce = '{{generatenonce}}';


            $nonce = rand(0, 10000);

            $id = $it['id'];


            $lab = 'dzsap_nonce_for_' . $id . '_ip_' . $_SERVER['REMOTE_ADDR'];

            $lab = DZSZoomSoundsHelper::sanitize_toKey($lab);
            $_SESSION[$lab] = $nonce;

            $src = site_url() . '/index.php?dzsap_action=' . DZSAP_VIEW_GET_TRACK_SOURCE . '&id=' . $id . '&' . $lab . '=' . $nonce;
          }


          $sample_time_start = get_post_meta($it['id'], 'dzsap_woo_sample_time_start', true);
          $sample_time_end = get_post_meta($it['id'], 'dzsap_woo_sample_time_end', true);
          $sample_time_total = get_post_meta($it['id'], 'dzsap_woo_sample_time_total', true);


          $config = 'playlist_player';

          if (isset($shortcodeOptions['player_config']) && $shortcodeOptions['player_config']) {
            $config = $shortcodeOptions['player_config'];
          }


          $fout .= '[zoomsounds_player  source="' . $src . '" config="' . $config . '" playerid="' . $it['id'] . '"  thumb="" autoplay="on" cue="auto" enable_likes="' . $enable_likes . '" enable_views="' . $enable_views . '"  enable_downloads_counter="' . $enable_downloads_counter . '" songname="' . $title . '" artistname="' . $desc . '" init_player="off" called_from="playlist_showcase"';


          if ($sample_time_start) {
            $fout .= ' sample_time_start="' . $sample_time_start . '"';
          }
          if ($sample_time_end) {
            $fout .= ' sample_time_end="' . $sample_time_end . '"';
          }
          if (isset($zoomsoundsIts['settings'])) {
            if ($zoomsoundsIts['settings']['enable_likes'] == 'on') {
              $fout .= ' enable_likes="' . 'on' . '"';
            }
            if ($zoomsoundsIts['settings']['enable_views'] == 'on') {
              $fout .= ' enable_views="' . 'on' . '"';
            }
          }
          if ($sample_time_total) {
            $fout .= ' sample_time_total="' . $sample_time_total . '"';
          }

          $fout .= ']';
        }
        $fout .= '[/zoomsounds]';


        $fout = do_shortcode($fout);
      }


      // -- list


      $i_number = 0;
      if ($shortcodeOptions['style'] === DZSAP_SHOWCASE_STYLE_TYPES['WIDGET_PLAYER']) {
        $fout .= '<div class="list-tracks-con">';

        foreach ($zoomsoundsIts as $track) {

          $link = get_permalink($track['id']);


          $fout .= '<a class="list-track ajax-link" href="' . $link . '">';

          $fout .= '<div class="track-thumb"';

          $l = '';

          if (isset($track['thumbnail'])) {

            $l = DZSZoomSoundsHelper::getImageSourceFromId($track['thumbnail']);
          }


          $src_thumb = $l;


          $fout .= ' style="background-image: url(' . $src_thumb . ')"';

          $fout .= '>';
          $fout .= '</div>';


          $fout .= '<div class="track-meta">';
          $fout .= '<span class="track-title color-brand-text"';
          $fout .= '>';

          $fout .= '<span href="">' . $track['title'] . '</span>';

          $fout .= '</span>';


          if (isset($shortcodeOptions['artist_name_is_author_name']) && $shortcodeOptions['artist_name_is_author_name'] === 'on') {
            $track['artistname'] = $track['original_author_name'];
          }

          $fout .= '<div class="track-author"';
          $fout .= '>';

          $fout .= '<span >' . $track['artistname'] . '</span>';

          $fout .= '</div>';

          $fout .= '<div class="track-number"';
          $fout .= '><span class="the-number">';

          $track_number = ($i_number + 1);
          if ($shortcodeOptions['paged']) {
            $track_number += intval($shortcodeOptions['paged']) * $shortcodeOptions['limit_posts'];
          }

          $fout .= $track_number;

          $fout .= '</span></div>';


          $fout .= '</div>';


          if (isset($shortcodeOptions['style_widget_player_show_likes']) && $shortcodeOptions['style_widget_player_show_likes'] === 'on') {


            $fout .= '<div class="likes-show">';

            $fout .= '<span class="the-count">';

            $str_likes = 0;
            if (isset($track['likes'])) {
              $str_likes = $track['likes'];
            }
            $fout .= $str_likes;
            $fout .= '</span>';

            $fout .= '<i class="fa fa-thumbs-up"></i>';


            $fout .= '</div>';
          }
          if (isset($shortcodeOptions['style_widget_player_show_downloads']) && $shortcodeOptions['style_widget_player_show_downloads'] === 'on') {


            $fout .= '<div class="likes-show">';

            $fout .= '<span class="the-count">';

            $str_likes = 0;
            if (isset($track['downloads'])) {
              $str_likes = $track['downloads'];
            }
            $fout .= $str_likes;
            $fout .= '</span>';

            $fout .= '<i class="fa fa-cloud-download"></i>';


            $fout .= '</div>';
          }
          if (isset($shortcodeOptions['style_widget_player_show_views']) && $shortcodeOptions['style_widget_player_show_views'] === 'on') {


            $fout .= '<div class="likes-show">';

            $fout .= '<span class="the-count">';

            $str_likes = 0;
            if (isset($track['views'])) {
              $str_likes = $track['views'];
            }
            $fout .= $str_likes;
            $fout .= '</span>';

            $fout .= '<i class="fa fa-play-circle"></i>';


            $fout .= '</div>';
          }


          $fout .= '</a>';


          $i_number++;

        }
        $fout .= '</div>';


        DZSZoomSoundsHelper::enqueueAudioPlayerShowcase();
        wp_enqueue_style('fontawesome', DZSAP_URL_FONTAWESOME_EXTERNAL);


      }


      $i_number = 0;
      if ($shortcodeOptions['style'] === DZSAP_SHOWCASE_STYLE_TYPES['SCROLLER']) {
        $fout .= '<div class="contentscroller from-front_shortcode_showcase auto-init   animate-height" data-options=\'{
"settings_direction": "horizontal"
,"settings_onlyone": "off"
,"settings_autoHeight_proportional": "off"
,"settings_autoHeight_proportional_max_height": "700"
,"settings_transition": "fade"
,"design_bulletspos": "none"
,"settings_slideshowTime": "' . $shortcodeOptions['slideshow_time'] . '"
,"per_row": "' . $shortcodeOptions['scroller_per_row'] . '"
}\'>';

        $fout .= '<div class="arrowsCon arrow-skin-bare" style="text-align: right;">
<div class="single--arrow arrow-left">
<div class="arrow-con">
<i class="the-icon fa fa-chevron-left"></i>
</div>
</div>
<div class="single--arrow arrow-right">
<div class="arrow-con">
<i class="the-icon fa fa-chevron-right"></i>
</div>
</div>
</div>';

        $fout .= '<div class="items">';

        foreach ($zoomsoundsIts as $track) {


          $link = get_permalink($track['id']);


          $src_thumb = '';
          $src = $track['source'];

          if (isset($track['thumbnail'])) {

            $src_thumb = DZSZoomSoundsHelper::getImageSourceFromId($track['thumbnail']);
          }
          if ($src_thumb == '' && isset($track['id'])) {

            $src_thumb = DZSZoomSoundsHelper::view_getComputedDzsapThumbnail($track['id']);
          }


          $track_number = ($i_number + 1);


          $config_args = array(

            'id' => 'default',
            'skin_ap' => 'skin-wave',
            'disable_volume' => 'on',
            'playfrom' => 'default',
            'loop' => 'off',
            'cue_method' => 'on',
          );

          $play_in_footer = 'off';

          if ($shortcodeOptions['play_in_footer'] == 'on') {

            $play_in_footer = 'on';
          }
          if ($play_in_footer) {
            $config_args['play_target'] = 'footer';
          }

          $player_args = array(
            'source' => $src,
            'config' => $config_args,
            'title_is_permalink' => 'on',
            'extra_classes_player' => 'disable-volume center-it disable-all-but-play-btn  button-aspect-noir  button-aspect-noir--filled   ',
            'extraattr' => ' style=" width: 40px!important;height: 40px;"',
          );
          if ($play_in_footer) {
            $player_args['play_target'] = 'footer';
          }

          $fout .= '<div class="csc-item">
      <div class="showcase-scroller-item">
          <div  class="divimage full-square" data-src="' . $src_thumb . '">
  ' . $dzsap->classView->shortcode_player($player_args) . '</div>
  <div class="showcase-scroller-item--meta">
      <div class="showcase-scroller-item--title">' . $track['artistname'] . '</div>
      <div class="showcase-scroller-item--subtitle"><a style="color:inherit;" href="' . get_permalink($track['id']) . '">' . $track['title'] . '</a></div>
  </div>


    </div>
</div>
';


          $i_number++;

        }
        $fout .= '</div>';
        $fout .= '</div>';


        DZSZoomSoundsHelper::enqueueAudioPlayerShowcase();

        wp_enqueue_script('dzscsc', DZSAP_BASE_URL . 'libs/contentscroller/contentscroller.js');
        wp_enqueue_style('dzscsc', DZSAP_BASE_URL . 'libs/contentscroller/contentscroller.css');
        wp_enqueue_style('fontawesome', DZSAP_URL_FONTAWESOME_EXTERNAL);


      }

      $i_number = 0;
      if ($shortcodeOptions['style'] === DZSAP_SHOWCASE_STYLE_TYPES['SLIDER']) {
        $fout .= '<div class="contentscroller contentscroller--type-slider from-front_shortcode_showcase auto-init   animate-height" data-options=\'{
"settings_direction": "horizontal"
,"settings_onlyone": "on"
,"settings_autoHeight_proportional": "off"
,"settings_autoHeight_proportional_max_height": "700"
,"settings_transition": "fade"
,"design_bulletspos": "none"
,"settings_slideshowTime": "' . $shortcodeOptions['slideshow_time'] . '"
}\'>';

        $fout .= '<div class="items">';

        foreach ($zoomsoundsIts as $track) {


          $link = get_permalink($track['id']);


          $src_thumb = '';
          $src = $track['source'];


          $src_thumb = DZSZoomSoundsHelper::getImageSourceFromThumbnailOrId($track);


          $track_number = ($i_number + 1);


          if ($src_thumb === '') {
            continue;
          }


          $fout .= '                        <div class="csc-item"  >
<div  class="divimage" data-src="' . $src_thumb . '"></div>';


          $fout .= '<div class="caption-con mode-appear-from-below show-on-active style-nove">

                    <div class="csc-caption--title">
                       ' . $track['artistname'] . '
                    </div>
                    <div class="csc-caption--subtitle">
                        <a class="csc-caption--subtitle_a" href="' . get_permalink($track['id']) . '">' . $track['title'] . '</a>
                    </div>

                </div>';
          $fout .= '
                        </div>
';


          $i_number++;

        }
        $fout .= '</div>';
        $fout .= '</div>';


        DZSZoomSoundsHelper::enqueueAudioPlayerShowcase();

        wp_enqueue_script('dzscsc', DZSAP_BASE_URL . 'libs/contentscroller/contentscroller.js');
        wp_enqueue_style('dzscsc', DZSAP_BASE_URL . 'libs/contentscroller/contentscroller.css');
        wp_enqueue_style('fontawesome', DZSAP_URL_FONTAWESOME_EXTERNAL);


      }
      // -- end slider


      $i_number = 0;
      if ($shortcodeOptions['style'] === DZSAP_SHOWCASE_STYLE_TYPES['FEATURED_SLIDER']) {


        $fout .= '<div class="dzs-row margin15">';
        $fout .= '<div class="dzs-col-md-8"  style="">';
        $fout .= '<div style="padding-top: 67.5%; position:relative;">';
        $fout .= '<div style=" width: 100%; height: 100%; position:absolute; top:0; left:0; " class="contentscroller showcase-type--featured_slider auto-init    animate-height" data-options=\'{
"settings_direction": "horizontal",
"settings_onlyone": "on",
"settings_autoHeight": "off",
"settings_autoHeight_proportional": "off",
"settings_autoHeight_proportional_max_height": "700",
"settings_transition": "fade",
"design_bulletspos": "none",
"outer_thumbs": ".cs-outerthumbs",
"outer_thumbs_keep_same_height": "on",
"settings_slideshowTime": "' . $shortcodeOptions['slideshow_time'] . '"
}\'>';

        $fout .= '<div class="items">';
        foreach ($zoomsoundsIts as $track) {


          $link = get_permalink($track['id']);


          $src_thumb = '';
          $src = $track['source'];


          // -- this mode will prefer wrapper_image
          if (isset($track['wrapper_image'])) {
            $src_thumb = $track['wrapper_image'];
          } else {

            if (isset($track['thumbnail'])) {
              $src_thumb = DZSZoomSoundsHelper::getImageSourceFromId($track['thumbnail']);
            }
            if ($src_thumb == '' && isset($track['id'])) {

              $src_thumb = DZSZoomSoundsHelper::view_getComputedDzsapThumbnail($track['id'], array(
                'try_to_get_wrapper_image' => 'on'
              ));
            }
          }
          $track_number = ($i_number + 1);


          $fout .= '<div class="csc-item"  >
<div  class="divimage fullheight" data-src="' . $src_thumb . '"></div>';


          $fout .= '<div class="caption-con mode-appear-from-below show-on-active style-nove">
 <div class="csc-caption--title"> ' . $track['artistname'] . '</div>
 <div class="csc-caption--subtitle">
<a class="csc-caption--subtitle_a" href="' . get_permalink($track['id']) . '">' . $track['title'] . '</a>
</div>
</div>';
          $fout .= '</div>
';


          $i_number++;

        }


        $fout .= '</div>';
        $fout .= '</div>';


        $fout .= '</div>';


        $fout .= '</div>'; // -- end col


        $fout .= '<div class="dzs-col-md-4">';
        $fout .= '<div class="contentscroller cs-outerthumbs   auto-init is-functional skin-nova   animate-height" style="height: 400px;" data-options=\'{
    "settings_direction": "vertical",
    "settings_onlyone": "off",
    "design_disableArrows": "on",
    "per_row": "' . $shortcodeOptions['scroller_per_row'] . '",
    "nav_type": "slide",
    "settings_autoHeight": "off"
}\'>';

        $fout .= '<div class="items">';

        foreach ($zoomsoundsIts as $track) {
          $link = get_permalink($track['id']);
          $src_thumb = DZSZoomSoundsHelper::getImageSourceFromThumbnailOrId($track);

          if (!$src_thumb) {
            continue;
          }

          $fout .= '<div class="csc-item"  >
<div  class="divimage" data-src="' . $src_thumb . '"></div>';
          $fout .= '</div>
';
        }


        $fout .= '</div>';
        $fout .= '</div>';


        $fout .= '</div>'; // -- end col
        $fout .= '</div>'; // -- end row

        DZSZoomSoundsHelper::enqueueAudioPlayerShowcase();

        wp_enqueue_script('dzscsc', DZSAP_BASE_URL . 'libs/contentscroller/contentscroller.js');
        wp_enqueue_style('dzscsc', DZSAP_BASE_URL . 'libs/contentscroller/contentscroller.css');


      }
      // -- END featured_slider


      $fout .= '</div>';
    }

    if (defined('DZSAPP_BASE_URL')) {
      wp_enqueue_style('dzsapp_showcase', DZSAPP_BASE_URL . 'libs/dzsapp/front-dzsapp.css');
      wp_enqueue_script('dzsapp_showcase', DZSAPP_BASE_URL . 'libs/dzsapp/front-dzsapp.js');
    }

    return $fout;

  }

}