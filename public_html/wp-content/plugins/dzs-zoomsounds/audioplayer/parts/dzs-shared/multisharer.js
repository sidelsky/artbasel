'use strict';


/**
 *
 * @param {string} stringShareLinkBlock
 * @param {string} urlTrack
 * @returns {string}
 */
function sanitize_socialShareBlock(stringShareLinkBlock, urlTrack){


  stringShareLinkBlock = stringShareLinkBlock.replace('{{shareurl}}', urlTrack);
  stringShareLinkBlock = stringShareLinkBlock.replace('{{replacewithcurrurl}}', urlTrack);
  stringShareLinkBlock = stringShareLinkBlock.replace('{{replacewithdataurl}}', urlTrack);

  return stringShareLinkBlock;
}

jQuery(document).ready(function ($) {

  window.dzsap_social_feed_for_social_networks = $('.dzsap-feed--social-networks').eq(0).html();
  window.dzsap_social_feed_for_share_link = $('.dzsap-feed--share-link').eq(0).html();
  window.dzsap_social_feed_for_embed_link = $('.dzsap-feed--embed-link').eq(0).html();


  setTimeout(function () {




    if (window.dzsap_multisharer_assets_loaded) {

    } else {
      if (window.dzsap_multisharer_assets_loaded !== true && window.loading_multi_sharer !== true) {

        // -- only if settings_php_handler is set


        // - -load script for lightbox
        window.loading_multi_sharer = true;



        setTimeout(function () {
          if (window.dzsap_box_main_con === null) {
            $('body').append('<div class="dzsap-main-con skin-default gallery-skin-default transition-slideup "> <div class="overlay-background"></div> <div class="box-mains-con"> <div class="box-main box-main-for-share" style=""> <div class="box-main-media-con transition-target"> <div class="close-btn-con"><span class="close-btn--icon">&times;</span></div> <div class="box-main-media type-inlinecontent" style="width: 530px; height: 280px;"><div class=" real-media" style=""><div class="hidden-content share-content" > <div class="social-networks-con"></div> <div class="share-link-con"></div> <div class="embed-link-con"></div> </div> </div> </div> <div class="box-main-under"></div> </div> </div> </div><!-- end .box-mains-con--> </div>');
            window.dzsap_box_main_con = $('.dzsap-main-con').eq(0);

          }

        }, 1000);


      }
    }

    // -- remove main con function
    $(document).on('click.dzsap_global_sharer', '.dzsap-main-con .close-btn-con,.dzsap-main-con .overlay-background', function () {


      var _c = $('.dzsap-main-con').eq(0);

      _c.removeClass('loading-item loaded-item');
    })

  }, 1000)



  $(document).on('click.dzsap_multisharer', '.dzsap-multisharer-but', click_open_embed_ultibox);

  function click_open_embed_ultibox(e, pargs) {

    var margs = {
      'call_from': 'default'


    };

    if (pargs) {
      margs = $.extend(margs, pargs);
    }



    open_dzsap_lightbox({
      'call_from': 'click_open_embed_ultibox'
      , 'lightbox_open': 'share'
      , 'overwrite_this': this
    });


    return false;
  }


  /**
   * call from player or gallery
   * @param pargs
   */
  function open_dzsap_lightbox(pargs) {

    var margs = {
      'call_from': 'default'
      , 'lightbox_open': 'share'
      , 'overwrite_this': null

    };

    if (pargs) {
      margs = $.extend(margs, pargs);
    }




    var $dzsapBoxMainCon = window.dzsap_box_main_con;
    var $playerBut = $(this);



    if (margs.overwrite_this) {
      $playerBut = $(margs.overwrite_this);
    }


    /** might be player, might be gallery */
    var $caller = null;
    if ($playerBut.data('cthis')) {
      $caller = $playerBut.data('cthis');
    }

    if ($caller) {

      window.dzsap_currplayer_from_share = $caller;
    } else {



      if ($playerBut.attr('data-post_id')) {
        window.dzsap_currplayer_from_share = $('.audioplayer[data-playerid="' + $playerBut.attr('data-post_id') + '"]').eq(0);
        $playerBut.data('cthis', window.dzsap_currplayer_from_share);
      } else {
        if ($playerBut.parent().parent().parent().parent().parent().parent().hasClass('audioplayer')) {

          window.dzsap_currplayer_from_share = $playerBut.parent().parent().parent().parent().parent().parent();
        }
      }
    }


    var stringSocialNetworksBlock = '';

    if (window.dzsap_social_feed_for_social_networks) {
      stringSocialNetworksBlock = window.dzsap_social_feed_for_social_networks;
    }

    // -- aux is feed from social_feed


    if (window.dzsap_box_main_con) {




      let urlTrack = '';


      urlTrack = window.location.href;
      if ($playerBut.attr('data-post-url')) {
        urlTrack = $playerBut.attr('data-post-url');
      }



      window.dzsap_box_main_con.find('.social-networks-con').html(sanitize_socialShareBlock(stringSocialNetworksBlock, urlTrack));




      let stringShareLinkBlock = '';
      if (window.dzsap_social_feed_for_share_link) {
        stringShareLinkBlock = window.dzsap_social_feed_for_share_link;
      }


      if (stringShareLinkBlock) {





        window.dzsap_box_main_con.find('.share-link-con').html(sanitize_socialShareBlock(stringShareLinkBlock, urlTrack));
      }

      var stringShareboxSocial = '';
      if (window.dzsap_social_feed_for_embed_link) {
        stringShareboxSocial = window.dzsap_social_feed_for_embed_link;
      }


      if (window.dzsap_currplayer_from_share && dzsap_currplayer_from_share.data('embed_code')) {



        if (stringShareboxSocial) {

          var replace_str = dzsap_currplayer_from_share.data('embed_code');


          if (replace_str.indexOf('&lt;') === -1) {
            replace_str = htmlEntities(replace_str);
          }
          stringShareboxSocial = stringShareboxSocial.replace('{{replacewithembedcode}}', (replace_str));
          $dzsapBoxMainCon.find('.embed-link-con').html(stringShareboxSocial);
        }

      }

      function selectText(arg) {

        if (document.selection) {
          var range = document.body.createTextRange();
          range.moveToElementText(arg);
          range.select();
        } else if (window.getSelection) {
          var range = document.createRange();
          range.selectNode(arg);
          window.getSelection().removeAllRanges();
          window.getSelection().addRange(range);
        }
      }



      $(document).on('click.dzsap', '.field-for-view', function () {

        selectText(this);


      });
      $dzsapBoxMainCon.addClass('loading-box-main-' + margs.lightbox_open);
      setTimeout(function () {
        $dzsapBoxMainCon.addClass('loading-item');
      }, 100);

      setTimeout(function () {
        $dzsapBoxMainCon.addClass('loaded-item');
      }, 200);


    } else {
      console.log('warning missing box-main');
    }
  }

})
