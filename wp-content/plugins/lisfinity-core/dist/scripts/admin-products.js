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
/******/ 	return __webpack_require__(__webpack_require__.s = "./resources/scripts/admin/products.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/scripts/admin/products.js":
/*!*********************************************!*\
  !*** ./resources/scripts/admin/products.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

__webpack_require__(/*! @styles/admin */ "./resources/styles/admin/index.scss");
var listing = lc_data.product_listing;
var paymentPackage = lc_data.payment_package;
var _lc_data = lc_data,
  promotion = _lc_data.promotion,
  commission = _lc_data.commission,
  payment_subscription = _lc_data.payment_subscription;
var listingMeta = document.getElementById('carbon_fields_container_product_information1');
var packageMeta = document.getElementById('carbon_fields_container_package_information');
var promotionMeta = document.getElementById('carbon_fields_container_promotion_information');
var subscriptionMeta = document.getElementById('carbon_fields_container_payment_subscription_information');
var postExcerpt = document.getElementById('postexcerpt');
var productType = $('#product-type');
productType.change(function (e) {
  listingMeta.classList.add('is-hidden');
  packageMeta.classList.add('is-hidden');
  promotionMeta.classList.add('is-hidden');
  subscriptionMeta.classList.add('is-hidden');
  postExcerpt.classList.remove('hide-if-js');
  if (listing === e.target.value) {
    listingMeta.classList.remove('is-hidden');
  }
  if (paymentPackage === e.target.value) {
    packageMeta.classList.remove('is-hidden');
  }
  if (payment_subscription === e.target.value) {
    subscriptionMeta.classList.remove('is-hidden');
  }
  if (promotion === e.target.value) {
    promotionMeta.classList.remove('is-hidden');
  }
  if (listing === e.target.value || paymentPackage === e.target.value || promotion === e.target.value || commission === e.target.value) {
    postExcerpt.classList.add('hide-if-js');
  }
  if (commission === e.target.value) {
    $('._sale_price_field, ._stock_custom_field').addClass('hide-if-js');
  } else {
    $('._sale_price_field, ._stock_custom_field').removeClass('hide-if-js');
  }
  if (promotion !== e.target.value && paymentPackage !== e.target.value && payment_subscription !== e.target.value) {
    $('#inventory_product_data ._sold_individually_field input[type=checkbox]').prop('checked', true);
  } else {
    $('#inventory_product_data ._sold_individually_field input[type=checkbox]').prop('checked', false);
  }
  if (listing === e.target.value || commission === e.target.value || promotion === e.target.value || paymentPackage === e.target.value || payment_subscription === e.target.value) {
    $('#_virtual').prop('checked', true);
  }
});
productType.change();
$('.general_options').addClass("show_if_simple show_if_grouped show_if_variable show_if_".concat(listing, " show_if_").concat(paymentPackage, " show_if_").concat(promotion, " show_if_").concat(commission, " show_if_").concat(payment_subscription));
$('.options_group.pricing').addClass("show_if_".concat(listing, " show_if_").concat(paymentPackage, " show_if_").concat(promotion, " show_if_").concat(commission, " show_if_").concat(payment_subscription)).show();
$('.shipping_options').addClass("hide_if_".concat(listing, " hide_if_").concat(paymentPackage, " hide_if_").concat(promotion, " hide_if_").concat(commission, " hide_if_").concat(payment_subscription));
$('.linked_product_options').addClass("hide_if_".concat(listing, " hide_if_").concat(paymentPackage, " hide_if_").concat(promotion, " hide_if_").concat(commission, " hide_if_").concat(payment_subscription));
$('.attribute_options').addClass("hide_if_".concat(listing, " hide_if_").concat(paymentPackage, " hide_if_").concat(promotion, " hide_if_").concat(commission, " hide_if_").concat(payment_subscription));
$('.variations_options').addClass("hide_if_".concat(listing, " hide_if_").concat(paymentPackage, " hide_if_").concat(promotion, " hide_if_").concat(commission, " hide_if_").concat(payment_subscription));
$('.advanced_options').addClass("hide_if_".concat(listing, " hide_if_").concat(paymentPackage, " hide_if_").concat(promotion, " hide_if_").concat(commission, " hide_if_").concat(payment_subscription));
var copyToClipboard = function copyToClipboard(str) {
  var el = document.createElement('textarea');
  el.value = str;
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);
};
function displayTaxonomies() {
  var productCategory = document.getElementsByName('carbon_fields_compact_input[_product-category]');
  var taxonomies = JSON.parse(lc_data.taxonomies);
  if (productCategory && 'listing' === $('#product-type').val()) {
    var productCategoryValue = productCategory[0].value;
    var defaultPostBoxes = ['submit', 'postimage', 'woocommerce-product-images', 'carbon_fields_container_product_videos'];
    $('#side-sortables').children().each(function (i, child) {
      var id = $(child).attr('id');
      var value = id.replace('div', '');
      $(child).hide();
      if (taxonomies[productCategoryValue]) {
        if (taxonomies['common'].includes(value) || taxonomies[productCategoryValue].includes(value) || defaultPostBoxes.includes(value)) {
          $(child).show();
        }
      } else {
        if (taxonomies['common'].includes(value) || defaultPostBoxes.includes(value)) {
          $(child).show();
        }
      }
    });
  } else {
    $('#side-sortables').children().each(function (i, child) {
      var defaultPostBoxes = ['submitdiv', 'postimagediv', 'woocommerce-product-images', 'product_catdiv', 'tagsdiv-product_tag'];
      var id = $(child).attr('id');
      $(child).hide();
      if (defaultPostBoxes.includes(id)) {
        $(child).show();
      }
    });
  }
}
document.addEventListener('DOMContentLoaded', function () {
  displayTaxonomies();
  $('select[name="carbon_fields_compact_input[_product-category]"]').on('change', function () {
    displayTaxonomies();
  });
  $('#product-type').on('change', function () {
    displayTaxonomies();
  });
  var clickToCopy = document.querySelectorAll('.click-to-copy');
  clickToCopy.forEach(function (el) {
    el.addEventListener('click', function () {
      copyToClipboard(el.dataset.value);
      var copySpan = document.createElement('span');
      var copyText = document.createTextNode('Copied!');
      copySpan.appendChild(copyText);
      el.appendChild(copySpan);
      setTimeout(function () {
        copySpan.remove();
      }, 1000);
    });
  });
  var dateInput = document.querySelector('.cf-datetime__input');
  if (dateInput) {
    dateInput.setAttribute('autocomplete', 'off');
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "jquery")))

/***/ }),

/***/ "./resources/styles/admin/index.scss":
/*!*******************************************!*\
  !*** ./resources/styles/admin/index.scss ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ })

/******/ });
//# sourceMappingURL=admin-products.js.map