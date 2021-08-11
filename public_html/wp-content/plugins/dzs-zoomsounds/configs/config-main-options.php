<?php

$dependencyToWaveModeCanvas = array(

  array(
    'element' => 'skinwave_wave_mode',
    'value' => array('canvas'),
  ),
);

return array(


  'always_embed' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Always Embed Scripts?', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )", DZSAP_ID),
  ),
  'single_index_seo_disable' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Disable audio item indexing', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("disable google indexing on audio item page", DZSAP_ID),
  ),
  'enable_auto_backup' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Enable Autobackup', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => dzs_esc__(esc_html__('enable auto backup % backups will be in %s folder', DZSAP_ID), '/', 'wp-content/dzsap_backups'),
  ),
  'replace_playlist_shortcode' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Replace Playlist Shortcode', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__('replace the default wordpress audio playlist with a zoomsounds playlist ', DZSAP_ID),
  ),
  'enable_aux_buttons' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Enable Extra Stats / Delete button', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => sprintf(esc_html__('enable auxiliarry stats / delete button under each songs to see the stats', DZSAP_ID), '/', 'wp-content/dzsap_backups'),
  ),
  'activate_comments_widget' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Activate Comments Widget', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__('comments widget in the wordpress dashboard', DZSAP_ID),
  ),
  'download_link_links_directly_to_file' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Download Link links directly to file', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__('if set to on, download will not be forced', DZSAP_ID),
  ),
  'force_autoplay_when_coming_from_share_link' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Force Autoplay When Coming From Shared Link', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__('when your users click on the shared link, this will force autoplay for them', DZSAP_ID),
  ),
  'loop_playlist' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Loop playlist ?', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("loop the playlist after reaching end", DZSAP_ID),
  ),
  'construct_player_list_for_sync' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Play Single Players One After Another on the Page', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("Automatically identify all the single players in the page", DZSAP_ID),
  ),
  'playlists_mode' => array(
    'type' => 'select',
    'category' => 'main_settings',
    'choices'=>array(
      array(
        'label' => esc_html__("Normal", DZSAP_ID),
        'value' => 'normal',
      ),
      array(
        'label' => esc_html__("( Deprecated ! ) Legacy", DZSAP_ID),
        'value' => 'legacy',
      ),
    ),
    'default' => 'canvas',
    'select_type' => ' ',
    'title' => esc_html__('Playlists Mode', DZSAP_ID),
    'extra_classes' => ' ',
    'sidenote' => esc_html__('this will output the footer player on the whole site.',DZSAP_ID),
  ),
  'player_pause_method' => array(
    'type' => 'select',
    'category' => 'settings_appearance',
    'choices'=>array(
      array(
        'lab' => esc_html__("Pause"),
        'val' => 'pause'
      ),
      array(
        'lab' => esc_html__("Stop"),
        'val' => 'stop'
      ),
    ),
    'default' => 'canvas',
    'select_type' => ' ',
    'title' => esc_html__('Pause method', DZSAP_ID),
    'extra_classes' => ' dzs-dependency-field',
    'sidenote' => esc_html__("select a class to restrict downloads too", DZSAP_ID),
  ),
  'skinwave_wave_mode' => array(
    'type' => 'select',
    'category' => 'settings_appearance',
    'choices'=>array(
      array(
        'lab' => esc_html__("Image"),
        'val' => 'image'
      ),
      array(
        'lab' => esc_html__("Canvas"),
        'val' => 'canvas'
      ),
      array(
        'lab' => esc_html__("Line"),
        'val' => 'line'
      ),
    ),
    'default' => 'canvas',
    'select_type' => ' ',
    'title' => esc_html__('Waveform Mode', DZSAP_ID),
    'extra_classes' => ' dzs-dependency-field',
    'sidenote' => esc_html__("this is the wave style ", DZSAP_ID). sprintf("<strong> %s </strong> - %s <br>", esc_html__("Image"), esc_html__("is just a image png that must be generated from the backend")).sprintf("<strong> %s </strong> - %s <br>", esc_html__("Canvas"), esc_html__("is a new and more immersive mode to show the waves. you can control color more easily, reflection size and wave bars number")),
  ),

  'try_to_cache_total_time' => array(
    'type' => 'checkbox',
    'category' => 'settings_appearance',
    'default' => 'on',
    'select_type' => ' ',
    'title' => esc_html__('Try to cache total time', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("try to cache the total time, so that the meta data does not need to be loaded in order to show track time", DZSAP_ID),
  ),

  'skinwave_wave_mode_canvas_reflection_size' => array(
    'type' => 'select',
    'category' => 'settings_appearance',
    'choices'=>array(
      array(
        'lab' => esc_html__("None"),
        'val' => '0'
      ),
      array(
        'lab' => esc_html__("Normal"),
        'val' => '0.25'
      ),
      array(
        'lab' => esc_html__("Big"),
        'val' => '0.5'
      ),
    ),
    'default' => '0.25',
    'dependency' => $dependencyToWaveModeCanvas,
    'select_type' => ' ',
    'title' => esc_html__('Reflection Size', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("the waveform bars size / the number of bars on screen", DZSAP_ID),
  ),
  'try_to_get_id3_thumb_in_frontend' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Try to get id3 thumbnail in frontend', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("thumbnail for tracks that have id3 will autogenerate in the backend. if you want to generate in the fronend too check this", DZSAP_ID),
  ),

  'mobile_disable_footer_player' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'on',
    'select_type' => ' ',
    'title' => esc_html__('Disable Footer Player in Mobile', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("disable the footer player on mobile - so songs play directly in their container", DZSAP_ID),
  ),

  'show_only_published' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Show only published Audio items in playlists', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("only audio items that are published are shown in playlists", DZSAP_ID),
  ),
  'track_downloads' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Track Downloads', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("create table for tracking views / downloads / etc.", DZSAP_ID),
  ),
  'wpdb_enable' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Use WordPress Database to Store Track Data', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("Use WordPress Database to Store Track Data", DZSAP_ID),
  ),
  'enable_ie11_compatibility' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Enable IE11 Compatibility', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("if your users use ie11, you can use compatibilty mode, but the file size will increase", DZSAP_ID),
  ),

  'script_use_async' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Async load script', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("you can load the script async, so it will not block page rendering", DZSAP_ID),
  ),
  'script_use_defer' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Defer load script', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("you can load the script defer, so it will not block page rendering", DZSAP_ID),
  ),
  'pcm_data_try_to_generate' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'on',
    'select_type' => ' ',
    'title' => esc_html__('Wave Generation', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("generate wave form or display placeholders ( off ) ", DZSAP_ID),
  ),

  'exceprt_zoomsounds_posts' => array(
    'type' => 'textarea',
    'category' => 'main_settings',
    'default' => '[zoomsounds_player type="detect" dzsap_meta_source_attachment_id="{{postid}}" source="{{source}}" thumb="{{thumb}}" config="sample--skin-wave--with-comments" autoplay="off" loop="off" open_in_ultibox="off" enable_likes="off" enable_views="on" play_in_footer_player="on" enable_download_button="off" download_custom_link_enable="off"]',
    'select_type' => ' ',
    'title' => esc_html__('Custom excerpt for zoomsounds audio posts archive page', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("delete this for switching to default theme excerpt", DZSAP_ID),
  ),

  'excerpt_hide_zoomsounds_data' => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Hide ZoomSounds Generated Text from Excerpt', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("hide zoomsounds generated data from archive pages", DZSAP_ID),
  ),

  DZSAP_ADMIN_PAGENAME_MAINOPTIONS_WAVE_GENERATOR_AUTO_GENERATE_PARAM => array(
    'type' => 'checkbox',
    'category' => 'main_settings',
    'default' => 'on',
    'title' => esc_html__('Waveform data autogenerate', DZSAP_ID),
    'extra_classes' => ' ',
    'sidenote' => esc_html__('try to autogenerate the wave data',DZSAP_ID),
  ),
  'debug_queries' => array(
    'type' => 'checkbox',
    'category' => 'developer_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Debug queries', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => '[developer] '.esc_html__("debug queries", DZSAP_ID),
  ),
  'failsafe_ajax_reinit_players' => array(
    'type' => 'checkbox',
    'category' => 'developer_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Repair Ajax Players', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("On ajax themes, players might need a reinitialization after ajax calls.", DZSAP_ID),
  ),
  'failsafe_repair_media_element' => array(
    'type' => 'checkbox',
    'category' => 'developer_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Repair Media Element', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("If the audio element used by zoomsounds is somehow replaced ( maybe conflicting with media element ) - you can use this to repair function ", DZSAP_ID),
  ),
  'developer_check_for_bots_and_dont_reveal_source' => array(
    'type' => 'checkbox',
    'category' => 'developer_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Disable bot source', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("check for bot scrubing the site - if it is detected, do not show him the source field", DZSAP_ID),
  ),
  'notice_no_media' => array(
    'type' => 'checkbox',
    'category' => 'developer_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Show Notice - no media', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("display when the audio cannot be loaded", DZSAP_ID),
  ),
  'dzsap_categories_rewrite' => array(
    'type' => 'text',
    'category' => 'developer_settings',
    'default' => 'audio-category',
    'select_type' => ' ',
    'title' => esc_html__('Rewrite categories slug', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("categories slug for the audio items categories archive page", DZSAP_ID),
  ),
  'dzsap_tags_rewrite' => array(
    'type' => 'text',
    'category' => 'developer_settings',
    'default' => 'audio-tag',
    'select_type' => ' ',
    'title' => esc_html__('Rewrite tags slug', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("tags slug for the audio items tags archive page", DZSAP_ID),
  ),
  'dzsap_sliders_rewrite' => array(
    'type' => 'text',
    'category' => 'developer_settings',
    'default' => 'audio-slider',
    'select_type' => ' ',
    'title' => esc_html__('Rewrite sliders slug', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => esc_html__("sliders slug for the audio items tags archive page", DZSAP_ID),
  ),
  'fontawesome_load_local' => array(
    'type' => 'select',
    'choices'=>array(
      array(
        'lab' => esc_html__("Off"),
        'val' => 'off'
      ),
      array(
        'lab' => esc_html__("On"),
        'val' => 'on'
      ),
    ),
    'category' => 'developer_settings',
    'default' => 'off',
    'select_type' => ' ',
    'title' => esc_html__('Load local fontawesome', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => '',
  ),
  'init_javascript_method' => array(
    'type' => 'select',
    'choices'=>array(
      array(
        'lab' => esc_html__("Automatic"),
        'val' => 'auto'
      ),
      array(
        'lab' => esc_html__("By script"),
        'val' => 'script'
      ),
    ),
    'category' => 'developer_settings',
    'default' => 'auto',
    'select_type' => ' ',
    'title' => esc_html__('Init player method', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => '',
  ),



  'dzsapp_player_shortcode' => array(
    'type' => 'textarea',
    'category' => 'settings_appearance',
    'default' => '',
    'select_type' => ' ',
    'title' => esc_html__('Audio Item - Page shortcode', DZSAP_ID),
    'extra_classes' => '',
    'sidenote' => ''.esc_html__("you can input here shortcode to replace the main player in woocommerce product ie -", DZSAP_ID).'<br>
                <pre style="white-space: pre-line;">[zoomsounds_player type="detect" dzsap_meta_source_attachment_id="{{postid}}" source="{{source}}" thumb="{{thumb}}" config="sample--skin-wave--with-comments" autoplay="off" loop="off" open_in_ultibox="off" enable_likes="off" enable_views="on" play_in_footer_player="on" enable_download_button="off" download_custom_link_enable="off"]',
  ),

  'multisharer_social_share_section' => array(
    'type' => 'textarea',
    'category' => 'settings_social',
    'default' => 'default',
    'select_type' => ' ',
    'title' => esc_some_html__('Multisharer - %s section', '<em>Social</em>'),
    'extra_classes' => '',
    'sidenote' => ''.esc_html__("you can input no share section, or leave default, or input custom share content", DZSAP_ID),
  ),

  'multisharer_shareLink_section' => array(
    'type' => 'textarea',
    'category' => 'settings_social',
    'default' => 'default',
    'select_type' => ' ',
    'title' => esc_some_html__('Multisharer - %s section', '<em>Share</em>'),
    'extra_classes' => '',
    'sidenote' => ''.esc_html__("you can input no share section, or leave default, or input custom share content", DZSAP_ID),
  ),

  'multisharer_embed_section' => array(
    'type' => 'textarea',
    'category' => 'settings_social',
    'default' => 'default',
    'select_type' => ' ',
    'title' => esc_some_html__('Multisharer - %s section', '<em>Embed</em>'),
    'extra_classes' => '',
    'sidenote' => ''.esc_html__("recommended to leave to default if you need embed code", DZSAP_ID),
  ),

);