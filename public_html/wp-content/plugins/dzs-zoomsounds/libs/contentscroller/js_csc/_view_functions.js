export function view_onlyOne_assignClasses(_c){

  if (_c.find('.vplayer').length > 0) {
    var _cach = _c.find('.vplayer').eq(0);



    if (_cach.get(0) && _cach.get(0).api_pauseMovie) {
      _cach.get(0).api_pauseMovie();
    }
  }

  _c.addClass('visible-item');
  _c.addClass('transitioning-out');
}

export function view_gotoPage(selfClass,targetIndex){

  var o = selfClass.argOptions;

  if (selfClass.currPage > -1 && selfClass.currPage !== targetIndex && o.settings_onlyone === 'on') {

    var _c = selfClass.$thumbsCon.children().eq(selfClass.currPage);

    if (o.settings_onlyone === 'on') {

      view_onlyOne_assignClasses(_c);
    }
  }
  var _c2 = selfClass.$thumbsCon.children().eq(targetIndex);
  selfClass.$currPage = _c2;

  if (o.settings_mode === 'onlyoneitem') {
    _c2.addClass('visible-item');
  }


  selfClass.$bulletsCon.children().removeClass('active');
  selfClass.$bulletsCon.children().eq(targetIndex).addClass('active');
}
