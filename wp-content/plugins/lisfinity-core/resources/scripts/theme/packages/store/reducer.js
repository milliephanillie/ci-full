import { combineReducers } from '@wordpress/data';

// product data.
function product(state = {}, action) {
  switch (action.type) {
    case 'SET_PRODUCT':
      return action.product;
    default:
      return state;
  }
}

function user(state = {}, action) {
  switch (action.type) {
    case 'SET_USER':
      return action.user;
    default:
      return state;
  }
}

function options(state = {}, action) {
  switch (action.type) {
    case 'SET_OPTIONS':
      return action.options;
    default:
      return state;
  }
}

// search form data.
function searchData(state = {}, action) {
  switch (action.type) {
    case 'STORE_SEARCH_DATA':
      return { ...state, ...action.data };
    default:
      return state;
  }
}

function searchDataChosen(state = {}, action) {
  switch (action.type) {
    case 'STORE_SEARCH_DATA_CHOSEN':
      return action.data;
    default:
      return state;
  }
}

function searchDataFirst(state = {}, action) {
  switch (action.type) {
    case 'STORE_SEARCH_DATA_FIRST':
      return action.data;
    default:
      return state;
  }
}

function fieldOptions(state = {}, action) {
  switch (action.type) {
    case 'STORE_FIELD_OPTIONS':
      return action.fieldOptions;
    default:
      return state;
  }
}

function taxonomyOptions(state = {}, action) {
  switch (action.type) {
    case 'STORE_TAXONOMY_OPTIONS':
      return action.taxonomyOptions;
    default:
      return state;
  }
}

function showMap(state = {}, action) {
  switch (action.type) {
    case 'STORE_SHOW_MAP':
      return action.showMap;
    default:
      return state;
  }
}

function showFilters(state = {}, action) {
  switch (action.type) {
    case 'STORE_SHOW_FILTERS':
      return action.showFilters;
    default:
      return state;
  }
}

function isDetailed(state = false, action) {
  switch (action.type) {
    case 'SET_IS_DETAILED':
      return action.detailed;
    default:
      return state;
  }
}

function specificationMenu(state = [], action) {
  switch (action.type) {
    case 'STORE_SPECIFICATION_MENU':
      return action.specMenu;
    default:
      return state;
  }
}

function otherTaxonomies(state = {}, action) {
  switch (action.type) {
    case 'STORE_OTHER_TAXONOMIES':
      return action.other;
    default:
      return state;
  }
}

function productMenuActive(state = 'basic', action) {
  switch (action.type) {
    case 'SET_PRODUCT_ACTIVE_GROUP':
      return action.active;
    default:
      return state;
  }
}

function homeFields(state = {}, action) {
  switch (action.type) {
    case 'SET_HOME_FIELDS':
      return action.fields;
    default:
      return state;
  }
}

// Search data.
function posts(
  state = {
    url: '',
    isFetching: false,
    items: {},
    maxPages: 0,
    is_premium: false,
  },
  action,
) {
  switch (action.type) {
    case 'REQUEST_POSTS':
      return {
        ...state,
        isFetching: true,
        url: action.url,
      };
    case 'REQUEST_PAGINATION':
      return {
        ...state,
        isFetching: true,
        totalPosts: action.totalPosts,
        maxPages: action.maxPages,
      };
    case 'RECEIVE_POSTS':
      return {
        ...state,
        isFetching: false,
        items: action.results,
        lastUpdated: action.receivedAt,
        is_premium: action.results.is_premium,
      };
    default:
      return state;
  }
}

function isFetching(state = false, action) {
  switch (action.type) {
    case 'FETCHING_POSTS':
      return action.isFetching;
    default:
      return state;
  }
}

function postsByUrl(state = {}, action) {
  switch (action.type) {
    case 'RECEIVE_POSTS':
    case 'REQUEST_POSTS':
    case 'REQUEST_PAGINATION':
      return {
        ...state,
        [action.type]: posts(state[action.url], action),
      };
    default:
      return state;
  }
}

function foundPosts(state = 0, action) {
  switch (action.type) {
    case 'CALCULATE_FOUND_POSTS':
      return action.found_posts;
    default:
      return state;
  }
}

function calculating(state = false, action) {
  switch (action.type) {
    case 'CALCULATING_POSTS':
      return action.calculating;
    default:
      return state;
  }
}

function adsLoading(state = false, action) {
  switch (action.type) {
    case 'ADS_LOADING':
      return action.loading;
    default:
      return state;
  }
}

function vendors(state = false, action) {
  switch (action.type) {
    case 'SET_VENDORS':
      return action.vendors;
    default:
      return state;
  }
}

function vendorsFL(state = true, action) {
  switch (action.type) {
    case 'SET_VENDORS_FL':
      return action.firstLoad;
    default:
      return state;
  }
}

function vendorsLoading(state = false, action) {
  switch (action.type) {
    case 'SET_VENDORS_LOADING':
      return action.loading;
    default:
      return state;
  }
}

function mapPosition(state = [50.505, -29.09], action) {
  switch (action.type) {
    case 'SET_MAP_POSITION':
      return action.mapPosition;
    default:
      return state;
  }
}

const rootReducer = combineReducers({
  product,
  user,
  options,
  searchDataChosen,
  taxonomyOptions,
  showMap,
  showFilters,
  otherTaxonomies,
  productMenuActive,
  isDetailed,
  homeFields,
  specificationMenu,
  postsByUrl,
  searchData,
  isFetching,
  fieldOptions,
  foundPosts,
  calculating,
  adsLoading,
  vendors,
  vendorsFL,
  vendorsLoading,
  mapPosition,
});

export default rootReducer;
