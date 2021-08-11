import {ajax_submit_views} from "../_dzsap_ajax";

export function extraHtmlBottomFunctionality(selfClass    ){


  var o = selfClass.initOptions;





    if (selfClass.increment_views === 1) {
      (ajax_submit_views.bind(selfClass))();
      selfClass.increment_views = 2;
    }



  if (selfClass.index_extrahtml_toloads === 0) {


    selfClass.cthis.find('.extra-html').addClass('active');
  }
  setTimeout(function () {
    selfClass.cthis.find('.extra-html').addClass('active');
    if (selfClass.cthis.find('.float-left').length === 0) {
      selfClass.cthis.find('.extra-html').append(selfClass.cthis.find('.extra-html-extra'));
    } else {
      selfClass.cthis.find('.extra-html .float-left').append(selfClass.cthis.find('.extra-html-extra'));
    }


    var _c = selfClass.cthis.find('.extra-html-extra').children().eq(0);

    selfClass.cthis.find('.extra-html-extra').children().unwrap();







  }, 2000);
}

export function feedEmbedFunctionality(selfClass,$, dzsapHelpers, struct_embedButtonWithTooltip){

  var o = selfClass.initOptions;

  if (o.design_skin === 'skin-wave') {

    if (o.enable_embed_button === 'on') {
      if (selfClass._apControlsRight) {

        selfClass._apControlsRight.appendOnce(struct_embedButtonWithTooltip);
        selfClass.$embedButton = selfClass._apControlsRight.find('.btn-embed-code-con').eq(0);
        selfClass.$embedButton.find('.btn-embed-code').addClass('player-but');
      }
    }

  } else {
    if (o.enable_embed_button === 'on') {
      selfClass._audioplayerInner.appendOnce(struct_embedButtonWithTooltip);
      selfClass.$embedButton = selfClass._audioplayerInner.find('.btn-embed-code-con').eq(0);
    }
  }
  if (selfClass.$embedButton) {

    selfClass.$embedButton.find('.embed-code--text').html(selfClass.feedEmbedCode);
  }


  selfClass.cthis.on('click', '.btn-embed-code-con, .btn-embed', function () {
    var _t = $(this);


    dzsapHelpers.select_all(_t.find('.dzstooltip').get(0));
    document.execCommand('copy');
  })
  selfClass.cthis.on('click', '.copy-embed-code-btn', function () {
    var _t = $(this);

    dzsapHelpers.select_all(_t.parent().parent().find('.dzstooltip--inner > span').get(0));

    document.execCommand('copy');
    setTimeout(function () {
      dzsapHelpers.select_all(_t.get(0));
    }, 100)
  })
}
