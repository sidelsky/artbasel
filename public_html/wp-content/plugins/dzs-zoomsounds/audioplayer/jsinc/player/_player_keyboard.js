import {DZSAP_SCRIPT_SELECTOR_KEYBOARD} from "../../configs/_constants";

export const dzsap_generate_keyboard_controls = function () {
  let keyboard_controls = {
    'play_trigger_step_back': 'off'
    , 'step_back_amount': '5'
    , 'step_back': '37'
    , 'step_forward': '39'
    , 'sync_players_goto_next': ''
    , 'sync_players_goto_prev': ''
    , 'pause_play': '32'
    , 'show_tooltips': 'off'
  }


  const $keyboardControlsInfo = jQuery(DZSAP_SCRIPT_SELECTOR_KEYBOARD);
  if ($keyboardControlsInfo.length) {
    window.dzsap_keyboard_controls = JSON.parse($keyboardControlsInfo.html());
  }

  if (window.dzsap_keyboard_controls) {
    keyboard_controls = jQuery.extend(keyboard_controls, window.dzsap_keyboard_controls);
  }

  keyboard_controls.step_back_amount = Number(keyboard_controls.step_back_amount);


  return keyboard_controls;
};


export function handle_keypresses(e) {

  if (window.dzsap_isTextFieldFocused) {
    return;
  }


  function isKeyPressed(checkKeyCode) {
    let isKeyPressed = false;
    if (checkKeyCode.indexOf('ctrl+') > -1) {
      if (e.ctrlKey) {
        checkKeyCode = checkKeyCode.replace('ctrl+', '');
        if (e.keyCode === Number(checkKeyCode)) {
          isKeyPressed = true;
        }
      }
    } else {
      if (e.keyCode === Number(checkKeyCode)) {
        isKeyPressed = true;
      }
    }
    return isKeyPressed;
  }

  var $ = jQuery;

  const keyboard_controls = $.extend({}, dzsap_generate_keyboard_controls());


  if (dzsap_currplayer_focused && dzsap_currplayer_focused.api_pause_media) {

    if (isKeyPressed(keyboard_controls.pause_play)) {
      if (!$(dzsap_currplayer_focused).hasClass('comments-writer-active')) {
        if ($(dzsap_currplayer_focused).hasClass('is-playing')) {
          dzsap_currplayer_focused.api_pause_media();
        } else {
          dzsap_currplayer_focused.api_play_media();
        }

        if (window.dzsap_mouseover) {
          e.preventDefault();
          return false;
        }
      }
    }

    if (isKeyPressed(keyboard_controls.step_back)) {
      dzsap_currplayer_focused.api_step_back(keyboard_controls.step_back_amount);
    }

    if (isKeyPressed(keyboard_controls.step_forward)) {
      dzsap_currplayer_focused.api_step_forward(keyboard_controls.step_back_amount);
    }

    if (isKeyPressed(keyboard_controls.sync_players_goto_next)) {
      dzsap_currplayer_focused.api_sync_players_goto_next();
    }


    if (isKeyPressed(keyboard_controls.sync_players_goto_prev)) {
      dzsap_currplayer_focused.api_sync_players_goto_prev();
    }


  }
}


/**
 * called in singleton
 */
export const dzsap_keyboardSetup = () => {

  let $ = jQuery;

  window.dzsap_isTextFieldFocused = false;

  $(document).off('keydown.dzsapkeyup');
  $(document).on('keydown.dzsapkeyup', handle_keypresses);


  $(document).on('focus blur', 'textarea,input', function (e) {

    if (e.type == 'focusin' || e.type == 'focus') {

      window.dzsap_isTextFieldFocused = true;
    }
    if (e.type == 'focusout' || e.type == 'blur') {

      window.dzsap_isTextFieldFocused = false;
    }
  });


  $(document).on('keydown blur', '.zoomsounds-search-field', function (e) {
    const _t = $(e.currentTarget);

    setTimeout(function () {

      if (_t) {
        let $audioGallery = $('.audiogallery').eq(0);
        if (_t.attr('data-target')) {
          $audioGallery = $(_t.attr('data-target'));
        }
        if ($audioGallery.get(0) && $audioGallery.get(0).api_filter) {
          $audioGallery.get(0).api_filter('title', _t.val());
        }
      }
    }, 100);

  });
}
