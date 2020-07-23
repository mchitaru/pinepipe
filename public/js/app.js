(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/app"],{

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
global.$ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
global.jQuery = global.$;

__webpack_require__(/*! jquery-ujs */ "./node_modules/jquery-ujs/src/rails.js");

__webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.js");

__webpack_require__(/*! select2 */ "./node_modules/select2/dist/js/select2.js");

__webpack_require__(/*! easy-autocomplete */ "./node_modules/easy-autocomplete/dist/jquery.easy-autocomplete.js");

__webpack_require__(/*! dropzone */ "./node_modules/dropzone/dist/dropzone.js");

__webpack_require__(/*! ./mrare */ "./resources/js/mrare/index.js");

var Lang = __webpack_require__(/*! lang.js */ "./node_modules/lang.js/src/lang.js");

var messages = __webpack_require__(/*! ../lang/js.json */ "./resources/lang/js.json");

window.lang = new Lang({
  messages: messages
});
window.lang.setFallback('en'); // window.Vue = require('vue');
// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */
// // const files = require.context('./', true, /\.vue$/i)
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */
// const app = new Vue({
//     el: '#app',
// });
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./resources/js/mrare/chat.js":
/*!************************************!*\
  !*** ./resources/js/mrare/chat.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var autosize__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! autosize */ "./node_modules/autosize/dist/autosize.js");
/* harmony import */ var autosize__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(autosize__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _util__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./util */ "./resources/js/mrare/util.js");
//
//
// chat.js
//
// Initializes the autosize library and scrolls chat list to bottom
//



autosize__WEBPACK_IMPORTED_MODULE_1___default()(document.querySelectorAll('.chat-module-bottom textarea')); // Scrolls the chat-module-body to the bottom

(function ($) {
  $(window).on('load', function () {
    var lastChatItems = document.querySelectorAll('.media.chat-item:last-child');

    if (lastChatItems) {
      _util__WEBPACK_IMPORTED_MODULE_2__["default"].forEach(lastChatItems, function (index, item) {
        item.scrollIntoView();
      });
    }
  });
})(jquery__WEBPACK_IMPORTED_MODULE_0___default.a);

/***/ }),

/***/ "./resources/js/mrare/dropzone.js":
/*!****************************************!*\
  !*** ./resources/js/mrare/dropzone.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var dropzone__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! dropzone */ "./node_modules/dropzone/dist/dropzone.js");
/* harmony import */ var dropzone__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(dropzone__WEBPACK_IMPORTED_MODULE_1__);
//
//
// dropzone.js
//
// Initializes dropzone plugin on elements to facilitate drag/drop for uploads
//


dropzone__WEBPACK_IMPORTED_MODULE_1___default.a.autoDiscover = false;
/*
(($) => {
  $(() => {
    let template = `<li class="list-group-item dz-preview dz-file-preview">
      <div class="media align-items-center dz-details">
        <ul class="avatars">
          <li>
            <div class="avatar bg-primary dz-file-representation">
              <i class="material-icons">attach_file</i>
            </div>
          </li>
        </ul>
        <div class="media-body d-flex justify-content-between align-items-center">
          <div class="dz-file-details">
            <span class="dz-filename"><span data-dz-name></span></span<br>
            <span class="text-small dz-size" data-dz-size></span>
          </div>
          <img alt="Loader" src="assets/img/loader.svg" class="dz-loading" />
          <div class="dropdown">
            <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item text-danger" href="#" data-dz-remove>Delete</a>
            </div>
          </div>
          <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>
            Cancel
          </button>
        </div>
      </div>
      <div class="progress dz-progress">
        <div class="progress-bar dz-upload" data-dz-uploadprogress></div>
      </div>
    </li>`;
    template = document.querySelector('.dz-template') ? document.querySelector('.dz-template').innerHTML : template;
    $('.dropzone').dropzone({
      previewTemplate: template,
      thumbnailWidth: 320,
      thumbnailHeight: 320,
      thumbnailMethod: 'contain',
      previewsContainer: '.dropzone-previews',
    });
  });
})(jQuery);
*/

/***/ }),

/***/ "./resources/js/mrare/filter.js":
/*!**************************************!*\
  !*** ./resources/js/mrare/filter.js ***!
  \**************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var list_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! list.js */ "./node_modules/list.js/src/index.js");
