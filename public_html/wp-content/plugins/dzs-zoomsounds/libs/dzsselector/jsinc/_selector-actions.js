import {openSelect} from "./_selector-functions";
import {view_initializeMasonry} from "./_view-functions";
import {opener_close, opener_selectOption} from "./_opener-functions";


let DEBOUNCE_TIME = 200;
let debouce_search_api = 0;

/**
 *
 * @param {DzsSelector} selfInstance
 */
export function setup_listeners(selfInstance) {
  var $ = jQuery;
  var o = selfInstance.initOptions;

  if (selfInstance.$selectInputForm) {

    selfInstance.$selectInputForm.on('change.dzssel', handle_changeSelectInputForm(selfInstance));
    selfInstance.$selectInputForm.on('focus', handle_focusSelectInputForm);
    selfInstance.$selectInputForm.on('focusout', handle_focusSelectInputForm);


    selfInstance.$selectInputForm.get(0).selector_wrap = selfInstance.$wrapperMain;
    if (selfInstance.$selectInputForm.hasClass('do-not-trigger-change-on-init')) {
      selfInstance.$selectInputForm.trigger('change');
    }

  }


  if (String(selfInstance.feedElementClass).indexOf('opener-') > -1) {


    var $wrapperHead = selfInstance.$dzsSelectorMain.parent().find('.dzs-select-wrapper-head');


    $wrapperHead.data('selector_wrap', selfInstance.$wrapperMain)


    $wrapperHead.on('click', handle_clickWrapperHead)
  } else {


    if (selfInstance.$wrapperMain) {
      selfInstance.$wrapperMain.find('.dzs-select-wrapper-head').on('click', function () {
        openSelect(selfInstance.$selectInputForm);
      })
    }
  }


  if (o.opener === ('opener-listbuttons') || o.opener === ('opener-bigoptions') || o.opener === ('opener-radio') || o.opener === ('opener-list')) {


    selfInstance.$openerMain.on('click.dzssel', '.bigoption', handle_mouse);
  }


  selfInstance.$wrapperMain.on('keyup.dzssel', '.search-field', handle_changeSearchField);

  // -------- functions
  // -------- below


  function handle_mouse(e) {
    var _t = $(this);

    if (e.type === 'click') {
      if (_t.hasClass('select-option')) {

        if (!window.dzssel_click_triggered) {
          var $selectedOpenerOption = $(this);


          // -- active


          // -- if it is select, remove other selected


          var currentIndexSelected = $selectedOpenerOption.parent().children('.select-option').index($selectedOpenerOption);


          window.dzssel_click_triggered = true;

          setTimeout(function () {
            window.dzssel_click_triggered = false;
          }, 50);


          if (selfInstance.$selectInputForm) {
            selfInstance.$selectInputForm.children().eq(currentIndexSelected).prop('selected', true);

            if($selectedOpenerOption.attr('data-value')){
              selfInstance.$selectInputForm.val($selectedOpenerOption.attr('data-value'));
              selfInstance.$selectInputForm.get(0).value = $selectedOpenerOption.attr('data-value');
            }
            selfInstance.$selectInputForm.trigger('change');
          } else {

            opener_selectOption(selfInstance, currentIndexSelected, undefined);
          }

          var str_curr_selected = selfInstance.constructSelectedOptionsString();

          selfInstance.setLabel(str_curr_selected);
          if (selfInstance.selectorOptions.type === 'select') {
            opener_close(selfInstance)();
          }

        }


      }
    }
  }


  /**
   *
   * @param {KeyboardEvent} e
   */
  function handle_changeSearchField(e) {
    var _t = $(this);

    if (e.type === 'keyup') {
      if (_t.hasClass('search-field')) {


        const searchedValue = _t.val();

        if (selfInstance.initOptions.search_api_feed) {

          if (debouce_search_api) {
            clearTimeout(debouce_search_api);
          }
          debouce_search_api = setTimeout(searchApiInOptions(searchedValue), DEBOUNCE_TIME);
        } else {


          if (searchedValue) {
            searchInOptions(searchedValue);
          }
        }
      }
    }
  }

  function searchApiInOptions(searchedValue) {
    return () => {
      const searchedApiVal = String(selfInstance.initOptions.search_api_feed).replace('{{query}}', searchedValue);

      jQuery.ajax({
        url: searchedApiVal,
        context: document.body
      }).done(function (responseArr) {


        if (selfInstance.selectorOptions.opener.indexOf('opener-') > -1) {
          selfInstance.$wrapperMain.find('.search-api-feeder').remove();

          let strOptions = '';

          responseArr.forEach((responseObj) => {
            strOptions += '<option value="' + responseObj.id + '">' + responseObj.title + '</option>';
          })

          if (selfInstance.$selectInputForm) {
            selfInstance.$selectInputForm.html(strOptions);
          }

          let strFeed = `<div class="search-api-feeder" hidden>${strOptions}<div>`;
          selfInstance.$wrapperMain.append(strFeed);
          selfInstance.$feederObject = selfInstance.$wrapperMain.find('.search-api-feeder').eq(0);

          selfInstance.reinit();
        }
      });
    }

  }

  function searchInOptions(searchedValue) {
    selfInstance.$openerMain.children().each(function () {
      var $option = $(this);
      if (String($option.text()).toLowerCase().indexOf(String(searchedValue).toLowerCase()) > -1) {
        $option.show();
      } else {
        $option.hide();
      }

      if (searchedValue === '') {
        $option.show();
      }
    })
  }


  function handle_clickWrapperHead(e) {
    var _t2 = $(this).data('selector_wrap');


    if ($(e.target).hasClass('search-field')) {


    } else {


      if (_t2.hasClass('active-animation')) {

        if (selfInstance.selectorOptions.nativeInputType !== 'checkbox') {

        }
        selfInstance.close_opener({
          'call_from': 'opener click'
        });

      } else {
        selfInstance.open_opener();
      }

    }


    setTimeout(function () {

      view_initializeMasonry(selfInstance);
    }, 500)

  }


  function handle_focusSelectInputForm(e) {

    if (e.type === 'focusout') {
      selfInstance.$wrapperMain.removeClass('select-focused');
    }
    if (e.type === 'focus') {

      if (selfInstance.$wrapperMain.hasClass('skin-beige')) {
        selfInstance.$wrapperMain.addClass('select-focused');
      }
    }
  }


  /**
   *
   * @param {DzsSelector} selfInstance
   */
  function handle_changeSelectInputForm(selfInstance) {
    return function () {


      var val = '';
      let currentIndexSelected = 0;


      if (selfInstance.$selectInputForm.length) {

        const $selectedHtml = selfInstance.$selectInputForm.find(':selected');
        val = $selectedHtml.html();
        currentIndexSelected = selfInstance.$selectInputForm.children().index($selectedHtml);

        if (selfInstance.selectorOptions.opener.indexOf('opener-') > -1) {
          opener_selectOption(selfInstance, currentIndexSelected, undefined);
        } else {
          selfInstance.setLabel(val);
        }
      }


    }
  }
}