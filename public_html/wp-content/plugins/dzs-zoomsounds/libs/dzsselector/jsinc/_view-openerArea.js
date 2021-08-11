import {CLASS_FEEDER, SELECTOR_TYPE} from "../configs/_selectorConfig";
import {opener_reinit_checkMultipleActiveOptions} from "./_opener-functions";

/**
 *
 * @param {DzsSelector} selfInstance
 */
export function opener_setupStructure(selfInstance) {
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
export function opener_clearBigOptions(selfInstance) {

  const $externalArea = jQuery('.dzs-select--opener-external-area');
  if($externalArea.length){
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
  feedOptions.forEach((feedOption) => {

    let extraClasses = '';
    let extraAttr = '';

    if(feedOption.isForValueSelectNull){
      extraClasses+=' is-for-value-select-null';
    }
    if(feedOption.bigOptionVal){
      extraAttr+=' data-value="'+feedOption.bigOptionVal+'"';
    }

    const feedOptionHtml = `<${feedOption.bigOptionHtmlTag} class="bigoption select-option ${feedOption.bigOptionExtraClasses} ${extraClasses}" ${extraAttr} ${feedOption.bigOptionExtraAttr}>${feedOption.bigOptionHtml}</${feedOption.bigOptionHtmlTag}>`;

    if (selfInstance.$openerMain) {
      selfInstance.$openerMain.append(feedOptionHtml);
    } else {
      selfInstance.$openerWrap.append(feedOptionHtml);
    }
  })
}


/**
 *
 * @param {DzsSelector} selfInstance
 */
export function opener_setupBigOptions(selfInstance) {
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
      if (selfInstance.$dzsSelectorMain.children('.'+CLASS_FEEDER).length) {
        $feederObject = selfInstance.$dzsSelectorMain.children('.'+CLASS_FEEDER).eq(0);
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

      if (selfInstance.selectorOptions.type === SELECTOR_TYPE.SELECT) {

        if(selfInstance.$selectInputForm.find('option').eq(i).val()===''){
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
        bigOptionExtraAttr += ' data-term_slug="' + $feedObject.attr('data-term_slug') + '"'
      }
      if ($feedObject.attr('data-term_id')) {
        bigOptionExtraAttr += ' data-term_id="' + $feedObject.attr('data-term_id') + '"'
      }
      if ($feedObject.attr('data-type')) {
        bigOptionExtraAttr += ' data-type="' + $feedObject.attr('data-type') + '"'
      }

      feedOptions.push({
        bigOptionHtmlTag: str_domTag,
        bigOptionHtml: bigOptionHtml,
        bigOptionExtraAttr,
        bigOptionVal,
        isForValueSelectNull,
        bigOptionExtraClasses: bigOptionExtraClasses,
      })


    }
  } else {
    // -- no feeder


  }

  opener_appendBigOptions(selfInstance, feedOptions);


  if ($feederObject.hasClass(CLASS_FEEDER)) {
    $feederObject.children().remove();
  }

  setTimeout(function () {
    if (selfInstance.$selectInputForm && !selfInstance.$selectInputForm.hasClass('do-not-trigger-change-on-reinit')) {
      selfInstance.$selectInputForm.trigger('change');
    }


    if (selfInstance.$openerWrap) {

      var aux = selfInstance.$openerWrap.html();


      if (aux.indexOf('{{starssvg}}') > -1) {
        aux = aux.replace(/{{starssvg}}/g, window.svg_star + window.svg_star + window.svg_star + window.svg_star + window.svg_star)
        selfInstance.$openerWrap.html(aux);
      }


    }


  }, 100);


  if(selfInstance.selectorOptions.opener.indexOf('opener-')===0){
    if (selfInstance.selectorOptions.type === SELECTOR_TYPE.MULTIPLE_CHECKBOXES) {
      opener_reinit_checkMultipleActiveOptions(selfInstance)
      selfInstance.$openerMain.find('input').each(function(){
        this.parent_selector_wrapper = selfInstance.$wrapperMain;
      })
    }
  }
}

/**
 *
 * @param {DzsSelector} selfInstance
 */
export function opener_determineAreas(selfInstance) {
  let {selectorOptions} = selfInstance;


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