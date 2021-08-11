'use strict';

/**
 * v2.1.0
 */

import {
  selector_initial_setup,
  setup_determinePlaceholder,
  setup_determineValueCurrent,
  setup_mainStructure,
  setup_reinitInitial,
  structure_generateWrapperHead
} from "./jsinc/_selector-setup";
import {assets} from './jsinc/_selector-functions';
import {setup_listeners} from "./jsinc/_selector-actions";
import {opener_clearBigOptions, opener_setupBigOptions} from "./jsinc/_view-openerArea";
import {
  opener_close,
  opener_open,
  opener_reinit_checkMultipleActiveOptions,
  opener_updateMainLabel
} from "./jsinc/_opener-functions";
import {selectorDefaultSettings} from "./configs/_selectorDefaultSettings";
import {CLASS_OPTION_LABEL, SELECTOR_TYPE} from "./configs/_selectorConfig";

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
      type: '', // -- select or multiple-checkboxes
      nativeInputType: '',
    }

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

      selector_initial_setup(selfInstance)


      selfInstance.close_opener = opener_close(selfInstance);
      selfInstance.open_opener = opener_open(selfInstance);

      if (selfInstance.$dzsSelectorMain.get(0) && selfInstance.$dzsSelectorMain.get(0).nodeName === "INPUT") {
        return false;
      }


      // -- if we have treated this , we have parent
      if (selfInstance.isAlreadySetup) {
        setup_reinitInitial(selfInstance);
        reinit();
        return false;
      }


      selfInstance.$dzsSelectorMain.addClass('treated');


      setup_mainStructure(selfInstance);
      setup_determinePlaceholder(selfInstance);
      setup_determineValueCurrent(selfInstance);

      selfInstance.$wrapperMain.prepend(structure_generateWrapperHead(selfInstance));


      reinit();


      setup_listeners(selfInstance);


      if (selfInstance.$selectInputForm) {

        applyApiFunctionsOnElement(selfInstance.$selectInputForm.get(0));
      }

      applyApiFunctionsOnElement(selfInstance.$wrapperMain.get(0));

      setTimeout(function () {
        selfInstance.$wrapperMain.addClass('init-readyall')
      }, 100);


    }

    function applyApiFunctionsOnElement($element_) {
      $element_.api_reinit = reinit;
      $element_.api_destroy = destroy;
      $element_.api_close_opener = opener_close(selfInstance);
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

      if (selfInstance.selectorOptions.type === SELECTOR_TYPE.SELECT) {
        if (selfInstance.$selectInputForm.length) {
          selfInstance.$selectInputForm.children().each(function () {
            var _t2 = $(this);

            if (_t2.prop('selected')) {
              if (selfInstance.$openerWrap) {
                selfInstance.$openerWrap.children().removeClass('active-checked');
                selfInstance.$openerWrap.children().eq(ind).addClass('active-checked');
              }
              selfInstance.setLabel(_t2.html())
              return false;
            }

            ind++;


          })

        }
      }

      if (selfInstance.selectorOptions.opener.indexOf('opener-') === 0) {
        if (selfInstance.selectorOptions.type === SELECTOR_TYPE.MULTIPLE_CHECKBOXES) {
          opener_reinit_checkMultipleActiveOptions(selfInstance)
          opener_updateMainLabel(selfInstance);
        }
      }


    }


    function reinit() {


      if (selfInstance.selectorOptions.opener.indexOf('opener-') > -1) {
        opener_clearBigOptions(selfInstance);
        opener_setupBigOptions(selfInstance);
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


          const $optionLabelText = $openerMainChild.find(CLASS_OPTION_LABEL);
          if ($optionLabelText.length) {

            selectorHeadLabel += $optionLabelText.text();
          } else {
            selectorHeadLabel += $openerMainChild.html();
          }
        }
      })
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
      if($(this).hasClass('treated')){

        if(this.SelfInstance){
          this.SelfInstance.reinit();

          return this.SelfInstance;
        }
      }else{

        return new DzsSelector(this, $, selectorDefaultSettings);
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


window.svg_star = assets.svg_star;