/* harmony import */ var list_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(list_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _util__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./util */ "./resources/js/mrare/util.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

//
//
// filter.js
//
// Initialises the List.js plugin and provides interface to list objects
//




var mrFilterList = function ($) {
  /**
   * Check for List.js dependency
   * List.js - http://listjs.com
   */
  if (typeof list_js__WEBPACK_IMPORTED_MODULE_1___default.a === 'undefined') {
    throw new Error('mrFilterList requires list.js (http://listjs.com)');
  }
  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */


  var NAME = 'mrFilterList';
  var VERSION = '1.0.0';
  var DATA_KEY = 'mr.filterList';
  var EVENT_KEY = ".".concat(DATA_KEY);
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var Event = {
    LOAD_DATA_API: "load".concat(EVENT_KEY).concat(DATA_API_KEY)
  };
  var Selector = {
    FILTER: '[data-filter-list]',
    DATA_ATTR: 'filter-list',
    DATA_ATTR_CAMEL: 'filterList',
    DATA_FILTER_BY: 'data-filter-by',
    DATA_FILTER_BY_CAMEL: 'filterBy',
    FILTER_INPUT: 'filter-list-input',
    FILTER_TEXT: 'filter-by-text'
  };
  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var FilterList = /*#__PURE__*/function () {
    function FilterList(element) {
      _classCallCheck(this, FilterList);

      // The current data-filter-list element
      this.element = element; // Get class of list elements to be used within this data-filter-list element

      var listData = element.dataset[Selector.DATA_ATTR_CAMEL]; // data-filter-by rules collected from filterable elements
      // to be passed to List.js

      this.valueNames = []; // List.js instances included in this filterList

      this.lists = []; // Find all matching list elements and initialise List.js on each

      this.initAllLists(listData); // Bind the search input to each list in the array of lists

      this.bindInputEvents();
    } // version getter


    _createClass(FilterList, [{
      key: "initAllLists",
      value: function initAllLists(listData) {
        var _this = this;

        // Initialise each list matching the selector in data-filter-list attribute
        _util__WEBPACK_IMPORTED_MODULE_2__["default"].forEach(this.element.querySelectorAll(".".concat(listData)), function (index, listElement) {
          _this.initList(_this.element, listElement);
        });
      }
    }, {
      key: "initList",
      value: function initList(element, listElement) {
        var _this2 = this;

        // Each individual list needs a unique ID to be added
        // as a class as List.js identifies lists by class
        var listID = "".concat(Selector.DATA_ATTR, "-").concat(new Date().getTime()); // Use the first child of the list and parse all data-filter-by attributes inside.
        // Pass to parseFilters to construct an array of valueNames appropriate for List.js

        var filterables = listElement.querySelectorAll("*:first-child [".concat(Selector.DATA_FILTER_BY, "]"));
        _util__WEBPACK_IMPORTED_MODULE_2__["default"].forEach(filterables, function (index, filterElement) {
          // Parse the comma separated values in the data-filter-by attribute
          // on each filterable element
          _this2.parseFilters(listElement, filterElement, filterElement.dataset[Selector.DATA_FILTER_BY_CAMEL]);
        }); // De-duplicate the array by creating new set of stringified objects and
        // mapping back to parsed objects.
        // This is necessary because similar items in the list element could produce
        // the same rule in the valueNames array.

        this.valueNames = _util__WEBPACK_IMPORTED_MODULE_2__["default"].dedupArray(this.valueNames); // Add unique ID as class to the list so List.js can handle it individually

        listElement.classList.add(listID); // Set up the list instance using the List.js library

        var list = new list_js__WEBPACK_IMPORTED_MODULE_1___default.a(element, {
          valueNames: this.valueNames,
          listClass: listID
        }); // Add this list instance to the array associated with this filterList instance
        // as each filterList can have miltiple list instances connected to the
        // same filter-list-input

        this.lists.push(list);
      }
    }, {
      key: "parseFilters",
      value: function parseFilters(listElement, filterElement, filterBy) {
        var _this3 = this;

        // Get a jQuery instance of the list for easier class manipulation on multiple elements
        var $listElement = $(listElement);
        var filters = []; // Get array of filter-by instructions from the data-filter-by attribute

        try {
          filters = filterBy.split(',');
        } catch (err) {
          throw new Error("Cannot read comma separated data-filter-by attribute: \"\n          ".concat(filterBy, "\" on element: \n          ").concat(this.element));
        }

        filters.forEach(function (filter) {
          // Store appropriate rule for List.js in the valueNames array
          if (filter === 'text') {
            if (filterElement.className !== "".concat(filterElement.nodeName, "-").concat(Selector.FILTER_TEXT)) {
              _this3.valueNames.push("".concat(filterElement.className, " ").concat(filterElement.nodeName, "-").concat(Selector.FILTER_TEXT));
            }

            $listElement.find("".concat(filterElement.nodeName.toLowerCase(), "[").concat(Selector.DATA_FILTER_BY, "*=\"text\"]")) // Prepend element type to class on filterable element as List.js needs separate classes
            .addClass("".concat(filterElement.nodeName, "-").concat(Selector.FILTER_TEXT));
          } else if (filter.indexOf('data-') === 0) {
            $listElement.find("[".concat(Selector.DATA_FILTER_BY, "*=\"").concat(filter, "\"]")).addClass("filter-by-".concat(filter));

            _this3.valueNames.push({
              name: "filter-by-".concat(filter),
              data: filter.replace('data-', '')
            });
          } else if (filterElement.getAttribute(filter)) {
            $listElement.find("[".concat(Selector.DATA_FILTER_BY, "*=\"").concat(filter, "\"]")).addClass("filter-by-".concat(filter));

            _this3.valueNames.push({
              name: "filter-by-".concat(filter),
              attr: filter
            });
          }
        });
      }
    }, {
      key: "bindInputEvents",
      value: function bindInputEvents() {
        var filterInput = this.element.querySelector(".".concat(Selector.FILTER_INPUT));
        $(filterInput).val(''); // Store reference to data-filter-list element on the input itself

        $(filterInput).data(DATA_KEY, this); // filterInput.addEventListener('keyup', this.searchLists, false);
        // filterInput.addEventListener('paste', this.searchLists, false);

        filterInput.addEventListener('input', this.searchLists, false); // filterInput.addEventListener('change', this.searchLists, false);
        // Handle submit to disable page reload

        filterInput.closest('form').addEventListener('submit', function (evt) {
          if (evt.preventDefault) {// evt.preventDefault();
          }
        });
      }
    }, {
      key: "searchLists",
      value: function searchLists(event) {
        // Retrieve the filterList object from the element
        var filterList = $(this).data(DATA_KEY); // Apply the currently searched term to the List.js instances in this filterList instance

        _util__WEBPACK_IMPORTED_MODULE_2__["default"].forEach(filterList.lists, function (index, list) {
          list.search(event.target.value);
        });
      }
    }], [{
      key: "jQueryInterface",
      value: function jQueryInterface() {
        return this.each(function jqEachFilterList() {
          var $element = $(this);
          var data = $element.data(DATA_KEY); // if (!data) {

          data = new FilterList(this);
          $element.data(DATA_KEY, data); // }
        });
      }
    }, {
      key: "VERSION",
      get: function get() {
        return VERSION;
      }
    }]);

    return FilterList;
  }(); // END Class definition

  /**
   * ------------------------------------------------------------------------
   * Initialise by data attribute
   * ------------------------------------------------------------------------
   */


  $(window).on(Event.LOAD_DATA_API, function () {
    var filterLists = $.makeArray($(Selector.FILTER));
    /* eslint-disable no-plusplus */

    for (var i = filterLists.length; i--;) {
      var $list = $(filterLists[i]);
      FilterList.jQueryInterface.call($list, $list.data());
    }
  });
  $(document).on("paginate-sort paginate-tag paginate-click paginate-filter paginate-load", function (e) {
    var filterLists = $.makeArray($(Selector.FILTER));
    /* eslint-disable no-plusplus */

    for (var i = filterLists.length; i--;) {
      var $list = $(filterLists[i]);
      FilterList.jQueryInterface.call($list, $list.data());
    }
  });
  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  /* eslint-disable no-param-reassign */

  $.fn[NAME] = FilterList.jQueryInterface;
  $.fn[NAME].Constructor = FilterList;

  $.fn[NAME].noConflict = function FilterListNoConflict() {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return FilterList.jQueryInterface;
  };
  /* eslint-enable no-param-reassign */


  return FilterList;
}(jquery__WEBPACK_IMPORTED_MODULE_0___default.a);

/* harmony default export */ __webpack_exports__["default"] = (mrFilterList);

/***/ }),

