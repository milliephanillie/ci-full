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
/******/ 	return __webpack_require__(__webpack_require__.s = "./resources/scripts/admin/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./config.json":
/*!*********************!*\
  !*** ./config.json ***!
  \*********************/
/*! exports provided: variables, development, default */
/***/ (function(module) {

module.exports = JSON.parse("{\"variables\":{\"color\":{\"material-red\":\"#f44336\",\"material-pink\":\"#e91e63\",\"material-purple\":\"#9c27b0\",\"material-deep-purple\":\"#673ab7\",\"material-indigo\":\"#3f51b5\",\"material-blue\":\"#2196f3\",\"material-light-blue\":\"#03a9f4\",\"material-cyan\":\"#00bcd4\",\"material-teal\":\"#009688\",\"material-green\":\"#4caf50\",\"material-light-green\":\"#8bc34a\",\"material-lime\":\"#cddc39\",\"material-yellow\":\"#ffeb3b\",\"material-amber\":\"#ffc107\",\"material-orange\":\"#ff9800\",\"material-deep-orange\":\"#ff5722\",\"material-brown\":\"#795548\",\"material-grey\":\"#9e9e9e\",\"material-blue-grey\":\"#607d8b\"},\"font-size\":{\"xs\":\"12px\",\"s\":\"16px\",\"m\":\"20px\",\"l\":\"28px\",\"xl\":\"36px\"}},\"development\":{\"url\":\"https://dev.concreteiron\"}}");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/interopRequireDefault.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/interopRequireDefault.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _interopRequireDefault(obj) {
  return obj && obj.__esModule ? obj : {
    "default": obj
  };
}
module.exports = _interopRequireDefault, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/axios/index.js":
/*!*************************************!*\
  !*** ./node_modules/axios/index.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./lib/axios */ "./node_modules/axios/lib/axios.js");

/***/ }),

/***/ "./node_modules/axios/lib/adapters/xhr.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/adapters/xhr.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");
var settle = __webpack_require__(/*! ./../core/settle */ "./node_modules/axios/lib/core/settle.js");
var buildURL = __webpack_require__(/*! ./../helpers/buildURL */ "./node_modules/axios/lib/helpers/buildURL.js");
var buildFullPath = __webpack_require__(/*! ../core/buildFullPath */ "./node_modules/axios/lib/core/buildFullPath.js");
var parseHeaders = __webpack_require__(/*! ./../helpers/parseHeaders */ "./node_modules/axios/lib/helpers/parseHeaders.js");
var isURLSameOrigin = __webpack_require__(/*! ./../helpers/isURLSameOrigin */ "./node_modules/axios/lib/helpers/isURLSameOrigin.js");
var createError = __webpack_require__(/*! ../core/createError */ "./node_modules/axios/lib/core/createError.js");

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password || '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    var fullPath = buildFullPath(config.baseURL, config.url);
    request.open(config.method.toUpperCase(), buildURL(fullPath, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request.onreadystatechange = function handleLoad() {
      if (!request || request.readyState !== 4) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle browser request cancellation (as opposed to a manual cancellation)
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }

      reject(createError('Request aborted', config, 'ECONNABORTED', request));

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config, null, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      var timeoutErrorMessage = 'timeout of ' + config.timeout + 'ms exceeded';
      if (config.timeoutErrorMessage) {
        timeoutErrorMessage = config.timeoutErrorMessage;
      }
      reject(createError(timeoutErrorMessage, config, 'ECONNABORTED',
        request));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__(/*! ./../helpers/cookies */ "./node_modules/axios/lib/helpers/cookies.js");

      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(fullPath)) && config.xsrfCookieName ?
        cookies.read(config.xsrfCookieName) :
        undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (!utils.isUndefined(config.withCredentials)) {
      request.withCredentials = !!config.withCredentials;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        // Expected DOMException thrown by browsers not compatible XMLHttpRequest Level 2.
        // But, this can be suppressed for 'json' type as it can be parsed by default 'transformResponse' function.
        if (config.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (requestData === undefined) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/axios.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/axios.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./utils */ "./node_modules/axios/lib/utils.js");
var bind = __webpack_require__(/*! ./helpers/bind */ "./node_modules/axios/lib/helpers/bind.js");
var Axios = __webpack_require__(/*! ./core/Axios */ "./node_modules/axios/lib/core/Axios.js");
var mergeConfig = __webpack_require__(/*! ./core/mergeConfig */ "./node_modules/axios/lib/core/mergeConfig.js");
var defaults = __webpack_require__(/*! ./defaults */ "./node_modules/axios/lib/defaults.js");

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(mergeConfig(axios.defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__(/*! ./cancel/Cancel */ "./node_modules/axios/lib/cancel/Cancel.js");
axios.CancelToken = __webpack_require__(/*! ./cancel/CancelToken */ "./node_modules/axios/lib/cancel/CancelToken.js");
axios.isCancel = __webpack_require__(/*! ./cancel/isCancel */ "./node_modules/axios/lib/cancel/isCancel.js");

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__(/*! ./helpers/spread */ "./node_modules/axios/lib/helpers/spread.js");

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/Cancel.js":
/*!*************************************************!*\
  !*** ./node_modules/axios/lib/cancel/Cancel.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CancelToken.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/cancel/CancelToken.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__(/*! ./Cancel */ "./node_modules/axios/lib/cancel/Cancel.js");

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/isCancel.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/cancel/isCancel.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/Axios.js":
/*!**********************************************!*\
  !*** ./node_modules/axios/lib/core/Axios.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");
var buildURL = __webpack_require__(/*! ../helpers/buildURL */ "./node_modules/axios/lib/helpers/buildURL.js");
var InterceptorManager = __webpack_require__(/*! ./InterceptorManager */ "./node_modules/axios/lib/core/InterceptorManager.js");
var dispatchRequest = __webpack_require__(/*! ./dispatchRequest */ "./node_modules/axios/lib/core/dispatchRequest.js");
var mergeConfig = __webpack_require__(/*! ./mergeConfig */ "./node_modules/axios/lib/core/mergeConfig.js");

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = arguments[1] || {};
    config.url = arguments[0];
  } else {
    config = config || {};
  }

  config = mergeConfig(this.defaults, config);

  // Set config.method
  if (config.method) {
    config.method = config.method.toLowerCase();
  } else if (this.defaults.method) {
    config.method = this.defaults.method.toLowerCase();
  } else {
    config.method = 'get';
  }

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

Axios.prototype.getUri = function getUri(config) {
  config = mergeConfig(this.defaults, config);
  return buildURL(config.url, config.params, config.paramsSerializer).replace(/^\?/, '');
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),

/***/ "./node_modules/axios/lib/core/InterceptorManager.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/core/InterceptorManager.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),

/***/ "./node_modules/axios/lib/core/buildFullPath.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/buildFullPath.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var isAbsoluteURL = __webpack_require__(/*! ../helpers/isAbsoluteURL */ "./node_modules/axios/lib/helpers/isAbsoluteURL.js");
var combineURLs = __webpack_require__(/*! ../helpers/combineURLs */ "./node_modules/axios/lib/helpers/combineURLs.js");

/**
 * Creates a new URL by combining the baseURL with the requestedURL,
 * only when the requestedURL is not already an absolute URL.
 * If the requestURL is absolute, this function returns the requestedURL untouched.
 *
 * @param {string} baseURL The base URL
 * @param {string} requestedURL Absolute or relative URL to combine
 * @returns {string} The combined full path
 */
module.exports = function buildFullPath(baseURL, requestedURL) {
  if (baseURL && !isAbsoluteURL(requestedURL)) {
    return combineURLs(baseURL, requestedURL);
  }
  return requestedURL;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/createError.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/core/createError.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__(/*! ./enhanceError */ "./node_modules/axios/lib/core/enhanceError.js");

/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, request, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, request, response);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/dispatchRequest.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/core/dispatchRequest.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");
var transformData = __webpack_require__(/*! ./transformData */ "./node_modules/axios/lib/core/transformData.js");
var isCancel = __webpack_require__(/*! ../cancel/isCancel */ "./node_modules/axios/lib/cancel/isCancel.js");
var defaults = __webpack_require__(/*! ../defaults */ "./node_modules/axios/lib/defaults.js");

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/core/enhanceError.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/core/enhanceError.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, request, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }

  error.request = request;
  error.response = response;
  error.isAxiosError = true;

  error.toJSON = function() {
    return {
      // Standard
      message: this.message,
      name: this.name,
      // Microsoft
      description: this.description,
      number: this.number,
      // Mozilla
      fileName: this.fileName,
      lineNumber: this.lineNumber,
      columnNumber: this.columnNumber,
      stack: this.stack,
      // Axios
      config: this.config,
      code: this.code
    };
  };
  return error;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/mergeConfig.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/core/mergeConfig.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ../utils */ "./node_modules/axios/lib/utils.js");

/**
 * Config-specific merge-function which creates a new config-object
 * by merging two configuration objects together.
 *
 * @param {Object} config1
 * @param {Object} config2
 * @returns {Object} New object resulting from merging config2 to config1
 */
module.exports = function mergeConfig(config1, config2) {
  // eslint-disable-next-line no-param-reassign
  config2 = config2 || {};
  var config = {};

  var valueFromConfig2Keys = ['url', 'method', 'params', 'data'];
  var mergeDeepPropertiesKeys = ['headers', 'auth', 'proxy'];
  var defaultToConfig2Keys = [
    'baseURL', 'url', 'transformRequest', 'transformResponse', 'paramsSerializer',
    'timeout', 'withCredentials', 'adapter', 'responseType', 'xsrfCookieName',
    'xsrfHeaderName', 'onUploadProgress', 'onDownloadProgress',
    'maxContentLength', 'validateStatus', 'maxRedirects', 'httpAgent',
    'httpsAgent', 'cancelToken', 'socketPath'
  ];

  utils.forEach(valueFromConfig2Keys, function valueFromConfig2(prop) {
    if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    }
  });

  utils.forEach(mergeDeepPropertiesKeys, function mergeDeepProperties(prop) {
    if (utils.isObject(config2[prop])) {
      config[prop] = utils.deepMerge(config1[prop], config2[prop]);
    } else if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    } else if (utils.isObject(config1[prop])) {
      config[prop] = utils.deepMerge(config1[prop]);
    } else if (typeof config1[prop] !== 'undefined') {
      config[prop] = config1[prop];
    }
  });

  utils.forEach(defaultToConfig2Keys, function defaultToConfig2(prop) {
    if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    } else if (typeof config1[prop] !== 'undefined') {
      config[prop] = config1[prop];
    }
  });

  var axiosKeys = valueFromConfig2Keys
    .concat(mergeDeepPropertiesKeys)
    .concat(defaultToConfig2Keys);

  var otherKeys = Object
    .keys(config2)
    .filter(function filterAxiosKeys(key) {
      return axiosKeys.indexOf(key) === -1;
    });

  utils.forEach(otherKeys, function otherKeysDefaultToConfig2(prop) {
    if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    } else if (typeof config1[prop] !== 'undefined') {
      config[prop] = config1[prop];
    }
  });

  return config;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/settle.js":
/*!***********************************************!*\
  !*** ./node_modules/axios/lib/core/settle.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__(/*! ./createError */ "./node_modules/axios/lib/core/createError.js");

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  if (!validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response.request,
      response
    ));
  }
};


/***/ }),

/***/ "./node_modules/axios/lib/core/transformData.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/transformData.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),

/***/ "./node_modules/axios/lib/defaults.js":
/*!********************************************!*\
  !*** ./node_modules/axios/lib/defaults.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__(/*! ./utils */ "./node_modules/axios/lib/utils.js");
var normalizeHeaderName = __webpack_require__(/*! ./helpers/normalizeHeaderName */ "./node_modules/axios/lib/helpers/normalizeHeaderName.js");

var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__(/*! ./adapters/xhr */ "./node_modules/axios/lib/adapters/xhr.js");
  } else if (typeof process !== 'undefined' && Object.prototype.toString.call(process) === '[object process]') {
    // For node use HTTP adapter
    adapter = __webpack_require__(/*! ./adapters/http */ "./node_modules/axios/lib/adapters/xhr.js");
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Accept');
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/axios/lib/helpers/bind.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/helpers/bind.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/buildURL.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/buildURL.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

function encode(val) {
  return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      } else {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    var hashmarkIndex = url.indexOf('#');
    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }

    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/combineURLs.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/combineURLs.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/cookies.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/helpers/cookies.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
    (function standardBrowserEnv() {
      return {
        write: function write(name, value, expires, path, domain, secure) {
          var cookie = [];
          cookie.push(name + '=' + encodeURIComponent(value));

          if (utils.isNumber(expires)) {
            cookie.push('expires=' + new Date(expires).toGMTString());
          }

          if (utils.isString(path)) {
            cookie.push('path=' + path);
          }

          if (utils.isString(domain)) {
            cookie.push('domain=' + domain);
          }

          if (secure === true) {
            cookie.push('secure');
          }

          document.cookie = cookie.join('; ');
        },

        read: function read(name) {
          var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
          return (match ? decodeURIComponent(match[3]) : null);
        },

        remove: function remove(name) {
          this.write(name, '', Date.now() - 86400000);
        }
      };
    })() :

  // Non standard browser env (web workers, react-native) lack needed support.
    (function nonStandardBrowserEnv() {
      return {
        write: function write() {},
        read: function read() { return null; },
        remove: function remove() {}
      };
    })()
);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAbsoluteURL.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAbsoluteURL.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isURLSameOrigin.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isURLSameOrigin.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
    (function standardBrowserEnv() {
      var msie = /(msie|trident)/i.test(navigator.userAgent);
      var urlParsingNode = document.createElement('a');
      var originURL;

      /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
      function resolveURL(url) {
        var href = url;

        if (msie) {
        // IE needs attribute set twice to normalize properties
          urlParsingNode.setAttribute('href', href);
          href = urlParsingNode.href;
        }

        urlParsingNode.setAttribute('href', href);

        // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
        return {
          href: urlParsingNode.href,
          protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
          host: urlParsingNode.host,
          search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
          hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
          hostname: urlParsingNode.hostname,
          port: urlParsingNode.port,
          pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
            urlParsingNode.pathname :
            '/' + urlParsingNode.pathname
        };
      }

      originURL = resolveURL(window.location.href);

      /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
      return function isURLSameOrigin(requestURL) {
        var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
        return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
      };
    })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
    (function nonStandardBrowserEnv() {
      return function isURLSameOrigin() {
        return true;
      };
    })()
);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/normalizeHeaderName.js":
/*!***************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/normalizeHeaderName.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ../utils */ "./node_modules/axios/lib/utils.js");

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseHeaders.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/parseHeaders.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

