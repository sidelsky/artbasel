const {sanitizeToCssPx} = require("../../js_common/_dzs_helpers");
var defaultOptions = require('./configs/_navigationSettings').defaultSettings;
var helpersDZSVG = require('../_dzsvg_helpers');

class DzsNavigation {



  /**
   *
   * @param parentClass
   * @param argOptions
   * @param $
   */
  constructor(parentClass, argOptions, $) {



    this.$ = $;
    this.parentClass = parentClass;
    this.initOptions = null;
    this.navAttributes = null;
    this.$mainArea = null;
    this.$mainNavigation = null;
    this.$mainNavigationClipped = null;
    this.$mainNavigationItemsContainer = null;
    this.$containerComponent = null;
    this.ultraResponsive = false;
    this.menuPosition = null;
    this.argOptions = argOptions;

    // -- dimensions
    this.totalItemsWidth = 0;
    this.totalItemsHeight = 0;
    this.totalAreaWidth = 0;
    this.totalAreaHeight = 0;
    this.mainAreaHeight = 0;

    // -- thumbsAndArrows
    this.currPage = 0;
    this.nav_max_pages = 0;
    this.navigation_mainDimensionTotalSize = 0;
    this.navigation_mainDimensionClipSize = 0;
    this.navigation_mainDimensionItemSize = 0;

    this.initClass();

  }