/***/ "./resources/js/mrare/flatpickr.js":
/*!*****************************************!*\
  !*** ./resources/js/mrare/flatpickr.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flatpickr__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flatpickr */ "./node_modules/flatpickr/dist/flatpickr.js");
/* harmony import */ var flatpickr__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flatpickr__WEBPACK_IMPORTED_MODULE_1__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

//
//
// flatpickr.js
//
// an initializer for the flatpickr date/time picker plugin
// https://flatpickr.js.org/
//



var mrFlatpickr = function ($) {
  /**
   * Check for flatpickr dependency
   */
  if (typeof flatpickr__WEBPACK_IMPORTED_MODULE_1___default.a === 'undefined') {
    throw new Error('mrFlatpickr requires flatpickr.js (https://github.com/flatpickr/flatpickr)');
  }
  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */


  var NAME = 'mrFlatpickr';
  var VERSION = '1.0.0';
  var DATA_KEY = 'mr.flatpickr';
  var EVENT_KEY = ".".concat(DATA_KEY);
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var Event = {
    LOAD_DATA_API: "load".concat(EVENT_KEY).concat(DATA_API_KEY)
  };
  var Selector = {
    FLATPICKR: '[data-flatpickr]'
  };
  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Flatpickr = /*#__PURE__*/function () {
    function Flatpickr(element) {
      _classCallCheck(this, Flatpickr);

      // The current flatpickr element
      this.element = element; // const $element = $(element);

      this.initflatpickr();
    } // getters


    _createClass(Flatpickr, [{
      key: "initflatpickr",
      value: function initflatpickr() {
        var options = $(this.element).data();
        this.instance = flatpickr__WEBPACK_IMPORTED_MODULE_1___default()(this.element, options);
      }
    }], [{
      key: "jQueryInterface",
      value: function jQueryInterface() {
        return this.each(function jqEachFlatpickr() {
          var $element = $(this);
          var data = $element.data(DATA_KEY);

          if (!data) {
            data = new Flatpickr(this);
            $element.data(DATA_KEY, data);
          }
        });
      }
    }, {
      key: "VERSION",
      get: function get() {
        return VERSION;
      }
    }]);

    return Flatpickr;
  }(); // END Class definition

  /**
   * ------------------------------------------------------------------------
   * Initialise by data attribute
   * ------------------------------------------------------------------------
   */


  $(window).on(Event.LOAD_DATA_API, function () {
    var pickers = $.makeArray($(Selector.FLATPICKR));
    /* eslint-disable no-plusplus */

    for (var i = pickers.length; i--;) {
      var $flatpickr = $(pickers[i]);
      Flatpickr.jQueryInterface.call($flatpickr, $flatpickr.data());
    }
  });
  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  /* eslint-disable no-param-reassign */

  $.fn[NAME] = Flatpickr.jQueryInterface;
  $.fn[NAME].Constructor = Flatpickr;

  $.fn[NAME].noConflict = function flatpickrNoConflict() {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Flatpickr.jQueryInterface;
  };
  /* eslint-enable no-param-reassign */


  return Flatpickr;
}(jquery__WEBPACK_IMPORTED_MODULE_0___default.a);

