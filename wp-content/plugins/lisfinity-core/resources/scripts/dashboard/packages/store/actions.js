/* global lc_data */
import axios from 'axios';

/**
 * Load external dependencies
 * --------------------------
 */

export const setLoading = (loading) => {
  return {
    type: 'SET_LOADING',
    loading,
  };
};

export const setBusiness = (business) => {
  return {
    type: 'SET_BUSINESS',
    business,
  };
};

export const setInfo = (info) => {
  return {
    type: 'SET_INFO',
    info,
  };
}

export const setProduct = (product) => {
  return {
    type: 'SET_PRODUCT',
    product,
  };
};

export const setMenuOpen = (open) => {
  return {
    type: 'SET_MENU_OPEN',
    open,
  };
};

export const setMainIcon = (options) => {
  return {
    type: 'SET_MAIN_ICON',
    options,
  };
};

export const setNotifications = (notifications) => {
  return {
    type: 'SET_NOTIFICATIONS',
    notifications,
  };
}

export const setNotificationsMenu = (menu) => {
  return {
    type: 'SET_NOTIFICATIONS_MENU',
    menu,
  };
}

export const fetchData = async (url, formData = '') => {
  const headers = {
    'X-WP-Nonce': lc_data.nonce,
  }
  let data = {
    id: lc_data.current_user_id,
  };
  if (formData !== '') {
    data = { ...data, ...formData };
  }
  const result = await axios({
    credentials: 'same-origin',
    headers,
    method: 'post',
    url,
    data,
  });

  return result;
};

export function setupSubmitFields(fields) {
  return {
    type: 'GET_FIELDS',
    fields,
  };
}

export function setupFieldGroups(groups) {
  return {
    type: 'GET_FIELD_GROUPS',
    groups,
  };
}

export function setupFieldsByGroups(fieldsByGroup) {
  return {
    type: 'GET_FIELDS_BY_GROUPS',
    fieldsByGroup,
  };
}

export function updateFormData(data) {
  return {
    type: 'STORE_FORM_DATA',
    data,
  };
}

export function updateFormErrors(errors) {
  return {
    type: 'UPDATE_FORM_ERRORS',
    errors,
  };
}

export function updateFormCF(customFields) {
  return {
    type: 'SET_CUSTOM_FIELDS',
    customFields,
  };
}

export function updateFormTaxonomies(taxonomies) {
  return {
    type: 'SET_CUSTOM_FIELDS_TAXONOMIES',
    taxonomies,
  };
}

export function updateFormTerms(terms) {
  return {
    type: 'SET_CUSTOM_FIELDS_TERMS',
    terms,
  };
}

export function setupPackage(payment) {
  return {
    type: 'SET_PACKAGE',
    payment,
  };
}

export function updateCosts(costs) {
  return {
    type: 'UPDATE_COSTS',
    costs,
  };
}

export const setProfile = (profile) => {
  return {
    type: 'SET_PROFILE',
    profile,
  };
};

export const setOptions = (options) => {
  return {
    type: 'SET_OPTIONS',
    options,
  };
};
