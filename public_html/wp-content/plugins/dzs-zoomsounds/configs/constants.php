<?php


define('DZSAP_ID', 'dzsap');
define('DZSAP_PREFIX_LOWERCASE', 'dzsap');
define('DZSAP_REGISTER_POST_TYPE_NAME', 'dzsap_items');
define('DZSAP_REGISTER_POST_TYPE_CATEGORY', 'dzsap_category');
define('DZSAP_REGISTER_POST_TYPE_TAGS', 'dzsap_tags');
define('DZSAP_DBNAME_OPTIONS', 'dzsap_options');
define('DZSAP_TAXONOMY_NAME_SLIDERS', 'dzsap_sliders');
define('DZSAP_PERMISSION_ULTIMATE', 'manage_options');
define('DZSAP_DBNAME_MAINITEMS', 'dzsap_items');
define('DZSAP_DBNAME_LEGACY_DBS', 'dzsap_dbs');
define('DZSAP_DBNAME_AUDIO_PLAYERS_CONFIGS', 'dzsap_vpconfigs');
define('DZSAP_DBNAME_MAINOPTIONS', 'dzsap_options');
define('DZSAP_DBNAME_SAMPLEDATA', 'dzsap_sample_data');
const DZSAP_DBNAME_CACHE_TOTAL_TIME = '_dzsap_total_time';
define('DZSAP_DBNAME_PCM_LINKS', 'dzsap_pcm_to_id_links'); // -- pcm bindings
const DZSAP_META_OPTION_PREFIX = 'dzsap_meta_';
const DZSAP_ADMIN_UPDATE_LATEST_VERSION_URI = 'https://zoomthe.me/cronjobs/cache/dzsap_get_version.static.html';
define('DZSAP_PHP_LOG_LABEL', '[dzsap]');
define('DZSAP_PHP_LOG_AJAX_LABEL', '[ajax]');
define('DZSAP_ADMIN_PREVIEW_PLAYER_PARAM', 'dzsap_preview_player');
define('DZSAP_ADMIN_PAGENAME_PARENT', 'dzsap_menu');
define('DZSAP_ADMIN_PAGENAME_AUTOUPDATER', 'dzsap-autoupdater');
define('DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS', 'dzsap_configs');
define('DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_SLIDERS', 'dzsap_menu');
define('DZSAP_ADMIN_PAGENAME_DESIGNER_CENTER', 'dzsap-dc');
define('DZSAP_ADMIN_PAGENAME_MAINOPTIONS', 'dzsap-mo');
define('DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR', 'dzsap_wave_regenerate');
define('DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR_AUTO_GENERATE_PARAM', 'dzsap_wave_generate_auto');
define('DZSAP_ADMIN_PAGENAME_ABOUT', 'dzsap-about');
define('DZSAP_VPCONFIGS_DEFAULT_SETTINGS_NAME', 'default-settings-for-zoomsounds');
define('DZSAP_ZOOMSOUNDS_ACRONYM', 'zoomsounds');
define('DZSAP_DB_VIEWS_META_NAME', '_dzsap_views');
define('DZSAP_DB_DOWNLOADS_META_NAME', '_dzsap_downloads');
const DZSAP_DB_LIKES_META_NAME = '_dzsap_likes';

const DZSAP_META_NAME_FOOTER_ENABLE = 'dzsap_footer_enable';
const DZSAP_META_NAME_FOOTER_FEED_TYPE = 'dzsap_footer_feed_type';
const DZSAP_META_NAME_FOOTER_VPCONFIG = 'dzsap_footer_vpconfig';
const DZSAP_META_NAME_FOOTER_FEATURED_MEDIA = 'dzsap_footer_featured_media';
const DZSAP_META_NAME_FOOTER_SONG_NAME = 'dzsap_footer_song_name';
const DZSAP_META_NAME_FOOTER_TYPE = 'dzsap_footer_type';

const DZSAP_VIEW_STICKY_PLAYER_ID = 'dzsap_footer';

