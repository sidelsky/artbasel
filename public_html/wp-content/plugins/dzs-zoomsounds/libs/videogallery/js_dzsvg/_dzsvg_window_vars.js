
export function init_windowVars(){

  window._global_vimeoIframeAPIReady = false;
  window._global_vimeoIframeAPILoading = false;

  window._global_youtubeIframeAPIReady = 0;
  window.dzsvg_fullscreen_counter = 0;
  window.dzsvg_fullscreen_curr_video = null;


  window.backup_onYouTubePlayerReady = null;


  window.dzsvg_self_options = {};
  window.dzsvp_self_options = {};
  window.dzsvg_time_started = 0;


  window.dzsvg_had_user_action = false; // -- if we had user action we can unmute autoplay 22

  if (!window.dzsvg_default_settings) {
    window.dzsvg_default_settings = {};
  }
}