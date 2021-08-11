(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.SELECTOR_TYPE=exports.CLASS_FEEDER=exports.CLASS_OPTION_LABEL=exports.CLASS_PLACEHOLDER_OPTION=exports.CLASS_SELECT_IS_SELF_FEEDER=void 0;const CLASS_SELECT_IS_SELF_FEEDER="select-is-self-feeder";exports.CLASS_SELECT_IS_SELF_FEEDER="select-is-self-feeder";const CLASS_PLACEHOLDER_OPTION=".feed-dzswtl--placeholder";exports.CLASS_PLACEHOLDER_OPTION=CLASS_PLACEHOLDER_OPTION;const CLASS_OPTION_LABEL=".the-label--name";exports.CLASS_OPTION_LABEL=".the-label--name";const CLASS_FEEDER="dzs-style-me-feeder";exports.CLASS_FEEDER=CLASS_FEEDER;const SELECTOR_TYPE={SELECT:"select",MULTIPLE_CHECKBOXES:"multiple-checkboxes"};exports.SELECTOR_TYPE=SELECTOR_TYPE;
},{}],2:[function(require,module,exports){
"use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.selectorDefaultSettings=void 0;const selectorDefaultSettings={opener:"auto",placeholder:"default",search_api_feed:""};exports.selectorDefaultSettings=selectorDefaultSettings;
},{}],3:[function(require,module,exports){
'use strict';
/**
 * v2.1.0
 */

var _selectorSetup = require("./jsinc/_selector-setup");

var _selectorFunctions = require("./jsinc/_selector-functions");

var _selectorActions = require("./jsinc/_selector-actions");

var _viewOpenerArea = require("./jsinc/_view-openerArea");

var _openerFunctions = require("./jsinc/_opener-functions");

var _selectorDefaultSettings = require("./configs/_selectorDefaultSettings");

var _selectorConfig = require("./configs/_selectorConfig");

window.dzssel_startup_init = true;
window.dzssel_click_triggered = false;
/**
 * @property initOptions - instance settings
 * @property $selectInputForm - the select element ( if it exists )
 * @property {HTMLElement} $element_
 * @property {jQuery} $wrapperMain
 * @property $feederObject - the select element ( if it exists )
 * @property $openerWrap
 * @property selectedIndex
 * @property $openerExternalArea
 * @property $dzsSelectorMain - the native element
 */

class DzsSelector {
  constructor($element_, $, finalOptions) {
    this.$ = $;
    this.$element_ = $element_;
    this.initOptions = Object.assign({}, finalOptions);
    this.$wrapperMain = null;
    this.$dzsSelectorMain = null;
    this.$feederObject = null;
    this.$selectInputForm = null;
    this.$openerWrap = null;
    this.$openerMain = null;
    this.$openerExternalArea = null;
    this.feedElementClass = 'select';
    this.isAlreadySetup = false;
    this.valueCurrent = '';
    /** @var {number|null} only for select */

    this.selectedIndex = null;
    /** @var {boolean | null} */

    this.typeSelectIsAllowEmptyOptions = null;
    this.selectorOptions = {
      opener: '',
      type: '',
      // -- select or multiple-checkboxes
      nativeInputType: ''
    };
    this.classInit();
  }

  classInit() {
    var $ = this.$;
    var selfInstance = this;
    var o = selfInstance.initOptions;
    selfInstance.reinit = reinit;
    this.$element_.SelfInstance = selfInstance;
    init();

    function init() {
      (0, _selectorSetup.selector_initial_setup)(selfInstance);
      selfInstance.close_opener = (0, _openerFunctions.opener_close)(selfInstance);
      selfInstance.open_opener = (0, _openerFunctions.opener_open)(selfInstance);

      if (selfInstance.$dzsSelectorMain.get(0) && selfInstance.$dzsSelectorMain.get(0).nodeName === "INPUT") {
        return false;
      } // -- if we have treated this , we have parent


      if (selfInstance.isAlreadySetup) {
        (0, _selectorSetup.setup_reinitInitial)(selfInstance);
        reinit();
        return false;
      }

      selfInstance.$dzsSelectorMain.addClass('treated');
      (0, _selectorSetup.setup_mainStructure)(selfInstance);
      (0, _selectorSetup.setup_determinePlaceholder)(selfInstance);
      (0, _selectorSetup.setup_determineValueCurrent)(selfInstance);
      selfInstance.$wrapperMain.prepend((0, _selectorSetup.structure_generateWrapperHead)(selfInstance));
      reinit();
      (0, _selectorActions.setup_listeners)(selfInstance);

      if (selfInstance.$selectInputForm) {
        applyApiFunctionsOnElement(selfInstance.$selectInputForm.get(0));
      }

      applyApiFunctionsOnElement(selfInstance.$wrapperMain.get(0));
      setTimeout(function () {
        selfInstance.$wrapperMain.addClass('init-readyall');
      }, 100);
    }

    function applyApiFunctionsOnElement($element_) {
      $element_.api_reinit = reinit;
      $element_.api_destroy = destroy;
      $element_.api_close_opener = (0, _openerFunctions.opener_close)(selfInstance);
      $element_.api_recheck_value_from_input = recheck_value_from_input;
    }

    function destroy() {
      if (selfInstance.$selectInputForm) {
        if (selfInstance.$selectInputForm.prev().hasClass('dzs-select-wrapper-head')) {
          selfInstance.$selectInputForm.prev().remove();
        }

        if (selfInstance.$selectInputForm.next().hasClass('opener-listbuttons-wrap')) {
          selfInstance.$selectInputForm.next().remove();
        }

        if (selfInstance.$selectInputForm.parent().hasClass('dzs-select-wrapper')) {
          selfInstance.$selectInputForm.unwrap();
        }
      }
    }
    /**
     * only called from api
     */


    function recheck_value_from_input() {
      var ind = 0;

      if (selfInstance.selectorOptions.type === _selectorConfig.SELECTOR_TYPE.SELECT) {
        if (selfInstance.$selectInputForm.length) {
          selfInstance.$selectInputForm.children().each(function () {
            var _t2 = $(this);

            if (_t2.prop('selected')) {
              if (selfInstance.$openerWrap) {
                selfInstance.$openerWrap.children().removeClass('active-checked');
                selfInstance.$openerWrap.children().eq(ind).addClass('active-checked');
              }

              selfInstance.setLabel(_t2.html());
              return false;
            }

            ind++;
          });
        }
      }

      if (selfInstance.selectorOptions.opener.indexOf('opener-') === 0) {
        if (selfInstance.selectorOptions.type === _selectorConfig.SELECTOR_TYPE.MULTIPLE_CHECKBOXES) {
          (0, _openerFunctions.opener_reinit_checkMultipleActiveOptions)(selfInstance);
          (0, _openerFunctions.opener_updateMainLabel)(selfInstance);
        }
      }
    }

    function reinit() {
      if (selfInstance.selectorOptions.opener.indexOf('opener-') > -1) {
        (0, _viewOpenerArea.opener_clearBigOptions)(selfInstance);
        (0, _viewOpenerArea.opener_setupBigOptions)(selfInstance);
      }
    }
  }

  constructSelectedOptionsString() {
    let selectorHeadLabel = '';

    if (this.selectorOptions.opener.indexOf('opener-') > -1) {
      this.$openerMain.children().each(function () {
        var $openerMainChild = jQuery(this);

        if ($openerMainChild.hasClass('active-checked')) {
          if (selectorHeadLabel) {
            selectorHeadLabel += ', ';
          }

          const $optionLabelText = $openerMainChild.find(_selectorConfig.CLASS_OPTION_LABEL);

          if ($optionLabelText.length) {
            selectorHeadLabel += $optionLabelText.text();
          } else {
            selectorHeadLabel += $openerMainChild.html();
          }
        }
      });
    }

    if (!selectorHeadLabel) {
      selectorHeadLabel = this.initOptions.placeholder;
    }

    return selectorHeadLabel;
  }
  /**
   * set label for '.dzs-select-wrapper-head .the-text'
   * @param {string} newHtml
   */


  setLabel(newHtml) {
    this.$dzsSelectorMain.parent().find('.dzs-select-wrapper-head .the-text').html(newHtml);
  }

}

(function ($) {
  $.fn.dzsselector = function (o) {
    this.each(function () {
      if ($(this).hasClass('treated')) {
        if (this.SelfInstance) {
          this.SelfInstance.reinit();
          return this.SelfInstance;
        }
      } else {
        return new DzsSelector(this, $, _selectorDefaultSettings.selectorDefaultSettings);
      }
    });
  };

  window.dzssel_init = function (selector, settings) {
    $(selector).dzsselector(Object.assign({}, settings));
  };
})(jQuery);

jQuery(document).ready(function ($) {
  if (window.dzssel_startup_init) {
    dzssel_init('*.dzs-style-me');
  }
});
window.svg_star = _selectorFunctions.assets.svg_star;

},{"./configs/_selectorConfig":1,"./configs/_selectorDefaultSettings":2,"./jsinc/_opener-functions":4,"./jsinc/_selector-actions":5,"./jsinc/_selector-functions":6,"./jsinc/_selector-setup":7,"./jsinc/_view-openerArea":9}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.opener_reinit_checkMultipleActiveOptions = opener_reinit_checkMultipleActiveOptions;
exports.opener_updateMainLabel = opener_updateMainLabel;
exports.opener_open = opener_open;
exports.opener_clearActiveChecked = opener_clearActiveChecked;
exports.opener_selectOption = opener_selectOption;
exports.opener_close = opener_close;

var _selectorConfig = require("../configs/_selectorConfig");
/**
 *
 * @param {DzsSelector} selfInstance
 */


function opener_reinit_checkMultipleActiveOptions(selfInstance) {
  let isGoingToUpdateLabel = false;
  selfInstance.$openerMain.find('input[type="checkbox"]').each(function () {
    var $t = jQuery(this);

    if ($t.prop('checked')) {
      if ($t.parent().hasClass('multiple-option')) {
        const ind = $t.parent().parent().children().index($t.parent());
        isGoingToUpdateLabel = true;
        opener_selectOption(selfInstance, ind, false);
      }
    } else {
      if ($t.parent().hasClass('multiple-option') && $t.parent().hasClass('active-checked')) {
        const ind = $t.parent().parent().children().index($t.parent());
        isGoingToUpdateLabel = true;
        opener_selectOption(selfInstance, ind, true);
      }
    }
  });

  if (isGoingToUpdateLabel) {
    opener_updateMainLabel(selfInstance);
  }
}

function opener_updateMainLabel(selfInstance) {
  var stringSelected = selfInstance.constructSelectedOptionsString();
  selfInstance.setLabel(stringSelected);
}
/**
 *
 * @param {DzsSelector} selfInstance
 * @returns {(function(*=): void)|*}
 */


function opener_open(selfInstance) {
  return function () {
    const o = selfInstance.initOptions;
    const isOpenerActive = selfInstance.$wrapperMain.hasClass('active-checked');

    if (!isOpenerActive) {
      // -- close other openers
      document.querySelectorAll('.dzs-select-wrapper').forEach($selectWrapper_ => {
        if ($selectWrapper_ === selfInstance.$wrapperMain.get(0)) {
          return false;
        }

        if ($selectWrapper_.getAttribute('class').indexOf('opener-') > -1) {
          if ($selectWrapper_.api_close_opener) {
            $selectWrapper_.api_close_opener();
          }
        }
      });
    }

    selfInstance.$wrapperMain.addClass('active-checked');
    setTimeout(function () {
      selfInstance.$wrapperMain.addClass('active-animation');
    }, 50);

    if (selfInstance.$openerExternalArea) {
      window.opener_externalOpenerToggle(selfInstance);
    }

    if (o.opener === 'opener-list') {}
  };
}

function opener_clearActiveChecked(selfInstance) {
  selfInstance.$openerMain.children().removeClass('active-checked');
}
/**
 *
 * @param {DzsSelector} selfInstance
 * @param {number} currentIndexSelected
 * @param {boolean|undefined} forceIsActive
 */


function opener_selectOption(selfInstance, currentIndexSelected, forceIsActive) {
  var isActiveChecked = false;
  const $openerChildAtIndex = selfInstance.$openerMain.children().eq(currentIndexSelected);

  if ($openerChildAtIndex.hasClass('active-checked')) {
    isActiveChecked = true;
  }

  if (forceIsActive !== undefined) {
    isActiveChecked = forceIsActive;
  }

  if (selfInstance.selectorOptions.type === _selectorConfig.SELECTOR_TYPE.SELECT) {
    opener_clearActiveChecked(selfInstance);
  }

  if (!isActiveChecked) {
    $openerChildAtIndex.addClass('active-checked');
  } else {
    if (selfInstance.selectorOptions.type === _selectorConfig.SELECTOR_TYPE.MULTIPLE_CHECKBOXES) {
      $openerChildAtIndex.removeClass('active-checked');
    }

    if (selfInstance.selectorOptions.type === 'select') {
      if (selfInstance.typeSelectIsAllowEmptyOptions === true) {
        selfInstance.$selectInputForm.val('');
      }

      if (selfInstance.typeSelectIsAllowEmptyOptions === false) {
        $openerChildAtIndex.addClass('active-checked');
      }
    }
  }
}
/**
 *
 * @param {DzsSelector} selfInstance
 * @returns {(function(*=): void)|*}
 */


function opener_close(selfInstance) {
  return function () {
    var o = selfInstance.initOptions;
    var delay = 300;
    selfInstance.$wrapperMain.removeClass('active-animation');

    if (o.opener === 'opener-list') {
      delay = 0;
    }

    setTimeout(function () {
      selfInstance.$wrapperMain.removeClass('active-checked');
    }, delay);

    if (String(selfInstance.feedElementClass).indexOf('opener-bigoptions') > -1) {
      ;
    }

    if (selfInstance.$openerExternalArea) {
      opener_externalOpenerClose(selfInstance);
    }
  };
}

},{"../configs/_selectorConfig":1}],5:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.setup_listeners = setup_listeners;

