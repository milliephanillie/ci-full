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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./resources/js/main.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/css/app.scss":
/*!********************************!*\
  !*** ./resources/css/app.scss ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nModuleBuildError: Module build failed (from ./node_modules/sass-loader/dist/cjs.js):\nError: Node Sass version 6.0.0 is incompatible with ^4.0.0.\n    at getSassImplementation (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/sass-loader/dist/getSassImplementation.js:46:13)\n    at Object.loader (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/sass-loader/dist/index.js:40:61)\n    at /Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/webpack/lib/NormalModule.js:316:20\n    at /Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:367:11\n    at /Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:233:18\n    at runSyncOrAsync (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:143:3)\n    at iterateNormalLoaders (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:232:2)\n    at iterateNormalLoaders (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:221:10)\n    at /Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:236:3\n    at runSyncOrAsync (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:130:11)\n    at iterateNormalLoaders (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:232:2)\n    at Array.<anonymous> (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/loader-runner/lib/LoaderRunner.js:205:4)\n    at Storage.finished (/Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/webpack/node_modules/enhanced-resolve/lib/CachedInputFileSystem.js:55:16)\n    at /Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/webpack/node_modules/enhanced-resolve/lib/CachedInputFileSystem.js:91:9\n    at /Users/banestojanovic/Code/Themeforest/lisfinity/wp-content/themes/lisfinity/node_modules/graceful-fs/graceful-fs.js:115:16\n    at FSReqCallback.readFileAfterClose [as oncomplete] (node:internal/fs/read_file_context:69:3)");

/***/ }),

/***/ "./resources/js/main.js":
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _css_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/app.scss */ "./resources/css/app.scss");
/* harmony import */ var _css_app_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_app_scss__WEBPACK_IMPORTED_MODULE_0__);
// Build CSS



/***/ })

/******/ });
//# sourceMappingURL=main.js.map