import {assets, openSelect} from "./_selector-functions";
import {CLASS_FEEDER, CLASS_PLACEHOLDER_OPTION, SELECTOR_TYPE} from "../configs/_selectorConfig";
import {opener_setupStructure} from "./_view-openerArea";


/**
 *
 * @param {DzsSelector} selfInstance
 */
export function setup_reinitInitial(selfInstance) {
  if (selfInstance.$dzsSelectorMain.hasClass('skin-justvisible')) {

    selfInstance.$wrapperMain = selfInstance.$dzsSelectorMain.parent();

    if (selfInstance.selectorOptions.type === 'select') {
      selfInstance.$selectInputForm = selfInstance.$wrapperMain.find('select').eq(0);
    }

    if (selfInstance.$wrapperMain) {
      selfInstance.$wrapperMain.find('.dzs-select-wrapper-head').unbind('click');
      selfInstance.$wrapperMain.find('.dzs-select-wrapper-head').on('click', function () {
        openSelect(selfInstance.$selectInputForm);
      })
    }
  }

}


/**
 *
 * @param {DzsSelector} selfInstance
 */
export function setup_determinePlaceholder(selfInstance) {
  var o = selfInstance.initOptions;


  if (o.placeholder === 'default') {
    if (selfInstance.$wrapperMain.find(CLASS_PLACEHOLDER_OPTION).length) {
      o.placeholder = selfInstance.$wrapperMain.find(CLASS_PLACEHOLDER_OPTION).eq(0).html();
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
export function setup_mainStructure(selfInstance) {


  var string_styleWidth = '';


  if (selfInstance.$dzsSelectorMain.get(0).style && selfInstance.$dzsSelectorMain.get(0).style.width !== '' && !isNaN(parseInt(selfInstance.$dzsSelectorMain.get(0).style.width, 10))) {
    string_styleWidth = 'width: ' + parseInt(selfInstance.$dzsSelectorMain.get(0).style.width, 10) + 'px';
  }


  if (!selfInstance.$dzsSelectorMain.parent().hasClass('dzs-select-wrapper')) {

    selfInstance.$dzsSelectorMain.wrap('<div class="dzs-select-wrapper fake-input ' + selfInstance.feedElementClass + '" style="' + string_styleWidth + '"></div>');
  } else {

  }
  selfInstance.$wrapperMain = selfInstance.$dzsSelectorMain.parent();


  selfInstance.$wrapperMain.removeClass('dzs-style-me');
  selfInstance.$wrapperMain.addClass('inited');
  selfInstance.$wrapperMain.addClass(selfInstance.feedElementClass);


  if (selfInstance.$dzsSelectorMain.parent().find('select').length) {
    selfInstance.$selectInputForm = selfInstance.$dzsSelectorMain.parent().find('select').eq(0);
  }


  if (selfInstance.$wrapperMain.next().hasClass(CLASS_FEEDER)) {
    selfInstance.$feederObject = selfInstance.$wrapperMain.next();
  } else {
    if (selfInstance.$wrapperMain.find('.' + CLASS_FEEDER).length) {
      selfInstance.$feederObject = selfInstance.$wrapperMain.find('.' + CLASS_FEEDER).eq(0);
    }
  }


  // -- if we have ul element
  if (selfInstance.$dzsSelectorMain.get(0) && selfInstance.$dzsSelectorMain.get(0).nodeName === 'UL') {
    setup_list(selfInstance);
  }


  // -- if no feeder object is found and we have a opener style, try to feed from the actual select
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
      opener_setupStructure(selfInstance);


      if (selfInstance.$openerMain) {

        selfInstance.$openerMain.find('.select-option').eq(selfInstance.selectedIndex).addClass('active-checked');
      }
    }
  }


  let selectorType = '';

  if (selfInstance.$dzsSelectorMain.get(0).nodeName === 'SELECT') {
    selectorType = SELECTOR_TYPE.SELECT;
  }

  const $inputCheckboxes = selfInstance.$wrapperMain.find('input[type="checkbox"]');
  if ($inputCheckboxes.length) {
    selectorType = SELECTOR_TYPE.MULTIPLE_CHECKBOXES;
  }

  if (!selectorType) {

    const $inputSelect = selfInstance.$wrapperMain.find('select');
    if ($inputSelect.length) {

      selectorType = SELECTOR_TYPE.SELECT;
    }
  }

  if (selectorType === SELECTOR_TYPE.SELECT) {

    selfInstance.typeSelectIsAllowEmptyOptions = !!((selfInstance.$selectInputForm.find('option:not([value])')).length || (selfInstance.$selectInputForm.find('option[value=""]')).length);

  }


  selfInstance.$wrapperMain.addClass('selector-type-' + selectorType);
  selfInstance.selectorOptions.type = selectorType;


}

/**
 *
 * @param {DzsSelector} selfInstance
 */
export function setup_list(selfInstance) {

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
export function setup_determineValueCurrent(selfInstance) {

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
export function selector_initial_setup(selfInstance) {

  let o = selfInstance.initOptions;


  selfInstance.feedElementClass = selfInstance.feedElementClass.replace('dzs-style-me', '');

  selfInstance.$dzsSelectorMain = jQuery(selfInstance.$element_);


  if (selfInstance.$dzsSelectorMain.hasClass('treated') || (selfInstance.$dzsSelectorMain.parent().hasClass('dzs-select-wrapper') && selfInstance.$dzsSelectorMain.parent().hasClass('inited')) || selfInstance.$dzsSelectorMain.parent().hasClass('select-wrapper')) {
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
    } catch (err) {

    }
  }

  selfInstance.selectorOptions.opener = o.opener;
}


/**
 * called from main init
 * @param {DzsSelector} selfInstance
 */
export function structure_generateWrapperHead(selfInstance) {


  var o = selfInstance.initOptions;


  var structure_wrapperHead = '<div class="dzs-select-wrapper-head">';


  structure_wrapperHead += '<span class="the-text the-text--placeholder">' + selfInstance.valueCurrent + '</span>';


  if (selfInstance.$wrapperMain.hasClass('skin-charm')) {
    structure_wrapperHead += assets.plus_sign;
  }

  structure_wrapperHead += '</div>';


  return structure_wrapperHead;
}