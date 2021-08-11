import {ajax_comment_publish} from "../_dzsap_ajax";

import * as dzsapHelpers from '../_dzsap_helpers';
import {getRelativeX} from "../../js_common/_dzs_helpers";

const TOOLTIP_ALL_TALIGN_CLASSES = `talign-start talign-center talign-end`;
const STRUCTURE_COMMENTS_WRITER = `<div class="dzstooltip dzstooltip--comments-writer    talign-center arrow-top style-rounded color-dark-light    dims-set transition-slidedown " style="width: 330px;">  <div class="dzstooltip--inner"><div class="comments-writer"><div class="comments-writer-inner">
<div class="comments-writer--form">

                <div class="dzsap-comments--section">

                  <textarea name="comment-text" placeholder="Your comment.." type="text" class="comment-input"></textarea>

                </div>
                <div class="dzsap-comments--section">
                  <input placeholder="Your email.." name="comment-email" type="text" class="comment-input">
                </div>
                <div class="dzsap-comments--section overflow-and-fixed  ">

                  <div class="flex-grow-1   "><span
                    class="dzsap-comments--comment-form-label">commenting on </span> <span
                    class="dzsap-comments--comment-form-label-time">1:07</span></div>
                  <div class="flex-grow-0 margin-left-auto"><button class="submit-ap-comment dzs-button-dzsap float-right">&#10148; Submit</button></div>
                  <div class="clear"></div>

                </div>
              </div>

              <div class="comments-writer--avatar-con">
                <div class="comments-writer--avatar" style=""></div>
              </div>
              </div></div><span class="dzstooltip--close"><span
              class="label--x-button">&#10006;</span></span></div></div>`;


/**
 *
 * @param {DzsAudioPlayer} selfClass
 */
export const hide_comments_writer = function (selfClass) {


  var $ = jQuery;


  selfClass.cthis.removeClass('comments-writer-active');
  selfClass._commentsHolder.find('.dzstooltip-con.placeholder').remove();
  selfClass.$commentsWritter.removeClass('active');
  selfClass.$commentsWritter.css({
    'height': 0
  })


  if (selfClass.initOptions.parentgallery && $(selfClass.initOptions.parentgallery).get(0) !== undefined && $(selfClass.initOptions.parentgallery).get(0).api_handleResize !== undefined) {
    $(selfClass.initOptions.parentgallery).get(0).api_handleResize();
  }

  setTimeout(function () {

    selfClass.cthis.find('.comments-writter-temp-css,.dzsap-style-comments').remove();
  }, 300);
};

/**
 *
 * @param {DzsAudioPlayer} selfClass
 */
export const comments_setupCommentsInitial = function (selfClass) {


  var $ = jQuery;
  var o = selfClass.initOptions;


  if (selfClass.cthis.find('.the-comments').length > 0 && selfClass.cthis.find('.the-comments').eq(0).children().length > 0) {
    selfClass.$commentsChildren = selfClass.cthis.find('.the-comments').eq(0).children();
  }

  var str_comments_holder = '<div class="comments-holder">';


  if (o.skinwave_comments_links_to) {

    str_comments_holder += '<a href="' + o.skinwave_comments_links_to + '" target="_blank" class="the-bg"></a>';
  } else {

    str_comments_holder += '<div class="the-comments-holder-bg"><div class="the-avatar comments-avatar--placeholder"></div></div>';
  }


  str_comments_holder += '</div><div class="clear"></div>' + STRUCTURE_COMMENTS_WRITER;


  selfClass._scrubbar.appendOnce(str_comments_holder);
  selfClass._commentsHolder = selfClass.cthis.find('.comments-holder').eq(0);
  selfClass.$commentsWritter = selfClass.cthis.find('.dzstooltip--comments-writer').eq(0);


  comments_setupCommentsHolder(selfClass);


  selfClass._commentsHolder.on('click', function (e) {
    comments_handleClickCommentsBg(selfClass, this, e);
  });
  selfClass._commentsHolder.on('mousemove', function (e) {

    selfClass._commentsHolder.find('.comments-avatar--placeholder').css('left', `${getRelativeX(e.pageX, e.currentTarget) - 7}px`)

  });
  selfClass.$commentsWritter.find('.dzstooltip--close').on('click', (e) => {
    comments_handleClickCancel(selfClass, e);
  });
  selfClass.$commentsWritter.find('.submit-ap-comment').on('click', (e) => {
    comments_handleClickSubmit(selfClass, e);
  });
}


