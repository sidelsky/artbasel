exports.mainoptions_init = function () {

  var $ = jQuery;
  var $tabs_ = document.querySelector('#dzs-tabs--main-options');

  var inter_debounce_search = 0;

  function search_for_setting_name(arg) {
    $('.setting').each(function (e) {

      var $t = $(this);
      var searchedSettingName = arg.toLowerCase();
      var currentSettingLabel = $t.find('.setting-label').length ? String($t.find('.setting-label').eq(0).text()).toLowerCase() : '';
      var currentSettingSidenote = $t.find('.sidenote').length ? String($t.find('.sidenote').eq(0).text()).toLowerCase() : '';



      if (searchedSettingName) {
        if (currentSettingLabel.indexOf(searchedSettingName) > -1 || currentSettingSidenote.indexOf(searchedSettingName) > -1) {
          $t.show();
        } else {
          $t.hide();
        }
      } else {
        $t.show();
      }
    })

    check_which_tabs_have_settings_visible(arg);
    setTimeout(()=>{
      $tabs_.api_handle_resize();
    }, 300);
  }

  function check_which_tabs_have_settings_visible(arg) {

    $('.tab-menu-con').each(function (e) {
      var $t = $(this);


      var isHasAnyDisplayOtherThanNone = false;
      $t.find('.setting').each(function (e) {

        var $setting = $(this);

        if ($setting.css('display') !== 'none') {
          isHasAnyDisplayOtherThanNone = true;
        }
      });

      isHasAnyDisplayOtherThanNone  ? $t.css('display','') : $t.hide();

      if (arg === '') {
        $t.css('display','');
      }
    })
  }

  function handle_change_settings_search(e) {

    var $t = $(e.currentTarget);


    var val = $t.val();


    if (val) {

      $tabs_.api_set_fixed_mode('toggle');
      $tabs_.classList.add('dzstaa-all-tabs-content-visible');

    } else {

      $tabs_.api_set_fixed_mode('');
      $tabs_.classList.remove('dzstaa-all-tabs-content-visible');
    }
    search_for_setting_name(val);
  }

  $(document).on('keyup change mouseup', '#dzs--settings-search', function(e){
    clearTimeout(inter_debounce_search);
    inter_debounce_search = setTimeout(handle_change_settings_search, 400, e);
  })
}