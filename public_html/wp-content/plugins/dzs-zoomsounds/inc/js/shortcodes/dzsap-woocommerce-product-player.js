'use strict';


jQuery(document).ready(function ($) {


  var singlePlayerPosition = $('.dzsap-feed-singlePlayerPosition').eq(0).text();
  var _body = $('body').eq(0);
  var loopPlayerOverlayClassInit = '.dzsap--go-to-thumboverlay--container';
  var loopPlayerOverlayClass = loopPlayerOverlayClassInit;
  var loopPlayerOverWriteClass = $('.dzsap-feed-loopPlayerOverWriteClass').eq(0).text();

  if (loopPlayerOverWriteClass) {
    loopPlayerOverlayClass = loopPlayerOverWriteClass;
  }


  if (_body.hasClass('single-product')) {
    // -- single product


    if (singlePlayerPosition == 'overlay') {
      var _c = $('.woocommerce-product-gallery__wrapper').eq(0);
      _c.append($('.go-to-thumboverlay').eq(0));
      var _c2 = $('.go-to-thumboverlay').eq(0);
      _c.css({

        'position': 'relative'
        , 'display': 'block'
      })
      _c2.css({
        'position': 'absolute'
        , 'width': '100%'
        , 'height': '100%'
        , 'top': '0'
        , 'left': '0'
      })
      _c.append($('.go-after-thumboverlay').eq(0));
      var _c2 = $('.go-after-thumboverlay').eq(0);
      _c2.css({});
    }
  } else {


    // -- archive shop ( try to position overlays )
    $('.go-to-thumboverlay').each(function () {
      var _t = $(this);
      if (_t.siblings(loopPlayerOverlayClass).length) {
        _t.siblings(loopPlayerOverlayClass).eq(0).append(_t);
      }
      if (loopPlayerOverlayClass !== loopPlayerOverlayClassInit) {

        if (_t.parent().find(loopPlayerOverlayClass).length) {
          _t.parent().find(loopPlayerOverlayClass).eq(0).append(_t);
        }else{

          if (_t.parent().siblings(loopPlayerOverlayClass).length) {
            _t.parent().siblings(loopPlayerOverlayClass).eq(0).append(_t);
          }
        }

      }

      if (_t.siblings('.wp-post-image').length) {
        _t.parent().css({
          'position': 'relative'
          , 'display': 'block'
        })

        const cssForOverlay = {
          'position': 'absolute'
          , 'width': '100%'
          , 'height': '100%'
          , 'top': '0'
          , 'left': '0'
        };

        if (_t.siblings('.wp-post-image').length) {
          cssForOverlay.height = _t.siblings('.wp-post-image').eq(0).height();
        }
        _t.css(cssForOverlay);
      }

    });


  }
})