/**
 *
 * @param {DzsAudioPlayer} selfClass
 */
export const comments_setupCommentsHolder = function (selfClass) {


  var $ = jQuery;
  var o = selfClass.initOptions;

  if (selfClass._commentsHolder && selfClass.$commentsChildren) {


    selfClass.$commentsChildren.each(function () {

      var _c = $(this);


      if (o.skinwave_comments_process_in_php === 'on') {

        if (_c && _c.hasClass && _c.hasClass('dzstooltip-con')) {
          if (_c.find('.dzstooltip > .dzstooltip--inner').length) {

          } else {


            _c.find('.dzstooltip').wrapInner('<div class="dzstooltip--inner"></div>');


            _c.find('.the-avatar').addClass('tooltip-indicator');
            _c.find('.dzstooltip').before(_c.find('.tooltip-indicator'));
            _c.find('.dzstooltip').addClass('talign-start style-rounded color-dark-light');
          }
        }
      }

      selfClass._commentsHolder.append(_c);
    })
  }

}

function comments_updateCommentHolderTimerWhenReady(selfClass, percClickFromScrubWidth) {


  if (selfClass.timeModel.getVisualTotalTime()) {

    selfClass.$commentsWritter.find('.dzsap-comments--comment-form-label-time').html(dzsapHelpers.formatTime(percClickFromScrubWidth * selfClass.timeModel.getVisualTotalTime()));
  } else {
    setTimeout(() => {
      comments_updateCommentHolderTimerWhenReady(selfClass, percClickFromScrubWidth);
    }, 100);
  }

}

export const comments_handleClickCommentsBg = function (selfClass, argThis, e) {


  var $ = jQuery;
  var o = selfClass.initOptions;

  var $commentsHolder = $(argThis);

  var leftMouseX = parseInt(e.clientX, 10) - $commentsHolder.offset().left;
  let percClickFromScrubWidth = leftMouseX / $commentsHolder.width();


  selfClass.commentPositionPerc = `calc(${(percClickFromScrubWidth) * 100}% - 7px)`;


  comments_updateCommentHolderTimerWhenReady(selfClass, percClickFromScrubWidth);

  if (o.skinwave_comments_links_to) {
    return;
  }
  if (o.skinwave_comments_allow_post_if_not_logged_in === 'off' && !(window.dzsap_settings && window.dzsap_settings.comments_username)) {
    return false;
  }

  // -- start

  var sw = true;


  selfClass._commentsHolder.children().each(function () {
    var $commentElement = $(this);


    if ($commentElement.hasClass('placeholder') || $commentElement.hasClass('the-bg')) {
      return;
    }

    var lmx2 = $commentElement.offset().left - $commentsHolder.offset().left;


    if (Math.abs(lmx2 - leftMouseX) < 20) {
      selfClass._commentsHolder.find('.dzstooltip-con.placeholder').remove();
      sw = false;

      return false;
    }
  })


  if (!sw) {
    return false;
  }


  // todo: assign left on tooltip
  selfClass.$commentsWritter.css({
    'left': `${leftMouseX}px`
  })


  selfClass.$commentsWritter.css('top', ((parseInt(selfClass._commentsHolder.css('top'), 10) + 20) + 'px'))


  if (selfClass.$commentsWritter.hasClass('active') === false) {
    selfClass.$commentsWritter.addClass('active');
    selfClass.cthis.addClass('comments-writer-active');
  }


  if (window.dzsap_settings && window.dzsap_settings.comments_username) {
    selfClass.cthis.find('input[name=comment-email]').remove();
  } else {
    selfClass.$commentsWritter.find('.comments-writer--avatar-con').remove();
  }


  add_comments_placeholder(selfClass);


}

/**
 *
 * @param {DzsAudioPlayer} selfClass
 */
