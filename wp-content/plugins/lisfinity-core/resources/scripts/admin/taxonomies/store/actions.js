/* global lc_data */

// Product data.
export function setData(data) {
  return {
    type: 'GET_DATA',
    data,
  };
}

export function setThemeOptions(options) {
  return {
    type: 'GET_OPTIONS',
    options,
  };
}
