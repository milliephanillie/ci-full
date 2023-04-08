import { combineReducers } from '@wordpress/data';

// product data.
function data(state = {}, action) {
  switch (action.type) {
    case 'GET_DATA':
      return action.data;
    default:
      return state;
  }
}
function themeOptions(state = {}, action) {
  switch (action.type) {
    case 'GET_OPTIONS':
      return action.options;
    default:
      return state;
  }
}

export default combineReducers({
  data,
  themeOptions,
});
