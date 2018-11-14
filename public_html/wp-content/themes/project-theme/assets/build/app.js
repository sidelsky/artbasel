/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(7);


/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__svg_sprite__ = __webpack_require__(2);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__svg_sprite___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__svg_sprite__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_hamburger__ = __webpack_require__(4);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_accordion__ = __webpack_require__(5);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_header__ = __webpack_require__(6);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_header___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__components_header__);\n\n\n\nnew __WEBPACK_IMPORTED_MODULE_1__components_hamburger__[\"a\" /* default */]();\n\n\nnew __WEBPACK_IMPORTED_MODULE_2__components_accordion__[\"a\" /* default */]();\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvQXNzZXRzL2pzL2FwcC5qcz9lMmIxIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7Ozs7QUFBQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EiLCJmaWxlIjoiMS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCAnLi9zdmctc3ByaXRlJztcblxuaW1wb3J0IEhhbWJ1cmdlciBmcm9tICcuL2NvbXBvbmVudHMvaGFtYnVyZ2VyJztcbm5ldyBIYW1idXJnZXIoKTtcblxuaW1wb3J0IEFjY29yZGlvbiBmcm9tICcuL2NvbXBvbmVudHMvYWNjb3JkaW9uJztcbm5ldyBBY2NvcmRpb24oKTtcblxuaW1wb3J0ICcuL2NvbXBvbmVudHMvaGVhZGVyJztcblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvQXNzZXRzL2pzL2FwcC5qcyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///1\n");

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

eval("/* eslint-disable */\n\n// SVG Plugin will find this variable on build and creates\n// the sprite file in the specified path\nvar __svg__ = { filename: __webpack_require__.p +\"/wp-content/themes/project-theme/assets/build/svg-sprite.svg\" };\n\n// Load The SVG Sprite using\nvar svgSprite = {\n    filename: '/wp-content/themes/project-theme/assets/build/svg-sprite.svg'\n};\n\n__webpack_require__(3)(svgSprite);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvQXNzZXRzL2pzL3N2Zy1zcHJpdGUuanM/YWI3MCJdLCJuYW1lcyI6WyJzdmdTcHJpdGUiLCJmaWxlbmFtZSIsInJlcXVpcmUiXSwibWFwcGluZ3MiOiI7O0FBRUE7QUFDQTtBQUNBLElBQUksNkdBQUo7O0FBS0E7QUFDQSxJQUFJQSxZQUFZO0FBQ1pDLGNBQVU7QUFERSxDQUFoQjs7QUFJQSxtQkFBQUMsQ0FBQSxDQUFBQSIsImZpbGUiOiIyLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyogZXNsaW50LWRpc2FibGUgKi9cblxuLy8gU1ZHIFBsdWdpbiB3aWxsIGZpbmQgdGhpcyB2YXJpYWJsZSBvbiBidWlsZCBhbmQgY3JlYXRlc1xuLy8gdGhlIHNwcml0ZSBmaWxlIGluIHRoZSBzcGVjaWZpZWQgcGF0aFxubGV0IF9fc3ZnX18gPSB7XG4gICAgcGF0aDogJy4uL2ltZy9zdmdzLyoqLyouc3ZnJyxcbiAgICBuYW1lOiAnL3dwLWNvbnRlbnQvdGhlbWVzL3Byb2plY3QtdGhlbWUvYXNzZXRzL2J1aWxkL3N2Zy1zcHJpdGUuc3ZnJ1xufTtcblxuLy8gTG9hZCBUaGUgU1ZHIFNwcml0ZSB1c2luZ1xubGV0IHN2Z1Nwcml0ZSA9IHtcbiAgICBmaWxlbmFtZTogJy93cC1jb250ZW50L3RoZW1lcy9wcm9qZWN0LXRoZW1lL2Fzc2V0cy9idWlsZC9zdmctc3ByaXRlLnN2Zydcbn07XG5cbnJlcXVpcmUoJ3dlYnBhY2stc3Znc3RvcmUtcGx1Z2luL3NyYy9oZWxwZXJzL3N2Z3hocicpKHN2Z1Nwcml0ZSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvQXNzZXRzL2pzL3N2Zy1zcHJpdGUuanMiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///2\n");

