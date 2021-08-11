'use strict';
jQuery(document).ready(function($){


  setInterval(function(){


    $('.audiogallery:not(.inited)').each(function(){
      var _t2 = $(this);
      dzsag_init(_t2);
    })
    $('.audioplayer-tobe').each(function(){
      var _t2 = $(this);
      _t2.audioplayer();
    });


  },2000);
});