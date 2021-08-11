
var ConstantsDzsvg = require('../../configs/Constants').constants;
exports.secondCon_initFunctions = function () {


  var $ = jQuery;
  $(document).off('click', '.dzsas-second-con .read-more-label');
  $(document).on('click', '.dzsas-second-con .read-more-label', function (e) {


    var _t = $(this);
    var _con = _t.parent();

    var _content = _con.children('.read-more-content').eq(0);

    if (_con.hasClass('active')) {

      _content.animate({
        'height': 0
      }, {
        queue: false
        , duration: ConstantsDzsvg.ANIMATIONS_DURATION
        , complete: function (e) {


        }
      })

      _con.removeClass('active');
    } else {
      _content.css('height', 'auto');

      var auxh = (_content.outerHeight());

      _content.css('height', 0);
      _content.animate({
        'height': auxh
      }, {
        queue: false
        , duration: ConstantsDzsvg.ANIMATIONS_DURATION
        , complete: function (e) {


          $(this).css('height', 'auto');
        }
      })

      _con.addClass('active');

    }


    return false;
  })
}

exports.init_secondCon = function () {
  jQuery('.dzsas-second-con').each(function () {
    var _t = jQuery(this);



    var _c = _t;

    if (_c.find('.item').eq(1).children('.menudescriptioncon').html()) {

    } else {

      if (_c.find('.item').eq(2).children('.menudescriptioncon').html()) {

        _c.find('.item').eq(1).remove();
      }
    }

    var xpos = 0;
    _t.find('.videogallery--navigation-outer--bigblock').each(function () {
      var _t = jQuery(this);
      _t.css('left', xpos + '%');
      xpos += 100;
    })


    var xpos = 0;
    _t.find('.item').each(function () {
      var _t2 = jQuery(this);
      _t2.css('left', xpos + '%');
      xpos += 100;
    })






    if (_t.attr('data-vgtarget') === '.id_auto') {


      var _cach = jQuery('.videogallery,.videogallery-tobe').eq(0);


      var stringClass = /id_(.*?) /.exec(_cach.attr('class'));

      if (stringClass && stringClass[1]) {
        _t.attr('data-vgtarget', '.id_' + stringClass[1]);
      }

      if (_cach.get(0) && _cach.get(0).api_set_secondCon) {
        _cach.get(0).api_set_secondCon(_t);
      }
      setTimeout(function () {
        if (_cach.get(0) && _cach.get(0).api_set_secondCon) {
          _cach.get(0).api_set_secondCon(_t);
        }
      }, 1000)
    }


  })

}
