import {is_ios, is_android} from "../_dzsap_helpers";


import {sanitizeToCssPx}  from "../../js_common/_dzs_helpers";
import {ajax_submit_like} from "../_dzsap_ajax";

export const ZoomSoundsNav = class {

  constructor(parentGallery) {
    this.parentGallery = parentGallery;

    this.structZoomsoundsNav = '';
    this._navMain = null;
    this._navClipper = null;
    this.cgallery = null;

    this.size_navMainClipSize = null;
    this.size_navMainTotalSize = null;
    this.finish_viy = 0;

    this.init();


  }

  init() {

    var selfGallery = this.parentGallery;

    this.structZoomsoundsNav = '<div class="nav-main zoomsounds-nav ' + selfGallery.initOptions.design_skin + ' navigation-method-' + selfGallery.initOptions.navigation_method + '"><div class="nav-clipper"></div></div>';


    // -- let us do some sanitize
    if (this.parentGallery.initOptions.navigation_method === 'full') {
      this.parentGallery.initOptions.design_menu_height = 'auto';
    }

  }

  init_ready() {



    const self = this;
    var selfGallery = this.parentGallery;

    if (selfGallery.initOptions.disable_menu_navigation == 'on') {
      this._navMain.hide();
    }

    if(!isNaN(Number(selfGallery.initOptions.design_menu_height))){
      this._navMain.css({
        'height': sanitizeToCssPx(selfGallery.initOptions.design_menu_height)
      })
    }

    if (is_ios() || is_android()) {
      this._navMain.css({
        'overflow': 'auto'
      })
    }


    if (selfGallery.initOptions.design_menu_state == 'closed') {
      this._navMain.css({
        'height': 0
      })
      this.cgallery.removeClass('menu-opened');
      this.cgallery.addClass('menu-closed');
    } else {
      this.cgallery.addClass('menu-opened');
      this.cgallery.removeClass('menu-closed');
    }


    this._navClipper.on('click', '.menu-btn-like,.menu-facebook-share', click_menuitem);
    this._navClipper.on('click', '.menu-item', click_menuitem);


    function click_menuitem(e) {
      var _t = jQuery(this);

      if (e.type == 'click') {
        if (_t.hasClass('menu-item')) {
          var ind = _t.parent().children().index(_t);

          selfGallery.goto_item(ind);
        }
        if (_t.hasClass('menu-btn-like')) {


          if (_t.parent().parent().attr('data-playerid')) {
            (ajax_submit_like.bind(selfGallery))(1, _t.parent().parent().attr('data-playerid'), {
              refferer: _t
            });
          }


          return false;
        }
        if (_t.hasClass('menu-facebook-share')) {
          if (_t.parent().parent().attr('data-playerid')) {
            (ajax_submit_like.bind(selfGallery))(1, _t.parent().parent().attr('data-playerid'), {
              refferer: _t
            });
          }


          return false;
        }
      }

    }


  }


  get_structZoomsoundsNav() {
    return this.structZoomsoundsNav;
  }

  set_elements(_navMain, _navClipper, cgallery) {
    this._navMain = _navMain;
    this._navClipper = _navClipper;
    this.cgallery = cgallery;
  }

  calculateDims() {

    this.size_navMainClipSize = this._navMain.height();
    this.size_navMainTotalSize = this._navClipper.outerHeight();

    const self = this;

    if (this.parentGallery.initOptions.navigation_method === 'mouseover') {



      if (this.size_navMainTotalSize > this.size_navMainClipSize && this.size_navMainClipSize > 0) {
        this._navMain.off('mousemove', navMain_mousemove);
        this._navMain.on('mousemove', navMain_mousemove);
      } else {
        this._navMain.off('mousemove', navMain_mousemove);
      }
    }

    function navMain_mousemove(e) {

      var aux_error = 20; //==erroring for the menu scroll
      var $ = jQuery;
      var _t = $(this);
      var mx = e.pageX - _t.offset().left;
      var my = e.pageY - _t.offset().top;


      if (self.size_navMainTotalSize <= self.size_navMainClipSize) {
        return;
      }

      self.size_navMainClipSize = self._navMain.outerHeight();



      var vix = 0;
      var viy = 0;

      viy = (my / self.size_navMainClipSize) * -(self.size_navMainTotalSize - self.size_navMainClipSize + 10 + aux_error * 2) + aux_error;

      if (viy > 0) {
        viy = 0;
      }
      if (viy < -(self.size_navMainTotalSize - self.size_navMainClipSize + 10)) {
        viy = -(self.size_navMainTotalSize - self.size_navMainClipSize + 10);
      }

      self.finish_viy = viy;




      if (is_ios() == false && is_android() == false) {
        if (self.parentGallery.initOptions.enable_easing != 'on') {

          self._navClipper.css({
            'transform': 'translateY(' + self.finish_viy + 'px)'
          });
        }
      }


    }

  }



  toggle_menu_state() {

    const self = this;

    if (this._navMain.height() == 0) {
      this._navMain.css({
        'height': this.parentGallery.initOptions.design_menu_height
      })


      this.cgallery.removeClass('menu-closed');
      this.cgallery.addClass('menu-opened');
    } else {

      this._navMain.css({
        'height': 0
      })
      this.cgallery.removeClass('menu-opened');
      this.cgallery.addClass('menu-closed');
    }
    setTimeout(function () {
      self.parentGallery.handleResize();
    }, 400); // -- animation delay

  }

  parseTrackData(track_data) {
    var foundnr = 0;
    var self = this;
    this._navMain.find('.menu-item-views').each(function () {
      var _t2 = $(this);

      var aux_html = _t2.html();

      var reg_findid = /{{views_(.*?)}}/g;


      var aux = reg_findid.exec(aux_html);



      var id = '';
      if (aux && aux[1]) {

        id = aux[1];

        for (var i in track_data) {



          if (id == track_data[i].label || id == 'ap' + track_data[i].label) {
            aux_html = aux_html.replace(aux[0], track_data[i].views);
            foundnr++;
            break;
          }
        }


        _t2.html(aux_html);

      }


    })

    if (foundnr < track_data.length) {

      self._navMain.find('.menu-item-views').each(function () {

        var _t2 = jQuery(this);

        var aux_html = _t2.html();
        var reg_findid = /{{views_(.*?)}}/g;

        var aux = reg_findid.exec(aux_html);

        if (aux && aux[0]) {

          aux_html = aux_html.replace(aux[0], 0);
          _t2.html(aux_html);
        }

      })
    }


  }
}
