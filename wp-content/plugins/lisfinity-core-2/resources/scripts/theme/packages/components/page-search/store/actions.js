/* global ager_data */
import jsonForm from '../../../../../forms/utils/build-form-data';
import queryString from 'query-string';

function requestPosts(url) {
  return {
    type: 'REQUEST_POSTS',
    url,
  };
}

function requestPostHeaders(totalPosts, maxPages, page) {
  return {
    type: 'REQUEST_PAGINATION',
    totalPosts,
    maxPages,
    page,
  };
}

function receivePosts(results) {
  return {
    type: 'RECEIVE_POSTS',
    results,
    receivedAt: Date.now(),
  };
}

function fetchingPosts(isFetching) {
  return {
    type: 'FETCHING_POSTS',
    isFetching,
  };
}

export function updateFieldOptions(fieldOptions) {
  return {
    type: 'STORE_FIELD_OPTIONS',
    fieldOptions,
  };
}

function calculatingPosts(found_posts) {
  return {
    type: 'CALCULATE_FOUND_POSTS',
    found_posts,
  };
}

function calculatingPostsLoading(calculating) {
  return {
    type: 'CALCULATING_POSTS',
    calculating,
  };
}

function adsLoading(loading) {
  return {
    type: 'ADS_LOADING',
    loading,
  };
}

export function calculateFoundPosts(data) { //eslint-disable-line
  return (dispatch) => {
    dispatch(calculatingPostsLoading(true));
    const myHeaders = new Headers();
    myHeaders.append('pragma', 'no-cache');
    myHeaders.append('cache-control', 'no-cache');
    return fetch(`${lc_data.search}?${queryString.stringify(data)}&timestamp=${new Date().getTime()}`, {
      headers: myHeaders,
      method: 'GET',
    }).then((response) => {
      dispatch(calculatingPosts(response.headers.get('X-WP-Total')));
      dispatch(calculatingPostsLoading(false));
      return response.json();
    });
  };
}

export function fetchPosts(data, fromPagination = false, paginating = false) { //eslint-disable-line
  if (lc_data.current_product_id) {
    data.business = lc_data.current_product_id;
  }
  if (!fromPagination && data !== undefined) {
    delete data.offset;
  }
  data['_wpnonce'] = lc_data.nonce;
  const formData = jsonForm(data);
  return (dispatch) => {
    dispatch(fetchingPosts(fromPagination || paginating ? 'paginating' : true));
    dispatch(calculatingPostsLoading(true));
    dispatch(requestPosts(formData));
    dispatch(adsLoading(true));
    return fetch(`${lc_data.search}?${queryString.stringify(data)}`, {
      method: 'GET',
    }).then((response) => {
      dispatch(requestPostHeaders(response.headers.get('X-WP-Total'), response.headers.get('X-WP-TotalPages')), response.headers.get('X-WP-Page'));
      return response.json();
    }).then(results => {
      dispatch(adsLoading(false));
      dispatch(fetchingPosts(false));
      dispatch(receivePosts(results));
      if ((!fromPagination && data !== undefined) || 'calculate_posts') {
        dispatch(calculatingPosts(results.found_posts));
      }
      dispatch(calculatingPostsLoading(false));
    });
  };
}