  initClass() {
    const selfInstance = this;
    const parentClass = this.parentClass;
    const newOptions = Object.assign(Object.assign(defaultOptions, {}), this.argOptions);
    this.initOptions = {...newOptions};
    this.navAttributes = {...newOptions};
    this.menuPosition = this.navAttributes.menuPosition;



    const navAttributes = this.navAttributes;



    if(navAttributes.menuPosition==='right' || navAttributes.menuPosition==='left'){
      navAttributes.navigation_direction = 'vertical';
    }
    if(navAttributes.menuPosition==='bottom' || navAttributes.menuPosition==='top'){
      if(navAttributes.navigation_direction==='auto'){
        navAttributes.navigation_direction = 'horizontal';
      }
    }

    var is_navThumbsDirectionVertical = false
      , is_navThumbsDirectionHorizontal = false
      , menu_move_locked = false
      , navMain_mousex = 0
      , navMain_mousey = 0
      , viewMaxH
    ;


    var duration_viy = 20
      , target_viy = 0
      , target_vix = 0
      , begin_viy = 0
      , begin_vix = 0
      , finish_viy = 0
      , finish_vix = 0
      , change_viy = 0
      , change_vix = 0
    ;


    var viewIndex = 0
      , viewMaxV
      , offsetBuffer = 70;
    ;


    if (this.parentClass) {
      if (this.parentClass.cgallery) {
        this.containerComponent = this.parentClass.cgallery;
      } else {
        if (this.parentClass.cthis) {
          this.containerComponent = this.parentClass.cthis;
        }
      }
    }


    if (navAttributes.menuPosition === 'left' || navAttributes.menuPosition === 'right') {

      if (isNaN(Number(selfInstance.initOptions.menuItemWidth)) || selfInstance.initOptions.menuItemWidth === 'default') {
        navAttributes.menuItemWidth = 254;
      }
    }




    setupStructure();


    setTimeout(init_navIsReady, 100);

    function init_navIsReady() {

      if (navAttributes.navigationType === 'hover') {

        handleEnterFrame();
      }



      if (navAttributes.isAutoScrollToCurrent === true) {
        if (navAttributes.navigationType === 'hover') {


          if (navAttributes.menuPosition === 'right' || navAttributes.menuPosition === 'left') {

            is_navThumbsDirectionVertical = true;
            is_navThumbsDirectionHorizontal = false;
          }


          if (navAttributes.menuPosition === 'top' || navAttributes.menuPosition === 'bottom') {

            is_navThumbsDirectionVertical = false;
            is_navThumbsDirectionHorizontal = true;
          }



          setTimeout(function () {
            animate_to_curr_thumb();

          }, 20);
        }
      }

    }


    function setupStructure() {


      var aux_main_navigation_str = '<div class="main-navigation dzs-navigation--type-' + selfInstance.initOptions.navigationType + '"><div class="navMain videogallery--navigation--clipped-container navigation--clipped-container"><div class="videogallery--navigation-container navigation--total-container">';
      aux_main_navigation_str += '</div></div></div>';

      parentClass.$navigationAndMainArea.addClass(`navPosition-${navAttributes.menuPosition} navType-${navAttributes.navigationType}`);

      parentClass.$navigationAndMainArea.append('<div class="sliderMain media--main-area"><div class="sliderCon"></div></div>');
      parentClass.$navigationAndMainArea.append(aux_main_navigation_str);


      selfInstance.$mainArea = parentClass.$navigationAndMainArea.find('.media--main-area');
      selfInstance.$mainNavigation = parentClass.$navigationAndMainArea.find('.main-navigation');
      selfInstance.$mainNavigationClipped = selfInstance.$mainNavigation.find('.navigation--clipped-container');
      selfInstance.$mainNavigationItemsContainer = selfInstance.$mainNavigation.find('.navigation--total-container');


      if (navAttributes.menuItemWidth === 'default') {
        navAttributes.menuItemWidth = '';
      }
      if (navAttributes.menuItemHeight === 'default') {
        navAttributes.menuItemHeight = '';
      }


      if (navAttributes.menuPosition === 'top' || navAttributes.menuPosition === 'bottom') {

      }


      if (navAttributes.menuPosition === 'top') {
        selfInstance.$mainArea.before(selfInstance.$mainNavigation);
      }



      if (navAttributes.navigationType === 'outer') {
        selfInstance.$mainNavigationClipped.children().addClass('dzs-layout-item');
      }


      if (navAttributes.navigationType === 'thumbsAndArrows') {
        selfInstance.$mainNavigation.prepend('<div class="nav--thumbsAndArrows--arrow thumbs-arrow-left arrow-is-inactive"></div>');
        selfInstance.$mainNavigation.append('<div class="nav--thumbsAndArrows--arrow thumbs-arrow-right"></div>');

        selfInstance.$mainNavigation.find('.thumbs-arrow-left,.thumbs-arrow-right').bind('click', handleClick_navigationArrow);
      }
    }

    function handleClick_navigationArrow() {


      var $t = jQuery(this);

      if ($t.hasClass('thumbs-arrow-left')) {
        gotoPrevPage();
      }
      if ($t.hasClass('thumbs-arrow-right')) {
        gotoNextPage();
      }
    }



    function gotoNextPage() {
      var tempPage = selfInstance.currPage;

      tempPage++;
      nav_thumbsandarrows_gotoPage(tempPage);

    }

    function gotoPrevPage() {
      if (selfInstance.currPage === 0)
        return;

      selfInstance.currPage--;
      nav_thumbsandarrows_gotoPage(selfInstance.currPage);

    }

    /**
     * called only from thumbsandarrows
     * @param arg
     */
    function nav_thumbsandarrows_gotoPage(arg) {

      if (arg > selfInstance.nav_max_pages || navAttributes.navigationType !== 'thumbsAndArrows') {
        return;
      }

      selfInstance.$mainNavigation.find('.nav--thumbsAndArrows--arrow').removeClass('arrow-is-inactive');

      if (arg === 0) {
        selfInstance.$mainNavigation.find('.thumbs-arrow-left').addClass('arrow-is-inactive');
      }
      if (arg >= selfInstance.nav_max_pages) {
        selfInstance.$mainNavigation.find('.thumbs-arrow-right').addClass('arrow-is-inactive');
      }

      if (arg >= selfInstance.nav_max_pages) {


        if (navAttributes.navigation_direction === "vertical") {
          selfInstance.$mainNavigationItemsContainer.animate({
            'top': -(selfInstance.navigation_mainDimensionTotalSize - selfInstance.navigation_mainDimensionClipSize)
            , 'left': 0
          }, {
            duration: 400,
            queue: false
          });
        }
        if (navAttributes.navigation_direction === "horizontal") {
          selfInstance.$mainNavigationItemsContainer.animate({
            'left': -(selfInstance.navigation_mainDimensionTotalSize - selfInstance.navigation_mainDimensionClipSize)
            , 'top': 0
          }, {
            duration: 400,
            queue: false
          });
        }

      } else {
        if (navAttributes.navigation_direction === "vertical") {
          var firstItemInSightWidth = selfInstance.$mainNavigationItemsContainer.children().eq(selfInstance.currPage).width();
          selfInstance.$mainNavigationItemsContainer.animate({
            'top': firstItemInSightWidth * -arg
            , 'left': 0
          }, {
            duration: 400,
            queue: false
          });

        }

        if (navAttributes.navigation_direction === "horizontal") {

          var firstItemInSightWidth = selfInstance.$mainNavigationItemsContainer.children().eq(selfInstance.currPage).width();
          selfInstance.$mainNavigationItemsContainer.animate({
            'left': firstItemInSightWidth * -arg
            , 'top': 0
          }, {
            duration: 400,
            queue: false
          });
        }

      }

      selfInstance.currPage = arg;
    }
    selfInstance.nav_thumbsandarrows_gotoPage = nav_thumbsandarrows_gotoPage;


    selfInstance.handleMouse = handleMouse;

    function handleMouse(e) {
      //handle mouse for the parentClass.$navigationItemsContainer element
      var menuAnimationSw = true;

      navMain_mousey = (e.pageY - selfInstance.$mainNavigationClipped.offset().top)
      navMain_mousex = (e.pageX - selfInstance.$mainNavigationClipped.offset().left)




      if (helpersDZSVG.is_ios() === false && helpersDZSVG.is_android() === false) {


        if (menu_move_locked) {
          return false;
        }

        if (navAttributes.navigation_direction === "vertical") {
          is_navThumbsDirectionVertical = true;
          is_navThumbsDirectionHorizontal = false;


          navigation_prepareAnimateMenuY(navMain_mousey, {

            called_from: "handleMouse"
          });


        }
        if (navAttributes.navigation_direction === "horizontal") {


          viewMaxH = selfInstance.totalItemsWidth - selfInstance.totalAreaWidth;
          finish_vix = ((navMain_mousex / selfInstance.totalAreaWidth) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer);
          finish_vix = parseInt(finish_vix, 10);





          if (finish_vix > 0)
            finish_vix = 0;
          if (finish_vix < -viewMaxH)
            finish_vix = -viewMaxH;




          is_navThumbsDirectionVertical = false;
          is_navThumbsDirectionHorizontal = true;

          if (navAttributes.isUseEasing) {

          } else {


            animate_menu_x(viewIndex);
          }

        }

      } else {

        return false;
      }

    }


    function handleEnterFrame() {



      if (isNaN(target_viy)) {
        target_viy = 0;
      }

      if (duration_viy === 0) {
        requestAnimFrame(handleEnterFrame);
        return false;
      }

      if (is_navThumbsDirectionVertical) {
        begin_viy = target_viy;
        change_viy = finish_viy - begin_viy;


        target_viy = Number(Math.easeIn(1, begin_viy, change_viy, duration_viy).toFixed(4));
        ;




        if (helpersDZSVG.is_ios() === false && helpersDZSVG.is_android() === false) {
          parentClass.$navigationItemsContainer.css({
            'transform': 'translate3d(0,' + target_viy + 'px,0)'
          });
        }

      }


      if (is_navThumbsDirectionHorizontal) {
        begin_vix = target_vix;
        change_vix = finish_vix - begin_vix;



        target_vix = Number(Math.easeIn(1, begin_vix, change_vix, duration_viy).toFixed(4));
        ;




        if (helpersDZSVG.is_ios() === false && helpersDZSVG.is_android() === false) {
          parentClass.$navigationItemsContainer.css({
            'transform': 'translate3d(' + target_vix + 'px,0,0)'
          });
        }

      }



      requestAnimFrame(handleEnterFrame);
    }


    function navigation_prepareAnimateMenuX(navMain_mousex) {
      let viewMaxH = (selfInstance.totalItemsWidth) - selfInstance.totalAreaWidth;
      finish_vix = (navMain_mousex / selfInstance.totalAreaWidth) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer;







      if (finish_vix > 0)
        finish_vix = 0;
      if (finish_vix < -viewMaxH)
        finish_vix = -viewMaxH;


      if (navAttributes.isUseEasing) {

      } else {


        animate_menu_x(viewIndex);
      }
    }

    function navigation_prepareAnimateMenuY(navMain_mousey, pargs) {


      var margs = {

        called_from: "default"
      }

      let viewMaxH = (selfInstance.totalItemsHeight) - selfInstance.totalAreaHeight;
      finish_viy = (navMain_mousey / selfInstance.totalAreaHeight) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer;











      if (finish_viy > 0)
        finish_viy = 0;
      if (finish_viy < -viewMaxH)
        finish_viy = -viewMaxH;


      if (helpersDZSVG.is_touch_device()) {
        navAttributes.isUseEasing = false;
      }

      if (navAttributes.isUseEasing) {

      } else {


        animate_menu_y(viewIndex);
      }
    }


    selfInstance.animate_to_curr_thumb = animate_to_curr_thumb;
    function animate_to_curr_thumb(pargs) {


      var margs = {
        caller: null
        , 'called_from': 'default'
      }



      if (helpersDZSVG.is_touch_device()) {

      }





      if (navAttributes.navigationType === 'hover') {


        var _nta = parentClass.$navigationItemsContainer.find('.dzs-navigation--item').eq(0);


        if (parentClass.$navigationItemsContainer.find('.dzs-navigation--item.active').length) {
          _nta = parentClass.$navigationItemsContainer.find('.dzs-navigation--item.active').eq(0);
        }


        var rat = (_nta.offset().top - parentClass.$navigationItemsContainer.offset().top) / (parentClass.$navigationItemsContainer.outerHeight() - selfInstance.$mainNavigationClipped.parent().outerHeight());


        if (is_navThumbsDirectionVertical) {

          if (parentClass.$navigationItemsContainer.outerHeight() > selfInstance.$mainNavigationClipped.parent().outerHeight()) {

            animate_menu_y(rat * (parentClass.$navigationItemsContainer.outerHeight() - selfInstance.$mainNavigationClipped.parent().outerHeight()), {
              'called_from': 'animate_to_curr_thumb'
            });
          }
        } else {

          if (is_navThumbsDirectionHorizontal) {

            rat = (_nta.offset().left - parentClass.$navigationItemsContainer.offset().left) / (parentClass.$navigationItemsContainer.outerWidth() - selfInstance.$mainNavigationClipped.outerWidth());


            if (is_navThumbsDirectionHorizontal) {
              navigation_prepareAnimateMenuX(rat * selfInstance.$mainNavigationClipped.outerWidth());
            }
          }


        }
      }
      if (navAttributes.navigationType === 'scroller') {

        var aux = 0;

        if (parentClass.$navigationItemsContainer.find('.dzs-navigation--item.active').length) {

          aux = parentClass.$navigationItemsContainer.find('.dzs-navigation--item.active').offset().top - parentClass.$navigationItemsContainer.eq(0).offset().top;





          setTimeout(function () {

            if (typeof selfInstance.$mainNavigationClipped.get(0).api_scrolly_to != 'undefined') {

              selfInstance.$mainNavigationClipped.get(0).api_scrolly_to(aux);
            }
          }, 300);
        }

      }
    }


    function animate_menu_x(viewIndex) {




      if (helpersDZSVG.is_ios() === false && helpersDZSVG.is_android() === false) {
        if (navAttributes.isUseEasing !== false) {

          if (jQuery('html').hasClass('supports-translate')) {


            parentClass.$navigationItemsContainer.css({
              '-webkit-transform': 'translate3d(' + finish_vix + 'px, ' + 0 + 'px, 0)'
              , 'transform': 'translate3d(' + finish_vix + 'px, ' + 0 + 'px, 0)'
            });
          } else {
            parentClass.$navigationItemsContainer.css({
              'left': finish_vix
            });
          }
        }


      }
    }


    function animate_menu_y(viewIndex, pargs) {


      // -- positive number viewIndexX
      var margs = {

        called_from: "default"
      }

      if (pargs) {
        margs = jQuery.extend(margs, pargs);
      }




      if (helpersDZSVG.is_touch_device() === false) {



        if (navAttributes.isUseEasing !== true) {

          parentClass.$navigationItemsContainer.css({
            'transform': 'translate3d(0, ' + (finish_viy) + 'px, 0)'
          });
        } else {
          if ((-finish_viy) < selfInstance.navigation_mainDimensionTotalSize - selfInstance.$mainNavigation.outerHeight()) {

            finish_viy = -(selfInstance.navigation_mainDimensionTotalSize - selfInstance.$mainNavigation.outerHeight());

          }
          finish_viy = -viewIndex;
        }


      } else {
        if (margs.called_from === 'animate_to_curr_thumb') {


          setTimeout(function () {

            selfInstance.$mainNavigation.animate({'scrollTop': viewIndex});
            selfInstance.$mainNavigation.scrollTop(viewIndex);
          }, 1500);
        }
      }
    }


  }

