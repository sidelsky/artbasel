import {SELECTOR_TYPE} from "../configs/_selectorConfig";


/**
 *
 * @param {DzsSelector} selfInstance
 */
export function opener_reinit_checkMultipleActiveOptions(selfInstance) {

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
  })

  if (isGoingToUpdateLabel) {
    opener_updateMainLabel(selfInstance);
  }
}

export function opener_updateMainLabel(selfInstance) {

  var stringSelected = selfInstance.constructSelectedOptionsString();

  selfInstance.setLabel(stringSelected);
}

/**
 *
 * @param {DzsSelector} selfInstance
 * @returns {(function(*=): void)|*}
 */
export function opener_open(selfInstance) {

  return function () {

    const o = selfInstance.initOptions;
    const isOpenerActive = selfInstance.$wrapperMain.hasClass('active-checked');

    if (!isOpenerActive) {
      // -- close other openers
      document.querySelectorAll('.dzs-select-wrapper').forEach(($selectWrapper_) => {
        if ($selectWrapper_ === selfInstance.$wrapperMain.get(0)) {
          return false;
        }
        if ($selectWrapper_.getAttribute('class').indexOf('opener-') > -1) {
          if ($selectWrapper_.api_close_opener) {
            $selectWrapper_.api_close_opener();
          }
        }
      })
    }
    selfInstance.$wrapperMain.addClass('active-checked');
    setTimeout(function () {
      selfInstance.$wrapperMain.addClass('active-animation');
    }, 50);
    if (selfInstance.$openerExternalArea) {
      window.opener_externalOpenerToggle(selfInstance);
    }

    if (o.opener === 'opener-list') {

    }
  }
}


export function opener_clearActiveChecked(selfInstance) {

  selfInstance.$openerMain.children().removeClass('active-checked');
}


/**
 *
 * @param {DzsSelector} selfInstance
 * @param {number} currentIndexSelected
 * @param {boolean|undefined} forceIsActive
 */
export function opener_selectOption(selfInstance, currentIndexSelected, forceIsActive) {


  var isActiveChecked = false;


  const $openerChildAtIndex = selfInstance.$openerMain.children().eq(currentIndexSelected);

  if ($openerChildAtIndex.hasClass('active-checked')) {
    isActiveChecked = true;
  }

  if (forceIsActive !== undefined) {
    isActiveChecked = forceIsActive;
  }


  if (selfInstance.selectorOptions.type === SELECTOR_TYPE.SELECT) {
    opener_clearActiveChecked(selfInstance);
  }


  if (!isActiveChecked) {
    $openerChildAtIndex.addClass('active-checked');
  } else {

    if (selfInstance.selectorOptions.type === SELECTOR_TYPE.MULTIPLE_CHECKBOXES) {

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
export function opener_close(selfInstance) {

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

  }
}
