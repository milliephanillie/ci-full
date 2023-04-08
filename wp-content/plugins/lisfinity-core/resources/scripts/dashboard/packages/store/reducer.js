import { isEmpty } from 'lodash';
import { combineReducers } from '@wordpress/data';

const business = (state = {}, action) => {
  switch (action.type) {
    case 'SET_BUSINESS':
      return { ...state, ...action.business };
    default:
      return state;
  }
};

const info = (state = {}, action) => {
  switch (action.type) {
    case 'SET_INFO':
      return action.info;
    default:
      return state;
  }
}

const product = (state = {}, action) => {
  switch (action.type) {
    case 'SET_PRODUCT':
      return action.product;
    default:
      return state;
  }
};

const loading = (state = {}, action) => {
  switch (action.type) {
    case 'SET_LOADING':
      return action.loading || false;
    default:
      return state;
  }
};

const menuOpen = (state = false, action) => {
  switch (action.type) {
    case 'SET_MENU_OPEN':
      return action.open;
    default:
      return state;
  }
};

const mainIcon = (state = {}, action) => {
  switch (action.type) {
    case 'SET_MAIN_ICON':
      return action.options;
    default:
      return state;
  }
};


const notifications = (state = {}, action) => {
  switch (action.type) {
    case 'SET_NOTIFICATIONS':
      return action.notifications;
    default:
      return state;
  }
};

const notificationsMenu = (state = {}, action) => {
  switch (action.type) {
    case 'SET_NOTIFICATIONS_MENU':
      return action.menu;
    default:
      return false;
  }
};

// form related.
function fields(state = {}, action) {
  switch (action.type) {
    case 'GET_FIELDS':
      return action.fields;
    default:
      return state;
  }
}

function fieldGroups(state = {}, action) {
  switch (action.type) {
    case 'GET_FIELD_GROUPS':
      return map(action.groups, group => (
        group.slug
      ));
    default:
      return state;
  }
}

function fieldsByGroup(state = {}, action) {
  switch (action.type) {
    case 'GET_FIELDS_BY_GROUP':
      return action.fieldsByGroup;
    default:
      return state;
  }
}

function formData(state = {}, action) {
  switch (action.type) {
    case 'STORE_FORM_DATA':
      return action.data;
    default:
      return state;
  }
}

function formErrors(state = {}, action) {
  switch (action.type) {
    case 'UPDATE_FORM_ERRORS':
      return action.errors;
    default:
      return state;
  }
}

function customFields(state = {}, action) {
  switch (action.type) {
    case 'SET_CUSTOM_FIELDS':
      return action.customFields;
    default:
      return state;
  }
}

function taxonomies(state = {}, action) {
  switch (action.type) {
    case 'SET_CUSTOM_FIELDS_TAXONOMIES':
      return action.taxonomies;
    default:
      return state;
  }
}

function terms(state = {}, action) {
  switch (action.type) {
    case 'SET_CUSTOM_FIELDS_TERMS':
      return action.terms;
    default:
      return state;
  }
}

function paymentPackage(state = {}, action) {
  switch (action.type) {
    case 'SET_PACKAGE':
      return action.payment;
    default:
      return state;
  }
}

function costs(state = {}, action) {
  switch (action.type) {
    case 'UPDATE_COSTS':
      return action.costs;
    default:
      return state;
  }
}

const profile = (state = {}, action) => {
  switch (action.type) {
    case 'SET_PROFILE':
      return action.profile;
    default:
      return state;
  }
};

const options = (state = {}, action) => {
  switch (action.type) {
    case 'SET_OPTIONS':
      return action.options;
    default:
      return state;
  }
};

const reducer = combineReducers({
  loading,
  business,
  info,
  product,
  menuOpen,
  mainIcon,
  notifications,
  notificationsMenu,
  fields,
  fieldGroups,
  fieldsByGroup,
  formData,
  formErrors,
  paymentPackage,
  costs,
  profile,
  options,
  customFields,
  taxonomies,
  terms,
});

export default reducer;