  calculateDims(pargs={}) {

    const calculateDimsArgs = Object.assign({
      forceMainAreaHeight: null
    }, pargs)




    const selfInstance = this;
    const parentClass = this.parentClass;
    const navAttributes = this.navAttributes;

    selfInstance.mainAreaHeight = calculateDimsArgs.forceMainAreaHeight ? calculateDimsArgs.forceMainAreaHeight : selfInstance.$mainArea.outerHeight();


    let totalAreaHeightPixels = 0;
    selfInstance.totalAreaWidth = parentClass.$navigationAndMainArea.outerWidth();
    selfInstance.totalAreaHeight = parentClass.$navigationAndMainArea.outerHeight();
    let mainAreaWidth = selfInstance.$mainArea.width();
    let mainNavigationWidth = selfInstance.$mainNavigation.width();
    let mainNavigationDesiredWidth = navAttributes.menuItemWidth;

    if(navAttributes.navigation_direction==='vertical'){
      selfInstance.navigation_mainDimensionTotalSize = selfInstance.$mainNavigationItemsContainer.height();
      selfInstance.navigation_mainDimensionClipSize = selfInstance.$mainNavigationClipped.height();
      selfInstance.navigation_mainDimensionItemSize = selfInstance.$mainNavigationItemsContainer.children().eq(0).height();
      selfInstance.nav_max_pages = Math.round((selfInstance.navigation_mainDimensionTotalSize - selfInstance.navigation_mainDimensionClipSize)/selfInstance.navigation_mainDimensionItemSize);;
    }

    if(navAttributes.navigation_direction==='horizontal'){
      selfInstance.navigation_mainDimensionTotalSize = selfInstance.$mainNavigationItemsContainer.width();
      selfInstance.navigation_mainDimensionClipSize = selfInstance.$mainNavigationClipped.width();
      selfInstance.navigation_mainDimensionItemSize = selfInstance.$mainNavigationItemsContainer.children().eq(0).width();
      selfInstance.nav_max_pages = Math.round((selfInstance.navigation_mainDimensionTotalSize - selfInstance.navigation_mainDimensionClipSize)/selfInstance.navigation_mainDimensionItemSize);;
    }

    parentClass.$navigationAndMainArea.children().each(function () {
      var $t = jQuery(this);


      totalAreaHeightPixels += $t.get(0).scrollHeight;
    })





    // -- ultra-responsive
    if (this.parentClass.initOptions.settings_disableVideo !== 'on' && (navAttributes.menuPosition === 'right' || navAttributes.menuPosition === 'left')) {


      if (selfInstance.totalAreaWidth - mainNavigationDesiredWidth < mainNavigationDesiredWidth) {

        if (selfInstance.containerComponent) {
          selfInstance.containerComponent.addClass('ultra-responsive');
        }
        parentClass.$navigationAndMainArea.addClass('nav-is-ultra-responsive');
        selfInstance.ultraResponsive = true;
      } else {

        parentClass.$navigationAndMainArea.removeClass('nav-is-ultra-responsive');
        selfInstance.ultraResponsive = false;
      }
    }


    if (navAttributes.menuPosition === 'top' || navAttributes.menuPosition === 'bottom') {






    }
    if (navAttributes.menuPosition === 'right' || navAttributes.menuPosition === 'left') {

      if (!selfInstance.ultraResponsive) {

        if (navAttributes.menuItemWidth) {

          selfInstance.$mainNavigation.css({
            'flex': `0 0 ${sanitizeToCssPx(navAttributes.menuItemWidth)}`
          })
        }

        if (selfInstance.initOptions.isSyncMainAreaAndNavigatinoAreas) {
          selfInstance.$mainNavigation.height(selfInstance.mainAreaHeight);
        }
      } else {

        selfInstance.$mainNavigation.css({
          'flex': `0 0 auto`
        })
      }

    }


    let navWidth = 0;


    selfInstance.totalItemsWidth = parentClass.$navigationItemsContainer.outerWidth();
    selfInstance.totalItemsHeight = parentClass.$navigationItemsContainer.outerHeight();

    // -- hover
    if (navAttributes.navigationType === 'hover') {
      if (navAttributes.navigation_direction === 'horizontal') {

        navWidth = 0;
        parentClass.$navigationItemsContainer.children().each(function () {
          var $t = jQuery(this);
          navWidth += $t.outerWidth(true);
        });


        if (navWidth > selfInstance.totalAreaWidth) {
          selfInstance.$mainNavigation.unbind('mousemove', selfInstance.handleMouse);
          selfInstance.$mainNavigation.bind('mousemove', selfInstance.handleMouse);
          selfInstance.containerComponent.removeClass('navWidth-bigger-then-totalWidth')

        } else {

          selfInstance.containerComponent.addClass('navWidth-bigger-then-totalWidth')
          parentClass.$navigationItemsContainer.css({'left': ''})
          selfInstance.$mainNavigation.unbind('mousemove', selfInstance.handleMouse);

        }
      }
      if (navAttributes.navigation_direction === 'vertical') {






        if (selfInstance.totalItemsHeight > selfInstance.totalAreaHeight) {
          selfInstance.$mainNavigation.unbind('mousemove', selfInstance.handleMouse);
          selfInstance.$mainNavigation.bind('mousemove', selfInstance.handleMouse);
        } else {
          parentClass.$navigationItemsContainer.css({'top': ''})
          selfInstance.$mainNavigation.unbind('mousemove', selfInstance.handleMouse);
        }
      }

    }

  }
}

exports.DzsNavigation = DzsNavigation;