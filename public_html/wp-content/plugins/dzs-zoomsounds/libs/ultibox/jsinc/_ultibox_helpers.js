var svg_close_btn = '<svg enable-background="new 0 0 40 40" id="" version="1.1" viewBox="0 0 40 40" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M28.1,26.8c0.4,0.4,0.4,1,0,1.4c-0.2,0.2-0.5,0.3-0.7,0.3s-0.5-0.1-0.7-0.3l-6.8-6.8l-6.8,6.8c-0.2,0.2-0.5,0.3-0.7,0.3   s-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l6.8-6.8l-6.8-6.8c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0l6.8,6.8l6.8-6.8   c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4L21.3,20L28.1,26.8z"/></g><g><path d="M19.9,40c-11,0-20-9-20-20s9-20,20-20c4.5,0,8.7,1.5,12.3,4.2c0.4,0.3,0.5,1,0.2,1.4c-0.3,0.4-1,0.5-1.4,0.2   c-3.2-2.5-7-3.8-11-3.8c-9.9,0-18,8.1-18,18s8.1,18,18,18s18-8.1,18-18c0-3.2-0.9-6.4-2.5-9.2c-0.3-0.5-0.1-1.1,0.3-1.4   c0.5-0.3,1.1-0.1,1.4,0.3c1.8,3.1,2.8,6.6,2.8,10.2C39.9,31,30.9,40,19.9,40z"/></g></svg>';


var svg_right_arrow = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 22.062 22.062" style="enable-background:new 0 0 22.062 22.062;" xml:space="preserve" width="512px" height="512px"> <g> <path d="M10.544,11.031l6.742-6.742c0.81-0.809,0.81-2.135,0-2.944l-0.737-0.737 c-0.81-0.811-2.135-0.811-2.945,0L4.769,9.443c-0.435,0.434-0.628,1.017-0.597,1.589c-0.031,0.571,0.162,1.154,0.597,1.588 l8.835,8.834c0.81,0.811,2.135,0.811,2.945,0l0.737-0.737c0.81-0.808,0.81-2.134,0-2.943L10.544,11.031z" fill="#696969"/> </g> </svg> ';


window.get_query_arg = function (purl, key) {



  if (purl.indexOf(key + '=') > -1) {

    var regexS = "[?&]" + key + "(.+?)(?=&|$)";
    var regex = new RegExp(regexS);
    var regtest = regex.exec(purl);



    if (regtest != null) {



      if (regtest[1]) {
        var aux = regtest[1].replace(/=/g, '');
        return aux;
      } else {
        return '';
      }


    }

  }
}


window.add_query_arg = function (purl, key, value) {
  key = encodeURIComponent(key);
  value = encodeURIComponent(value);



  var s = purl;
  var pair = key + "=" + value;

  var r = new RegExp("(&|\\?)" + key + "=[^\&]*");




  s = s.replace(r, "$1" + pair);

  var addition = '';
  if (s.indexOf(key + '=') > -1) {


  } else {
    if (s.indexOf('?') > -1) {
      addition = '&' + pair;
    } else {
      addition = '?' + pair;
    }
    s += addition;
  }

  //if value NaN we remove this field from the url
  if (value == 'NaN') {
    var regex_attr = new RegExp('[\?|\&]' + key + '=' + value);
    s = s.replace(regex_attr, '');
  }




  return s;
}

function init_jquery_helpers() {

  jQuery.fn.outerHTML = function (e) {
    return e
      ? this.before(e).remove()
      : jQuery("<p>").append(this.eq(0).clone()).html();
  };


  jQuery.fn.prependOnce = function (arg, argfind) {
    var _t = jQuery(this) // It's your element



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
  jQuery.fn.appendOnce = function (arg, argfind) {
    var _t = jQuery(this) // It's your element


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

}

module.exports.detect_ultibox_media_type = function (arg, suggestedMediaType='image') {

  var type = suggestedMediaType;


  if(arg){

    if (arg.indexOf('.mp4') >= arg.length - 4 || arg.indexOf('.m4v') >= arg.length - 4) {
      type = 'video';
    }
    if (arg.indexOf('.mp3') >= arg.length - 4 || arg.indexOf('.m4a') >= arg.length - 4) {
      type = 'audio';
    }
    if (arg.indexOf('#') === 0 || arg.indexOf('.') === 0) {
      type = 'inlinecontent';
    }
  }

  return type;
}
module.exports.svg_close_btn = svg_close_btn;
module.exports.svg_right_arrow = svg_right_arrow;
module.exports.init_jquery_helpers = init_jquery_helpers;