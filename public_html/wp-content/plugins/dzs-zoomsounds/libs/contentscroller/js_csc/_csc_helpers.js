exports.init_sanitizeInitialOptions = function (selfClass, o) {


  selfClass.design_itemwidth = o.design_itemwidth;
  if (String(o.design_itemwidth) !== 'auto' && String(o.design_itemwidth).indexOf("%") === -1) {
    selfClass.design_itemwidth = parseInt(o.design_itemwidth, 10);
  }


  o.per_row = parseInt(o.per_row, 10);
  selfClass.responsive_per_row = o.per_row;
  o.design_itemheight = parseInt(o.design_itemheight, 10);

  if (isNaN(Number(o.design_arrowsize)) === false) {
    o.design_arrowsize = Number(o.design_arrowsize);
  }
  if (isNaN(Number(selfClass.cthis.attr('data-margin'))) === false) {
    selfClass.margin = Number(selfClass.cthis.attr('data-margin'));
  }

  o.settings_slideshowTime = parseInt(o.settings_slideshowTime, 10);
  o.design_forceitemwidth = parseInt(o.design_forceitemwidth, 10);
  selfClass.slideshowTime = o.settings_slideshowTime;

  // -- if we have only one, we will allow vertical transitions
  if (o.settings_direction === 'vertical' && o.settings_onlyone !== 'on') {
    o.settings_autoHeight = 'off';
  }

  if (o.nav_type === 'slide') {
    o.design_bulletspos = 'none';
  }

  selfClass.totalComponentHeight = selfClass.cthis.outerHeight(false);

  if (selfClass.cthis.attr('class').indexOf("skin-") === -1) {
    selfClass.cthis.addClass(o.settings_skin);
  }


  if (selfClass.cthis.attr('id')) {
    selfClass.componentId = selfClass.cthis.attr('id');
  }




  if (selfClass.responsive_per_row === 1) {
    o.settings_onlyone = 'on';
  }

  if(o.settings_onlyone!=='on'){
    o.settings_transition = 'slide';
  }

  selfClass.cthis.addClass('only-one-' + o.settings_onlyone);


  selfClass.cthis.css('opacity', '');

  selfClass.cthis.addClass('mode-' + o.settings_mode);
  selfClass.cthis.addClass('nav-type-' + o.nav_type);
  selfClass.cthis.addClass('transition-' + o.settings_transition);
}

exports.treatCscItemVplayer = function(_cscItem, _t){

  if (_t.hasClass('vplayer')) {
    _cscItem.data('', 500);
    if (_t.attr('data-width-for-gallery')) {
      _cscItem.data('naturalWidth', _t.attr('data-width-for-gallery'));
    } else {
      _cscItem.data('naturalWidth', 800);
    }
    if (_t.attr('data-height-for-gallery')) {
      _cscItem.data('naturalHeight', _t.attr('data-height-for-gallery'));
    } else {
      _cscItem.data('naturalHeight', 800);
    }
  }

  if (_t.parent().parent().hasClass('wipeout-wrapper')) {
    _t.parent().parent().addClass('is-video');
  }
}
exports.determineConInLoadedImage = function(_con, _t){

  if(_con!==null){
    return _con;
  }


  if (_t.parent().hasClass('csc-item')) {
    return _t.parent();
  }
  if (_t.parent().parent().hasClass('csc-item')) {
    return _t.parent().parent();
  }
  if (_t.parent().parent().parent().hasClass('csc-item')) {
    return _t.parent().parent().parent();
  }
  if (_t.parent().parent().parent().parent().hasClass('csc-item')) {
    return _t.parent().parent().parent().parent();
  }
}

function is_ios() {
  return ((navigator.platform.indexOf("iPhone") !== -1) || (navigator.platform.indexOf("iPod") !== -1) || (navigator.platform.indexOf("iPad") !== -1)
  );
}

function is_android() {

  return (navigator.platform.indexOf("Android") !== -1);
}

exports.is_ios = is_ios;
exports.is_android = is_android;