var _selectorFunctions = require("./_selector-functions");

var _viewFunctions = require("./_view-functions");

var _openerFunctions = require("./_opener-functions");

let DEBOUNCE_TIME = 200;
let debouce_search_api = 0;
/**
 *
 * @param {DzsSelector} selfInstance
 */

function setup_listeners(selfInstance) {
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
    $wrapperHead.data('selector_wrap', selfInstance.$wrapperMain);
    $wrapperHead.on('click', handle_clickWrapperHead);
  } else {
    if (selfInstance.$wrapperMain) {
      selfInstance.$wrapperMain.find('.dzs-select-wrapper-head').on('click', function () {
        (0, _selectorFunctions.openSelect)(selfInstance.$selectInputForm);
      });
    }
  }

  if (o.opener === 'opener-listbuttons' || o.opener === 'opener-bigoptions' || o.opener === 'opener-radio' || o.opener === 'opener-list') {
    selfInstance.$openerMain.on('click.dzssel', '.bigoption', handle_mouse);
  }

  selfInstance.$wrapperMain.on('keyup.dzssel', '.search-field', handle_changeSearchField); // -------- functions
  // -------- below

  function handle_mouse(e) {
    var _t = $(this);

    if (e.type === 'click') {
      if (_t.hasClass('select-option')) {
        if (!window.dzssel_click_triggered) {
          var $selectedOpenerOption = $(this); // -- active
          // -- if it is select, remove other selected

          var currentIndexSelected = $selectedOpenerOption.parent().children('.select-option').index($selectedOpenerOption);
          window.dzssel_click_triggered = true;
          setTimeout(function () {
            window.dzssel_click_triggered = false;
          }, 50);

          if (selfInstance.$selectInputForm) {
            selfInstance.$selectInputForm.children().eq(currentIndexSelected).prop('selected', true);

            if ($selectedOpenerOption.attr('data-value')) {
              selfInstance.$selectInputForm.val($selectedOpenerOption.attr('data-value'));
              selfInstance.$selectInputForm.get(0).value = $selectedOpenerOption.attr('data-value');
            }

            selfInstance.$selectInputForm.trigger('change');
          } else {
            (0, _openerFunctions.opener_selectOption)(selfInstance, currentIndexSelected, undefined);
          }

          var str_curr_selected = selfInstance.constructSelectedOptionsString();
          selfInstance.setLabel(str_curr_selected);

          if (selfInstance.selectorOptions.type === 'select') {
            (0, _openerFunctions.opener_close)(selfInstance)();
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
          responseArr.forEach(responseObj => {
            strOptions += '<option value="' + responseObj.id + '">' + responseObj.title + '</option>';
          });

          if (selfInstance.$selectInputForm) {
            selfInstance.$selectInputForm.html(strOptions);
          }

          let strFeed = `<div class="search-api-feeder" hidden>${strOptions}<div>`;
          selfInstance.$wrapperMain.append(strFeed);
          selfInstance.$feederObject = selfInstance.$wrapperMain.find('.search-api-feeder').eq(0);
          selfInstance.reinit();
        }
      });
    };
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
    });
  }

  function handle_clickWrapperHead(e) {
    var _t2 = $(this).data('selector_wrap');

    if ($(e.target).hasClass('search-field')) {} else {
      if (_t2.hasClass('active-animation')) {
        if (selfInstance.selectorOptions.nativeInputType !== 'checkbox') {}

        selfInstance.close_opener({
          'call_from': 'opener click'
        });
      } else {
        selfInstance.open_opener();
      }
    }

    setTimeout(function () {
      (0, _viewFunctions.view_initializeMasonry)(selfInstance);
    }, 500);
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
          (0, _openerFunctions.opener_selectOption)(selfInstance, currentIndexSelected, undefined);
        } else {
          selfInstance.setLabel(val);
        }
      }
    };
  }
}

},{"./_opener-functions":4,"./_selector-functions":6,"./_view-functions":8}],6:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.openSelect = openSelect;
exports.getWrapperClass = exports.assets = void 0;
const assets = {
  'svg_star': '<svg enable-background="new -1.23 -8.789 141.732 141.732" height="141.732px" id="Livello_1" version="1.1" viewBox="-1.23 -8.789 141.732 141.732" width="141.732px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Livello_100"><path d="M139.273,49.088c0-3.284-2.75-5.949-6.146-5.949c-0.219,0-0.434,0.012-0.646,0.031l-42.445-1.001l-14.5-37.854   C74.805,1.824,72.443,0,69.637,0c-2.809,0-5.168,1.824-5.902,4.315L49.232,42.169L6.789,43.17c-0.213-0.021-0.43-0.031-0.646-0.031   C2.75,43.136,0,45.802,0,49.088c0,2.1,1.121,3.938,2.812,4.997l33.807,23.9l-12.063,37.494c-0.438,0.813-0.688,1.741-0.688,2.723   c0,3.287,2.75,5.952,6.146,5.952c1.438,0,2.766-0.484,3.812-1.29l35.814-22.737l35.812,22.737c1.049,0.806,2.371,1.29,3.812,1.29   c3.393,0,6.143-2.665,6.143-5.952c0-0.979-0.25-1.906-0.688-2.723l-12.062-37.494l33.806-23.9   C138.15,53.024,139.273,51.185,139.273,49.088"/></g><g id="Livello_1_1_"/></svg>',
  'plus_sign': '<span class="plus-sign"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="12px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" xml:space="preserve"> <circle fill="#999999" cx="6" cy="6" r="6"/><rect class="rect1" x="5" y="2" fill="#FFFFFF" width="2" height="8"/><rect class="rect2" x="2" y="5" fill="#FFFFFF" width="8" height="2"/></svg></span>'
};
exports.assets = assets;

