export function view_calculatePosXandY(selfClass,targetIndex, items_per_page, pag_excess_thumbnr){
  let o = selfClass.argOptions;

  if (targetIndex !== selfClass.pag_total_pages - 1 || o.settings_mode === 'onlyoneitem') {
    selfClass.currPageX = -((items_per_page) * targetIndex) * selfClass.$thumbsCon.children().eq(0).outerWidth(true);
    selfClass.cthis.removeClass('islastpage');
  } else {
    selfClass.currPageX = -((items_per_page) * targetIndex - (items_per_page - pag_excess_thumbnr)) * selfClass.$thumbsCon.children().eq(0).outerWidth(true);
    selfClass.cthis.addClass('islastpage');
  }


  if (o.settings_direction === 'vertical') {
    if (o.settings_onlyone === 'on') {
      selfClass.currPageY = selfClass.$thumbsCon.children().eq(targetIndex).offset().top;
      selfClass.currPageY = -(selfClass.$thumbsCon.children().eq(targetIndex).offset().top - selfClass.$thumbsCon.eq(0).offset().top);
    }
  }
  if (o.settings_direction === 'horizontal') {
    if (o.settings_onlyone === 'on') {

      if (selfClass.$thumbsCon && selfClass.$thumbsCon.children().length) {

        selfClass.currPageY = selfClass.$thumbsCon.children().eq(targetIndex).offset().top;
        selfClass.currPageX = -(selfClass.$thumbsCon.children().eq(targetIndex).offset().left - selfClass.$thumbsCon.eq(0).offset().left);
      }
    } else {


      // if we scroll one page of a time
      selfClass.currPageX = -targetIndex * selfClass.totalItemContainerClipWidth;


      var slide_max_offset = selfClass.$thumbsClip.outerWidth() - selfClass.$thumbsCon.outerWidth() + selfClass.margin * 2;
      if (selfClass.currPageX < slide_max_offset) {

        selfClass.currPageX = slide_max_offset;
      }

    }
  }
}