exports.conditionalAddSwiping = function (o, selfClass, $) {

  if (o.settings_swipe === 'on') {
    if ((o.settings_swipeOnDesktopsToo === 'on' || (o.settings_swipeOnDesktopsToo === 'off' && (is_ios() || is_android())))) {
      this.supportForSwiping(selfClass, $);
    }
  }
}
exports.supportForSwiping = function (selfClass, $) {

  var target_swiper = selfClass.cthis;

  selfClass.cthis.addClass('swipe-enabled');

  var down_x = 0
    , up_x = 0
    , screen_mousex = 0
    , dragging = false
    , def_x = 0
    , targetPositionX = 0
    , _swiper = _thumbsCon
  ;
  var busy = false
    , paused_roll = false
  ;


  var _t = selfClass.cthis;
  var sw_tw = selfClass.totalItemContainerWidth;
  var sw_ctw = selfClass.totalItemContainerClipWidth;

  _swiper.on('mousedown', function (e) {
    if (e.which == 3) {
      return false;
    }
    target_swiper = selfClass.cthis;
    down_x = e.screenX;
    def_x = 0;
    dragging = true;
    paused_roll = true;
    selfClass.cthis.addClass('closedhand');
    return false;
  });

  $(document).on('mousemove', function (e) {
    if (dragging == false) {

    } else {
      screen_mousex = e.screenX;

      if (selfClass.cthis.hasClass('transition-slide')) {
        targetPositionX = selfClass.currPageX + def_x + (screen_mousex - down_x);
        if (targetPositionX > 0) {
          targetPositionX /= 2;
        }

        // -- dunno what sw_ctw suppose to mean so I added if only mode
        if (o.settings_mode == 'onlyoneitem' && targetPositionX < -sw_ctw + sw_tw) {

          targetPositionX = targetPositionX - ((targetPositionX + sw_ctw - sw_tw) / 2);
        }

        _swiper.css('left', targetPositionX);
      }
    }
  });
  $(document).on('mouseup', function (e) {

    selfClass.cthis.removeClass('closedhand');
    up_x = e.screenX;
    dragging = false;
    checkswipe();

    paused_roll = false;
    return false;

  });
  _swiper.on('click', function (e) {

    if (Math.abs((down_x - up_x)) > 50) {
      return false;
    }
  });


  _swiper.on('touchstart', function (e) {
    target_swiper = selfClass.cthis;
    down_x = e.originalEvent.touches[0].pageX;


    dragging = true;

    paused_roll = true;
    selfClass.cthis.addClass('closedhand');
  });
  _swiper.on('touchmove', function (e) {
    if (dragging) {
      up_x = e.originalEvent.touches[0].pageX;

      if (selfClass.cthis.hasClass('transition-slide')) {
        targetPositionX = selfClass.currPageX + def_x + (up_x - down_x);
        if (targetPositionX > 0) {
          targetPositionX /= 2;
        }
        if (targetPositionX < -sw_ctw + sw_tw) {
          targetPositionX = targetPositionX - ((targetPositionX + sw_ctw - sw_tw) / 2);
        }

        _swiper.css('left', targetPositionX);
      }
    }
  });
  _swiper.on('touchend', function (e) {
    dragging = false;
    checkswipe();
    paused_roll = false;
    selfClass.cthis.removeClass('closedhand');
  });

  function checkswipe() {
    if (target_swiper != selfClass.cthis) {
      return;
    }
    var sw = false;


    if (up_x - down_x < -(sw_tw / 5)) {

      slide_right();
      sw = true;
    }
    if (up_x - down_x > (sw_tw / 5)) {
      slide_left();
      sw = true;
    }

    if (sw == false) {
      if (selfClass.cthis.hasClass('transition-slide')) {
        _swiper.css({left: selfClass.currPageX});
      }
    }
    target_swiper = undefined;
  }

  function slide_left() {
    if (selfClass.currPage < 1) {
      if (selfClass.cthis.hasClass('transition-slide')) {
        _swiper.css({left: selfClass.currPageX});
      }
      return;
    }
    selfClass.gotoPrevPage();
  }

  function slide_right() {

    if (selfClass.currPage > selfClass.pag_total_pages - 2) {
      if (selfClass.cthis.hasClass('transition-slide')) {
        _swiper.css({left: selfClass.currPageX});
      }
      return;
    }
    selfClass.gotoNextPage();
  }

}
exports.calculateDimensionsForEachItem = function (selfClass, _thumbsCon, responsive_per_row, o) {
  var $ = jQuery;
  _thumbsCon.children().each(function () {
    var _t = $(this);
    var perc_part = 4;


    if (_t.hasClass('csc-row-part')) {
      if (_t.hasClass('csc-row-manual') == false) {
        for (let i = 0; i < 7; i++) {
          _t.removeClass('csc-row-part-' + i)
        }
      }

    } else {
    }
    if (_t.hasClass('csc-row-manual') == false) {

      _t.addClass('csc-row-part csc-row-part-' + responsive_per_row);
    }





    for (let i = 0; i < 7; i++) {
      perc_part = i;
      if (_t.hasClass('csc-row-part-' + perc_part)) {
        if (o.settings_direction === 'horizontal') {
          _t.outerWidth((selfClass.totalItemContainerClipWidth + selfClass.margin) / perc_part);
        }
        if (o.settings_direction === 'vertical') {
          _t.outerHeight((selfClass.totalItemContainerClipHeight + selfClass.margin) / perc_part);
        }

      }
    }

  })

}
exports.determineTotalNumberOfPages = function (selfClass, nrItems, o) {


  selfClass.pag_total_pages = Math.ceil(selfClass.totalItemContainerWidth / selfClass.totalItemContainerClipWidth);
  if (o.settings_direction === 'vertical') {
    selfClass.pag_total_pages = Math.ceil(selfClass.totalItemContainerHeight / selfClass.totalComponentHeight);
    if (o.settings_onlyone === 'on') {
      selfClass.pag_total_pages = nrItems;
    }
  }

  if (o.settings_direction === 'horizontal') {
    selfClass.pag_total_pages = Math.ceil(selfClass.totalItemContainerWidth / selfClass.totalComponentWidth);

    if (o.settings_onlyone === 'on') {
      selfClass.pag_total_pages = nrItems;
    }
  }

}
exports.determineIfHasSpaceToScroll = function (selfClass, _thumbsCon, _thumbsClip, o) {

  if (o.settings_direction === 'horizontal') {


    selfClass.totalItemContainerWidth = _thumbsCon.outerWidth(); // --- swiper total width
    selfClass.totalItemContainerClipWidth = _thumbsClip.width(); // --- swiper image width ( visible )


    if (selfClass.totalItemContainerWidth > selfClass.totalItemContainerClipWidth) {

      selfClass.hasSpaceToScroll = true;


      selfClass.cthis.addClass('has-space-to-scroll');
      selfClass.cthis.removeClass('no-has-space-to-scroll');
    } else {
      selfClass.hasSpaceToScroll = false;

      selfClass.cthis.removeClass('has-space-to-scroll');
      selfClass.cthis.addClass('no-has-space-to-scroll');


    }
  }


  if (o.settings_direction === 'vertical') {


    selfClass.totalItemContainerHeight = _thumbsCon.outerHeight(); // --- swiper total width
    selfClass.totalItemContainerClipHeight = _thumbsClip.height(); // --- swiper image width ( visible )


    if (selfClass.totalItemContainerHeight > selfClass.totalItemContainerClipHeight) {

      selfClass.hasSpaceToScroll = true;

      selfClass.cthis.addClass('has-space-to-scroll');
      selfClass.cthis.removeClass('no-has-space-to-scroll');
    } else {
      selfClass.hasSpaceToScroll = false;

      selfClass.cthis.removeClass('has-space-to-scroll');
      selfClass.cthis.addClass('no-has-space-to-scroll');


    }
  }
}