function openSelect(selector) {
  var element = jQuery(selector)[0],
      worked = false;

  if (document.createEvent) {
    // all browsers
    var e = document.createEvent("MouseEvents");
    e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    worked = element.dispatchEvent(e);
  } else if (element.fireEvent) {
    // ie
    worked = element.fireEvent("onmousedown");
  }

  if (!worked) {
    // unknown browser / error
    alert("It didn't worked in your browser.");
  }
}

const getWrapperClass = $wrapperMain => {
  const styleMatches = /style--(.*?)(?: |$)/g.exec($wrapperMain.attr('class'));
  return styleMatches && styleMatches[1];
};

exports.getWrapperClass = getWrapperClass;

},{}],7:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.setup_reinitInitial = setup_reinitInitial;
exports.setup_determinePlaceholder = setup_determinePlaceholder;
exports.setup_mainStructure = setup_mainStructure;
exports.setup_list = setup_list;
exports.setup_determineValueCurrent = setup_determineValueCurrent;
exports.selector_initial_setup = selector_initial_setup;
exports.structure_generateWrapperHead = structure_generateWrapperHead;

var _selectorFunctions = require("./_selector-functions");

var _selectorConfig = require("../configs/_selectorConfig");

var _viewOpenerArea = require("./_view-openerArea");
/**
 *
 * @param {DzsSelector} selfInstance
 */


