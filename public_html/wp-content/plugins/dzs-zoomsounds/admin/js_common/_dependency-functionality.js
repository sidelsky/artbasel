window.dzs_check_dependency_settings = function (pargs) {

  // -- this checks for all dependencies .. lets make a timer


  if (window.inter_dzs_check_dependency_settings) {
    clearTimeout(window.inter_dzs_check_dependency_settings);

  }

  window.inter_dzs_check_dependency_settings = setTimeout(function () {
    dzs_check_dependency_settings_real(pargs);
  }, 100);


}


window.dzs_check_dependency_settings_real = function (pargs) {
  var margs = {
    target_attribute: 'name'
  }

  var $ = jQuery;
  $('*[data-dependency]').each(function () {
    var $targetSetting = $(this);


    var dependencyObject = {};


    var dependencySerialized = $targetSetting.attr('data-dependency');


    if (dependencySerialized.indexOf('"') == 0) {
      dependencySerialized = dependencySerialized.substr(1, dependencySerialized.length);
      dependencySerialized = dependencySerialized.substr(0, dependencySerialized.length - 1);
    }


    dependencySerialized = dependencySerialized.replace(/{quotquot}/g, '"');


    try {
      dependencyObject = JSON.parse(dependencySerialized);


      if (dependencyObject[0]) {


        var $sourceDependency = null;


        var target_attribute = margs.target_attribute;

        var $targetContainer = $(document);


        if ($targetSetting.hasClass('check-label')) {
          target_attribute = 'data-label';
        }
        if ($targetSetting.hasClass('check-parent1')) {
          $targetContainer = $targetSetting.parent();
        }
        if ($targetSetting.hasClass('check-parent2')) {
          $targetContainer = $targetSetting.parent().parent();
        }
        if ($targetSetting.hasClass('check-parent3')) {
          $targetContainer = $targetSetting.parent().parent().parent();
        }


        if (dependencyObject[0].lab) {
          $sourceDependency = $targetContainer.find('*[' + target_attribute + '="' + dependencyObject[0].lab + '"]:not(.fake-input)').eq(0);
          if ($sourceDependency.length == 0 && dependencyObject[0].lab == 'name') {
            $sourceDependency = $targetContainer.find('*[name="0-settings-' + dependencyObject[0].lab + '"]:not(.fake-input)').eq(0);

          }
        }
        if (dependencyObject[0].label) {
          $sourceDependency = $targetContainer.find('*[' + target_attribute + '="' + dependencyObject[0].label + '"]:not(.fake-input)').eq(0);

          if ($sourceDependency.length == 0 && dependencyObject[0].label == 'name') {
            $sourceDependency = $targetContainer.find('*[name="0-settings-' + dependencyObject[0].label + '"]:not(.fake-input)').eq(0);

          }
        }
        if (dependencyObject[0].element) {


          // -- if it's player generator there is no dzsap_meta_
          if ($('body').hasClass('zoomsounds_page_dzsap-mo')) {
            dependencyObject[0].element = String(dependencyObject[0].element).replace('dzsap_meta_', '');
          }
          if ($targetSetting.attr('data-option-name') === 'dzsap_meta_download_custom_link') {


          }


          $sourceDependency = $targetContainer.find('*[' + target_attribute + '="' + dependencyObject[0].element + '"]:not(.fake-input)').eq(0);
        }


        if (dependencyObject[0].element && dependencyObject[0].element == 'dzsap_meta_download_custom_link_enable') {

        }


        var cval = $sourceDependency.val();


        if ($sourceDependency.attr('type') == 'checkbox') {
          if ($sourceDependency.prop('checked')) {

          } else {
            cval = '';
          }
        }

        var isShowing = false;

        if (dependencyObject[0].val) {
          for (var i3 in dependencyObject[0].val) {


            if (cval == dependencyObject[0].val[i3]) {
              isShowing = true;
              break;

            }
          }
        }

        if (dependencyObject.relation) {


          for (var depObjKey in dependencyObject) {
            if (depObjKey == 'relation') {
              continue;
            }


            if (dependencyObject[depObjKey].value) {
              if (dependencyObject.relation == 'AND') {
                isShowing = false;
              }


              if (dependencyObject[0].element) {
                $sourceDependency = $targetContainer.find('*[' + target_attribute + '="' + dependencyObject[depObjKey].element + '"]:not(.fake-input)').eq(0);


              }


              for (let dependencyKey in dependencyObject[depObjKey].value) {

                if ($targetSetting.attr('data-option-name') === 'dzsap_meta_wrapper_image_type') {

                  console.log($targetSetting);
                  console.log(' dependencyObject[depObjKey].value - ', dependencyObject[depObjKey].value);
                  console.log(' $sourceDependency - ', $sourceDependency,' \'*[\' + target_attribute + \'="\' + dependencyObject[i].element + \'"]:not(.fake-input)\' ');
                }

                if ($sourceDependency.val() == dependencyObject[depObjKey].value[dependencyKey]) {


                  if ($sourceDependency.attr('type') == 'checkbox') {
                    if ($sourceDependency.val() == dependencyObject[depObjKey].value[dependencyKey] && $sourceDependency.prop('checked')) {

                      isShowing = true;
                    }
                  } else {

                    isShowing = true;
                  }

                  break;

                }


                if (dependencyObject[depObjKey].value[dependencyKey] == 'anything_but_blank' && cval) {

                  isShowing = true;
                  break;
                }
              }


            }

          }

        } else {

          if (dependencyObject[0].value) {

            for (var i3 in dependencyObject[0].value) {
              if ($sourceDependency.val() == dependencyObject[0].value[i3]) {


                if ($sourceDependency.attr('type') == 'checkbox') {
                  if ($sourceDependency.val() == dependencyObject[0].value[i3] && $sourceDependency.prop('checked')) {

                    isShowing = true;
                  }
                } else {

                  isShowing = true;
                }

                break;

              }


              if (dependencyObject[0].value[i3] == 'anything_but_blank' && cval) {

                isShowing = true;
                break;
              }
            }
          }
        }


        if ($targetSetting.attr('data-option-name') === 'dzsap_meta_wrapper_image_type') {
          console.log('isShowing --- ', isShowing, $targetSetting);
          // debugger;
        }

        if (isShowing) {
          $targetSetting.show();
        } else {
          $targetSetting.hide();
        }

        console.log('$targetSetting - ', $targetSetting);


      }


    } catch (err) {
      console.info('cannot parse depedency json', "'", dependencySerialized, "'", err, $targetSetting);
    }
  })
};


window.dzs_handle_submit_dependency_field = function (e) {
  var _t = jQuery(this);

  if (e.type == 'change') {

    if (_t.hasClass('dzs-dependency-field')) {

      dzs_check_dependency_settings();
    }
  }
}

window.dzs_dependency_on_document_ready = function () {


  if(window.dzsdepe_isInited){
    return;
  }

  window.dzsdepe_isInited = true;

  var $ = jQuery;


  $(document).off('change.dzsdepe', '.dzs-dependency-field', dzs_handle_submit_dependency_field);
  $(document).on('change.dzsdepe', '.dzs-dependency-field', dzs_handle_submit_dependency_field);


  setTimeout(function () {


    $('.dzs-dependency-field').trigger('change');


  }, 800);


}