/* harmony default export */ __webpack_exports__["default"] = (mrFlatpickr);

/***/ }),

/***/ "./resources/js/mrare/index.js":
/*!*************************************!*\
  !*** ./resources/js/mrare/index.js ***!
  \*************************************/
/*! exports provided: mrFilterList, mrFlatpickr, mrUtil */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _chat__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./chat */ "./resources/js/mrare/chat.js");
/* harmony import */ var _dropzone__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dropzone */ "./resources/js/mrare/dropzone.js");
/* harmony import */ var _filter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./filter */ "./resources/js/mrare/filter.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "mrFilterList", function() { return _filter__WEBPACK_IMPORTED_MODULE_2__["default"]; });

/* harmony import */ var _flatpickr__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./flatpickr */ "./resources/js/mrare/flatpickr.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "mrFlatpickr", function() { return _flatpickr__WEBPACK_IMPORTED_MODULE_3__["default"]; });

/* harmony import */ var _prism__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./prism */ "./resources/js/mrare/prism.js");
/* harmony import */ var _util__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./util */ "./resources/js/mrare/util.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "mrUtil", function() { return _util__WEBPACK_IMPORTED_MODULE_5__["default"]; });








(function () {
  if (typeof $ === 'undefined') {
    throw new TypeError('Medium Rare JavaScript requires jQuery. jQuery must be included before theme.js.');
  }
})();



/***/ }),

/***/ "./resources/js/mrare/prism.js":
/*!*************************************!*\
  !*** ./resources/js/mrare/prism.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var prismjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! prismjs */ "./node_modules/prismjs/prism.js");
/* harmony import */ var prismjs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(prismjs__WEBPACK_IMPORTED_MODULE_0__);
//
//
// prism.js
//
// Initialises the prism code highlighting plugin

prismjs__WEBPACK_IMPORTED_MODULE_0___default.a.highlightAll();

/***/ }),