function setup_reinitInitial(selfInstance) {
  if (selfInstance.$dzsSelectorMain.hasClass('skin-justvisible')) {
    selfInstance.$wrapperMain = selfInstance.$dzsSelectorMain.parent();

    if (selfInstance.selectorOptions.type === 'select') {
      selfInstance.$selectInputForm = selfInstance.$wrapperMain.find('select').eq(0);
    }

    if (selfInstance.$wrapperMain) {
      selfInstance.$wrapperMain.find('.dzs-select-wrapper-head').unbind('click');
      selfInstance.$wrapperMain.find('.dzs-select-wrapper-head').on('click', function () {
        (0, _selectorFunctions.openSelect)(selfInstance.$selectInputForm);
      });
    }
  }
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function setup_determinePlaceholder(selfInstance) {
  var o = selfInstance.initOptions;

  if (o.placeholder === 'default') {
    if (selfInstance.$wrapperMain.find(_selectorConfig.CLASS_PLACEHOLDER_OPTION).length) {
      o.placeholder = selfInstance.$wrapperMain.find(_selectorConfig.CLASS_PLACEHOLDER_OPTION).eq(0).html();
    }
  }

  if (o.placeholder === 'default') {
    o.placeholder = 'Search ...';
  }
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function setup_mainStructure(selfInstance) {
  var string_styleWidth = '';

  if (selfInstance.$dzsSelectorMain.get(0).style && selfInstance.$dzsSelectorMain.get(0).style.width !== '' && !isNaN(parseInt(selfInstance.$dzsSelectorMain.get(0).style.width, 10))) {
    string_styleWidth = 'width: ' + parseInt(selfInstance.$dzsSelectorMain.get(0).style.width, 10) + 'px';
  }

  if (!selfInstance.$dzsSelectorMain.parent().hasClass('dzs-select-wrapper')) {
    selfInstance.$dzsSelectorMain.wrap('<div class="dzs-select-wrapper fake-input ' + selfInstance.feedElementClass + '" style="' + string_styleWidth + '"></div>');
  } else {}

  selfInstance.$wrapperMain = selfInstance.$dzsSelectorMain.parent();
  selfInstance.$wrapperMain.removeClass('dzs-style-me');
  selfInstance.$wrapperMain.addClass('inited');
  selfInstance.$wrapperMain.addClass(selfInstance.feedElementClass);

  if (selfInstance.$dzsSelectorMain.parent().find('select').length) {
    selfInstance.$selectInputForm = selfInstance.$dzsSelectorMain.parent().find('select').eq(0);
  }

  if (selfInstance.$wrapperMain.next().hasClass(_selectorConfig.CLASS_FEEDER)) {
    selfInstance.$feederObject = selfInstance.$wrapperMain.next();
  } else {
    if (selfInstance.$wrapperMain.find('.' + _selectorConfig.CLASS_FEEDER).length) {
      selfInstance.$feederObject = selfInstance.$wrapperMain.find('.' + _selectorConfig.CLASS_FEEDER).eq(0);
    }
  } // -- if we have ul element


  if (selfInstance.$dzsSelectorMain.get(0) && selfInstance.$dzsSelectorMain.get(0).nodeName === 'UL') {
    setup_list(selfInstance);
  } // -- if no feeder object is found and we have a opener style, try to feed from the actual select


  if (selfInstance.$feederObject === null) {
    if (selfInstance.$dzsSelectorMain && selfInstance.$dzsSelectorMain.get(0).nodeName === 'SELECT') {
      selfInstance.$feederObject = selfInstance.$dzsSelectorMain;
    }
  }

  if (selfInstance.$dzsSelectorMain.parent().find('select').length) {
    selfInstance.$selectInputForm = selfInstance.$dzsSelectorMain.parent().find('select').eq(0);
  }

  if (selfInstance.selectorOptions.opener && selfInstance.selectorOptions.opener.indexOf('opener-') === 0) {
    if (selfInstance.$feederObject) {
      (0, _viewOpenerArea.opener_setupStructure)(selfInstance);

      if (selfInstance.$openerMain) {
        selfInstance.$openerMain.find('.select-option').eq(selfInstance.selectedIndex).addClass('active-checked');
      }
    }
  }

  let selectorType = '';

  if (selfInstance.$dzsSelectorMain.get(0).nodeName === 'SELECT') {
    selectorType = _selectorConfig.SELECTOR_TYPE.SELECT;
  }

  const $inputCheckboxes = selfInstance.$wrapperMain.find('input[type="checkbox"]');

  if ($inputCheckboxes.length) {
    selectorType = _selectorConfig.SELECTOR_TYPE.MULTIPLE_CHECKBOXES;
  }

  if (!selectorType) {
    const $inputSelect = selfInstance.$wrapperMain.find('select');

    if ($inputSelect.length) {
      selectorType = _selectorConfig.SELECTOR_TYPE.SELECT;
    }
  }

  if (selectorType === _selectorConfig.SELECTOR_TYPE.SELECT) {
    selfInstance.typeSelectIsAllowEmptyOptions = !!(selfInstance.$selectInputForm.find('option:not([value])').length || selfInstance.$selectInputForm.find('option[value=""]').length);
  }

  selfInstance.$wrapperMain.addClass('selector-type-' + selectorType);
  selfInstance.selectorOptions.type = selectorType;
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function setup_list(selfInstance) {
  var aux3 = '<select name="' + selfInstance.$dzsSelectorMain.attr('data-name') + '">';

  for (let i = 0; i < selfInstance.$dzsSelectorMain.children().length; i++) {
    aux3 += '<option value="' + selfInstance.$dzsSelectorMain.children().eq(i).attr('data-value') + '">' + selfInstance.$dzsSelectorMain.children().eq(i).attr('data-value') + '</option>';
  }

  aux3 += '</select>';
  selfInstance.$dzsSelectorMain.after(aux3);
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function setup_determineValueCurrent(selfInstance) {
  var o = selfInstance.initOptions;

  if (selfInstance.$selectInputForm && selfInstance.$selectInputForm.find(':selected').text()) {
    selfInstance.valueCurrent = selfInstance.$selectInputForm.find(':selected').text();
  }

  if (!selfInstance.valueCurrent) {
    selfInstance.valueCurrent = o.placeholder;
  }
}
/**
 *
 * get selector main
 * @param {DzsSelector} selfInstance
 */


function selector_initial_setup(selfInstance) {
  let o = selfInstance.initOptions;
  selfInstance.feedElementClass = selfInstance.feedElementClass.replace('dzs-style-me', '');
  selfInstance.$dzsSelectorMain = jQuery(selfInstance.$element_);

  if (selfInstance.$dzsSelectorMain.hasClass('treated') || selfInstance.$dzsSelectorMain.parent().hasClass('dzs-select-wrapper') && selfInstance.$dzsSelectorMain.parent().hasClass('inited') || selfInstance.$dzsSelectorMain.parent().hasClass('select-wrapper')) {
    selfInstance.isAlreadySetup = true;
  }

  selfInstance.feedElementClass = String(selfInstance.$dzsSelectorMain.attr('class'));

  if (selfInstance.$dzsSelectorMain.hasClass('opener-listbuttons')) {
    o.opener = 'opener-listbuttons';
  }

  if (selfInstance.$dzsSelectorMain.hasClass('opener-bigoptions')) {
    o.opener = 'opener-bigoptions';
  }

  if (selfInstance.$dzsSelectorMain.hasClass('opener-radio')) {
    o.opener = 'opener-radio';
  }

  if (selfInstance.$dzsSelectorMain.hasClass('opener-list')) {
    o.opener = 'opener-list';
  }

  if (selfInstance.$dzsSelectorMain.hasClass('type-checkbox')) {
    selfInstance.selectorOptions.nativeInputType = 'checkbox';
  }

  if (selfInstance.$element_.getAttribute('data-options')) {
    try {
      const dataOptions = selfInstance.$element_.getAttribute('data-options');
      const jsonOptions = JSON.parse(dataOptions);
      selfInstance.initOptions = Object.assign(selfInstance.initOptions, jsonOptions);
    } catch (err) {}
  }

  selfInstance.selectorOptions.opener = o.opener;
}
/**
 * called from main init
 * @param {DzsSelector} selfInstance
 */


function structure_generateWrapperHead(selfInstance) {
  var o = selfInstance.initOptions;
  var structure_wrapperHead = '<div class="dzs-select-wrapper-head">';
  structure_wrapperHead += '<span class="the-text the-text--placeholder">' + selfInstance.valueCurrent + '</span>';

  if (selfInstance.$wrapperMain.hasClass('skin-charm')) {
    structure_wrapperHead += _selectorFunctions.assets.plus_sign;
  }

  structure_wrapperHead += '</div>';
  return structure_wrapperHead;
}

},{"../configs/_selectorConfig":1,"./_selector-functions":6,"./_view-openerArea":9}],8:[function(require,module,exports){
"use strict";function view_initializeMasonry(e){var i=jQuery,n={columnWidth:1,itemSelector:".masonry-gallery--item"};i.fn.masonry&&e.$dzsSelectorMain.parent().find(".dzslayouter .items-con").masonry(n)}Object.defineProperty(exports,"__esModule",{value:!0}),exports.view_initializeMasonry=view_initializeMasonry;
},{}],9:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.opener_setupStructure = opener_setupStructure;
exports.opener_clearBigOptions = opener_clearBigOptions;
exports.opener_setupBigOptions = opener_setupBigOptions;
exports.opener_determineAreas = opener_determineAreas;

var _selectorConfig = require("../configs/_selectorConfig");

var _openerFunctions = require("./_opener-functions");
/**
 *
 * @param {DzsSelector} selfInstance
 */


function opener_setupStructure(selfInstance) {
  var o = selfInstance.initOptions;
  var str_openerItemStructure = '<div class="' + String(o.opener) + '-wrap real-opener-wrap" data-opener-children-length="' + selfInstance.$feederObject.children().length + '">';

  if (o.opener === 'opener-list' || o.opener === 'opener-bigoptions') {
    // -- we just need opener-list for search
    if (o.opener === 'opener-list' && selfInstance.$dzsSelectorMain.parent().hasClass('disable-search') === false) {
      str_openerItemStructure += '<input type="search" class="search-field"/>';
    }

    str_openerItemStructure += '<div class="' + String(o.opener) + ' real-opener">';
  }

  for (var i = 0; i < selfInstance.$dzsSelectorMain.children().length; i++) {
    var $selectOption = selfInstance.$dzsSelectorMain.children().eq(i);

    if ($selectOption.prop('selected')) {
      selfInstance.selectedIndex = i;
    }
  }

  if (o.opener === 'opener-bigoptions') {
    str_openerItemStructure += '</div>';
  }

  str_openerItemStructure += '</div>';
  selfInstance.$dzsSelectorMain.parent().append(str_openerItemStructure);
  opener_determineAreas(selfInstance);
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function opener_clearBigOptions(selfInstance) {
  const $externalArea = jQuery('.dzs-select--opener-external-area');

  if ($externalArea.length) {
    selfInstance.$openerExternalArea = $externalArea;
  }

  if (selfInstance.$openerMain) {
    selfInstance.$openerMain.html('');
  } else {
    opener_determineAreas(selfInstance);
  }
}
/**
 *
 * @param {DzsSelector} selfInstance
 * @param {object[]} feedOptions
 */


function opener_appendBigOptions(selfInstance, feedOptions) {
  feedOptions.forEach(feedOption => {
    let extraClasses = '';
    let extraAttr = '';

    if (feedOption.isForValueSelectNull) {
      extraClasses += ' is-for-value-select-null';
    }

    if (feedOption.bigOptionVal) {
      extraAttr += ' data-value="' + feedOption.bigOptionVal + '"';
    }

    const feedOptionHtml = `<${feedOption.bigOptionHtmlTag} class="bigoption select-option ${feedOption.bigOptionExtraClasses} ${extraClasses}" ${extraAttr} ${feedOption.bigOptionExtraAttr}>${feedOption.bigOptionHtml}</${feedOption.bigOptionHtmlTag}>`;

    if (selfInstance.$openerMain) {
      selfInstance.$openerMain.append(feedOptionHtml);
    } else {
      selfInstance.$openerWrap.append(feedOptionHtml);
    }
  });
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function opener_setupBigOptions(selfInstance) {
  var o = selfInstance.initOptions;
  var str_bigOptionStructure;
  const feedOptions = [];
  /** @var {jQuery} */

  let $feederObject = null;

  if (selfInstance.$feederObject) {
    $feederObject = selfInstance.$feederObject;
  } else {
    $feederObject = selfInstance.$dzsSelectorMain;

    if (selfInstance.selectorOptions.nativeInputType === 'checkbox') {
      if (selfInstance.$dzsSelectorMain.children('.' + _selectorConfig.CLASS_FEEDER).length) {
        $feederObject = selfInstance.$dzsSelectorMain.children('.' + _selectorConfig.CLASS_FEEDER).eq(0);
      }
    }
  }

  if ($feederObject) {
    for (var i = 0; i < $feederObject.children().length; i++) {
      var str_domTag = 'div';
      let isForValueSelectNull = false;
      const $feedObject = $feederObject.children().eq(i);

      if ($feedObject.get(0).nodeName === 'LABEL') {
        str_domTag = 'label';
      }

      if ($feedObject.get(0).nodeName === 'SECTION') {
        str_domTag = 'section';
      }

      let bigOptionExtraAttr = '';
      let bigOptionHtml = $feedObject.html();
      let bigOptionVal = '';
      let bigOptionExtraClasses = '';

      if (selfInstance.selectorOptions.type === _selectorConfig.SELECTOR_TYPE.SELECT) {
        if (selfInstance.$selectInputForm.find('option').eq(i).val() === '') {
          isForValueSelectNull = true;
        }
      }

      if ($feedObject.attr('class')) {
        bigOptionExtraClasses = ` ${$feedObject.attr('class')}`;
      }

      if ($feedObject.attr('value')) {
        bigOptionVal = `${$feedObject.attr('value')}`;
      }

      if ($feedObject.hasClass('active-checked')) {
        bigOptionExtraClasses += ' active-checked';
      }

      bigOptionHtml = $feedObject.html();

      if (o.opener === 'opener-radio') {
        bigOptionHtml += '<div class="small-bubble"></div>';
      }

      if ($feedObject.attr('data-term_slug')) {
        bigOptionExtraAttr += ' data-term_slug="' + $feedObject.attr('data-term_slug') + '"';
      }

      if ($feedObject.attr('data-term_id')) {
        bigOptionExtraAttr += ' data-term_id="' + $feedObject.attr('data-term_id') + '"';
      }

      if ($feedObject.attr('data-type')) {
        bigOptionExtraAttr += ' data-type="' + $feedObject.attr('data-type') + '"';
      }

      feedOptions.push({
        bigOptionHtmlTag: str_domTag,
        bigOptionHtml: bigOptionHtml,
        bigOptionExtraAttr,
        bigOptionVal,
        isForValueSelectNull,
        bigOptionExtraClasses: bigOptionExtraClasses
      });
    }
  } else {// -- no feeder
  }

  opener_appendBigOptions(selfInstance, feedOptions);

  if ($feederObject.hasClass(_selectorConfig.CLASS_FEEDER)) {
    $feederObject.children().remove();
  }

  setTimeout(function () {
    if (selfInstance.$selectInputForm && !selfInstance.$selectInputForm.hasClass('do-not-trigger-change-on-reinit')) {
      selfInstance.$selectInputForm.trigger('change');
    }

    if (selfInstance.$openerWrap) {
      var aux = selfInstance.$openerWrap.html();

      if (aux.indexOf('{{starssvg}}') > -1) {
        aux = aux.replace(/{{starssvg}}/g, window.svg_star + window.svg_star + window.svg_star + window.svg_star + window.svg_star);
        selfInstance.$openerWrap.html(aux);
      }
    }
  }, 100);

  if (selfInstance.selectorOptions.opener.indexOf('opener-') === 0) {
    if (selfInstance.selectorOptions.type === _selectorConfig.SELECTOR_TYPE.MULTIPLE_CHECKBOXES) {
      (0, _openerFunctions.opener_reinit_checkMultipleActiveOptions)(selfInstance);
      selfInstance.$openerMain.find('input').each(function () {
        this.parent_selector_wrapper = selfInstance.$wrapperMain;
      });
    }
  }
}
/**
 *
 * @param {DzsSelector} selfInstance
 */


function opener_determineAreas(selfInstance) {
  let {
    selectorOptions
  } = selfInstance;

  if (selectorOptions.opener === 'opener-bigoptions' || selectorOptions.opener === 'opener-list') {
    selfInstance.$openerWrap = selfInstance.$wrapperMain.find('.' + selectorOptions.opener + '-wrap');

    if (selfInstance.$wrapperMain.find('div.' + selectorOptions.opener + '.real-opener').length) {
      selfInstance.$openerMain = selfInstance.$wrapperMain.find('div.' + selectorOptions.opener + '.real-opener');
    } else {
      selfInstance.$openerMain = selfInstance.$wrapperMain.find('div.' + selectorOptions.opener + '');
    }
  }

  if (selectorOptions.opener === 'opener-listbuttons' || selectorOptions.opener === 'opener-radio') {
    selfInstance.$openerWrap = selfInstance.$wrapperMain.find('.' + selectorOptions.opener + '-wrap');
    selfInstance.$openerMain = selfInstance.$wrapperMain.find('div.' + selectorOptions.opener + '-wrap');
  }
}

},{"../configs/_selectorConfig":1,"./_opener-functions":4}]},{},[3])


//# sourceMappingURL=dzsselector.js.map