/***/ }),
/* 3 */
/***/ (function(module, exports) {

eval("/**\n * Load svg via ajax\n * @param  {string} url path to svg sprite\n * @generator: webpack-svgstore-plugin\n * @see: https://www.npmjs.com/package/webpack-svgstore-plugin\n * @return {[type]}     [description]\n */\nvar svgXHR = function(options) {\n  var url = false;\n  var baseUrl = undefined;\n\n  options && options.filename ? (url = options.filename) : null;\n\n  if (!url) return false;\n  var _ajax = new XMLHttpRequest();\n  var _fullPath;\n\n  if (typeof XDomainRequest !== 'undefined') {\n    _ajax = new XDomainRequest();\n  }\n\n  if (typeof baseUrl === 'undefined') {\n    if (typeof window.baseUrl !== 'undefined') {\n      baseUrl = window.baseUrl;\n    } else {\n      baseUrl =\n        window.location.protocol +\n        '//' +\n        window.location.hostname +\n        (window.location.port ? ':' + window.location.port : '');\n    }\n  }\n\n  _fullPath = (baseUrl + '/' + url).replace(/([^:]\\/)\\/+/g, '$1');\n  _ajax.open('GET', _fullPath, true);\n  _ajax.onprogress = function() {};\n  _ajax.onload = function() {\n    if (!_ajax.responseText || _ajax.responseText.substr(0, 4) !== '<svg') {\n      throw Error('Invalid SVG Response');\n    }\n    if (_ajax.status < 200 || _ajax.status >= 300) {\n      return;\n    }\n    var div = document.createElement('div');\n    div.innerHTML = _ajax.responseText;\n\n    domready(function() {\n      document.body.insertBefore(div, document.body.childNodes[0]);\n    });\n  };\n  _ajax.send();\n};\n\n/**\n * jQuery like $.ready function.\n *\n * @param {Function} fn\n * @return void\n */\nfunction domready(callback) {\n  if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {\n    callback();\n  } else {\n    document.addEventListener('DOMContentLoaded', callback);\n  }\n}\n\nmodule.exports = svgXHR;\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vKHdlYnBhY2spLXN2Z3N0b3JlLXBsdWdpbi9zcmMvaGVscGVycy9zdmd4aHIuanM/NzY4MyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0EsWUFBWSxPQUFPO0FBQ25CO0FBQ0E7QUFDQSxZQUFZLE9BQU87QUFDbkI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFdBQVcsU0FBUztBQUNwQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTs7QUFFQSIsImZpbGUiOiIzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBMb2FkIHN2ZyB2aWEgYWpheFxuICogQHBhcmFtICB7c3RyaW5nfSB1cmwgcGF0aCB0byBzdmcgc3ByaXRlXG4gKiBAZ2VuZXJhdG9yOiB3ZWJwYWNrLXN2Z3N0b3JlLXBsdWdpblxuICogQHNlZTogaHR0cHM6Ly93d3cubnBtanMuY29tL3BhY2thZ2Uvd2VicGFjay1zdmdzdG9yZS1wbHVnaW5cbiAqIEByZXR1cm4ge1t0eXBlXX0gICAgIFtkZXNjcmlwdGlvbl1cbiAqL1xudmFyIHN2Z1hIUiA9IGZ1bmN0aW9uKG9wdGlvbnMpIHtcbiAgdmFyIHVybCA9IGZhbHNlO1xuICB2YXIgYmFzZVVybCA9IHVuZGVmaW5lZDtcblxuICBvcHRpb25zICYmIG9wdGlvbnMuZmlsZW5hbWUgPyAodXJsID0gb3B0aW9ucy5maWxlbmFtZSkgOiBudWxsO1xuXG4gIGlmICghdXJsKSByZXR1cm4gZmFsc2U7XG4gIHZhciBfYWpheCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuICB2YXIgX2Z1bGxQYXRoO1xuXG4gIGlmICh0eXBlb2YgWERvbWFpblJlcXVlc3QgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgX2FqYXggPSBuZXcgWERvbWFpblJlcXVlc3QoKTtcbiAgfVxuXG4gIGlmICh0eXBlb2YgYmFzZVVybCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICBpZiAodHlwZW9mIHdpbmRvdy5iYXNlVXJsICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgYmFzZVVybCA9IHdpbmRvdy5iYXNlVXJsO1xuICAgIH0gZWxzZSB7XG4gICAgICBiYXNlVXJsID1cbiAgICAgICAgd2luZG93LmxvY2F0aW9uLnByb3RvY29sICtcbiAgICAgICAgJy8vJyArXG4gICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ob3N0bmFtZSArXG4gICAgICAgICh3aW5kb3cubG9jYXRpb24ucG9ydCA/ICc6JyArIHdpbmRvdy5sb2NhdGlvbi5wb3J0IDogJycpO1xuICAgIH1cbiAgfVxuXG4gIF9mdWxsUGF0aCA9IChiYXNlVXJsICsgJy8nICsgdXJsKS5yZXBsYWNlKC8oW146XVxcLylcXC8rL2csICckMScpO1xuICBfYWpheC5vcGVuKCdHRVQnLCBfZnVsbFBhdGgsIHRydWUpO1xuICBfYWpheC5vbnByb2dyZXNzID0gZnVuY3Rpb24oKSB7fTtcbiAgX2FqYXgub25sb2FkID0gZnVuY3Rpb24oKSB7XG4gICAgaWYgKCFfYWpheC5yZXNwb25zZVRleHQgfHwgX2FqYXgucmVzcG9uc2VUZXh0LnN1YnN0cigwLCA0KSAhPT0gJzxzdmcnKSB7XG4gICAgICB0aHJvdyBFcnJvcignSW52YWxpZCBTVkcgUmVzcG9uc2UnKTtcbiAgICB9XG4gICAgaWYgKF9hamF4LnN0YXR1cyA8IDIwMCB8fCBfYWpheC5zdGF0dXMgPj0gMzAwKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIHZhciBkaXYgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICBkaXYuaW5uZXJIVE1MID0gX2FqYXgucmVzcG9uc2VUZXh0O1xuXG4gICAgZG9tcmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgICBkb2N1bWVudC5ib2R5Lmluc2VydEJlZm9yZShkaXYsIGRvY3VtZW50LmJvZHkuY2hpbGROb2Rlc1swXSk7XG4gICAgfSk7XG4gIH07XG4gIF9hamF4LnNlbmQoKTtcbn07XG5cbi8qKlxuICogalF1ZXJ5IGxpa2UgJC5yZWFkeSBmdW5jdGlvbi5cbiAqXG4gKiBAcGFyYW0ge0Z1bmN0aW9ufSBmblxuICogQHJldHVybiB2b2lkXG4gKi9cbmZ1bmN0aW9uIGRvbXJlYWR5KGNhbGxiYWNrKSB7XG4gIGlmIChkb2N1bWVudC5yZWFkeVN0YXRlID09PSAnY29tcGxldGUnIHx8IChkb2N1bWVudC5yZWFkeVN0YXRlICE9PSAnbG9hZGluZycgJiYgIWRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5kb1Njcm9sbCkpIHtcbiAgICBjYWxsYmFjaygpO1xuICB9IGVsc2Uge1xuICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBjYWxsYmFjayk7XG4gIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBzdmdYSFI7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAod2VicGFjayktc3Znc3RvcmUtcGx1Z2luL3NyYy9oZWxwZXJzL3N2Z3hoci5qc1xuLy8gbW9kdWxlIGlkID0gM1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///3\n");

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\n/**\n * This class adds a click listener to '.js-hamburger'. Once clicked this will\n * toggle the css class \"body--menu-is-expanded\" to document body. This class can be used\n * to style mobile menu and hamburger.\n */\n\nvar Hamburger = function () {\n    function Hamburger() {\n        var _this = this;\n\n        _classCallCheck(this, Hamburger);\n\n        this.body = document.querySelector('body');\n        this.menuTrigger = document.querySelector('.js-hamburger');\n        this.menuTrigger.addEventListener('click', function () {\n            return _this.handleClick();\n        });\n        this.menuIsExpanded = false;\n    }\n\n    _createClass(Hamburger, [{\n        key: 'handleClick',\n        value: function handleClick(e) {\n            if (this.menuIsExpanded) {\n                this.body.classList.remove('body--menu-is-expanded');\n                this.menuIsExpanded = false;\n            } else {\n                this.body.classList.add('body--menu-is-expanded');\n                this.menuIsExpanded = true;\n            }\n        }\n    }]);\n\n    return Hamburger;\n}();\n\n/* harmony default export */ __webpack_exports__[\"a\"] = (Hamburger);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvQXNzZXRzL2pzL2NvbXBvbmVudHMvaGFtYnVyZ2VyLmpzPzQxMTMiXSwibmFtZXMiOlsiY29uc3RydWN0b3IiLCJkb2N1bWVudCIsImhhbmRsZUNsaWNrIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7O0lBTWUsUztBQUNYQSx5QkFBYztBQUFBOztBQUFBOztBQUNWLG9CQUFZQyx1QkFBWixNQUFZQSxDQUFaO0FBQ0EsMkJBQW1CQSx1QkFBbkIsZUFBbUJBLENBQW5CO0FBQ0EsbURBQTJDO0FBQUEsbUJBQU0sTUFBakQsV0FBaUQsRUFBTjtBQUFBLFNBQTNDO0FBQ0E7QUFDSDs7OztvQ0FFREMsQyxFQUFlO0FBQ1gsZ0JBQUksS0FBSixnQkFBeUI7QUFDckI7QUFDQTtBQUZKLG1CQUdPO0FBQ0g7QUFDQTtBQUNIO0FBQ0o7Ozs7Ozt5REFoQlUsUyIsImZpbGUiOiI0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBUaGlzIGNsYXNzIGFkZHMgYSBjbGljayBsaXN0ZW5lciB0byAnLmpzLWhhbWJ1cmdlcicuIE9uY2UgY2xpY2tlZCB0aGlzIHdpbGxcbiAqIHRvZ2dsZSB0aGUgY3NzIGNsYXNzIFwiYm9keS0tbWVudS1pcy1leHBhbmRlZFwiIHRvIGRvY3VtZW50IGJvZHkuIFRoaXMgY2xhc3MgY2FuIGJlIHVzZWRcbiAqIHRvIHN0eWxlIG1vYmlsZSBtZW51IGFuZCBoYW1idXJnZXIuXG4gKi9cblxuZXhwb3J0IGRlZmF1bHQgY2xhc3MgSGFtYnVyZ2VyIHtcbiAgICBjb25zdHJ1Y3RvcigpIHtcbiAgICAgICAgdGhpcy5ib2R5ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYm9keScpO1xuICAgICAgICB0aGlzLm1lbnVUcmlnZ2VyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmpzLWhhbWJ1cmdlcicpO1xuICAgICAgICB0aGlzLm1lbnVUcmlnZ2VyLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4gdGhpcy5oYW5kbGVDbGljaygpKTtcbiAgICAgICAgdGhpcy5tZW51SXNFeHBhbmRlZCA9IGZhbHNlO1xuICAgIH1cblxuICAgIGhhbmRsZUNsaWNrKGUpIHtcbiAgICAgICAgaWYgKHRoaXMubWVudUlzRXhwYW5kZWQpIHtcbiAgICAgICAgICAgIHRoaXMuYm9keS5jbGFzc0xpc3QucmVtb3ZlKCdib2R5LS1tZW51LWlzLWV4cGFuZGVkJyk7XG4gICAgICAgICAgICB0aGlzLm1lbnVJc0V4cGFuZGVkID0gZmFsc2U7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLmJvZHkuY2xhc3NMaXN0LmFkZCgnYm9keS0tbWVudS1pcy1leHBhbmRlZCcpO1xuICAgICAgICAgICAgdGhpcy5tZW51SXNFeHBhbmRlZCA9IHRydWU7XG4gICAgICAgIH1cbiAgICB9XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvQXNzZXRzL2pzL2NvbXBvbmVudHMvaGFtYnVyZ2VyLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///4\n");

/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nvar Accordion = function () {\n    function Accordion() {\n        var _this = this;\n\n        _classCallCheck(this, Accordion);\n\n        this.accordionTrigger = document.getElementsByClassName('accordion');\n\n        for (var i = 0; i < this.accordionTrigger.length; i++) {\n            var currentElement = this.accordionTrigger[i];\n            currentElement.addEventListener('click', function (e) {\n                return _this.handleClick(e);\n            });\n        }\n    }\n\n    _createClass(Accordion, [{\n        key: 'handleClick',\n        value: function handleClick(e) {\n            e.target.parentElement.classList.toggle('active');\n        }\n    }]);\n\n    return Accordion;\n}();\n\n/*\n    var acc = document.getElementsByClassName(\"accordion\"),\n    i,\n    panel;\n\n    for (i = 0; i < acc.length; i++) {\n        acc[i].addEventListener(\"click\", function() {\n            this.parentElement.classList.toggle(\"active\");\n        });\n    }\n*/\n\n\n/* harmony default export */ __webpack_exports__[\"a\"] = (Accordion);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvQXNzZXRzL2pzL2NvbXBvbmVudHMvYWNjb3JkaW9uLmpzP2U5MzMiXSwibmFtZXMiOlsiY29uc3RydWN0b3IiLCJkb2N1bWVudCIsImkiLCJjdXJyZW50RWxlbWVudCIsImhhbmRsZUNsaWNrIiwiZSJdLCJtYXBwaW5ncyI6Ijs7OztJQUFlLFM7QUFFWEEseUJBQWM7QUFBQTs7QUFBQTs7QUFDVixnQ0FBd0JDLGdDQUF4QixXQUF3QkEsQ0FBeEI7O0FBRUEsYUFBSyxJQUFJQyxJQUFULEdBQWdCQSxJQUFJLHNCQUFwQixhQUF1RDtBQUNuRCxnQkFBSUMsaUJBQWlCLHNCQUFyQixDQUFxQixDQUFyQjtBQUNBQSxxREFBeUM7QUFBQSx1QkFBTyxrQkFBaERBLENBQWdELENBQVA7QUFBQSxhQUF6Q0E7QUFDSDtBQUVKOzs7O29DQUVEQyxDLEVBQWU7QUFDWEM7QUFDSDs7Ozs7O0FBT0w7Ozs7Ozs7Ozs7Ozs7eURBckJlLFMiLCJmaWxlIjoiNS5qcyIsInNvdXJjZXNDb250ZW50IjpbImV4cG9ydCBkZWZhdWx0IGNsYXNzIEFjY29yZGlvbiB7XG4gICAgXG4gICAgY29uc3RydWN0b3IoKSB7XG4gICAgICAgIHRoaXMuYWNjb3JkaW9uVHJpZ2dlciA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ2FjY29yZGlvbicpO1xuICAgICAgICBcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0aGlzLmFjY29yZGlvblRyaWdnZXIubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIGxldCBjdXJyZW50RWxlbWVudCA9IHRoaXMuYWNjb3JkaW9uVHJpZ2dlcltpXTtcbiAgICAgICAgICAgIGN1cnJlbnRFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKGUpID0+IHRoaXMuaGFuZGxlQ2xpY2soZSkgKTtcbiAgICAgICAgfVxuICAgICAgICBcbiAgICB9XG4gICAgXG4gICAgaGFuZGxlQ2xpY2soZSkge1xuICAgICAgICBlLnRhcmdldC5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC50b2dnbGUoJ2FjdGl2ZScpXG4gICAgfVxuXG59XG5cblxuXG5cbi8qXG4gICAgdmFyIGFjYyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoXCJhY2NvcmRpb25cIiksXG4gICAgaSxcbiAgICBwYW5lbDtcblxuICAgIGZvciAoaSA9IDA7IGkgPCBhY2MubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgYWNjW2ldLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHRoaXMucGFyZW50RWxlbWVudC5jbGFzc0xpc3QudG9nZ2xlKFwiYWN0aXZlXCIpO1xuICAgICAgICB9KTtcbiAgICB9XG4qL1xuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NyYy9Bc3NldHMvanMvY29tcG9uZW50cy9hY2NvcmRpb24uanMiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///5\n");

/***/ }),
/* 6 */
/***/ (function(module, exports) {

eval("var w = window,\n    header = document.getElementById('header'),\n    body = document.getElementById('body'),\n    headerHeight,\n    title,\n    distance,\n    top;\n\nheaderHeight = header.offsetHeight;\n\n//Animated Header\nfunction animatedHeader() {\n    if (w.pageYOffset >= headerHeight) {\n        body.classList.add(\"is-scrolled\");\n    } else {\n        body.classList.remove(\"is-scrolled\");\n    }\n}\n\n//Hero Paralax\nfunction heroParalax() {\n\n    distance = 5;\n    title = document.getElementById(\"title\");\n    top = w.scrollY;\n\n    if (title) {\n        //title.style.transform = \"translateY(\" + +top / distance + \"px)\";\n        title.style.transform = 'translate3d(0, ' + top / distance + 'px, 0)';\n    }\n}\n\nw.onscroll = function () {\n    animatedHeader();\n    heroParalax();\n};//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvQXNzZXRzL2pzL2NvbXBvbmVudHMvaGVhZGVyLmpzP2Y0YjEiXSwibmFtZXMiOlsidyIsImhlYWRlciIsImRvY3VtZW50IiwiYm9keSIsImhlYWRlckhlaWdodCIsImRpc3RhbmNlIiwidGl0bGUiLCJ0b3AiLCJhbmltYXRlZEhlYWRlciIsImhlcm9QYXJhbGF4Il0sIm1hcHBpbmdzIjoiQUFBQSxJQUFJQSxJQUFKO0FBQUEsSUFDSUMsU0FBU0Msd0JBRGIsUUFDYUEsQ0FEYjtBQUFBLElBRUlDLE9BQU9ELHdCQUZYLE1BRVdBLENBRlg7QUFBQTtBQUFBO0FBQUE7QUFBQTs7QUFRSUUsZUFBZUgsT0FBZkc7O0FBRUE7QUFDQSwwQkFBMEI7QUFDdEIsUUFBSUosaUJBQUosY0FBbUM7QUFDL0JHO0FBREosV0FFTztBQUNIQTtBQUNIO0FBQ0o7O0FBRUQ7QUFDQSx1QkFBdUI7O0FBRW5CRTtBQUNBQyxZQUFRSix3QkFBUkksT0FBUUosQ0FBUkk7QUFDQUMsVUFBTVAsRUFBTk87O0FBRUEsZUFBVTtBQUNOO0FBQ0FELG9EQUEwQ0MsTUFBMUNEO0FBQ0g7QUFFSjs7QUFFRE4sYUFBYSxZQUFXO0FBQ3BCUTtBQUNBQztBQUZKVCIsImZpbGUiOiI2LmpzIiwic291cmNlc0NvbnRlbnQiOlsidmFyIHcgPSB3aW5kb3csXG4gICAgaGVhZGVyID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2hlYWRlcicpLFxuICAgIGJvZHkgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYm9keScpLFxuICAgIGhlYWRlckhlaWdodCxcbiAgICB0aXRsZSxcbiAgICBkaXN0YW5jZSxcbiAgICB0b3A7XG5cbiAgICBoZWFkZXJIZWlnaHQgPSBoZWFkZXIub2Zmc2V0SGVpZ2h0O1xuXG4gICAgLy9BbmltYXRlZCBIZWFkZXJcbiAgICBmdW5jdGlvbiBhbmltYXRlZEhlYWRlcigpIHtcbiAgICAgICAgaWYgKHcucGFnZVlPZmZzZXQgPj0gaGVhZGVySGVpZ2h0KSB7XG4gICAgICAgICAgICBib2R5LmNsYXNzTGlzdC5hZGQoXCJpcy1zY3JvbGxlZFwiKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGJvZHkuY2xhc3NMaXN0LnJlbW92ZShcImlzLXNjcm9sbGVkXCIpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLy9IZXJvIFBhcmFsYXhcbiAgICBmdW5jdGlvbiBoZXJvUGFyYWxheCgpIHtcbiAgICAgICAgXG4gICAgICAgIGRpc3RhbmNlID0gNTtcbiAgICAgICAgdGl0bGUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInRpdGxlXCIpO1xuICAgICAgICB0b3AgPSB3LnNjcm9sbFk7XG5cbiAgICAgICAgaWYodGl0bGUpIHtcbiAgICAgICAgICAgIC8vdGl0bGUuc3R5bGUudHJhbnNmb3JtID0gXCJ0cmFuc2xhdGVZKFwiICsgK3RvcCAvIGRpc3RhbmNlICsgXCJweClcIjtcbiAgICAgICAgICAgIHRpdGxlLnN0eWxlLnRyYW5zZm9ybSA9IGB0cmFuc2xhdGUzZCgwLCAke3RvcCAvIGRpc3RhbmNlfXB4LCAwKWA7XG4gICAgICAgIH1cblxuICAgIH1cblxuICAgIHcub25zY3JvbGwgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgYW5pbWF0ZWRIZWFkZXIoKTtcbiAgICAgICAgaGVyb1BhcmFsYXgoKTtcbiAgICB9O1xuXG5cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NyYy9Bc3NldHMvanMvY29tcG9uZW50cy9oZWFkZXIuanMiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///6\n");

/***/ }),
/* 7 */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvQXNzZXRzL3Njc3Mvc3R5bGUuc2Nzcz8zZWE4Il0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBIiwiZmlsZSI6IjcuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL0Fzc2V0cy9zY3NzL3N0eWxlLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDdcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///7\n");

/***/ })
/******/ ]);