// Headers whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
var ignoreDuplicateOf = [
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
];

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      if (parsed[key] && ignoreDuplicateOf.indexOf(key) >= 0) {
        return;
      }
      if (key === 'set-cookie') {
        parsed[key] = (parsed[key] ? parsed[key] : []).concat([val]);
      } else {
        parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
      }
    }
  });

  return parsed;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/spread.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/helpers/spread.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),

/***/ "./node_modules/axios/lib/utils.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/utils.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__(/*! ./helpers/bind */ "./node_modules/axios/lib/helpers/bind.js");

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is a Buffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Buffer, otherwise false
 */
function isBuffer(val) {
  return val !== null && !isUndefined(val) && val.constructor !== null && !isUndefined(val.constructor)
    && typeof val.constructor.isBuffer === 'function' && val.constructor.isBuffer(val);
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 * nativescript
 *  navigator.product -> 'NativeScript' or 'NS'
 */
function isStandardBrowserEnv() {
  if (typeof navigator !== 'undefined' && (navigator.product === 'ReactNative' ||
                                           navigator.product === 'NativeScript' ||
                                           navigator.product === 'NS')) {
    return false;
  }
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object') {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = merge(result[key], val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Function equal to merge with the difference being that no reference
 * to original objects is kept.
 *
 * @see merge
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function deepMerge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = deepMerge(result[key], val);
    } else if (typeof val === 'object') {
      result[key] = deepMerge({}, val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isBuffer: isBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  deepMerge: deepMerge,
  extend: extend,
  trim: trim
};


/***/ }),

/***/ "./node_modules/choices.js/public/assets/scripts/choices.min.js":
/*!**********************************************************************!*\
  !*** ./node_modules/choices.js/public/assets/scripts/choices.min.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*! choices.js v7.1.5 | © 2019 Josh Johnson | https://github.com/jshjohnson/Choices#readme */
