export const decode_json = function (arg) {
  var fout = {};

  if (arg) {

    try {

      fout = JSON.parse(arg);
    } catch (err) {

      return null;
    }
  }

  return fout;
}


export function simpleStringify (object){
  if (object && typeof object === 'object') {
    object = copyWithoutCircularReferences([object], object);
  }
  return JSON.stringify(object);

  function copyWithoutCircularReferences(references, object) {
    var cleanObject = {};
    Object.keys(object).forEach(function(key) {
      var value = object[key];
      if (value && typeof value === 'object') {
        if (references.indexOf(value) < 0) {
          references.push(value);
          cleanObject[key] = copyWithoutCircularReferences(references, value);
          references.pop();
        } else {
          cleanObject[key] = '###_Circular_###';
        }
      } else if (typeof value !== 'function') {
        cleanObject[key] = value;
      }
    });
    return cleanObject;
  }
}

export const loadScriptIfItDoesNotExist = (scriptSrc, checkForVar) => {
  return new Promise((resolve, reject) => {
    if (checkForVar) {
      resolve('loadfromvar');
      return;
    }

    var script = document.createElement('script');
    script.onload = function () {
      resolve('loadfromload');
    };
    script.onerror = function () {
      reject();
    };
    script.src = scriptSrc;

    document.head.appendChild(script);
  })
}


export const getBaseUrl = (baseUrlVar, scriptName) => {
  if (window[baseUrlVar]) {
    return window[baseUrlVar];
  }

  let scripts = document.getElementsByTagName("script");
  for (var scriptKey in scripts) {
    if (scripts[scriptKey] && scripts[scriptKey].src && String(scripts[scriptKey].src).indexOf(scriptName) > -1) {
      break;
    }
  }
  var baseUrl_arr = String(scripts[scriptKey].src).split('/');
  baseUrl_arr.splice(-1, 1);
  const result = string_addTrailingSlash(baseUrl_arr.join('/'));
  window[baseUrlVar] = result+'/';
  return result;
}

function string_addTrailingSlash(url){
  var lastChar = url.substr(-1); // Selects the last character
  if (lastChar != '/') {         // If the last character is not a slash
    url = url + '/';            // Append a slash to it.
  }
  return url;
}
export const sanitizeToCssPx = (arg) => {

  if (String(arg).indexOf('%') > -1 || String(arg).indexOf('em') > -1 || String(arg).indexOf('px') > -1 || String(arg).indexOf('auto') > -1) {
    return arg;
  }
  return arg + 'px';
}


export const setupTooltip = (args) => {

  var mainArgs = Object.assign({
    tooltipInnerHTML: '',
    tooltipIndicatorText: '',
    tooltipConClass: '',
  }, args)

  return `<div class="dzstooltip-con ${mainArgs.tooltipConClass}"><span class="dzstooltip main-tooltip   talign-end arrow-bottom style-rounded color-dark-light  dims-set transition-slidedown " style="width: 280px;"><span class="dzstooltip--inner">${mainArgs.tooltipInnerHTML}</span> </span></span><span class="tooltip-indicator">${mainArgs.tooltipIndicatorText}</span></div>`;
}


export const isInt = function (n) {
  return typeof n == 'number' && Math.round(n) % 1 == 0;
}

export const isValid = function (n) {
  return typeof n != 'undefined' && n != '';
}


export function getRelativeX (mouseX, $el_) {
  if (jQuery) {
    return mouseX - jQuery($el_).offset().left;
  }
}