function add_comments_placeholder(selfClass) {

  var strCommentsAvatarUrl = '';

  if (window.dzsap_settings && window.dzsap_settings.comments_avatar) {
    strCommentsAvatarUrl = window.dzsap_settings.comments_avatar;
  }

  selfClass._commentsHolder.remove('.dzsap-style-comments');
  selfClass._commentsHolder.append('<style class="dzsap-style-comments">.dzstooltip-con:not(.placeholder) { opacity: 0.5; }</style>')
  selfClass._commentsHolder.find('.dzstooltip-con.placeholder').remove();
  selfClass._commentsHolder.append('<span class="dzstooltip-con placeholder" style="left:' + selfClass.commentPositionPerc + ';"><div class="the-avatar" style="background-image: url(' + strCommentsAvatarUrl + ')"></div></span>');
}

export const comments_handleClickCancel = function (selfClass, e) {
  hide_comments_writer(selfClass);
}


function comment_submit(selfClass, comment_text, comment_email, comment_username) {

  var $ = jQuery;
  var o = selfClass.initOptions;

  var comm_author = '';
  if (comment_email) {
    var regex_mail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (regex_mail.test(comment_email) == false) {
      alert('please insert email, your email is just used for gravatar. it will not be sent or stored anywhere');
      return false;
    }

    comm_author = String(comment_email).split('@')[0];


    if (selfClass.$commentsSelector) {

      selfClass.$commentsSelector.find('*[name=comment_email],*[name=comment_user]').remove();
    }

    if (!window.dzsap_settings) {
      window.dzsap_settings = {};
    }

    window.dzsap_settings.comments_username = comm_author;
    window.dzsap_settings.comments_avatar = 'https://secure.gravatar.com/avatar/' + window.MD5(String(selfClass.cthis.find('input[name=comment-email]').eq(0).val()).toLowerCase()) + '?s=20';
  }


  var aux = '';


  // -- process php

  aux += comment_text;


  selfClass.cthis.find('*[name=comment-text]').eq(0).val('');


  selfClass.cthis.find('.comments-writter-temp-css,.dzsap-style-comments').remove();


  (ajax_comment_publish.bind(selfClass))(aux);

  hide_comments_writer(selfClass);

  if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_player_commentSubmitted != undefined) {
    $(o.parentgallery).get(0).api_player_commentSubmitted();
  }

}


/**
 *
 * @param selfClass
 * @param e
 * @returns {boolean}
 */
export const comments_handleClickSubmit = function (selfClass, e) {

  var comment_email = '';

  if (selfClass.cthis.find('input[name=comment-email]').length) {
    comment_email = selfClass.cthis.find('input[name=comment-email]').eq(0).val();
  }


  comment_submit(selfClass, selfClass.cthis.find('*[name=comment-text]').eq(0).val(), comment_email);


  return false;
}


/**
 *
 * @param {DzsAudioPlayer} selfClass
 * @param e
 * @returns {boolean}
 */
export const comments_selector_event = function (selfClass, e) {
  var $ = jQuery;
  var _t = $(this);
  var _con = null;


  if (_t.parent().parent().hasClass('zoomsounds-comment-wrapper')) {
    _con = _t.parent().parent();
  }
  if (_t.parent().parent().parent().hasClass('zoomsounds-comment-wrapper')) {
    _con = _t.parent().parent().parent();
  }

  if (e.type == 'focusin') {


    var spx = selfClass.timeCurrent / selfClass.timeTotal * selfClass._commentsHolder.width();
    spx += 'px';


    selfClass.commentPositionPerc = `calc(${selfClass.timeCurrent / selfClass.timeTotal * 100}% - 7px)`;

    _con.addClass('active');

    add_comments_placeholder(selfClass);
  }
  if (e.type == 'focusout') {


  }
  if (e.type == 'click') {

    if (_t.hasClass('dzstooltip--close')) {

      _con.removeClass('active');
      _con.find('input').val('');
    }
    if (_t.hasClass('comments-btn-submit')) {


      var comment_email = '';

      if (_con.find('.comment_email').length) {
        comment_email = _con.find('.comment_email').eq(0).val();
      }


      comment_submit(selfClass, _con.find('.comment_text').eq(0).val(), comment_email);


      _con.removeClass('active');
      _con.find('input').val('');


      return false;
    }


  }
}

