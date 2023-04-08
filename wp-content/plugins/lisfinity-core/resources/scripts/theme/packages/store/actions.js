/* global lc_data */
/**
 * Load external dependencies
 * --------------------------
 */
import apiFetch from '@wordpress/api-fetch';
import store from '../../index';
import { isEmpty } from 'lodash';

// Product data.
export function setProduct(product) {
  return {
    type: 'SET_PRODUCT',
    product,
  };
}

export function setUser(user) {
  return {
    type: 'SET_USER',
    user,
  };
}

export function setOptions(options) {
  return {
    type: 'SET_OPTIONS',
    options,
  };
}
export function getProduct(product) {
  return {
    type: 'GET_PRODUCT',
    product,
  };
}

export function getProductData() {
  const product_id = lc_data.current_product_id;
  const url = `${lc_data.product}/${product_id}`;

  apiFetch({ path: url }).then(product => store.dispatch(getProduct(product)));
}

// Search form data.
export function updateSearchData(data) {
  return {
    type: 'STORE_SEARCH_DATA',
    data,
  };
}

export function updateSearchDataChosen(data) {
  return {
    type: 'STORE_SEARCH_DATA_CHOSEN',
    data,
  };
}

export function updateSearchDataFirst(data) {
  return {
    type: 'STORE_SEARCH_DATA_FIRST',
    data,
  };
}

export function updateFieldOptions(fieldOptions) {
  return {
    type: 'STORE_FIELD_OPTIONS',
    fieldOptions,
  };
}

export function updateTaxonomyOptions(taxonomyOptions) {
  return {
    type: 'STORE_TAXONOMY_OPTIONS',
    taxonomyOptions,
  };
}

export function updateShowMap(showMap) {
  return {
    type: 'STORE_SHOW_MAP',
    showMap,
  };
}

export function updateShowFilters(showFilters) {
  return {
    type: 'STORE_SHOW_FILTERS',
    showFilters,
  };
}

export function updateMapPosition(mapPosition) {
  return {
    type: 'SET_MAP_POSITION',
    mapPosition,
  };
}

export function specificationMenu(specMenu) {
  return {
    type: 'STORE_SPECIFICATION_MENU',
    specMenu,
  };
}

export function otherTaxonomies(other) {
  return {
    type: 'STORE_OTHER_TAXONOMIES',
    other,
  };
}

export function productMenuActive(active) {
  return {
    type: 'SET_PRODUCT_ACTIVE_GROUP',
    active,
  };
}

export function setIsDetailed(detailed) {
  return {
    type: 'SET_IS_DETAILED',
    detailed,
  };
}

export function setHomeFields(fields) {
  return {
    type: 'SET_HOME_FIELDS',
    fields,
  };
}

export function setVendors(vendors) {
  return {
    type: 'SET_VENDORS',
    vendors,
  };
}

export function setVendorsLoading(loading) {
  return {
    type: 'SET_VENDORS_LOADING',
    loading,
  };
}

export function setVendorsFL(firstLoad) {
  return {
    type: 'SET_VENDORS_FL',
    firstLoad,
  };
}
