/* global lc_data */
/**
 * External dependencies
 */
import {sprintf, _n} from '@wordpress/i18n';
import axios from 'axios';
import * as actions from '../../dashboard/packages/store/actions';
import React, {useRef, useEffect} from 'react';

/**
 * Get remaining time from the given timestamp
 * -------------------------------------------
 *
 * @param endTime
 * @returns {{total: number, hours: number, seconds: number, minutes: number, days: number}}
 */
function getTimeRemaining(endTime) {
  const date = new Date();
  const end = new Date(endTime * 1000);
  const endDate = new Date(end.toLocaleString('en-EN', {timeZone: 'UTC'}));
  let newDate;
  if (isNaN(lc_data.timezone)) {
    newDate = new Date(date.toLocaleString('en-EN', {timeZone: lc_data.timezone})).getTime();
  } else {
    newDate = date.getTime() + parseInt(lc_data.timezone, 10);
  }

  const t = endDate.getTime() - newDate;
  let seconds = Math.floor((t / 1000) % 60);
  let minutes = Math.floor((t / 1000 / 60) % 60);
  const hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  const days = Math.floor(t / (1000 * 60 * 60 * 24));
  seconds = seconds < 10 ? `0${seconds}` : seconds;
  minutes = minutes < 10 ? `0${minutes}` : minutes;
  return {
    total: t,
    days,
    hours,
    minutes,
    seconds,
  };
}

/**
 * Initialize clock on the DOM
 * ---------------------------
 *
 * @param wrapper
 * @param endTime
 * @param fullFormat
 */
export function initializeClock(wrapper, endTime, fullFormat = false) {
  const timeinterval = setInterval(() => {
    const t = getTimeRemaining(endTime);
    const days = t.days > 0 && `<div class="timer--el days">${t.days}</div>`;
    const hours = `<div class="timer--el hours">${t.hours}</div>`;
    const minutes = `<div class="timer--el minutes">${t.minutes}</div>`;
    const seconds = `<div class="timer--el seconds">${t.seconds}</div>`;
    let html = '';
    if (fullFormat) {
      if (t.days > 0) {
        html += `${days}:${hours}:${minutes}:${seconds}`;
      } else {
        html += `${hours}:${minutes}:${seconds}`;
      }
    }
    if (!fullFormat) {
      if (t.days > 0) {
        html += sprintf(_n(lc_data.jst[546], lc_data.jst[547], t.days, 'lisfinity-core'), parseInt(t.days, 10));
        clearInterval(timeinterval);
      } else {
        html += `${hours}:${minutes}:${seconds}`;
      }
    }
    wrapper.innerHTML = `<div class="timer flex items-center">${html}</div>`;
    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  }, 1000);
}

/**
 * Format money same as in WooCommerce settings
 * --------------------------------------------
 *
 * @param amount
 * @param decimalCount
 * @param decimal
 * @param thousands
 * @returns {string}
 */
export function formatMoney(amount, decimalCount = 2, decimal = '.', thousands = ',') {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? '-' : '';

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : '');
  } catch (e) {
    console.log(e);
  }
}

/**
 * Store the stat for the given product id
 * ---------------------------------------
 *
 * @param productId
 * @param type
 */
export const storeStat = async (productId, type) => {
  const headers = {
    'X-WP-Nonce': lc_data.nonce,
  };
  const url = lc_data.update_stats;
  await axios({
    credentials: 'same-origin',
    headers,
    method: 'post',
    url,
    data: {
      product_id: productId,
      type,
    }
  }).then(response => {
  });
};

export function updateDataset(e, datasetIndex) {
  const index = datasetIndex;
  const ci = e.view.myChart;
  const meta = ci.getDatasetMeta(index);

  // See controller.isDatasetVisible comment
  meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;

  // We hid a dataset ... rerender the chart
  ci.update();
};

export function sideScroll(element, direction, speed, distance, step) {
  let scrollAmount = 0;
  const slideTimer = setInterval(() => {
    if (direction === 'left') {
      element.scrollLeft -= step;
    } else {
      element.scrollLeft += step;
    }
    scrollAmount += step;
    if (scrollAmount >= distance) {
      window.clearInterval(slideTimer);
    }
  }, speed);
};

export function btnRipple(button) {
  button.onmousedown = (e) => {

    const x = e.pageX - (window.pageXOffset + button.getBoundingClientRect().left);
    const y = e.pageY - (window.pageYOffset + button.getBoundingClientRect().top);
    const w = button.offsetWidth;

    const ripple = document.createElement('span');

    if (button.classList.contains('r:alt')) {
      ripple.className = 'ripple ripple__alt';
    } else {
      ripple.className = 'ripple';
    }
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.style.setProperty('--scale', w);

    button.appendChild(ripple);

    setTimeout(() => {
      ripple.parentNode.removeChild(ripple);
    }, 500);

  };
}

export function isInViewport(element, offset = 0) {
  const windowHeight = window.innerHeight;
  const gridBottom = windowHeight * .1;
  if (element) {
    const {top} = element.getBoundingClientRect();
    return (top + offset) >= 0 && (top - offset) <= gridBottom;
  }
}

export function getSafe(fn, defaultVal = false) {
  try {
    return fn();
  } catch (e) {
    if (defaultVal) {
      return defaultVal;
    } else {
      console.log(e);
    }
  }
}

export function setCookie(name, value, days) {
  var expires = '';
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = '; expires=' + date.toUTCString();
  }
  document.cookie = name + '=' + (value || '') + expires + '; path=/';
}

export function getCookie(name) {
  var nameEQ = name + '=';
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

export function eraseCookie(name) {
  document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

export function getMobileOperatingSystem() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

  // Windows Phone must come first because its UA also contains "Android"
  if (/windows phone/i.test(userAgent)) {
    return 'Windows Phone';
  }

  if (/android/i.test(userAgent)) {
    return 'Android';
  }

  // iOS detection from: http://stackoverflow.com/a/9039885/177710
  if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
    return 'iOS';
  }

  return 'unknown';
}

export function addProps(obj, arr, val) {

  if (typeof arr == 'string')
    arr = arr.split('.');

  obj[arr[0]] = obj[arr[0]] || {};

  let tmpObj = obj[arr[0]];

  if (arr.length > 1) {
    arr.shift();
    addProps(tmpObj, arr, val);
  } else
    obj[arr[0]] = val;

  return obj;

}

export const isLodash = () => {
  let isLodash = false;

  // If _ is defined and the function _.forEach exists then we know underscore OR lodash are in place
  if ('undefined' != typeof (_) && 'function' == typeof (_.forEach)) {

    // A small sample of some of the functions that exist in lodash but not underscore
    const funcs = ['get', 'set', 'at', 'cloneDeep'];

    // Simplest if assume exists to start
    isLodash = true;

    funcs.forEach(function (func) {
      // If just one of the functions do not exist, then not lodash
      isLodash = ('function' != typeof (_[func])) ? false : isLodash;
    });
  }

  if (isLodash) {
    // We know that lodash is loaded in the _ variable
    return true;
  } else {
    // We know that lodash is NOT loaded
    return false;
  }
};

export const validatePrice = (price) => {
  return /^(?!.*(,,|,\.|\.,|\.\.))[\d.,]+$/.test(price);
}