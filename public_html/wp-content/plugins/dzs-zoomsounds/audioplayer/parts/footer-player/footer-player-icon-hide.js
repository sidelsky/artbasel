if (window.jQuery) {

  jQuery(document).ready(function ($) {

    $(document).on('click.dzsiconhide', '.sticktobottom-close-con,.sticktobottom-close-con .svg-icon', function () {
      var _t = $(this);

      $('.dzsap-sticktobottom .audioplayer').get(0).api_pause_media();


      var _con = null;

      if (_t.parent().hasClass("dzsap-sticktobottom")) {
        _con = _t.parent();
      }
      if (_t.parent().parent().hasClass("dzsap-sticktobottom")) {
        _con = _t.parent().parent();
      }
      if (_t.parent().parent().parent().hasClass("dzsap-sticktobottom")) {
        _con = _t.parent().parent().parent();
      }


      if (_con.hasClass('audioplayer-loaded')) {

        _con.removeClass('audioplayer-loaded');
        _con.addClass('audioplayer-was-loaded');


      } else {

        _con.addClass('audioplayer-loaded');
        _con.addClass('audioplayer-was-loaded');
      }

      return false;
    })


    $(document).on('click.dzsiconshow', '.dzsap-sticktobottom .icon-show', function () {
      var _t = $(this);


      return false;
    })
  })
}