define('DZSAP_DEFAULT_ZOOMSOUNDS_CONFIG', 'default-settings-for-zoomsounds');
define('DZSAP_COOKIENAME_SYSTEM_CHECK_WAVES', 'dzsap_is_admin_systemCheck_waves');
define('DZSAP_VIEW_SHOWCASE_PLAYLIST_ID', 'playlist_gallery');
const DZSAP_VIEW_GET_TRACK_SOURCE = 'get_track_source';
const DZSAP_VIEW_NONCE_IDENTIFIER = 'generatenonce';
define('DZSAP_VIEW_EMBED_IFRAME_HEIGHT', '180');
define('DZSAP_VIEW_APCONFIG_PREFIX', '.apconfig-');
define('DZSAP_AJAX_DELETE_CACHE_WAVEFORM_DATA', 'dzsap_delete_waveforms');
define('DZSAP_AJAX_DELETE_CACHE_TOTAL_TIMES', 'dzsap_delete_times');
const DZSAP_WOOCOMMERCE_OVERLAY_CENTER_CLASS = '.dzsap--go-to-thumboverlay--container';

const DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER = 'currslider';
const DZSAP_LEGACY_SLIDERS__GET_CURRSLIDER_FINDER = 'find_slider_by_slug';
const DZSAP_ADMIN_SHORTCODE_PLAYER_GENERATOR_KEY = 'dzsap_shortcode_player_builder';

const DZSAP_GET_KEY_DOWNLOAD = 'dzsap_download';

const DZSAP_WOOCOMMERCE_PLAYLIST_IN_PRODUCT_PREFIX = 'zoomsounds-product-playlist-';

if (defined('DZSAP_DEBUG_LOCAL_SCRIPTS') && DZSAP_DEBUG_LOCAL_SCRIPTS === true) {
  define('DZSAP_URL_AUDIOPLAYER', 'http://devsite/zoomsounds/source/audioplayer/');
}else{

  define('DZSAP_URL_AUDIOPLAYER', DZSAP_BASE_URL . "audioplayer/");
}

define('DZSAP_URL_FONTAWESOME_EXTERNAL','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

const DZSAP_ITEMS__META_FILE_SOURCE = array(
  'FROM_FOLDER_IMPORT'=>'FROM_FOLDER_IMPORT',
  'FROM_MANUAL_ADD'=>'FROM_MANUAL_ADD',
);
const DZSAP_META_WOOCOMMERCE_ZOOMSOUNDS_MP3 = 'dzsap_woo_product_track';

const DZSAP_VIEW_DEFAULT_SHORTCODE_PLAYER_ATTS = array(
  'width' => '100%',
  'config' => 'default-settings-for-zoomsounds',
  'height' => '300',
  'source' => '',
  'sourceogg' => '',
  'coverimage' => '',

  'cue' => 'auto',
  'loop' => 'off',
  'autoplay' => 'off',
  'show_tags' => 'off',
  'type' => 'audio',
  'player' => '',
  'itunes_link' => '',
  'playerid' => '', // -- if player id okay
  'wpPlayerPostId' => '', // -- if player id okay
  'thumb' => '',
  'thumb_for_parent' => '',
  'mp4' => '',
  'openinzoombox' => 'off',
  'enable_likes' => 'off',
  'enable_downloads_counter' => 'off',
  'enable_views' => 'off',
  'enable_rates' => 'off',
  'title_is_permalink' => 'off',
  'playfrom' => 'off',
  'artistname' => 'default',
  'songname' => 'default',
  'is_single' => 'on',
  'embedded' => 'off', // -- in case it is embedded, we might remove embed button from conifg
  'divinsteadofscript' => 'off',
  'init_player' => 'on',
  'faketarget' => '',
  'sample_time_start' => '',
  'sample_time_end' => '',
  'sample_time_total' => '',
  'feed_type' => '',
  'extra_init_settings' => array(),
  'player_index' => '',
  'inner_html' => '',
  'extraattr' => '',
  'extra_classes' => '',
  'content_inner' => '', // -- will replace content inner
  'extra_html' => '',
  'extra_html_in_controls_right' => '',
  'js_settings_extrahtml_in_float_right' => '', // -- js settings extra html in float right .. configs will go into extra_html_in_controls_right
  'play_target' => 'default', // -- "default" or "footer"
  'dzsap_meta_source_attachment_id' => '',
  'outer_comments_field' => '',
  'extra_classes_player' => '',
  'called_from' => 'player',
);

const DZSAP_SHOWCASE_STYLE_TYPES=array(
  'SCROLLER'=>'scroller',
  'PLAYER'=>'player',
  'WIDGET_PLAYER'=>'widget_player',
  'SLIDER'=>'slider',
  'PLAYLIST'=>'playlist',
  'FEATURED_SLIDER'=>'featured_slider',
);