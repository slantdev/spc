/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (() => {

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
jQuery(function ($) {
  if ($(".counterNumber").length) {
    counterNumber();
  }
  function counterNumber() {
    var counterUp = window.counterUp["default"];
    var callback = function callback(entries) {
      entries.forEach(function (entry) {
        var el = entry.target;
        if (entry.isIntersecting && !el.classList.contains("is-visible")) {
          var _iterator = _createForOfIteratorHelper(counters),
            _step;
          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var counter = _step.value;
              counterUp(counter, {
                duration: 3000,
                delay: 16
              });
              el.classList.add("is-visible");
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        }
      });
    };

    // observer
    var IO = new IntersectionObserver(callback, {
      threshold: 1
    });

    // First element to target
    var el = document.querySelector(".counterNumber");

    // all numbers
    var counters = document.querySelectorAll(".counterNumber");
    IO.observe(el);
  }
  $(window).scroll(function () {
    $(".toAnim").each(function () {
      var _win = $(window),
        _ths = $(this),
        _pos = _ths.offset().top,
        _scroll = _win.scrollTop(),
        _height = _win.height();
      _scroll > _pos - _height * 0.7 ? _ths.addClass("anim") : _ths.removeClass("anim");
    });
  });
  var observerOptions = {
    rootMargin: "0px",
    threshold: 0.2
  };
  var observer = new IntersectionObserver(observerCallback, observerOptions);
  function observerCallback(entries, observer) {
    entries.forEach(function (entry) {
      var node = entry.target;
      if (entry.isIntersecting) {
        node.classList.add("animated");
        return; // if we added the class, exit the function
      }

      // We're not intersecting, so remove the class!
      //node.classList.remove("animated");
    });
  }
  var target = ".animation-item";
  document.querySelectorAll(target).forEach(function (i) {
    if (i) {
      observer.observe(i);
    }
  });

  // MEGA MENU
  $(".has_submenu").hover(function () {
    //console.log('hovered');
  }, function () {
    $(".menu-article").removeAttr("style");
  });
  $(".menu-has-article").hover(function () {
    $(this).closest(".mega-menu--col-content").find(".menu-article").removeClass("active").addClass("inactive");
    var dataArticle = $(this).data("target");
    //console.log(dataArticle);
    $("#" + dataArticle).removeClass("inactive").addClass("active");
  });
  $(".menu-article").hover(function () {
    $(".menu-has-article[data-target='" + $(this).attr("id") + "'] .menu-icon").css({
      visibility: "visible",
      opacity: "100"
    });
  }, function () {
    $(".menu-has-article[data-target='" + $(this).attr("id") + "'] .menu-icon").removeAttr("style");
  });

  // Mobile Menu
  $(".menu-open-btn").click(function (e) {
    e.preventDefault();
    $(".main-nav--div").addClass("open");
    $(".menu-overlay").addClass("active");
    $("body").addClass("overflow-hidden");
  });
  $(".menu-close-btn, .menu-overlay").click(function (e) {
    e.preventDefault();
    $(".main-nav--div").removeClass("open");
    $(".menu-overlay").removeClass("active");
    $("body").removeClass("overflow-hidden");
  });
  $(".menu-right-btn").click(function (e) {
    e.preventDefault();
    $(this).siblings(".mega-menu").addClass("active");
    $(this).siblings(".dropdown-menu").addClass("active");
  });
  $(".menu-back-btn").click(function (e) {
    e.preventDefault();
    $(this).parents(".mega-menu").removeClass("active");
    $(this).parents(".dropdown-menu").removeClass("active");
  });
});

/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/css/editor-style.css":
/*!****************************************!*\
  !*** ./resources/css/editor-style.css ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/css/admin-style.css":
/*!***************************************!*\
  !*** ./resources/css/admin-style.css ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/css/acf-layouts.css":
/*!***************************************!*\
  !*** ./resources/css/acf-layouts.css ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/app": 0,
/******/ 			"assets/css/acf-layouts": 0,
/******/ 			"assets/css/admin-style": 0,
/******/ 			"assets/css/editor-style": 0,
/******/ 			"assets/css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkspc"] = self["webpackChunkspc"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/acf-layouts","assets/css/admin-style","assets/css/editor-style","assets/css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/acf-layouts","assets/css/admin-style","assets/css/editor-style","assets/css/app"], () => (__webpack_require__("./resources/css/app.css")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/acf-layouts","assets/css/admin-style","assets/css/editor-style","assets/css/app"], () => (__webpack_require__("./resources/css/editor-style.css")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/acf-layouts","assets/css/admin-style","assets/css/editor-style","assets/css/app"], () => (__webpack_require__("./resources/css/admin-style.css")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/acf-layouts","assets/css/admin-style","assets/css/editor-style","assets/css/app"], () => (__webpack_require__("./resources/css/acf-layouts.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;