/***/ "./resources/js/mrare/util.js":
/*!************************************!*\
  !*** ./resources/js/mrare/util.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
//
//
// Util
//
// Medium Rare utility functions
// v 1.2.0


var mrUtil = function ($) {
  var VERSION = '1.2.0';
  var Tagname = {
    SCRIPT: 'script'
  };
  var Selector = {
    RECAPTCHA: '[data-recaptcha]'
  }; // Activate tooltips

  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    container: 'body'
  }); // Activate popovers

  $('body').popover({
    selector: '[data-toggle="popover"]',
    container: 'body'
  }); // Activate toasts

  $('.toast').toast();
  var Util = {
    version: VERSION,
    selector: Selector,
    activateIframeSrc: function activateIframeSrc(iframe) {
      var $iframe = $(iframe);

      if ($iframe.attr('data-src')) {
        $iframe.attr('src', $iframe.attr('data-src'));
      }
    },
    idleIframeSrc: function idleIframeSrc(iframe) {
      var $iframe = $(iframe);
      $iframe.attr('data-src', $iframe.attr('src')).attr('src', '');
    },
    forEach: function forEach(array, callback, scope) {
      if (array) {
        if (array.length) {
          for (var i = 0; i < array.length; i += 1) {
            callback.call(scope, i, array[i]); // passes back stuff we need
          }
        } else if (array[0] || mrUtil.isElement(array)) {
          callback.call(scope, 0, array);
        }
      }
    },
    dedupArray: function dedupArray(arr) {
      return arr.reduce(function (p, c) {
        // create an identifying String from the object values
        var id = JSON.stringify(c); // if the JSON string is not found in the temp array
        // add the object to the output array
        // and add the key to the temp array

        if (p.temp.indexOf(id) === -1) {
          p.out.push(c);
          p.temp.push(id);
        }

        return p; // return the deduped array
      }, {
        temp: [],
        out: []
      }).out;
    },
    isElement: function isElement(obj) {
      return !!(obj && obj.nodeType === 1);
    },
    getFuncFromString: function getFuncFromString(funcName, context) {
      var findFunc = funcName || null; // if already a function, return

      if (typeof findFunc === 'function') return funcName; // if string, try to find function or method of object (of "obj.func" format)

      if (typeof findFunc === 'string') {
        if (!findFunc.length) return null;
        var target = context || window;
        var func = findFunc.split('.');

        while (func.length) {
          var ns = func.shift();
          if (typeof target[ns] === 'undefined') return null;
          target = target[ns];
        }

        if (typeof target === 'function') return target;
      } // return null if could not parse


      return null;
    },
    getScript: function getScript(source, callback) {
      var script = document.createElement(Tagname.SCRIPT);
      var prior = document.getElementsByTagName(Tagname.SCRIPT)[0];
      script.async = 1;
      script.defer = 1;

      script.onreadystatechange = function (_, isAbort) {
        if (isAbort || !script.readyState || /loaded|complete/.test(script.readyState)) {
          script.onload = null;
          script.onreadystatechange = null;
          script = undefined;

          if (!isAbort && callback && typeof callback === 'function') {
            callback();
          }
        }
      };

      script.onload = script.onreadystatechange;
      script.src = source;
      prior.parentNode.insertBefore(script, prior);
    }
  };
  return Util;
}(jquery__WEBPACK_IMPORTED_MODULE_0___default.a);

/* harmony default export */ __webpack_exports__["default"] = (mrUtil);

/***/ }),

/***/ "./resources/lang/js.json":
/*!********************************!*\
  !*** ./resources/lang/js.json ***!
  \********************************/
/*! exports provided: en.messages, en.errors, ro.messages, ro.errors, default */
/***/ (function(module) {

module.exports = JSON.parse("{\"en.messages\":{\"dropzone.upload\":\"File uploaded\"},\"en.errors\":{\"dropzone.upload\":\"You can only upload images, documents and archives, that are less than 10mb in size\",\"paginate.load\":\"Data could not be loaded. Please try again!\"},\"ro.messages\":{\"dropzone.upload\":\"Fișierul a fost încărcat\"},\"ro.errors\":{\"dropzone.upload\":\"Poți încărca doar imagini, documente și arhive, cu dimensiune mai mică de 10mb\",\"paginate.load\":\"A apărut o eroare la încărcarea datelor. Te rugăm să încerci din nou!\"}}");

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! D:\xampp\htdocs\pinepipe\resources\js\app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! D:\xampp\htdocs\pinepipe\resources\sass\app.scss */"./resources/sass/app.scss");


/***/ })

},[[0,"/js/manifest","/js/vendor"]]]);