!function(e,t){ true?module.exports=t():undefined}(window,(function(){return function(e){var t={};function i(n){if(t[n])return t[n].exports;var s=t[n]={i:n,l:!1,exports:{}};return e[n].call(s.exports,s,s.exports,i),s.l=!0,s.exports}return i.m=e,i.c=t,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var s in e)i.d(n,s,function(t){return e[t]}.bind(null,s));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="/public/assets/scripts/",i(i.s=5)}([function(e,t,i){var n;
/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/!function(){"use strict";var i={}.hasOwnProperty;function s(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var r=typeof n;if("string"===r||"number"===r)e.push(n);else if(Array.isArray(n)&&n.length){var o=s.apply(null,n);o&&e.push(o)}else if("object"===r)for(var a in n)i.call(n,a)&&n[a]&&e.push(a)}}return e.join(" ")}e.exports?(s.default=s,e.exports=s):void 0===(n=function(){return s}.apply(t,[]))||(e.exports=n)}()},function(e,t,i){"use strict";var n=function(e){return function(e){return!!e&&"object"==typeof e}(e)&&!function(e){var t=Object.prototype.toString.call(e);return"[object RegExp]"===t||"[object Date]"===t||function(e){return e.$$typeof===s}(e)}(e)};var s="function"==typeof Symbol&&Symbol.for?Symbol.for("react.element"):60103;function r(e,t){return!1!==t.clone&&t.isMergeableObject(e)?c((i=e,Array.isArray(i)?[]:{}),e,t):e;var i}function o(e,t,i){return e.concat(t).map((function(e){return r(e,i)}))}function a(e){return Object.keys(e).concat(function(e){return Object.getOwnPropertySymbols?Object.getOwnPropertySymbols(e).filter((function(t){return e.propertyIsEnumerable(t)})):[]}(e))}function l(e,t,i){var n={};return i.isMergeableObject(e)&&a(e).forEach((function(t){n[t]=r(e[t],i)})),a(t).forEach((function(s){(function(e,t){try{return t in e&&!(Object.hasOwnProperty.call(e,t)&&Object.propertyIsEnumerable.call(e,t))}catch(e){return!1}})(e,s)||(i.isMergeableObject(t[s])&&e[s]?n[s]=function(e,t){if(!t.customMerge)return c;var i=t.customMerge(e);return"function"==typeof i?i:c}(s,i)(e[s],t[s],i):n[s]=r(t[s],i))})),n}function c(e,t,i){(i=i||{}).arrayMerge=i.arrayMerge||o,i.isMergeableObject=i.isMergeableObject||n,i.cloneUnlessOtherwiseSpecified=r;var s=Array.isArray(t);return s===Array.isArray(e)?s?i.arrayMerge(e,t,i):l(e,t,i):r(t,i)}c.all=function(e,t){if(!Array.isArray(e))throw new Error("first argument should be an array");return e.reduce((function(e,i){return c(e,i,t)}),{})};var h=c;e.exports=h},function(e,t,i){"use strict";(function(e,n){var s,r=i(4);s="undefined"!=typeof self?self:"undefined"!=typeof window?window:void 0!==e?e:n;var o=Object(r.a)(s);t.a=o}).call(this,i(7),i(8)(e))},function(e,t,i){
/*!
 * Fuse.js v3.4.2 - Lightweight fuzzy-search (http://fusejs.io)
 * 
 * Copyright (c) 2012-2017 Kirollos Risk (http://kiro.me)
 * All Rights Reserved. Apache Software License 2.0
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 */
var n;n=function(){return function(e){var t={};function i(n){if(t[n])return t[n].exports;var s=t[n]={i:n,l:!1,exports:{}};return e[n].call(s.exports,s,s.exports,i),s.l=!0,s.exports}return i.m=e,i.c=t,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var s in e)i.d(n,s,function(t){return e[t]}.bind(null,s));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="",i(i.s="./src/index.js")}({"./src/bitap/bitap_matched_indices.js":
/*!********************************************!*\
  !*** ./src/bitap/bitap_matched_indices.js ***!
  \********************************************/
/*! no static exports found */function(e,t){e.exports=function(){for(var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1,i=[],n=-1,s=-1,r=0,o=e.length;r<o;r+=1){var a=e[r];a&&-1===n?n=r:a||-1===n||((s=r-1)-n+1>=t&&i.push([n,s]),n=-1)}return e[r-1]&&r-n>=t&&i.push([n,r-1]),i}},"./src/bitap/bitap_pattern_alphabet.js":
/*!*********************************************!*\
  !*** ./src/bitap/bitap_pattern_alphabet.js ***!
  \*********************************************/
/*! no static exports found */function(e,t){e.exports=function(e){for(var t={},i=e.length,n=0;n<i;n+=1)t[e.charAt(n)]=0;for(var s=0;s<i;s+=1)t[e.charAt(s)]|=1<<i-s-1;return t}},"./src/bitap/bitap_regex_search.js":
/*!*****************************************!*\
  !*** ./src/bitap/bitap_regex_search.js ***!
  \*****************************************/
/*! no static exports found */function(e,t){var i=/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g;e.exports=function(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:/ +/g,s=new RegExp(t.replace(i,"\\$&").replace(n,"|")),r=e.match(s),o=!!r,a=[];if(o)for(var l=0,c=r.length;l<c;l+=1){var h=r[l];a.push([e.indexOf(h),h.length-1])}return{score:o?.5:1,isMatch:o,matchedIndices:a}}},"./src/bitap/bitap_score.js":
/*!**********************************!*\
  !*** ./src/bitap/bitap_score.js ***!
  \**********************************/
/*! no static exports found */function(e,t){e.exports=function(e,t){var i=t.errors,n=void 0===i?0:i,s=t.currentLocation,r=void 0===s?0:s,o=t.expectedLocation,a=void 0===o?0:o,l=t.distance,c=void 0===l?100:l,h=n/e.length,u=Math.abs(a-r);return c?h+u/c:u?1:h}},"./src/bitap/bitap_search.js":
/*!***********************************!*\
  !*** ./src/bitap/bitap_search.js ***!
  \***********************************/
/*! no static exports found */function(e,t,i){var n=i(/*! ./bitap_score */"./src/bitap/bitap_score.js"),s=i(/*! ./bitap_matched_indices */"./src/bitap/bitap_matched_indices.js");e.exports=function(e,t,i,r){for(var o=r.location,a=void 0===o?0:o,l=r.distance,c=void 0===l?100:l,h=r.threshold,u=void 0===h?.6:h,d=r.findAllMatches,p=void 0!==d&&d,f=r.minMatchCharLength,m=void 0===f?1:f,v=a,g=e.length,_=u,b=e.indexOf(t,v),y=t.length,E=[],S=0;S<g;S+=1)E[S]=0;if(-1!==b){var I=n(t,{errors:0,currentLocation:b,expectedLocation:v,distance:c});if(_=Math.min(I,_),-1!==(b=e.lastIndexOf(t,v+y))){var w=n(t,{errors:0,currentLocation:b,expectedLocation:v,distance:c});_=Math.min(w,_)}}b=-1;for(var C=[],O=1,A=y+g,x=1<<y-1,T=0;T<y;T+=1){for(var L=0,k=A;L<k;)n(t,{errors:T,currentLocation:v+k,expectedLocation:v,distance:c})<=_?L=k:A=k,k=Math.floor((A-L)/2+L);A=k;var D=Math.max(1,v-k+1),P=p?g:Math.min(v+k,g)+y,F=Array(P+2);F[P+1]=(1<<T)-1;for(var j=P;j>=D;j-=1){var M=j-1,N=i[e.charAt(M)];if(N&&(E[M]=1),F[j]=(F[j+1]<<1|1)&N,0!==T&&(F[j]|=(C[j+1]|C[j])<<1|1|C[j+1]),F[j]&x&&(O=n(t,{errors:T,currentLocation:M,expectedLocation:v,distance:c}))<=_){if(_=O,(b=M)<=v)break;D=Math.max(1,2*v-b)}}if(n(t,{errors:T+1,currentLocation:v,expectedLocation:v,distance:c})>_)break;C=F}return{isMatch:b>=0,score:0===O?.001:O,matchedIndices:s(E,m)}}},"./src/bitap/index.js":
/*!****************************!*\
  !*** ./src/bitap/index.js ***!
  \****************************/
/*! no static exports found */function(e,t,i){function n(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var s=i(/*! ./bitap_regex_search */"./src/bitap/bitap_regex_search.js"),r=i(/*! ./bitap_search */"./src/bitap/bitap_search.js"),o=i(/*! ./bitap_pattern_alphabet */"./src/bitap/bitap_pattern_alphabet.js"),a=function(){function e(t,i){var n=i.location,s=void 0===n?0:n,r=i.distance,a=void 0===r?100:r,l=i.threshold,c=void 0===l?.6:l,h=i.maxPatternLength,u=void 0===h?32:h,d=i.isCaseSensitive,p=void 0!==d&&d,f=i.tokenSeparator,m=void 0===f?/ +/g:f,v=i.findAllMatches,g=void 0!==v&&v,_=i.minMatchCharLength,b=void 0===_?1:_;!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.options={location:s,distance:a,threshold:c,maxPatternLength:u,isCaseSensitive:p,tokenSeparator:m,findAllMatches:g,minMatchCharLength:b},this.pattern=this.options.isCaseSensitive?t:t.toLowerCase(),this.pattern.length<=u&&(this.patternAlphabet=o(this.pattern))}var t,i,a;return t=e,(i=[{key:"search",value:function(e){if(this.options.isCaseSensitive||(e=e.toLowerCase()),this.pattern===e)return{isMatch:!0,score:0,matchedIndices:[[0,e.length-1]]};var t=this.options,i=t.maxPatternLength,n=t.tokenSeparator;if(this.pattern.length>i)return s(e,this.pattern,n);var o=this.options,a=o.location,l=o.distance,c=o.threshold,h=o.findAllMatches,u=o.minMatchCharLength;return r(e,this.pattern,this.patternAlphabet,{location:a,distance:l,threshold:c,findAllMatches:h,minMatchCharLength:u})}}])&&n(t.prototype,i),a&&n(t,a),e}();e.exports=a},"./src/helpers/deep_value.js":
/*!***********************************!*\
  !*** ./src/helpers/deep_value.js ***!
  \***********************************/
/*! no static exports found */function(e,t,i){var n=i(/*! ./is_array */"./src/helpers/is_array.js");e.exports=function(e,t){return function e(t,i,s){if(i){var r=i.indexOf("."),o=i,a=null;-1!==r&&(o=i.slice(0,r),a=i.slice(r+1));var l=t[o];if(null!=l)if(a||"string"!=typeof l&&"number"!=typeof l)if(n(l))for(var c=0,h=l.length;c<h;c+=1)e(l[c],a,s);else a&&e(l,a,s);else s.push(l.toString())}else s.push(t);return s}(e,t,[])}},"./src/helpers/is_array.js":
/*!*********************************!*\
  !*** ./src/helpers/is_array.js ***!
  \*********************************/
/*! no static exports found */function(e,t){e.exports=function(e){return Array.isArray?Array.isArray(e):"[object Array]"===Object.prototype.toString.call(e)}},"./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no static exports found */function(e,t,i){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function s(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var r=i(/*! ./bitap */"./src/bitap/index.js"),o=i(/*! ./helpers/deep_value */"./src/helpers/deep_value.js"),a=i(/*! ./helpers/is_array */"./src/helpers/is_array.js"),l=function(){function e(t,i){var n=i.location,s=void 0===n?0:n,r=i.distance,a=void 0===r?100:r,l=i.threshold,c=void 0===l?.6:l,h=i.maxPatternLength,u=void 0===h?32:h,d=i.caseSensitive,p=void 0!==d&&d,f=i.tokenSeparator,m=void 0===f?/ +/g:f,v=i.findAllMatches,g=void 0!==v&&v,_=i.minMatchCharLength,b=void 0===_?1:_,y=i.id,E=void 0===y?null:y,S=i.keys,I=void 0===S?[]:S,w=i.shouldSort,C=void 0===w||w,O=i.getFn,A=void 0===O?o:O,x=i.sortFn,T=void 0===x?function(e,t){return e.score-t.score}:x,L=i.tokenize,k=void 0!==L&&L,D=i.matchAllTokens,P=void 0!==D&&D,F=i.includeMatches,j=void 0!==F&&F,M=i.includeScore,N=void 0!==M&&M,K=i.verbose,R=void 0!==K&&K;!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.options={location:s,distance:a,threshold:c,maxPatternLength:u,isCaseSensitive:p,tokenSeparator:m,findAllMatches:g,minMatchCharLength:b,id:E,keys:I,includeMatches:j,includeScore:N,shouldSort:C,getFn:A,sortFn:T,verbose:R,tokenize:k,matchAllTokens:P},this.setCollection(t)}var t,i,l;return t=e,(i=[{key:"setCollection",value:function(e){return this.list=e,e}},{key:"search",value:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{limit:!1};this._log('---------\nSearch pattern: "'.concat(e,'"'));var i=this._prepareSearchers(e),n=i.tokenSearchers,s=i.fullSearcher,r=this._search(n,s),o=r.weights,a=r.results;return this._computeScore(o,a),this.options.shouldSort&&this._sort(a),t.limit&&"number"==typeof t.limit&&(a=a.slice(0,t.limit)),this._format(a)}},{key:"_prepareSearchers",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=[];if(this.options.tokenize)for(var i=e.split(this.options.tokenSeparator),n=0,s=i.length;n<s;n+=1)t.push(new r(i[n],this.options));var o=new r(e,this.options);return{tokenSearchers:t,fullSearcher:o}}},{key:"_search",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],t=arguments.length>1?arguments[1]:void 0,i=this.list,n={},s=[];if("string"==typeof i[0]){for(var r=0,o=i.length;r<o;r+=1)this._analyze({key:"",value:i[r],record:r,index:r},{resultMap:n,results:s,tokenSearchers:e,fullSearcher:t});return{weights:null,results:s}}for(var a={},l=0,c=i.length;l<c;l+=1)for(var h=i[l],u=0,d=this.options.keys.length;u<d;u+=1){var p=this.options.keys[u];if("string"!=typeof p){if(a[p.name]={weight:1-p.weight||1},p.weight<=0||p.weight>1)throw new Error("Key weight has to be > 0 and <= 1");p=p.name}else a[p]={weight:1};this._analyze({key:p,value:this.options.getFn(h,p),record:h,index:l},{resultMap:n,results:s,tokenSearchers:e,fullSearcher:t})}return{weights:a,results:s}}},{key:"_analyze",value:function(e,t){var i=e.key,n=e.arrayIndex,s=void 0===n?-1:n,r=e.value,o=e.record,l=e.index,c=t.tokenSearchers,h=void 0===c?[]:c,u=t.fullSearcher,d=void 0===u?[]:u,p=t.resultMap,f=void 0===p?{}:p,m=t.results,v=void 0===m?[]:m;if(null!=r){var g=!1,_=-1,b=0;if("string"==typeof r){this._log("\nKey: ".concat(""===i?"-":i));var y=d.search(r);if(this._log('Full text: "'.concat(r,'", score: ').concat(y.score)),this.options.tokenize){for(var E=r.split(this.options.tokenSeparator),S=[],I=0;I<h.length;I+=1){var w=h[I];this._log('\nPattern: "'.concat(w.pattern,'"'));for(var C=!1,O=0;O<E.length;O+=1){var A=E[O],x=w.search(A),T={};x.isMatch?(T[A]=x.score,g=!0,C=!0,S.push(x.score)):(T[A]=1,this.options.matchAllTokens||S.push(1)),this._log('Token: "'.concat(A,'", score: ').concat(T[A]))}C&&(b+=1)}_=S[0];for(var L=S.length,k=1;k<L;k+=1)_+=S[k];_/=L,this._log("Token score average:",_)}var D=y.score;_>-1&&(D=(D+_)/2),this._log("Score average:",D);var P=!this.options.tokenize||!this.options.matchAllTokens||b>=h.length;if(this._log("\nCheck Matches: ".concat(P)),(g||y.isMatch)&&P){var F=f[l];F?F.output.push({key:i,arrayIndex:s,value:r,score:D,matchedIndices:y.matchedIndices}):(f[l]={item:o,output:[{key:i,arrayIndex:s,value:r,score:D,matchedIndices:y.matchedIndices}]},v.push(f[l]))}}else if(a(r))for(var j=0,M=r.length;j<M;j+=1)this._analyze({key:i,arrayIndex:j,value:r[j],record:o,index:l},{resultMap:f,results:v,tokenSearchers:h,fullSearcher:d})}}},{key:"_computeScore",value:function(e,t){this._log("\n\nComputing score:\n");for(var i=0,n=t.length;i<n;i+=1){for(var s=t[i].output,r=s.length,o=1,a=1,l=0;l<r;l+=1){var c=e?e[s[l].key].weight:1,h=(1===c?s[l].score:s[l].score||.001)*c;1!==c?a=Math.min(a,h):(s[l].nScore=h,o*=h)}t[i].score=1===a?o:a,this._log(t[i])}}},{key:"_sort",value:function(e){this._log("\n\nSorting...."),e.sort(this.options.sortFn)}},{key:"_format",value:function(e){var t=[];if(this.options.verbose){var i=[];this._log("\n\nOutput:\n\n",JSON.stringify(e,(function(e,t){if("object"===n(t)&&null!==t){if(-1!==i.indexOf(t))return;i.push(t)}return t}))),i=null}var s=[];this.options.includeMatches&&s.push((function(e,t){var i=e.output;t.matches=[];for(var n=0,s=i.length;n<s;n+=1){var r=i[n];if(0!==r.matchedIndices.length){var o={indices:r.matchedIndices,value:r.value};r.key&&(o.key=r.key),r.hasOwnProperty("arrayIndex")&&r.arrayIndex>-1&&(o.arrayIndex=r.arrayIndex),t.matches.push(o)}}})),this.options.includeScore&&s.push((function(e,t){t.score=e.score}));for(var r=0,o=e.length;r<o;r+=1){var a=e[r];if(this.options.id&&(a.item=this.options.getFn(a.item,this.options.id)[0]),s.length){for(var l={item:a.item},c=0,h=s.length;c<h;c+=1)s[c](a,l);t.push(l)}else t.push(a.item)}return t}},{key:"_log",value:function(){var e;this.options.verbose&&(e=console).log.apply(e,arguments)}}])&&s(t.prototype,i),l&&s(t,l),e}();e.exports=l}})},e.exports=n()},function(e,t,i){"use strict";function n(e){var t,i=e.Symbol;return"function"==typeof i?i.observable?t=i.observable:(t=i("observable"),i.observable=t):t="@@observable",t}i.d(t,"a",(function(){return n}))},function(e,t,i){e.exports=i(9)},function(e,t){window.delegateEvent=function(){var e,t;function i(t){var i=e.get(t.type);i&&i.forEach((function(e){return e(t)}))}return void 0===e&&(e=new Map),void 0===t&&(t=[]),{add:function(n,s){e.has(n)?e.get(n).push(s):e.set(n,[s]),-1===t.indexOf(n)&&(document.documentElement.addEventListener(n,i,!0),t.push(n))},remove:function(i,n){e.get(i)&&(e.set(i,e.get(i).filter((function(e){return e!==n}))),e.get(i).length||t.splice(t.indexOf(i),1))}}}()},function(e,t){var i;i=function(){return this}();try{i=i||new Function("return this")()}catch(e){"object"==typeof window&&(i=window)}e.exports=i},function(e,t){e.exports=function(e){if(!e.webpackPolyfill){var t=Object.create(e);t.children||(t.children=[]),Object.defineProperty(t,"loaded",{enumerable:!0,get:function(){return t.l}}),Object.defineProperty(t,"id",{enumerable:!0,get:function(){return t.i}}),Object.defineProperty(t,"exports",{enumerable:!0}),t.webpackPolyfill=1}return t}},function(e,t,i){"use strict";i.r(t);var n=i(3),s=i.n(n),r=i(1),o=i.n(r),a=(i(6),i(2)),l=function(){return Math.random().toString(36).substring(7).split("").join(".")},c={INIT:"@@redux/INIT"+l(),REPLACE:"@@redux/REPLACE"+l(),PROBE_UNKNOWN_ACTION:function(){return"@@redux/PROBE_UNKNOWN_ACTION"+l()}};function h(e){if("object"!=typeof e||null===e)return!1;for(var t=e;null!==Object.getPrototypeOf(t);)t=Object.getPrototypeOf(t);return Object.getPrototypeOf(e)===t}function u(e,t,i){var n;if("function"==typeof t&&"function"==typeof i||"function"==typeof i&&"function"==typeof arguments[3])throw new Error("It looks like you are passing several store enhancers to createStore(). This is not supported. Instead, compose them together to a single function.");if("function"==typeof t&&void 0===i&&(i=t,t=void 0),void 0!==i){if("function"!=typeof i)throw new Error("Expected the enhancer to be a function.");return i(u)(e,t)}if("function"!=typeof e)throw new Error("Expected the reducer to be a function.");var s=e,r=t,o=[],l=o,d=!1;function p(){l===o&&(l=o.slice())}function f(){if(d)throw new Error("You may not call store.getState() while the reducer is executing. The reducer has already received the state as an argument. Pass it down from the top reducer instead of reading it from the store.");return r}function m(e){if("function"!=typeof e)throw new Error("Expected the listener to be a function.");if(d)throw new Error("You may not call store.subscribe() while the reducer is executing. If you would like to be notified after the store has been updated, subscribe from a component and invoke store.getState() in the callback to access the latest state. See https://redux.js.org/api-reference/store#subscribe(listener) for more details.");var t=!0;return p(),l.push(e),function(){if(t){if(d)throw new Error("You may not unsubscribe from a store listener while the reducer is executing. See https://redux.js.org/api-reference/store#subscribe(listener) for more details.");t=!1,p();var i=l.indexOf(e);l.splice(i,1)}}}function v(e){if(!h(e))throw new Error("Actions must be plain objects. Use custom middleware for async actions.");if(void 0===e.type)throw new Error('Actions may not have an undefined "type" property. Have you misspelled a constant?');if(d)throw new Error("Reducers may not dispatch actions.");try{d=!0,r=s(r,e)}finally{d=!1}for(var t=o=l,i=0;i<t.length;i++){(0,t[i])()}return e}return v({type:c.INIT}),(n={dispatch:v,subscribe:m,getState:f,replaceReducer:function(e){if("function"!=typeof e)throw new Error("Expected the nextReducer to be a function.");s=e,v({type:c.REPLACE})}})[a.a]=function(){var e,t=m;return(e={subscribe:function(e){if("object"!=typeof e||null===e)throw new TypeError("Expected the observer to be an object.");function i(){e.next&&e.next(f())}return i(),{unsubscribe:t(i)}}})[a.a]=function(){return this},e},n}function d(e,t){var i=t&&t.type;return"Given "+(i&&'action "'+String(i)+'"'||"an action")+', reducer "'+e+'" returned undefined. To ignore an action, you must explicitly return the previous state. If you want this reducer to hold no value, you can return null instead of undefined.'}var p=[];var f=[];var m=[];var v,g={loading:!1},_=function(e,t){switch(void 0===e&&(e=g),t.type){case"SET_IS_LOADING":return{loading:t.isLoading};default:return e}},b=function(e){for(var t,i,n="",s=0;s<e;s++){n+=(t=0,i=36,Math.floor(Math.random()*(i-t)+t)).toString(36)}return n},y=function(e,t){var i=e.id||e.name&&e.name+"-"+b(2)||b(4);return i=t+"-"+(i=i.replace(/(:|\.|\[|\]|,)/g,""))},E=function(e){return Object.prototype.toString.call(e).slice(8,-1)},S=function(e,t){return null!=t&&E(t)===e},I=function(e){return e instanceof Element},w=function(e,t){for(var i=e;i;){if(i.hasAttribute(t))return i;i=i.parentElement}return null},C=function(e){return S("String",e)?e.replace(/&/g,"&amp;").replace(/>/g,"&rt;").replace(/</g,"&lt;").replace(/"/g,"&quot;"):e},O=(v=document.createElement("div"),function(e){var t=e.trim();v.innerHTML=t;for(var i=v.children[0];v.firstChild;)v.removeChild(v.firstChild);return i}),A=function(e,t){return e.score-t.score},x=function e(t,i){var n=i.indexOf(".");return n>-1?e(t[i.substring(0,n)],i.substr(n+1)):t[i]},T=function(e){return JSON.parse(JSON.stringify(e))},L=function(e,t){var i=Object.keys(e).sort(),n=Object.keys(t).sort();return i.filter((function(e){return n.indexOf(e)<0}))},k=function(e){for(var t=Object.keys(e),i={},n=0;n<t.length;n++){var s=t[n];0,"function"==typeof e[s]&&(i[s]=e[s])}var r,o=Object.keys(i);try{!function(e){Object.keys(e).forEach((function(t){var i=e[t];if(void 0===i(void 0,{type:c.INIT}))throw new Error('Reducer "'+t+"\" returned undefined during initialization. If the state passed to the reducer is undefined, you must explicitly return the initial state. The initial state may not be undefined. If you don't want to set a value for this reducer, you can use null instead of undefined.");if(void 0===i(void 0,{type:c.PROBE_UNKNOWN_ACTION()}))throw new Error('Reducer "'+t+"\" returned undefined when probed with a random type. Don't try to handle "+c.INIT+' or other actions in "redux/*" namespace. They are considered private. Instead, you must return the current state for any unknown actions, unless it is undefined, in which case you must return the initial state, regardless of the action type. The initial state may not be undefined, but can be null.')}))}(i)}catch(e){r=e}return function(e,t){if(void 0===e&&(e={}),r)throw r;for(var n=!1,s={},a=0;a<o.length;a++){var l=o[a],c=i[l],h=e[l],u=c(h,t);if(void 0===u){var p=d(l,t);throw new Error(p)}s[l]=u,n=n||u!==h}return n?s:e}}({items:function(e,t){switch(void 0===e&&(e=p),t.type){case"ADD_ITEM":return[].concat(e,[{id:t.id,choiceId:t.choiceId,groupId:t.groupId,value:t.value,label:t.label,active:!0,highlighted:!1,customProperties:t.customProperties,placeholder:t.placeholder||!1,keyCode:null}]).map((function(e){var t=e;return t.highlighted=!1,t}));case"REMOVE_ITEM":return e.map((function(e){var i=e;return i.id===t.id&&(i.active=!1),i}));case"HIGHLIGHT_ITEM":return e.map((function(e){var i=e;return i.id===t.id&&(i.highlighted=t.highlighted),i}));default:return e}},groups:function(e,t){switch(void 0===e&&(e=f),t.type){case"ADD_GROUP":return[].concat(e,[{id:t.id,value:t.value,active:t.active,disabled:t.disabled}]);case"CLEAR_CHOICES":return[];default:return e}},choices:function(e,t){switch(void 0===e&&(e=m),t.type){case"ADD_CHOICE":return[].concat(e,[{id:t.id,elementId:t.elementId,groupId:t.groupId,value:t.value,label:t.label||t.value,disabled:t.disabled||!1,selected:!1,active:!0,score:9999,customProperties:t.customProperties,placeholder:t.placeholder||!1,keyCode:null}]);case"ADD_ITEM":return t.activateOptions?e.map((function(e){var i=e;return i.active=t.active,i})):t.choiceId>-1?e.map((function(e){var i=e;return i.id===parseInt(t.choiceId,10)&&(i.selected=!0),i})):e;case"REMOVE_ITEM":return t.choiceId>-1?e.map((function(e){var i=e;return i.id===parseInt(t.choiceId,10)&&(i.selected=!1),i})):e;case"FILTER_CHOICES":return e.map((function(e){var i=e;return i.active=t.results.some((function(e){var t=e.item,n=e.score;return t.id===i.id&&(i.score=n,!0)})),i}));case"ACTIVATE_CHOICES":return e.map((function(e){var i=e;return i.active=t.active,i}));case"CLEAR_CHOICES":return m;default:return e}},general:_}),D=function(e,t){var i=e;if("CLEAR_ALL"===t.type)i=void 0;else if("RESET_TO"===t.type)return T(t.state);return k(i,t)};function P(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var F=function(){function e(){this._store=u(D,window.__REDUX_DEVTOOLS_EXTENSION__&&window.__REDUX_DEVTOOLS_EXTENSION__())}var t,i,n,s=e.prototype;return s.subscribe=function(e){this._store.subscribe(e)},s.dispatch=function(e){this._store.dispatch(e)},s.isLoading=function(){return this.state.general.loading},s.getChoiceById=function(e){return!!e&&this.activeChoices.find((function(t){return t.id===parseInt(e,10)}))},s.getGroupById=function(e){return this.groups.find((function(t){return t.id===parseInt(e,10)}))},t=e,(i=[{key:"state",get:function(){return this._store.getState()}},{key:"items",get:function(){return this.state.items}},{key:"activeItems",get:function(){return this.items.filter((function(e){return!0===e.active}))}},{key:"highlightedActiveItems",get:function(){return this.items.filter((function(e){return e.active&&e.highlighted}))}},{key:"choices",get:function(){return this.state.choices}},{key:"activeChoices",get:function(){return this.choices.filter((function(e){return!0===e.active}))}},{key:"selectableChoices",get:function(){return this.choices.filter((function(e){return!0!==e.disabled}))}},{key:"searchableChoices",get:function(){return this.selectableChoices.filter((function(e){return!0!==e.placeholder}))}},{key:"placeholderChoice",get:function(){return[].concat(this.choices).reverse().find((function(e){return!0===e.placeholder}))}},{key:"groups",get:function(){return this.state.groups}},{key:"activeGroups",get:function(){var e=this.groups,t=this.choices;return e.filter((function(e){var i=!0===e.active&&!1===e.disabled,n=t.some((function(e){return!0===e.active&&!1===e.disabled}));return i&&n}),[])}}])&&P(t.prototype,i),n&&P(t,n),e}(),j=function(){function e(e){var t=e.element,i=e.type,n=e.classNames;Object.assign(this,{element:t,type:i,classNames:n}),this.isActive=!1}var t=e.prototype;return t.distanceFromTopWindow=function(){return this.dimensions=this.element.getBoundingClientRect(),this.position=Math.ceil(this.dimensions.top+window.pageYOffset+this.element.offsetHeight),this.position},t.getChild=function(e){return this.element.querySelector(e)},t.show=function(){return this.element.classList.add(this.classNames.activeState),this.element.setAttribute("aria-expanded","true"),this.isActive=!0,this},t.hide=function(){return this.element.classList.remove(this.classNames.activeState),this.element.setAttribute("aria-expanded","false"),this.isActive=!1,this},e}(),M=function(){function e(e){var t=e.element,i=e.type,n=e.classNames,s=e.position;Object.assign(this,{element:t,classNames:n,type:i,position:s}),this.isOpen=!1,this.isFlipped=!1,this.isFocussed=!1,this.isDisabled=!1,this.isLoading=!1,this._onFocus=this._onFocus.bind(this),this._onBlur=this._onBlur.bind(this)}var t=e.prototype;return t.addEventListeners=function(){this.element.addEventListener("focus",this._onFocus),this.element.addEventListener("blur",this._onBlur)},t.removeEventListeners=function(){this.element.removeEventListener("focus",this._onFocus),this.element.removeEventListener("blur",this._onBlur)},t.shouldFlip=function(e,t){var i,n;if(void 0===t&&(i=document.body,n=document.documentElement,t=Math.max(i.scrollHeight,i.offsetHeight,n.clientHeight,n.scrollHeight,n.offsetHeight)),void 0===e)return!1;var s=!1;return"auto"===this.position?s=e>=t:"top"===this.position&&(s=!0),s},t.setActiveDescendant=function(e){this.element.setAttribute("aria-activedescendant",e)},t.removeActiveDescendant=function(){this.element.removeAttribute("aria-activedescendant")},t.open=function(e){this.element.classList.add(this.classNames.openState),this.element.setAttribute("aria-expanded","true"),this.isOpen=!0,this.shouldFlip(e)&&(this.element.classList.add(this.classNames.flippedState),this.isFlipped=!0)},t.close=function(){this.element.classList.remove(this.classNames.openState),this.element.setAttribute("aria-expanded","false"),this.removeActiveDescendant(),this.isOpen=!1,this.isFlipped&&(this.element.classList.remove(this.classNames.flippedState),this.isFlipped=!1)},t.focus=function(){this.isFocussed||this.element.focus()},t.addFocusState=function(){this.element.classList.add(this.classNames.focusState)},t.removeFocusState=function(){this.element.classList.remove(this.classNames.focusState)},t.enable=function(){this.element.classList.remove(this.classNames.disabledState),this.element.removeAttribute("aria-disabled"),"select-one"===this.type&&this.element.setAttribute("tabindex","0"),this.isDisabled=!1},t.disable=function(){this.element.classList.add(this.classNames.disabledState),this.element.setAttribute("aria-disabled","true"),"select-one"===this.type&&this.element.setAttribute("tabindex","-1"),this.isDisabled=!0},t.wrap=function(e){!function(e,t){void 0===t&&(t=document.createElement("div")),e.nextSibling?e.parentNode.insertBefore(t,e.nextSibling):e.parentNode.appendChild(t),t.appendChild(e)}(e,this.element)},t.unwrap=function(e){this.element.parentNode.insertBefore(e,this.element),this.element.parentNode.removeChild(this.element)},t.addLoadingState=function(){this.element.classList.add(this.classNames.loadingState),this.element.setAttribute("aria-busy","true"),this.isLoading=!0},t.removeLoadingState=function(){this.element.classList.remove(this.classNames.loadingState),this.element.removeAttribute("aria-busy"),this.isLoading=!1},t._onFocus=function(){this.isFocussed=!0},t._onBlur=function(){this.isFocussed=!1},e}();function N(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var K=function(){function e(e){var t=e.element,i=e.type,n=e.classNames,s=e.placeholderValue;Object.assign(this,{element:t,type:i,classNames:n,placeholderValue:s}),this.element=t,this.classNames=n,this.isFocussed=this.element===document.activeElement,this.isDisabled=!1,this._onPaste=this._onPaste.bind(this),this._onInput=this._onInput.bind(this),this._onFocus=this._onFocus.bind(this),this._onBlur=this._onBlur.bind(this)}var t,i,n,s=e.prototype;return s.addEventListeners=function(){this.element.addEventListener("input",this._onInput),this.element.addEventListener("paste",this._onPaste),this.element.addEventListener("focus",this._onFocus),this.element.addEventListener("blur",this._onBlur),this.element.form&&this.element.form.addEventListener("reset",this._onFormReset)},s.removeEventListeners=function(){this.element.removeEventListener("input",this._onInput),this.element.removeEventListener("paste",this._onPaste),this.element.removeEventListener("focus",this._onFocus),this.element.removeEventListener("blur",this._onBlur),this.element.form&&this.element.form.removeEventListener("reset",this._onFormReset)},s.enable=function(){this.element.removeAttribute("disabled"),this.isDisabled=!1},s.disable=function(){this.element.setAttribute("disabled",""),this.isDisabled=!0},s.focus=function(){this.isFocussed||this.element.focus()},s.blur=function(){this.isFocussed&&this.element.blur()},s.clear=function(e){return void 0===e&&(e=!0),this.element.value&&(this.element.value=""),e&&this.setWidth(),this},s.setWidth=function(e){var t=this,i=function(e){t.element.style.width=e};if(this._placeholderValue){var n=this.element.value.length>=this._placeholderValue.length/1.25;(this.element.value&&n||e)&&this.calcWidth(i)}else this.calcWidth(i)},s.calcWidth=function(e){return function(e,t){var i=e.value||e.placeholder,n=e.offsetWidth;if(i){var s=O("<span>"+C(i)+"</span>");if(s.style.position="absolute",s.style.padding="0",s.style.top="-9999px",s.style.left="-9999px",s.style.width="auto",s.style.whiteSpace="pre",document.body.contains(e)&&window.getComputedStyle){var r=window.getComputedStyle(e);r&&(s.style.fontSize=r.fontSize,s.style.fontFamily=r.fontFamily,s.style.fontWeight=r.fontWeight,s.style.fontStyle=r.fontStyle,s.style.letterSpacing=r.letterSpacing,s.style.textTransform=r.textTransform,s.style.paddingLeft=r.paddingLeft,s.style.paddingRight=r.paddingRight)}document.body.appendChild(s),requestAnimationFrame((function(){i&&s.offsetWidth!==e.offsetWidth&&(n=s.offsetWidth+4),document.body.removeChild(s),t.call(void 0,n+"px")}))}else t.call(void 0,n+"px")}(this.element,e)},s.setActiveDescendant=function(e){this.element.setAttribute("aria-activedescendant",e)},s.removeActiveDescendant=function(){this.element.removeAttribute("aria-activedescendant")},s._onInput=function(){"select-one"!==this.type&&this.setWidth()},s._onPaste=function(e){e.target===this.element&&this.preventPaste&&e.preventDefault()},s._onFocus=function(){this.isFocussed=!0},s._onBlur=function(){this.isFocussed=!1},t=e,(i=[{key:"placeholder",set:function(e){this.element.placeholder=e}},{key:"value",set:function(e){this.element.value=e},get:function(){return C(this.element.value)}}])&&N(t.prototype,i),n&&N(t,n),e}(),R={items:[],choices:[],silent:!1,renderChoiceLimit:-1,maxItemCount:-1,addItems:!0,addItemFilterFn:null,removeItems:!0,removeItemButton:!1,editItems:!1,duplicateItemsAllowed:!0,delimiter:",",paste:!0,searchEnabled:!0,searchChoices:!0,searchFloor:1,searchResultLimit:4,searchFields:["label","value"],position:"auto",resetScrollPosition:!0,shouldSort:!0,shouldSortItems:!1,sortFn:function(e,t){var i=(""+(e.label||e.value)).toLowerCase(),n=(""+(t.label||t.value)).toLowerCase();return i<n?-1:i>n?1:0},placeholder:!0,placeholderValue:null,searchPlaceholderValue:null,prependValue:null,appendValue:null,renderSelectedChoices:"auto",loadingText:"Loading...",noResultsText:"No results found",noChoicesText:"No choices to choose from",itemSelectText:"Press to select",uniqueItemText:"Only unique values can be added",customAddItemText:"Only values matching specific conditions can be added",addItemText:function(e){return'Press Enter to add <b>"'+C(e)+'"</b>'},maxItemText:function(e){return"Only "+e+" values can be added"},itemComparer:function(e,t){return e===t},fuseOptions:{includeScore:!0},callbackOnInit:null,callbackOnCreateTemplates:null,classNames:{containerOuter:"choices",containerInner:"choices__inner",input:"choices__input",inputCloned:"choices__input--cloned",list:"choices__list",listItems:"choices__list--multiple",listSingle:"choices__list--single",listDropdown:"choices__list--dropdown",item:"choices__item",itemSelectable:"choices__item--selectable",itemDisabled:"choices__item--disabled",itemChoice:"choices__item--choice",placeholder:"choices__placeholder",group:"choices__group",groupHeading:"choices__heading",button:"choices__button",activeState:"is-active",focusState:"is-focused",openState:"is-open",disabledState:"is-disabled",highlightedState:"is-highlighted",hiddenState:"is-hidden",flippedState:"is-flipped",loadingState:"is-loading",noResults:"has-no-results",noChoices:"has-no-choices"}},B="showDropdown",V="hideDropdown",H="change",G="choice",q="search",W="addItem",U="removeItem",z="highlightItem",X="highlightChoice",Y="ADD_CHOICE",$="FILTER_CHOICES",J="ACTIVATE_CHOICES",Z="CLEAR_CHOICES",Q="ADD_GROUP",ee="ADD_ITEM",te="REMOVE_ITEM",ie="HIGHLIGHT_ITEM",ne=46,se=8,re=13,oe=65,ae=27,le=38,ce=40,he=33,ue=34,de=function(){function e(e){var t=e.element;Object.assign(this,{element:t}),this.scrollPos=this.element.scrollTop,this.height=this.element.offsetHeight,this.hasChildren=!!this.element.children}var t=e.prototype;return t.clear=function(){this.element.innerHTML=""},t.append=function(e){this.element.appendChild(e)},t.getChild=function(e){return this.element.querySelector(e)},t.scrollToTop=function(){this.element.scrollTop=0},t.scrollToChoice=function(e,t){var i=this;if(e){var n=this.element.offsetHeight,s=e.offsetHeight,r=e.offsetTop+s,o=this.element.scrollTop+n,a=t>0?this.element.scrollTop+r-o:e.offsetTop;requestAnimationFrame((function(e){i._animateScroll(e,a,t)}))}},t._scrollDown=function(e,t,i){var n=(i-e)/t,s=n>1?n:1;this.element.scrollTop=e+s},t._scrollUp=function(e,t,i){var n=(e-i)/t,s=n>1?n:1;this.element.scrollTop=e-s},t._animateScroll=function(e,t,i){var n=this,s=this.element.scrollTop,r=!1;i>0?(this._scrollDown(s,4,t),s<t&&(r=!0)):(this._scrollUp(s,4,t),s>t&&(r=!0)),r&&requestAnimationFrame((function(){n._animateScroll(e,t,i)}))},e}();function pe(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var fe=function(){function e(e){var t=e.element,i=e.classNames;if(Object.assign(this,{element:t,classNames:i}),!I(t))throw new TypeError("Invalid element passed");this.isDisabled=!1}var t,i,n,s=e.prototype;return s.conceal=function(){this.element.classList.add(this.classNames.input),this.element.classList.add(this.classNames.hiddenState),this.element.tabIndex="-1";var e=this.element.getAttribute("style");e&&this.element.setAttribute("data-choice-orig-style",e),this.element.setAttribute("aria-hidden","true"),this.element.setAttribute("data-choice","active")},s.reveal=function(){this.element.classList.remove(this.classNames.input),this.element.classList.remove(this.classNames.hiddenState),this.element.removeAttribute("tabindex");var e=this.element.getAttribute("data-choice-orig-style");e?(this.element.removeAttribute("data-choice-orig-style"),this.element.setAttribute("style",e)):this.element.removeAttribute("style"),this.element.removeAttribute("aria-hidden"),this.element.removeAttribute("data-choice"),this.element.value=this.element.value},s.enable=function(){this.element.removeAttribute("disabled"),this.element.disabled=!1,this.isDisabled=!1},s.disable=function(){this.element.setAttribute("disabled",""),this.element.disabled=!0,this.isDisabled=!0},s.triggerEvent=function(e,t){!function(e,t,i){void 0===i&&(i=null);var n=new CustomEvent(t,{detail:i,bubbles:!0,cancelable:!0});e.dispatchEvent(n)}(this.element,e,t)},t=e,(i=[{key:"value",get:function(){return this.element.value},set:function(e){this.element.value=e}}])&&pe(t.prototype,i),n&&pe(t,n),e}();function me(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var ve=function(e){var t,i,n,s,r;function o(t){var i,n=t.element,s=t.classNames,r=t.delimiter;return(i=e.call(this,{element:n,classNames:s})||this).delimiter=r,i}return i=e,(t=o).prototype=Object.create(i.prototype),t.prototype.constructor=t,t.__proto__=i,n=o,(s=[{key:"value",set:function(e){var t=e.map((function(e){return e.value})).join(this.delimiter);this.element.setAttribute("value",t),this.element.value=t},get:function(){return this.element.value}}])&&me(n.prototype,s),r&&me(n,r),o}(fe);function ge(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var _e=function(e){var t,i,n,s,r;function o(t){var i,n=t.element,s=t.classNames,r=t.template;return(i=e.call(this,{element:n,classNames:s})||this).template=r,i}return i=e,(t=o).prototype=Object.create(i.prototype),t.prototype.constructor=t,t.__proto__=i,o.prototype.appendDocFragment=function(e){this.element.innerHTML="",this.element.appendChild(e)},n=o,(s=[{key:"placeholderOption",get:function(){return this.element.querySelector('option[value=""]')||this.element.querySelector("option[placeholder]")}},{key:"optionGroups",get:function(){return Array.from(this.element.getElementsByTagName("OPTGROUP"))}},{key:"options",get:function(){return Array.from(this.element.options)},set:function(e){var t=this,i=document.createDocumentFragment();e.forEach((function(e){return n=e,s=t.template(n),void i.appendChild(s);var n,s})),this.appendDocFragment(i)}}])&&ge(n.prototype,s),r&&ge(n,r),o}(fe),be=i(0),ye=i.n(be),Ee={containerOuter:function(e,t,i,n,s,r){var o=n?'tabindex="0"':"",a=i?'role="listbox"':"",l="";return i&&s&&(a='role="combobox"',l='aria-autocomplete="list"'),O('\n      <div\n        class="'+e.containerOuter+'"\n        data-type="'+r+'"\n        '+a+"\n        "+o+"\n        "+l+'\n        aria-haspopup="true"\n        aria-expanded="false"\n        dir="'+t+'"\n        >\n      </div>\n    ')},containerInner:function(e){return O('\n      <div class="'+e.containerInner+'"></div>\n    ')},itemList:function(e,t){var i,n=ye()(e.list,((i={})[e.listSingle]=t,i[e.listItems]=!t,i));return O('\n      <div class="'+n+'"></div>\n    ')},placeholder:function(e,t){return O('\n      <div class="'+e.placeholder+'">\n        '+t+"\n      </div>\n    ")},item:function(e,t,i){var n,s,r=t.active?'aria-selected="true"':"",o=t.disabled?'aria-disabled="true"':"",a=ye()(e.item,((n={})[e.highlightedState]=t.highlighted,n[e.itemSelectable]=!t.highlighted,n[e.placeholder]=t.placeholder,n));return i?(a=ye()(e.item,((s={})[e.highlightedState]=t.highlighted,s[e.itemSelectable]=!t.disabled,s[e.placeholder]=t.placeholder,s)),O('\n        <div\n          class="'+a+'"\n          data-item\n          data-id="'+t.id+'"\n          data-value="'+t.value+"\"\n          data-custom-properties='"+t.customProperties+"'\n          data-deletable\n          "+r+"\n          "+o+"\n          >\n          "+t.label+'\x3c!--\n       --\x3e<button\n            type="button"\n            class="'+e.button+'"\n            data-button\n            aria-label="Remove item: \''+t.value+"'\"\n            >\n            Remove item\n          </button>\n        </div>\n      ")):O('\n      <div\n        class="'+a+'"\n        data-item\n        data-id="'+t.id+'"\n        data-value="'+t.value+'"\n        '+r+"\n        "+o+"\n        >\n        "+t.label+"\n      </div>\n    ")},choiceList:function(e,t){var i=t?"":'aria-multiselectable="true"';return O('\n      <div\n        class="'+e.list+'"\n        dir="ltr"\n        role="listbox"\n        '+i+"\n        >\n      </div>\n    ")},choiceGroup:function(e,t){var i,n=t.disabled?'aria-disabled="true"':"",s=ye()(e.group,((i={})[e.itemDisabled]=t.disabled,i));return O('\n      <div\n        class="'+s+'"\n        data-group\n        data-id="'+t.id+'"\n        data-value="'+t.value+'"\n        role="group"\n        '+n+'\n        >\n        <div class="'+e.groupHeading+'">'+t.value+"</div>\n      </div>\n    ")},choice:function(e,t,i){var n,s=t.groupId>0?'role="treeitem"':'role="option"',r=ye()(e.item,e.itemChoice,((n={})[e.itemDisabled]=t.disabled,n[e.itemSelectable]=!t.disabled,n[e.placeholder]=t.placeholder,n));return O('\n      <div\n        class="'+r+'"\n        data-select-text="'+i+'"\n        data-choice\n        data-id="'+t.id+'"\n        data-value="'+t.value+'"\n        '+(t.disabled?'data-choice-disabled aria-disabled="true"':"data-choice-selectable")+'\n        id="'+t.elementId+'"\n        '+s+"\n        >\n        "+t.label+"\n      </div>\n    ")},input:function(e,t){var i=ye()(e.input,e.inputCloned);return O('\n      <input\n        type="text"\n        class="'+i+'"\n        autocomplete="off"\n        autocapitalize="off"\n        spellcheck="false"\n        role="textbox"\n        aria-autocomplete="list"\n        aria-label="'+t+'"\n        >\n    ')},dropdown:function(e){var t=ye()(e.list,e.listDropdown);return O('\n      <div\n        class="'+t+'"\n        aria-expanded="false"\n        >\n      </div>\n    ')},notice:function(e,t,i){var n;void 0===i&&(i="");var s=ye()(e.item,e.itemChoice,((n={})[e.noResults]="no-results"===i,n[e.noChoices]="no-choices"===i,n));return O('\n      <div class="'+s+'">\n        '+t+"\n      </div>\n    ")},option:function(e){return O('\n      <option value="'+e.value+'" '+(e.active?"selected":"")+" "+(e.disabled?"disabled":"")+" "+(e.customProperties?"data-custom-properties="+e.customProperties:"")+">"+e.label+"</option>\n    ")}},Se=function(e){return void 0===e&&(e=!0),{type:J,active:e}},Ie=function(e,t){return{type:ie,id:e,highlighted:t}},we=function(e,t,i,n){return{type:Q,value:e,id:t,active:i,disabled:n}},Ce=function(){function e(t,i){var n=this;if(void 0===t&&(t="[data-choice]"),void 0===i&&(i={}),S("String",t)){var s=Array.from(document.querySelectorAll(t));if(s.length>1)return this._generateInstances(s,i)}this.config=o.a.all([R,e.userDefaults,i],{arrayMerge:function(e,t){return[].concat(t)}});var r=L(this.config,R);r.length&&console.warn("Unknown config option(s) passed",r.join(", ")),["auto","always"].includes(this.config.renderSelectedChoices)||(this.config.renderSelectedChoices="auto");var a=S("String",t)?document.querySelector(t):t;if(a){if(this._isTextElement="text"===a.type,this._isSelectOneElement="select-one"===a.type,this._isSelectMultipleElement="select-multiple"===a.type,this._isSelectElement=this._isSelectOneElement||this._isSelectMultipleElement,this._isTextElement?this.passedElement=new ve({element:a,classNames:this.config.classNames,delimiter:this.config.delimiter}):this._isSelectElement&&(this.passedElement=new _e({element:a,classNames:this.config.classNames,template:function(e){return n.config.templates.option(e)}})),!this.passedElement)return console.error("Passed element was of an invalid type");this.initialised=!1,this._store=new F(this.render),this._initialState={},this._currentState={},this._prevState={},this._currentValue="",this._canSearch=this.config.searchEnabled,this._isScrollingOnIe=!1,this._highlightPosition=0,this._wasTap=!0,this._placeholderValue=this._generatePlaceholderValue(),this._baseId=y(this.passedElement.element,"choices-"),this._direction=this.passedElement.element.getAttribute("dir")||"ltr",this._idNames={itemChoice:"item-choice"},this._presetChoices=this.config.choices,this._presetItems=this.config.items,this.passedElement.value&&(this._presetItems=this._presetItems.concat(this.passedElement.value.split(this.config.delimiter))),this._render=this._render.bind(this),this._onFocus=this._onFocus.bind(this),this._onBlur=this._onBlur.bind(this),this._onKeyUp=this._onKeyUp.bind(this),this._onKeyDown=this._onKeyDown.bind(this),this._onClick=this._onClick.bind(this),this._onTouchMove=this._onTouchMove.bind(this),this._onTouchEnd=this._onTouchEnd.bind(this),this._onMouseDown=this._onMouseDown.bind(this),this._onMouseOver=this._onMouseOver.bind(this),this._onFormReset=this._onFormReset.bind(this),this._onAKey=this._onAKey.bind(this),this._onEnterKey=this._onEnterKey.bind(this),this._onEscapeKey=this._onEscapeKey.bind(this),this._onDirectionKey=this._onDirectionKey.bind(this),this._onDeleteKey=this._onDeleteKey.bind(this),this.config.silent||(!0===this.config.shouldSortItems&&this._isSelectOneElement&&console.warn("shouldSortElements: Type of passed element is 'select-one', falling back to false."),"active"===this.passedElement.element.getAttribute("data-choice")&&console.warn("Trying to initialise Choices on element already initialised")),this.init()}else this.config.silent||console.error("Could not find passed element or passed element was of an invalid type")}var t=e.prototype;return t.init=function(){if(!this.initialised){this._createTemplates(),this._createElements(),this._createStructure(),this._initialState=T(this._store.state),this._store.subscribe(this._render),this._render(),this._addEventListeners(),(!this.config.addItems||this.passedElement.element.hasAttribute("disabled"))&&this.disable(),this.initialised=!0;var e=this.config.callbackOnInit;e&&S("Function",e)&&e.call(this)}},t.destroy=function(){this.initialised&&(this._removeEventListeners(),this.passedElement.reveal(),this.containerOuter.unwrap(this.passedElement.element),this._isSelectElement&&(this.passedElement.options=this._presetChoices),this.clearStore(),this.config.templates=null,this.initialised=!1)},t.enable=function(){return this.passedElement.isDisabled&&this.passedElement.enable(),this.containerOuter.isDisabled&&(this._addEventListeners(),this.input.enable(),this.containerOuter.enable()),this},t.disable=function(){return this.passedElement.isDisabled||this.passedElement.disable(),this.containerOuter.isDisabled||(this._removeEventListeners(),this.input.disable(),this.containerOuter.disable()),this},t.highlightItem=function(e,t){if(void 0===t&&(t=!0),!e)return this;var i=e.id,n=e.groupId,s=void 0===n?-1:n,r=e.value,o=void 0===r?"":r,a=e.label,l=void 0===a?"":a,c=s>=0?this._store.getGroupById(s):null;return this._store.dispatch(Ie(i,!0)),t&&this.passedElement.triggerEvent(z,{id:i,value:o,label:l,groupValue:c&&c.value?c.value:null}),this},t.unhighlightItem=function(e){if(!e)return this;var t=e.id,i=e.groupId,n=void 0===i?-1:i,s=e.value,r=void 0===s?"":s,o=e.label,a=void 0===o?"":o,l=n>=0?this._store.getGroupById(n):null;return this._store.dispatch(Ie(t,!1)),this.passedElement.triggerEvent(z,{id:t,value:r,label:a,groupValue:l&&l.value?l.value:null}),this},t.highlightAll=function(){var e=this;return this._store.items.forEach((function(t){return e.highlightItem(t)})),this},t.unhighlightAll=function(){var e=this;return this._store.items.forEach((function(t){return e.unhighlightItem(t)})),this},t.removeActiveItemsByValue=function(e){var t=this;return this._store.activeItems.filter((function(t){return t.value===e})).forEach((function(e){return t._removeItem(e)})),this},t.removeActiveItems=function(e){var t=this;return this._store.activeItems.filter((function(t){return t.id!==e})).forEach((function(e){return t._removeItem(e)})),this},t.removeHighlightedItems=function(e){var t=this;return void 0===e&&(e=!1),this._store.highlightedActiveItems.forEach((function(i){t._removeItem(i),e&&t._triggerChange(i.value)})),this},t.showDropdown=function(e){var t=this;return this.dropdown.isActive?this:(requestAnimationFrame((function(){t.dropdown.show(),t.containerOuter.open(t.dropdown.distanceFromTopWindow()),!e&&t._canSearch&&t.input.focus(),t.passedElement.triggerEvent(B,{})})),this)},t.hideDropdown=function(e){var t=this;return this.dropdown.isActive?(requestAnimationFrame((function(){t.dropdown.hide(),t.containerOuter.close(),!e&&t._canSearch&&(t.input.removeActiveDescendant(),t.input.blur()),t.passedElement.triggerEvent(V,{})})),this):this},t.getValue=function(e){void 0===e&&(e=!1);var t=this._store.activeItems.reduce((function(t,i){var n=e?i.value:i;return t.push(n),t}),[]);return this._isSelectOneElement?t[0]:t},t.setValue=function(e){var t=this;return this.initialised?([].concat(e).forEach((function(e){return t._setChoiceOrItem(e)})),this):this},t.setChoiceByValue=function(e){var t=this;return!this.initialised||this._isTextElement?this:((S("Array",e)?e:[e]).forEach((function(e){return t._findAndSelectChoiceByValue(e)})),this)},t.setChoices=function(e,t,i,n){var s=this;if(void 0===e&&(e=[]),void 0===t&&(t=""),void 0===i&&(i=""),void 0===n&&(n=!1),!this._isSelectElement||!t)return this;n&&this.clearChoices(),this.containerOuter.removeLoadingState();return this._setLoading(!0),e.forEach((function(e){e.choices?s._addGroup({group:e,id:e.id||null,valueKey:t,labelKey:i}):s._addChoice({value:e[t],label:e[i],isSelected:e.selected,isDisabled:e.disabled,customProperties:e.customProperties,placeholder:e.placeholder})})),this._setLoading(!1),this},t.clearChoices=function(){this._store.dispatch({type:Z})},t.clearStore=function(){return this._store.dispatch({type:"CLEAR_ALL"}),this},t.clearInput=function(){var e=!this._isSelectOneElement;return this.input.clear(e),!this._isTextElement&&this._canSearch&&(this._isSearching=!1,this._store.dispatch(Se(!0))),this},t.ajax=function(e){var t=this;return this.initialised&&this._isSelectElement&&e?(requestAnimationFrame((function(){return t._handleLoadingState(!0)})),e(this._ajaxCallback()),this):this},t._render=function(){if(!this._store.isLoading()){this._currentState=this._store.state;var e=this._currentState.choices!==this._prevState.choices||this._currentState.groups!==this._prevState.groups||this._currentState.items!==this._prevState.items,t=this._isSelectElement,i=this._currentState.items!==this._prevState.items;e&&(t&&this._renderChoices(),i&&this._renderItems(),this._prevState=this._currentState)}},t._renderChoices=function(){var e=this,t=this._store,i=t.activeGroups,n=t.activeChoices,s=document.createDocumentFragment();if(this.choiceList.clear(),this.config.resetScrollPosition&&requestAnimationFrame((function(){return e.choiceList.scrollToTop()})),i.length>=1&&!this._isSearching){var r=n.filter((function(e){return!0===e.placeholder&&-1===e.groupId}));r.length>=1&&(s=this._createChoicesFragment(r,s)),s=this._createGroupsFragment(i,n,s)}else n.length>=1&&(s=this._createChoicesFragment(n,s));if(s.childNodes&&s.childNodes.length>0){var o=this._store.activeItems,a=this._canAddItem(o,this.input.value);a.response?(this.choiceList.append(s),this._highlightChoice()):this.choiceList.append(this._getTemplate("notice",a.notice))}else{var l,c;this._isSearching?(c=S("Function",this.config.noResultsText)?this.config.noResultsText():this.config.noResultsText,l=this._getTemplate("notice",c,"no-results")):(c=S("Function",this.config.noChoicesText)?this.config.noChoicesText():this.config.noChoicesText,l=this._getTemplate("notice",c,"no-choices")),this.choiceList.append(l)}},t._renderItems=function(){var e=this._store.activeItems||[];this.itemList.clear();var t=this._createItemsFragment(e);t.childNodes&&this.itemList.append(t)},t._createGroupsFragment=function(e,t,i){var n=this,s=i||document.createDocumentFragment();return this.config.shouldSort&&e.sort(this.config.sortFn),e.forEach((function(e){var i=function(e){return t.filter((function(t){return n._isSelectOneElement?t.groupId===e.id:t.groupId===e.id&&("always"===n.config.renderSelectedChoices||!t.selected)}))}(e);if(i.length>=1){var r=n._getTemplate("choiceGroup",e);s.appendChild(r),n._createChoicesFragment(i,s,!0)}})),s},t._createChoicesFragment=function(e,t,i){var n=this;void 0===i&&(i=!1);var s=t||document.createDocumentFragment(),r=this.config,o=r.renderSelectedChoices,a=r.searchResultLimit,l=r.renderChoiceLimit,c=this._isSearching?A:this.config.sortFn,h=function(e){if("auto"!==o||(n._isSelectOneElement||!e.selected)){var t=n._getTemplate("choice",e,n.config.itemSelectText);s.appendChild(t)}},u=e;"auto"!==o||this._isSelectOneElement||(u=e.filter((function(e){return!e.selected})));var d=u.reduce((function(e,t){return t.placeholder?e.placeholderChoices.push(t):e.normalChoices.push(t),e}),{placeholderChoices:[],normalChoices:[]}),p=d.placeholderChoices,f=d.normalChoices;(this.config.shouldSort||this._isSearching)&&f.sort(c);var m=u.length,v=[].concat(p,f);this._isSearching?m=a:l>0&&!i&&(m=l);for(var g=0;g<m;g+=1)v[g]&&h(v[g]);return s},t._createItemsFragment=function(e,t){var i=this;void 0===t&&(t=null);var n=this.config,s=n.shouldSortItems,r=n.sortFn,o=n.removeItemButton,a=t||document.createDocumentFragment();s&&!this._isSelectOneElement&&e.sort(r),this._isTextElement?this.passedElement.value=e:this.passedElement.options=e;return e.forEach((function(e){return function(e){var t=i._getTemplate("item",e,o);a.appendChild(t)}(e)})),a},t._triggerChange=function(e){null!=e&&this.passedElement.triggerEvent(H,{value:e})},t._selectPlaceholderChoice=function(){var e=this._store.placeholderChoice;e&&(this._addItem({value:e.value,label:e.label,choiceId:e.id,groupId:e.groupId,placeholder:e.placeholder}),this._triggerChange(e.value))},t._handleButtonAction=function(e,t){if(e&&t&&this.config.removeItems&&this.config.removeItemButton){var i=t.parentNode.getAttribute("data-id"),n=e.find((function(e){return e.id===parseInt(i,10)}));this._removeItem(n),this._triggerChange(n.value),this._isSelectOneElement&&this._selectPlaceholderChoice()}},t._handleItemAction=function(e,t,i){var n=this;if(void 0===i&&(i=!1),e&&t&&this.config.removeItems&&!this._isSelectOneElement){var s=t.getAttribute("data-id");e.forEach((function(e){e.id!==parseInt(s,10)||e.highlighted?!i&&e.highlighted&&n.unhighlightItem(e):n.highlightItem(e)})),this.input.focus()}},t._handleChoiceAction=function(e,t){if(e&&t){var i=t.getAttribute("data-id"),n=this._store.getChoiceById(i),s=e[0]&&e[0].keyCode?e[0].keyCode:null,r=this.dropdown.isActive;if(n.keyCode=s,this.passedElement.triggerEvent(G,{choice:n}),n&&!n.selected&&!n.disabled)this._canAddItem(e,n.value).response&&(this._addItem({value:n.value,label:n.label,choiceId:n.id,groupId:n.groupId,customProperties:n.customProperties,placeholder:n.placeholder,keyCode:n.keyCode}),this._triggerChange(n.value));this.clearInput(),r&&this._isSelectOneElement&&(this.hideDropdown(!0),this.containerOuter.focus())}},t._handleBackspace=function(e){if(this.config.removeItems&&e){var t=e[e.length-1],i=e.some((function(e){return e.highlighted}));this.config.editItems&&!i&&t?(this.input.value=t.value,this.input.setWidth(),this._removeItem(t),this._triggerChange(t.value)):(i||this.highlightItem(t,!1),this.removeHighlightedItems(!0))}},t._setLoading=function(e){this._store.dispatch(function(e){return{type:"SET_IS_LOADING",isLoading:e}}(e))},t._handleLoadingState=function(e){void 0===e&&(e=!0);var t=this.itemList.getChild("."+this.config.classNames.placeholder);e?(this.disable(),this.containerOuter.addLoadingState(),this._isSelectOneElement?t?t.innerHTML=this.config.loadingText:(t=this._getTemplate("placeholder",this.config.loadingText),this.itemList.append(t)):this.input.placeholder=this.config.loadingText):(this.enable(),this.containerOuter.removeLoadingState(),this._isSelectOneElement?t.innerHTML=this._placeholderValue||"":this.input.placeholder=this._placeholderValue||"")},t._handleSearch=function(e){if(e&&this.input.isFocussed){var t=this._store.choices,i=this.config,n=i.searchFloor,s=i.searchChoices,r=t.some((function(e){return!e.active}));if(e&&e.length>=n){var o=s?this._searchChoices(e):0;this.passedElement.triggerEvent(q,{value:e,resultCount:o})}else r&&(this._isSearching=!1,this._store.dispatch(Se(!0)))}},t._canAddItem=function(e,t){var i=!0,n=S("Function",this.config.addItemText)?this.config.addItemText(t):this.config.addItemText;if(!this._isSelectOneElement){var s=function(e,t,i){return void 0===i&&(i="value"),e.some((function(e){return S("String",t)?e[i]===t.trim():e[i]===t}))}(e,t);this.config.maxItemCount>0&&this.config.maxItemCount<=e.length&&(i=!1,n=S("Function",this.config.maxItemText)?this.config.maxItemText(this.config.maxItemCount):this.config.maxItemText),!this.config.duplicateItemsAllowed&&s&&i&&(i=!1,n=S("Function",this.config.uniqueItemText)?this.config.uniqueItemText(t):this.config.uniqueItemText),this._isTextElement&&this.config.addItems&&i&&S("Function",this.config.addItemFilterFn)&&!this.config.addItemFilterFn(t)&&(i=!1,n=S("Function",this.config.customAddItemText)?this.config.customAddItemText(t):this.config.customAddItemText)}return{response:i,notice:n}},t._ajaxCallback=function(){var e=this;return function(t,i,n){if(t&&i){var s=S("Object",t)?[t]:t;s&&S("Array",s)&&s.length?(e._handleLoadingState(!1),e._setLoading(!0),s.forEach((function(t){t.choices?e._addGroup({group:t,id:t.id||null,valueKey:i,labelKey:n}):e._addChoice({value:x(t,i),label:x(t,n),isSelected:t.selected,isDisabled:t.disabled,customProperties:t.customProperties,placeholder:t.placeholder})})),e._setLoading(!1),e._isSelectOneElement&&e._selectPlaceholderChoice()):e._handleLoadingState(!1)}}},t._searchChoices=function(e){var t=S("String",e)?e.trim():e,i=S("String",this._currentValue)?this._currentValue.trim():this._currentValue;if(t.length<1&&t===i+" ")return 0;var n=this._store.searchableChoices,r=t,o=[].concat(this.config.searchFields),a=Object.assign(this.config.fuseOptions,{keys:o}),l=new s.a(n,a).search(r);return this._currentValue=t,this._highlightPosition=0,this._isSearching=!0,this._store.dispatch(function(e){return{type:$,results:e}}(l)),l.length},t._addEventListeners=function(){window.delegateEvent.add("keyup",this._onKeyUp),window.delegateEvent.add("keydown",this._onKeyDown),window.delegateEvent.add("click",this._onClick),window.delegateEvent.add("touchmove",this._onTouchMove),window.delegateEvent.add("touchend",this._onTouchEnd),window.delegateEvent.add("mousedown",this._onMouseDown),window.delegateEvent.add("mouseover",this._onMouseOver),this._isSelectOneElement&&(this.containerOuter.element.addEventListener("focus",this._onFocus),this.containerOuter.element.addEventListener("blur",this._onBlur)),this.input.element.addEventListener("focus",this._onFocus),this.input.element.addEventListener("blur",this._onBlur),this.input.element.form&&this.input.element.form.addEventListener("reset",this._onFormReset),this.input.addEventListeners()},t._removeEventListeners=function(){window.delegateEvent.remove("keyup",this._onKeyUp),window.delegateEvent.remove("keydown",this._onKeyDown),window.delegateEvent.remove("click",this._onClick),window.delegateEvent.remove("touchmove",this._onTouchMove),window.delegateEvent.remove("touchend",this._onTouchEnd),window.delegateEvent.remove("mousedown",this._onMouseDown),window.delegateEvent.remove("mouseover",this._onMouseOver),this._isSelectOneElement&&(this.containerOuter.element.removeEventListener("focus",this._onFocus),this.containerOuter.element.removeEventListener("blur",this._onBlur)),this.input.element.removeEventListener("focus",this._onFocus),this.input.element.removeEventListener("blur",this._onBlur),this.input.element.form&&this.input.element.form.removeEventListener("reset",this._onFormReset),this.input.removeEventListeners()},t._onKeyDown=function(e){var t,i=e.target,n=e.keyCode,s=e.ctrlKey,r=e.metaKey;if(i===this.input.element||this.containerOuter.element.contains(i)){var o=this._store.activeItems,a=this.input.isFocussed,l=this.dropdown.isActive,c=this.itemList.hasChildren,h=String.fromCharCode(n),u=ne,d=se,p=re,f=oe,m=ae,v=le,g=ce,_=he,b=ue,y=s||r;!this._isTextElement&&/[a-zA-Z0-9-_ ]/.test(h)&&this.showDropdown();var E=((t={})[f]=this._onAKey,t[p]=this._onEnterKey,t[m]=this._onEscapeKey,t[v]=this._onDirectionKey,t[_]=this._onDirectionKey,t[g]=this._onDirectionKey,t[b]=this._onDirectionKey,t[d]=this._onDeleteKey,t[u]=this._onDeleteKey,t);E[n]&&E[n]({event:e,target:i,keyCode:n,metaKey:r,activeItems:o,hasFocusedInput:a,hasActiveDropdown:l,hasItems:c,hasCtrlDownKeyPressed:y})}},t._onKeyUp=function(e){var t=e.target,i=e.keyCode;if(t===this.input.element){var n=this.input.value,s=this._store.activeItems,r=this._canAddItem(s,n),o=ne,a=se;if(this._isTextElement){if(r.notice&&n){var l=this._getTemplate("notice",r.notice);this.dropdown.element.innerHTML=l.outerHTML,this.showDropdown(!0)}else this.hideDropdown(!0)}else{var c=(i===o||i===a)&&!t.value,h=!this._isTextElement&&this._isSearching,u=this._canSearch&&r.response;c&&h?(this._isSearching=!1,this._store.dispatch(Se(!0))):u&&this._handleSearch(this.input.value)}this._canSearch=this.config.searchEnabled}},t._onAKey=function(e){var t=e.hasItems;e.hasCtrlDownKeyPressed&&t&&(this._canSearch=!1,this.config.removeItems&&!this.input.value&&this.input.element===document.activeElement&&this.highlightAll())},t._onEnterKey=function(e){var t=e.event,i=e.target,n=e.activeItems,s=e.hasActiveDropdown,r=re,o=i.hasAttribute("data-button");if(this._isTextElement&&i.value){var a=this.input.value;this._canAddItem(n,a).response&&(this.hideDropdown(!0),this._addItem({value:a}),this._triggerChange(a),this.clearInput())}if(o&&(this._handleButtonAction(n,i),t.preventDefault()),s){var l=this.dropdown.getChild("."+this.config.classNames.highlightedState);l&&(n[0]&&(n[0].keyCode=r),this._handleChoiceAction(n,l)),t.preventDefault()}else this._isSelectOneElement&&(this.showDropdown(),t.preventDefault())},t._onEscapeKey=function(e){e.hasActiveDropdown&&(this.hideDropdown(!0),this.containerOuter.focus())},t._onDirectionKey=function(e){var t=e.event,i=e.hasActiveDropdown,n=e.keyCode,s=e.metaKey,r=ce,o=he,a=ue;if(i||this._isSelectOneElement){this.showDropdown(),this._canSearch=!1;var l,c=n===r||n===a?1:-1;if(s||n===a||n===o)l=c>0?Array.from(this.dropdown.element.querySelectorAll("[data-choice-selectable]")).pop():this.dropdown.element.querySelector("[data-choice-selectable]");else{var h=this.dropdown.element.querySelector("."+this.config.classNames.highlightedState);l=h?function(e,t,i){if(void 0===i&&(i=1),e&&t){var n=e.parentNode.parentNode,s=Array.from(n.querySelectorAll(t)),r=s.indexOf(e);return s[r+(i>0?1:-1)]}}(h,"[data-choice-selectable]",c):this.dropdown.element.querySelector("[data-choice-selectable]")}l&&(function(e,t,i){if(void 0===i&&(i=1),e)return i>0?t.scrollTop+t.offsetHeight>=e.offsetTop+e.offsetHeight:e.offsetTop>=t.scrollTop}(l,this.choiceList.element,c)||this.choiceList.scrollToChoice(l,c),this._highlightChoice(l)),t.preventDefault()}},t._onDeleteKey=function(e){var t=e.event,i=e.target,n=e.hasFocusedInput,s=e.activeItems;!n||i.value||this._isSelectOneElement||(this._handleBackspace(s),t.preventDefault())},t._onTouchMove=function(){this._wasTap&&(this._wasTap=!1)},t._onTouchEnd=function(e){var t=(e||e.touches[0]).target;this._wasTap&&this.containerOuter.element.contains(t)&&((t===this.containerOuter.element||t===this.containerInner.element)&&(this._isTextElement?this.input.focus():this._isSelectMultipleElement&&this.showDropdown()),e.stopPropagation());this._wasTap=!0},t._onMouseDown=function(e){var t=e.target,i=e.shiftKey;if(this.choiceList.element.contains(t)&&navigator.userAgent.match(/Trident/)&&navigator.userAgent.match(/rv[ :]11/)&&(this._isScrollingOnIe=!0),this.containerOuter.element.contains(t)&&t!==this.input.element){var n=this._store.activeItems,s=i,r=w(t,"data-button"),o=w(t,"data-item"),a=w(t,"data-choice");r?this._handleButtonAction(n,r):o?this._handleItemAction(n,o,s):a&&this._handleChoiceAction(n,a),e.preventDefault()}},t._onMouseOver=function(e){var t=e.target;(t===this.dropdown||this.dropdown.element.contains(t))&&t.hasAttribute("data-choice")&&this._highlightChoice(t)},t._onClick=function(e){var t=e.target;this.containerOuter.element.contains(t)?this.dropdown.isActive||this.containerOuter.isDisabled?this._isSelectOneElement&&t!==this.input.element&&!this.dropdown.element.contains(t)&&this.hideDropdown():this._isTextElement?document.activeElement!==this.input.element&&this.input.focus():(this.showDropdown(),this.containerOuter.focus()):(this._store.highlightedActiveItems.length>0&&this.unhighlightAll(),this.containerOuter.removeFocusState(),this.hideDropdown(!0))},t._onFocus=function(e){var t=this,i=e.target;this.containerOuter.element.contains(i)&&{text:function(){i===t.input.element&&t.containerOuter.addFocusState()},"select-one":function(){t.containerOuter.addFocusState(),i===t.input.element&&t.showDropdown(!0)},"select-multiple":function(){i===t.input.element&&(t.showDropdown(!0),t.containerOuter.addFocusState())}}[this.passedElement.element.type]()},t._onBlur=function(e){var t=this,i=e.target;if(this.containerOuter.element.contains(i)&&!this._isScrollingOnIe){var n=this._store.activeItems.some((function(e){return e.highlighted}));({text:function(){i===t.input.element&&(t.containerOuter.removeFocusState(),n&&t.unhighlightAll(),t.hideDropdown(!0))},"select-one":function(){t.containerOuter.removeFocusState(),(i===t.input.element||i===t.containerOuter.element&&!t._canSearch)&&t.hideDropdown(!0)},"select-multiple":function(){i===t.input.element&&(t.containerOuter.removeFocusState(),t.hideDropdown(!0),n&&t.unhighlightAll())}})[this.passedElement.element.type]()}else this._isScrollingOnIe=!1,this.input.element.focus()},t._onFormReset=function(){this._store.dispatch({type:"RESET_TO",state:this._initialState})},t._highlightChoice=function(e){var t=this;void 0===e&&(e=null);var i=Array.from(this.dropdown.element.querySelectorAll("[data-choice-selectable]"));if(i.length){var n=e;Array.from(this.dropdown.element.querySelectorAll("."+this.config.classNames.highlightedState)).forEach((function(e){e.classList.remove(t.config.classNames.highlightedState),e.setAttribute("aria-selected","false")})),n?this._highlightPosition=i.indexOf(n):(n=i.length>this._highlightPosition?i[this._highlightPosition]:i[i.length-1])||(n=i[0]),n.classList.add(this.config.classNames.highlightedState),n.setAttribute("aria-selected","true"),this.passedElement.triggerEvent(X,{el:n}),this.dropdown.isActive&&(this.input.setActiveDescendant(n.id),this.containerOuter.setActiveDescendant(n.id))}},t._addItem=function(e){var t=e.value,i=e.label,n=void 0===i?null:i,s=e.choiceId,r=void 0===s?-1:s,o=e.groupId,a=void 0===o?-1:o,l=e.customProperties,c=void 0===l?null:l,h=e.placeholder,u=void 0!==h&&h,d=e.keyCode,p=void 0===d?null:d,f=S("String",t)?t.trim():t,m=p,v=c,g=this._store.items,_=n||f,b=parseInt(r,10)||-1,y=a>=0?this._store.getGroupById(a):null,E=g?g.length+1:1;return this.config.prependValue&&(f=this.config.prependValue+f.toString()),this.config.appendValue&&(f+=this.config.appendValue.toString()),this._store.dispatch(function(e){var t=e.value,i=e.label,n=e.id,s=e.choiceId,r=e.groupId,o=e.customProperties,a=e.placeholder,l=e.keyCode;return{type:ee,value:t,label:i,id:n,choiceId:s,groupId:r,customProperties:o,placeholder:a,keyCode:l}}({value:f,label:_,id:E,choiceId:b,groupId:a,customProperties:c,placeholder:u,keyCode:m})),this._isSelectOneElement&&this.removeActiveItems(E),this.passedElement.triggerEvent(W,{id:E,value:f,label:_,customProperties:v,groupValue:y&&y.value?y.value:void 0,keyCode:m}),this},t._removeItem=function(e){if(!e||!S("Object",e))return this;var t=e.id,i=e.value,n=e.label,s=e.choiceId,r=e.groupId,o=r>=0?this._store.getGroupById(r):null;return this._store.dispatch(function(e,t){return{type:te,id:e,choiceId:t}}(t,s)),o&&o.value?this.passedElement.triggerEvent(U,{id:t,value:i,label:n,groupValue:o.value}):this.passedElement.triggerEvent(U,{id:t,value:i,label:n}),this},t._addChoice=function(e){var t=e.value,i=e.label,n=void 0===i?null:i,s=e.isSelected,r=void 0!==s&&s,o=e.isDisabled,a=void 0!==o&&o,l=e.groupId,c=void 0===l?-1:l,h=e.customProperties,u=void 0===h?null:h,d=e.placeholder,p=void 0!==d&&d,f=e.keyCode,m=void 0===f?null:f;if(null!=t){var v=this._store.choices,g=n||t,_=v?v.length+1:1,b=this._baseId+"-"+this._idNames.itemChoice+"-"+_;this._store.dispatch(function(e){var t=e.value,i=e.label,n=e.id,s=e.groupId,r=e.disabled,o=e.elementId,a=e.customProperties,l=e.placeholder,c=e.keyCode;return{type:Y,value:t,label:i,id:n,groupId:s,disabled:r,elementId:o,customProperties:a,placeholder:l,keyCode:c}}({value:t,label:g,id:_,groupId:c,disabled:a,elementId:b,customProperties:u,placeholder:p,keyCode:m})),r&&this._addItem({value:t,label:g,choiceId:_,customProperties:u,placeholder:p,keyCode:m})}},t._addGroup=function(e){var t=this,i=e.group,n=e.id,s=e.valueKey,r=void 0===s?"value":s,o=e.labelKey,a=void 0===o?"label":o,l=S("Object",i)?i.choices:Array.from(i.getElementsByTagName("OPTION")),c=n||Math.floor((new Date).valueOf()*Math.random()),h=!!i.disabled&&i.disabled;if(l){this._store.dispatch(we(i.label,c,!0,h));l.forEach((function(e){var i=e.disabled||e.parentNode&&e.parentNode.disabled;t._addChoice({value:e[r],label:S("Object",e)?e[a]:e.innerHTML,isSelected:e.selected,isDisabled:i,groupId:c,customProperties:e.customProperties,placeholder:e.placeholder})}))}else this._store.dispatch(we(i.label,i.id,!1,i.disabled))},t._getTemplate=function(e){var t;if(!e)return null;for(var i=this.config,n=i.templates,s=i.classNames,r=arguments.length,o=new Array(r>1?r-1:0),a=1;a<r;a++)o[a-1]=arguments[a];return(t=n[e]).call.apply(t,[this,s].concat(o))},t._createTemplates=function(){var e=this.config.callbackOnCreateTemplates,t={};e&&S("Function",e)&&(t=e.call(this,O)),this.config.templates=o()(Ee,t)},t._createElements=function(){this.containerOuter=new M({element:this._getTemplate("containerOuter",this._direction,this._isSelectElement,this._isSelectOneElement,this.config.searchEnabled,this.passedElement.element.type),classNames:this.config.classNames,type:this.passedElement.element.type,position:this.config.position}),this.containerInner=new M({element:this._getTemplate("containerInner"),classNames:this.config.classNames,type:this.passedElement.element.type,position:this.config.position}),this.input=new K({element:this._getTemplate("input",this._placeholderValue),classNames:this.config.classNames,type:this.passedElement.element.type}),this.choiceList=new de({element:this._getTemplate("choiceList",this._isSelectOneElement)}),this.itemList=new de({element:this._getTemplate("itemList",this._isSelectOneElement)}),this.dropdown=new j({element:this._getTemplate("dropdown"),classNames:this.config.classNames,type:this.passedElement.element.type})},t._createStructure=function(){this.passedElement.conceal(),this.containerInner.wrap(this.passedElement.element),this.containerOuter.wrap(this.containerInner.element),this._isSelectOneElement?this.input.placeholder=this.config.searchPlaceholderValue||"":this._placeholderValue&&(this.input.placeholder=this._placeholderValue,this.input.setWidth(!0)),this.containerOuter.element.appendChild(this.containerInner.element),this.containerOuter.element.appendChild(this.dropdown.element),this.containerInner.element.appendChild(this.itemList.element),this._isTextElement||this.dropdown.element.appendChild(this.choiceList.element),this._isSelectOneElement?this.config.searchEnabled&&this.dropdown.element.insertBefore(this.input.element,this.dropdown.element.firstChild):this.containerInner.element.appendChild(this.input.element),this._isSelectElement?this._addPredefinedChoices():this._isTextElement&&this._addPredefinedItems()},t._addPredefinedChoices=function(){var e=this,t=this.passedElement.optionGroups;if(this._highlightPosition=0,this._isSearching=!1,this._setLoading(!0),t&&t.length){var i=this.passedElement.placeholderOption;i&&"SELECT"===i.parentNode.tagName&&this._addChoice({value:i.value,label:i.innerHTML,isSelected:i.selected,isDisabled:i.disabled,placeholder:!0}),t.forEach((function(t){return e._addGroup({group:t,id:t.id||null})}))}else{var n=this.passedElement.options,s=this.config.sortFn,r=this._presetChoices;n.forEach((function(e){r.push({value:e.value,label:e.innerHTML,selected:e.selected,disabled:e.disabled||e.parentNode.disabled,placeholder:e.hasAttribute("placeholder"),customProperties:e.getAttribute("data-custom-properties")})})),this.config.shouldSort&&r.sort(s);var o=r.some((function(e){return e.selected}));r.forEach((function(t,i){return function(t,i){var n=t.value,s=t.label,r=t.customProperties,a=t.placeholder;if(e._isSelectElement)if(t.choices)e._addGroup({group:t,id:t.id||null});else{var l=e._isSelectOneElement&&!o&&0===i,c=!!l||t.selected,h=!l&&t.disabled;e._addChoice({value:n,label:s,isSelected:c,isDisabled:h,customProperties:r,placeholder:a})}else e._addChoice({value:n,label:s,isSelected:t.selected,isDisabled:t.disabled,customProperties:r,placeholder:a})}(t,i)}))}this._setLoading(!1)},t._addPredefinedItems=function(){var e=this;this._presetItems.forEach((function(t){return function(t){var i=E(t);"Object"===i&&t.value?e._addItem({value:t.value,label:t.label,choiceId:t.id,customProperties:t.customProperties,placeholder:t.placeholder}):"String"===i&&e._addItem({value:t})}(t)}))},t._setChoiceOrItem=function(e){var t=this;({object:function(){e.value&&(t._isTextElement?t._addItem({value:e.value,label:e.label,choiceId:e.id,customProperties:e.customProperties,placeholder:e.placeholder}):t._addChoice({value:e.value,label:e.label,isSelected:!0,isDisabled:!1,customProperties:e.customProperties,placeholder:e.placeholder}))},string:function(){t._isTextElement?t._addItem({value:e}):t._addChoice({value:e,label:e,isSelected:!0,isDisabled:!1})}})[E(e).toLowerCase()]()},t._findAndSelectChoiceByValue=function(e){var t=this,i=this._store.choices.find((function(i){return t.config.itemComparer(i.value,e)}));i&&!i.selected&&this._addItem({value:i.value,label:i.label,choiceId:i.id,groupId:i.groupId,customProperties:i.customProperties,placeholder:i.placeholder,keyCode:i.keyCode})},t._generateInstances=function(t,i){return t.reduce((function(t,n){return t.push(new e(n,i)),t}),[this])},t._generatePlaceholderValue=function(){return!this._isSelectOneElement&&(!!this.config.placeholder&&(this.config.placeholderValue||this.passedElement.element.getAttribute("placeholder")))},e}();Ce.userDefaults={};t.default=Ce}]).default}));

/***/ }),

/***/ "./node_modules/decode-uri-component/index.js":
/*!****************************************************!*\
  !*** ./node_modules/decode-uri-component/index.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var token = '%[a-f0-9]{2}';
var singleMatcher = new RegExp('(' + token + ')|([^%]+?)', 'gi');
var multiMatcher = new RegExp('(' + token + ')+', 'gi');

function decodeComponents(components, split) {
	try {
		// Try to decode the entire string first
		return [decodeURIComponent(components.join(''))];
	} catch (err) {
		// Do nothing
	}

	if (components.length === 1) {
		return components;
	}

	split = split || 1;

	// Split the array in 2 parts
	var left = components.slice(0, split);
	var right = components.slice(split);

	return Array.prototype.concat.call([], decodeComponents(left), decodeComponents(right));
}

function decode(input) {
	try {
		return decodeURIComponent(input);
	} catch (err) {
		var tokens = input.match(singleMatcher) || [];

		for (var i = 1; i < tokens.length; i++) {
			input = decodeComponents(tokens, i).join('');

			tokens = input.match(singleMatcher) || [];
		}

		return input;
	}
}

function customDecodeURIComponent(input) {
	// Keep track of all the replacements and prefill the map with the `BOM`
	var replaceMap = {
		'%FE%FF': '\uFFFD\uFFFD',
		'%FF%FE': '\uFFFD\uFFFD'
	};

	var match = multiMatcher.exec(input);
	while (match) {
		try {
			// Decode as big chunks as possible
			replaceMap[match[0]] = decodeURIComponent(match[0]);
		} catch (err) {
			var result = decode(match[0]);

			if (result !== match[0]) {
				replaceMap[match[0]] = result;
			}
		}

		match = multiMatcher.exec(input);
	}

	// Add `%C2` at the end of the map to make sure it does not replace the combinator before everything else
	replaceMap['%C2'] = '\uFFFD';

	var entries = Object.keys(replaceMap);

	for (var i = 0; i < entries.length; i++) {
		// Replace all decoded components
		var key = entries[i];
		input = input.replace(new RegExp(key, 'g'), replaceMap[key]);
	}

	return input;
}

module.exports = function (encodedURI) {
	if (typeof encodedURI !== 'string') {
		throw new TypeError('Expected `encodedURI` to be of type `string`, got `' + typeof encodedURI + '`');
	}

	try {
		encodedURI = encodedURI.replace(/\+/g, ' ');

		// Try the built in decoder first
		return decodeURIComponent(encodedURI);
	} catch (err) {
		// Fallback to a more advanced decoder
		return customDecodeURIComponent(encodedURI);
	}
};


/***/ }),

/***/ "./node_modules/filter-obj/index.js":
/*!******************************************!*\
  !*** ./node_modules/filter-obj/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = function (obj, predicate) {
	var ret = {};
	var keys = Object.keys(obj);
	var isArr = Array.isArray(predicate);

	for (var i = 0; i < keys.length; i++) {
		var key = keys[i];
		var val = obj[key];

		if (isArr ? predicate.indexOf(key) !== -1 : predicate(key, val, obj)) {
			ret[key] = val;
		}
	}

	return ret;
};


/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/query-string/index.js":
/*!********************************************!*\
  !*** ./node_modules/query-string/index.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

const strictUriEncode = __webpack_require__(/*! strict-uri-encode */ "./node_modules/strict-uri-encode/index.js");
const decodeComponent = __webpack_require__(/*! decode-uri-component */ "./node_modules/decode-uri-component/index.js");
const splitOnFirst = __webpack_require__(/*! split-on-first */ "./node_modules/split-on-first/index.js");
const filterObject = __webpack_require__(/*! filter-obj */ "./node_modules/filter-obj/index.js");

const isNullOrUndefined = value => value === null || value === undefined;

function encoderForArrayFormat(options) {
	switch (options.arrayFormat) {
		case 'index':
			return key => (result, value) => {
				const index = result.length;

				if (
					value === undefined ||
					(options.skipNull && value === null) ||
					(options.skipEmptyString && value === '')
				) {
					return result;
				}

				if (value === null) {
					return [...result, [encode(key, options), '[', index, ']'].join('')];
				}

				return [
					...result,
					[encode(key, options), '[', encode(index, options), ']=', encode(value, options)].join('')
				];
			};

		case 'bracket':
			return key => (result, value) => {
				if (
					value === undefined ||
					(options.skipNull && value === null) ||
					(options.skipEmptyString && value === '')
				) {
					return result;
				}

				if (value === null) {
					return [...result, [encode(key, options), '[]'].join('')];
				}

				return [...result, [encode(key, options), '[]=', encode(value, options)].join('')];
			};

		case 'comma':
		case 'separator':
			return key => (result, value) => {
				if (value === null || value === undefined || value.length === 0) {
					return result;
				}

				if (result.length === 0) {
					return [[encode(key, options), '=', encode(value, options)].join('')];
				}

				return [[result, encode(value, options)].join(options.arrayFormatSeparator)];
			};

		default:
			return key => (result, value) => {
				if (
					value === undefined ||
					(options.skipNull && value === null) ||
					(options.skipEmptyString && value === '')
				) {
					return result;
				}

				if (value === null) {
					return [...result, encode(key, options)];
				}

				return [...result, [encode(key, options), '=', encode(value, options)].join('')];
			};
	}
}

function parserForArrayFormat(options) {
	let result;

	switch (options.arrayFormat) {
		case 'index':
			return (key, value, accumulator) => {
				result = /\[(\d*)\]$/.exec(key);

				key = key.replace(/\[\d*\]$/, '');

				if (!result) {
					accumulator[key] = value;
					return;
				}

				if (accumulator[key] === undefined) {
					accumulator[key] = {};
				}

				accumulator[key][result[1]] = value;
			};

		case 'bracket':
			return (key, value, accumulator) => {
				result = /(\[\])$/.exec(key);
				key = key.replace(/\[\]$/, '');

				if (!result) {
					accumulator[key] = value;
					return;
				}

				if (accumulator[key] === undefined) {
					accumulator[key] = [value];
					return;
				}

				accumulator[key] = [].concat(accumulator[key], value);
			};

		case 'comma':
		case 'separator':
			return (key, value, accumulator) => {
				const isArray = typeof value === 'string' && value.includes(options.arrayFormatSeparator);
				const isEncodedArray = (typeof value === 'string' && !isArray && decode(value, options).includes(options.arrayFormatSeparator));
				value = isEncodedArray ? decode(value, options) : value;
				const newValue = isArray || isEncodedArray ? value.split(options.arrayFormatSeparator).map(item => decode(item, options)) : value === null ? value : decode(value, options);
				accumulator[key] = newValue;
			};

		default:
			return (key, value, accumulator) => {
				if (accumulator[key] === undefined) {
					accumulator[key] = value;
					return;
				}

				accumulator[key] = [].concat(accumulator[key], value);
			};
	}
}

function validateArrayFormatSeparator(value) {
	if (typeof value !== 'string' || value.length !== 1) {
		throw new TypeError('arrayFormatSeparator must be single character string');
	}
}

function encode(value, options) {
	if (options.encode) {
		return options.strict ? strictUriEncode(value) : encodeURIComponent(value);
	}

	return value;
}

function decode(value, options) {
	if (options.decode) {
		return decodeComponent(value);
	}

	return value;
}

function keysSorter(input) {
	if (Array.isArray(input)) {
		return input.sort();
	}

	if (typeof input === 'object') {
		return keysSorter(Object.keys(input))
			.sort((a, b) => Number(a) - Number(b))
			.map(key => input[key]);
	}

	return input;
}

function removeHash(input) {
	const hashStart = input.indexOf('#');
	if (hashStart !== -1) {
		input = input.slice(0, hashStart);
	}

	return input;
}

function getHash(url) {
	let hash = '';
	const hashStart = url.indexOf('#');
	if (hashStart !== -1) {
		hash = url.slice(hashStart);
	}

	return hash;
}

function extract(input) {
	input = removeHash(input);
	const queryStart = input.indexOf('?');
	if (queryStart === -1) {
		return '';
	}

	return input.slice(queryStart + 1);
}

function parseValue(value, options) {
	if (options.parseNumbers && !Number.isNaN(Number(value)) && (typeof value === 'string' && value.trim() !== '')) {
		value = Number(value);
	} else if (options.parseBooleans && value !== null && (value.toLowerCase() === 'true' || value.toLowerCase() === 'false')) {
		value = value.toLowerCase() === 'true';
	}

	return value;
}

function parse(query, options) {
	options = Object.assign({
		decode: true,
		sort: true,
		arrayFormat: 'none',
		arrayFormatSeparator: ',',
		parseNumbers: false,
		parseBooleans: false
	}, options);

	validateArrayFormatSeparator(options.arrayFormatSeparator);

	const formatter = parserForArrayFormat(options);

	// Create an object with no prototype
	const ret = Object.create(null);

	if (typeof query !== 'string') {
		return ret;
	}

	query = query.trim().replace(/^[?#&]/, '');

	if (!query) {
		return ret;
	}

	for (const param of query.split('&')) {
		if (param === '') {
			continue;
		}

		let [key, value] = splitOnFirst(options.decode ? param.replace(/\+/g, ' ') : param, '=');

		// Missing `=` should be `null`:
		// http://w3.org/TR/2012/WD-url-20120524/#collect-url-parameters
		value = value === undefined ? null : ['comma', 'separator'].includes(options.arrayFormat) ? value : decode(value, options);
		formatter(decode(key, options), value, ret);
	}

	for (const key of Object.keys(ret)) {
		const value = ret[key];
		if (typeof value === 'object' && value !== null) {
			for (const k of Object.keys(value)) {
				value[k] = parseValue(value[k], options);
			}
		} else {
			ret[key] = parseValue(value, options);
		}
	}

	if (options.sort === false) {
		return ret;
	}

	return (options.sort === true ? Object.keys(ret).sort() : Object.keys(ret).sort(options.sort)).reduce((result, key) => {
		const value = ret[key];
		if (Boolean(value) && typeof value === 'object' && !Array.isArray(value)) {
			// Sort object keys, not values
			result[key] = keysSorter(value);
		} else {
			result[key] = value;
		}

		return result;
	}, Object.create(null));
}

exports.extract = extract;
exports.parse = parse;

exports.stringify = (object, options) => {
	if (!object) {
		return '';
	}

	options = Object.assign({
		encode: true,
		strict: true,
		arrayFormat: 'none',
		arrayFormatSeparator: ','
	}, options);

	validateArrayFormatSeparator(options.arrayFormatSeparator);

	const shouldFilter = key => (
		(options.skipNull && isNullOrUndefined(object[key])) ||
		(options.skipEmptyString && object[key] === '')
	);

	const formatter = encoderForArrayFormat(options);

	const objectCopy = {};

	for (const key of Object.keys(object)) {
		if (!shouldFilter(key)) {
			objectCopy[key] = object[key];
		}
	}

	const keys = Object.keys(objectCopy);

	if (options.sort !== false) {
		keys.sort(options.sort);
	}

	return keys.map(key => {
		const value = object[key];

		if (value === undefined) {
			return '';
		}

		if (value === null) {
			return encode(key, options);
		}

		if (Array.isArray(value)) {
			return value
				.reduce(formatter(key), [])
				.join('&');
		}

		return encode(key, options) + '=' + encode(value, options);
	}).filter(x => x.length > 0).join('&');
};

exports.parseUrl = (url, options) => {
	options = Object.assign({
		decode: true
	}, options);

	const [url_, hash] = splitOnFirst(url, '#');

	return Object.assign(
		{
			url: url_.split('?')[0] || '',
			query: parse(extract(url), options)
		},
		options && options.parseFragmentIdentifier && hash ? {fragmentIdentifier: decode(hash, options)} : {}
	);
};

exports.stringifyUrl = (object, options) => {
	options = Object.assign({
		encode: true,
		strict: true
	}, options);

	const url = removeHash(object.url).split('?')[0] || '';
	const queryFromUrl = exports.extract(object.url);
	const parsedQueryFromUrl = exports.parse(queryFromUrl, {sort: false});

	const query = Object.assign(parsedQueryFromUrl, object.query);
	let queryString = exports.stringify(query, options);
	if (queryString) {
		queryString = `?${queryString}`;
	}

	let hash = getHash(object.url);
	if (object.fragmentIdentifier) {
		hash = `#${encode(object.fragmentIdentifier, options)}`;
	}

	return `${url}${queryString}${hash}`;
};

exports.pick = (input, filter, options) => {
	options = Object.assign({
		parseFragmentIdentifier: true
	}, options);

	const {url, query, fragmentIdentifier} = exports.parseUrl(input, options);
	return exports.stringifyUrl({
		url,
		query: filterObject(query, filter),
		fragmentIdentifier
	}, options);
};

exports.exclude = (input, filter, options) => {
	const exclusionFilter = Array.isArray(filter) ? key => !filter.includes(key) : (key, value) => !filter(key, value);

	return exports.pick(input, exclusionFilter, options);
};


/***/ }),

/***/ "./node_modules/split-on-first/index.js":
/*!**********************************************!*\
  !*** ./node_modules/split-on-first/index.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = (string, separator) => {
	if (!(typeof string === 'string' && typeof separator === 'string')) {
		throw new TypeError('Expected the arguments to be of type `string`');
	}

	if (separator === '') {
		return [string];
	}

	const separatorIndex = string.indexOf(separator);

	if (separatorIndex === -1) {
		return [string];
	}

	return [
		string.slice(0, separatorIndex),
		string.slice(separatorIndex + separator.length)
	];
};


/***/ }),

