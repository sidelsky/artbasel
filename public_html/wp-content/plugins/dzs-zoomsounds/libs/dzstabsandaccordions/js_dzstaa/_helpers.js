

exports.jQuery_aux_extends = function($){


  $.fn.prependOnce = function (arg, argfind) {
    var _t = $(this) // It's your element



    if (typeof (argfind) == 'undefined') {
      var regex = new RegExp('class="(.*?)"');
      var auxarr = regex.exec(arg);


      if (typeof auxarr[1] != 'undefined') {
        argfind = '.' + auxarr[1];
      }
    }


    // we compromise chaining for returning the success
    if (_t.children(argfind).length < 1) {
      _t.prepend(arg);
      return true;
    } else {
      return false;
    }
  };
  $.fn.appendOnce = function (arg, argfind) {
    var _t = $(this) // It's your element


    if (typeof (argfind) == 'undefined') {
      var regex = new RegExp('class="(.*?)"');
      var auxarr = regex.exec(arg);


      if (typeof auxarr[1] != 'undefined') {
        argfind = '.' + auxarr[1];
      }
    }

    if (_t.children(argfind).length < 1) {
      _t.append(arg);
      return true;
    } else {
      return false;
    }
  };


  $.fn.outerHTML = function (e) {
    return e
      ? this.before(e).remove()
      : jQuery("<p>").append(this.eq(0).clone()).html();
  };
}