/***/ "./node_modules/strict-uri-encode/index.js":
/*!*************************************************!*\
  !*** ./node_modules/strict-uri-encode/index.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

module.exports = str => encodeURIComponent(str).replace(/[!'()*]/g, x => `%${x.charCodeAt(0).toString(16).toUpperCase()}`);


/***/ }),

/***/ "./resources/scripts/admin/index.js":
/*!******************************************!*\
  !*** ./resources/scripts/admin/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "./node_modules/@babel/runtime/helpers/interopRequireDefault.js");
var _config = _interopRequireDefault(__webpack_require__(/*! @config */ "./config.json"));
__webpack_require__(/*! @styles/admin */ "./resources/styles/admin/index.scss");
var _choices = _interopRequireDefault(__webpack_require__(/*! choices.js */ "./node_modules/choices.js/public/assets/scripts/choices.min.js"));
var _queryString = _interopRequireDefault(__webpack_require__(/*! query-string */ "./node_modules/query-string/index.js"));
var _axios = _interopRequireDefault(__webpack_require__(/*! axios */ "./node_modules/axios/index.js"));
;
document.addEventListener('DOMContentLoaded', function () {
  var choices = document.querySelectorAll('.crb-select2 select');
  if (choices.length !== 0) {
    choices.forEach(function (choice) {
      var options = choice.dataset.select ? JSON.parse(choice.dataset.select) : {};
      options.removeItemButton = true;
      new _choices.default(choice, options);
    });
  }
  var params = _queryString.default.parse(location.search);
  if (params.page === 'lisfinity-elements') {
    location.href = location.origin + '/wp-admin/edit.php?post_type=lisfinity_header';
  }
});

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


/***/ })

/******/ });
//# sourceMappingURL=admin.js.map