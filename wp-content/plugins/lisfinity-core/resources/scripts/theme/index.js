/* global $, lc_data */
// eslint-disable-next-line no-unused-vars
import config from '@config';
import './vendor/*.js';
import '@styles/theme';
import 'airbnb-browser-shims';
import SVGInject from './vendor/svg-inject';

if ( isLodash() ) {
  _.noConflict();
};
/**
 * Dependencies.
 */
import { render } from '@wordpress/element';
import axios from 'axios';
import Toastify from 'toastify-js';
import { Provider } from 'react-redux';
import HeaderTaxonomy from './packages/components/form-fields/HeaderTaxonomy';
import HomeSearch from './packages/components/home-search/HomeSearch';
import PageAuth from './packages/components/page-auth/PageAuth';
import PageSecurityTips from './packages/components/page-tips/PageTips';
import PageSearch from './packages/components/page-search/PageSearch';
import ProductSingle from './packages/components/product-single/ProductSingle';
import configureStoreHeader from '../dashboard/packages/store/configureStore';
import * as functions from './vendor/functions';
import BusinessSingle from './packages/components/business-single/BusinessSingle';
import HeaderCompareWrapper from '../dashboard/packages/components/header/HeaderCompareWrapper';
import HeaderNotificationsWrapper from '../dashboard/packages/components/header/HeaderNotificationsWrapper';
import MenuMobile from './packages/components/menus/MenuMobile';
import MenuMobileSearch from './packages/components/menus/MenuMobileSearch';
import queryString from 'query-string';
import BusinessArchive from './packages/components/business-archive/BusinessArchive';
import HeaderKeywordEl from './packages/components/form-fields/HeaderKeywordEl';
import HomeSearchElement from './packages/components/home-search/HomeSearchElement';
import PageSearchEl from './packages/components/page-search-elementor/PageSearchEl';
import SearchFiltersEl from './packages/components/page-search-elementor/SearchFiltersEl';
import BreadcrumbEl from './packages/components/page-search-elementor/BreadcrumbEl';
import SearchFilterTop from './packages/components/page-search-elementor/SearchFilterTop';
import SearchMapEl from './packages/components/page-search-elementor/SearchMapEl';
import PageSearchContentEl from './packages/components/page-search-elementor/PageSearchContentEl';
import SearchDetailedEl from './packages/components/page-search-elementor/SearchDetailedEl';
import ProductSingleEl from './packages/components/product-single-elementor/ProductSingleEl';
import ProductTitleEl from './packages/components/product-single-elementor/ProductTitleEl';
import ProductIdEl from './packages/components/product-single-elementor/ProductIdEl';
import ProductInfoEl from './packages/components/product-single-elementor/ProductInfoEl';
import ProductBreadcrumbsEl from './packages/components/product-single-elementor/ProductBreadcrumbsEl';
import ProductActionsEl from './packages/components/product-single-elementor/ProductActionsEl';
import ProductGalleryEl from './packages/components/product-single-elementor/ProductGalleryEl';
import ProductSpecificationEl
  from './packages/components/product-single-elementor/specification/ProductSpecificationEl';
import ProductDescriptionEl from './packages/components/product-single-elementor/ProductDescriptionEl';
import ProductSidebarMenuEl from './packages/components/product-single-elementor/sidebar-menu/ProductSidebarMenuEl';
import ProductStickyMenuEl from './packages/components/product-single-elementor/sticky-menu/ProductStickyMenuEl';
import ProductLogoEL from './packages/components/product-single-elementor/ProductLogoEl';
import ProductOwnerNameEl from './packages/components/product-single-elementor/ProductOwnerNameEl';
import ProductOwnerInfoIconEl from './packages/components/product-single-elementor/ProductInfoIconEl';
import ProductOwnerPhoneEl from './packages/components/product-single-elementor/owner-phone/ProductOwnerPhoneEl';
import ProductOwnerButtonEl from './packages/components/product-single-elementor/owner-button/ProductOwnerButtonEl';
import ProductLocationMapEl from './packages/components/product-single-elementor/ProductLocationMapEl';
import ProductFinancingCalculatorEl
  from './packages/components/product-single-elementor/financing-calculator/ProductFinancingCalculatorEl';
import ProductSafetyTipsEl from './packages/components/product-single-elementor/safety-tips/ProductSafetyTipsEl';
import ProductMobileMenuEl from './packages/components/product-single-elementor/ProductMobileMenuEl';
import RegisterFormEl from './packages/components/page-auth-elementor/RegisterFormEl';
import AuthBreadcrumbEl from './packages/components/page-auth-elementor/AuthBreadcrumbEl';
import PasswordResetFormEl from './packages/components/page-auth-elementor/PasswordResetFormEl';
import BusinessSingleEl from './packages/components/business-single-elementor/BusinessSingleEl';
import BusinessReviewsEl from './packages/components/business-single-elementor/BusinessReviewsEl';
import BusinessContactEl from './packages/components/business-single-elementor/BusinessContactEl';
import BusinessAboutEl from './packages/components/business-single-elementor/BusinessAboutEl';
import BusinessStoreEl from './packages/components/business-single-elementor/BusinessStoreEl';
import { applyMiddleware, createStore } from 'redux';
import thunkMiddleware from 'redux-thunk';
import rootReducer from './packages/store/reducer';
import LoginFormEl from './packages/components/page-auth-elementor/LoginFormEl';
import BusinessTestimonialEl from './packages/components/business-single-elementor/BusinessTestimonialEl';
import { eraseCookie, isLodash, setCookie } from './vendor/functions';
import { map } from 'lodash';
import ProductOwnerHoursAltEl from './packages/components/product-single-elementor/ProductOwnerHoursAltEl';
import AuthorBoxEl from './packages/components/authors-page-elementor/AuthorBoxEl';
import AuthorSearchEl from './packages/components/authors-page-elementor/AuthorSearchEl';
import AuthorsEl from './packages/components/authors-page-elementor/AuthorsEl';
import { forEach } from 'callbag-basics';
import MenuMobileSearchEl from "./packages/components/menus/MenuMobileSearchEl";

function configureMainStore(preloadedState) {
  return createStore(
    rootReducer,
    preloadedState,
    applyMiddleware(thunkMiddleware),
  );
}

const store = configureMainStore();

const params = queryString.parse(location.search);
const search = document.getElementById('page-search');
const pageSearchElementor = document.getElementById('page-search-elementor');
const productSingle = document.getElementById('page-single');
const pageSecurityTips = document.getElementById('page-tips');
const authenticate = document.getElementById('page-auth');
const headerTaxonomy = document.getElementById('header-taxonomy');
const businessSingle = document.getElementById('page-single-business');
const businessArchive = document.getElementById('page-archive-vendors');
const mobileMenuWrapper = document.getElementById('mobile-menu--wrapper');
const mobileMenuSearch = document.getElementById('mobile-menu--search');
const mobileMenuSearchEl = document.querySelectorAll('.mobile-menu--search');
const headerCompareWrapper = document.querySelectorAll('.compare--wrapper');
let pageBusinessProfile = document.getElementById('page-single-business-elementor');
if (!pageBusinessProfile) {
  pageBusinessProfile = document.getElementById('page-single-business-premium-elementor');
}

if (mobileMenuSearchEl) {
  mobileMenuSearchEl.forEach(wrapper => {
    render(
      <Provider store={store}>
        <MenuMobileSearchEl/>
      </Provider>,
      wrapper,
    );
  });
}

// Product Single Elementor Widgets End!
const vendorsEl = document.getElementById('page-vendors-elementor');

// product single elementor hook.
if (vendorsEl) {
  render(
    <Provider store={store}>
      <AuthorsEl/>
    </Provider>,
    vendorsEl,
  );
}

// Product Single Elementor Widgets End!
const productSingleEl = document.getElementById('page-single-elementor');

// product single elementor hook.
if (productSingleEl) {
  render(
    <Provider store={store}>
      <ProductSingleEl/>
    </Provider>,
    productSingleEl,
  );
}

// product single elementor hook.
const productTitle = document.querySelectorAll('.elementor-product-title');
if (productTitle) {
  productTitle.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductTitleEl/>
      </Provider>,
      wrapper,
    );
  });
}
// product single elementor hook.
const productId = document.querySelectorAll('.elementor-product-id');
if (productId) {
  productId.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductIdEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productOwnerHours = document.querySelectorAll('.elementor-product-working-hours');

if (productOwnerHours) {
  productOwnerHours.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductOwnerHoursAltEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productInfo = document.querySelectorAll('.elementor-product-info');
if (productInfo) {
  productInfo.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductInfoEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productActions = document.querySelectorAll('.elementor-product-actions');
if (productActions) {
  productActions.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductActionsEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productBreadcrumbs = document.querySelectorAll('.elementor-product-breadcrumbs');

if (productBreadcrumbs) {
  productBreadcrumbs.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductBreadcrumbsEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productGallery = document.querySelectorAll('.elementor-product-gallery');
if (productGallery) {
  productGallery.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductGalleryEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productSpecification = document.querySelectorAll('.elementor-product-specification');

if (productSpecification) {
  productSpecification.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductSpecificationEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productDescription = document.querySelectorAll('.elementor-product-description');
if (productDescription) {
  productDescription.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductDescriptionEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productSidebarMenu = document.querySelectorAll('.elementor-product-sidebar-menu');
if (productSidebarMenu) {
  productSidebarMenu.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductSidebarMenuEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productStickyMenu = document.querySelectorAll('.elementor-product-sticky-menu');
if (productStickyMenu) {
  productStickyMenu.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductStickyMenuEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productLogo = document.querySelectorAll('.elementor-product-logo');
if (productLogo) {
  productLogo.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductLogoEL/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productOwnerName = document.querySelectorAll('.elementor-product-owner-name');
if (productOwnerName) {
  productOwnerName.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductOwnerNameEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productOwnerInfoIcon = document.querySelectorAll('.elementor-product-owner-info-icon');
if (productOwnerInfoIcon) {
  productOwnerInfoIcon.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductOwnerInfoIconEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productOwnerPhone = document.querySelectorAll('.elementor-product-owner-phone');
if (productOwnerPhone) {
  productOwnerPhone.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductOwnerPhoneEl/>
      </Provider>,
      wrapper,
    );
  });
}

const productLocationMep = document.querySelectorAll('.elementor-product-location-map');
if (productLocationMep) {
  productLocationMep.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductLocationMapEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productOwnerButton = document.querySelectorAll('.elementor-product-owner-button');
if (productOwnerButton) {
  productOwnerButton.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductOwnerButtonEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productFinancingCalculator = document.querySelectorAll('.elementor-product-financing-calculator');
if (productFinancingCalculator) {
  productFinancingCalculator.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductFinancingCalculatorEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productSafetyTips = document.querySelectorAll('.elementor-product-safety-tips');
if (productSafetyTips) {
  productSafetyTips.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductSafetyTipsEl/>
      </Provider>,
      wrapper,
    );
  });
}

// product single elementor hook.
const productMobileMenu = document.querySelectorAll('.elementor-product-mobile-menu');
if (productMobileMenu) {
  productMobileMenu.forEach(wrapper => {
    render(
      <Provider store={store}>
        <ProductMobileMenuEl/>
      </Provider>,
      wrapper,
    );
  });
}
// Product single elementor hooks end!

// author elementor hook.
const authorBox = document.querySelectorAll('.elementor-author-box');
if (authorBox) {
  authorBox.forEach(wrapper => {
    render(
      <Provider store={store}>
        <AuthorBoxEl/>
      </Provider>,
      wrapper,
    );
  });
}

const authorSearch = document.querySelectorAll('.elementor-author-search');
if (authorSearch) {
  authorSearch.forEach(wrapper => {
    render(
      <Provider store={store}>
        <AuthorSearchEl/>
      </Provider>,
      wrapper,
    );
  });
}
// Author elementor hooks end!

const loginForm = document.querySelectorAll('.elementor-login-form');
if (loginForm) {
  loginForm.forEach(wrapper => {
    render(
      <Provider store={store}>
        <LoginFormEl/>
      </Provider>,
      wrapper,
    );
  });
}

const authBreadcrumbs = document.querySelectorAll('.elementor-auth-breadcrumbs');
if (authBreadcrumbs) {
  authBreadcrumbs.forEach(wrapper => {
    render(
      <Provider store={store}>
        <AuthBreadcrumbEl/>
      </Provider>,
      wrapper,
    );
  });
}

const registerForm = document.querySelectorAll('.elementor-register-form');
if (registerForm) {
  registerForm.forEach(wrapper => {
    render(
      <Provider store={store}>
        <RegisterFormEl/>
      </Provider>,
      wrapper,
    );
  });
}

const passwordResetForm = document.querySelectorAll('.elementor-password-reset-form');
if (passwordResetForm) {
  passwordResetForm.forEach(wrapper => {
    render(
      <Provider store={store}>
        <PasswordResetFormEl/>
      </Provider>,
      wrapper,
    );
  });
}
// auth elementor hooks end!

// business profile elementor hook.
const businessReviews = document.querySelectorAll('.elementor-business-reviews');
if (businessReviews) {
  businessReviews.forEach(wrapper => {
    render(
      <Provider store={store}>
        <BusinessReviewsEl/>
      </Provider>,
      wrapper,
    );
  });
}

// business profile elementor hook.
const businessTestimonials = document.querySelectorAll('.elementor-business-testimonial');
if (businessTestimonials) {
  businessTestimonials.forEach(wrapper => {
    render(
      <Provider store={store}>
        <BusinessTestimonialEl/>
      </Provider>,
      wrapper,
    );
  });
}

const businessContact = document.querySelectorAll('.elementor-business-contact');
if (businessContact) {
  businessContact.forEach(wrapper => {
    render(
      <Provider store={store}>
        <BusinessContactEl/>
      </Provider>,
      wrapper,
    );
  });
}

const businessAbout = document.querySelectorAll('.elementor-business-about');
if (businessAbout) {
  businessAbout.forEach(wrapper => {
    render(
      <Provider store={store}>
        <BusinessAboutEl/>
      </Provider>,
      wrapper,
    );
  });
}

const businessStore = document.querySelectorAll('.elementor-business-store');
if (businessStore) {
  businessStore.forEach(wrapper => {
    render(
      <Provider store={store}>
        <BusinessStoreEl/>
      </Provider>,
      wrapper,
    );
  });
}
// Product single elementor hooks end!

const headerKeyword = document.querySelectorAll('.header-keyword');
// hook on headerKeyword field in a header
if (headerKeyword) {
  headerKeyword.forEach(wrapper => {
    render(
      <Provider store={store}>
        <HeaderKeywordEl/>
      </Provider>,
      wrapper,
    );
  });
}

function compareIcon() {
  if (headerCompareWrapper) {
    headerCompareWrapper.forEach(wrapper => {
      const storeHeader = configureStoreHeader();
      render(
        <Provider store={storeHeader}>
          <HeaderCompareWrapper/>
        </Provider>,
        wrapper,
      );
    });
  }
}

const headerNotificationsWrapper = document.querySelectorAll('.notifications--wrapper');

// hook on a header
function notifications() {
  if (headerNotificationsWrapper) {
    headerNotificationsWrapper.forEach(wrapper => {
      const storeHeader = configureStoreHeader();
      render(
        <Provider store={storeHeader}>
          <HeaderNotificationsWrapper/>
        </Provider>,
        wrapper,
      );
    });
  }
}

// hook on security tips page.
if (pageSecurityTips) {
  render(
    <PageSecurityTips/>,
    pageSecurityTips,
  );
}

// hook mobile menu wrapper.
if (mobileMenuWrapper) {
  const storeHeader = configureStoreHeader();
  render(
    <Provider store={storeHeader}>
      <MenuMobile/>
    </Provider>,
    mobileMenuWrapper,
  );
}

if (mobileMenuSearch) {
  render(
    <Provider store={store}>
      <MenuMobileSearch/>
    </Provider>,
    mobileMenuSearch,
  );
}

// hook on headerTaxonomy field in a header
if (headerTaxonomy) {
  render(
    <Provider store={store}>
      <HeaderTaxonomy/>
    </Provider>,
    headerTaxonomy,
  );
}

// hook on authentication page
if (authenticate) {
  render(
    <PageAuth/>,
    authenticate,
  );
}
const homeSearchId = document.getElementById('home-search');
// hook on headerKeyword field in a header
if (homeSearchId) {
  render(
    <Provider store={store}>
      <HomeSearch/>
    </Provider>,
    homeSearchId,
  );
}

// hook on home page search form
const homeSearch = document.querySelectorAll('.home-search');
// hook on headerKeyword field in a header
if (homeSearch) {
  homeSearch.forEach(wrapper => {
    render(
      <Provider store={store}>
        <HomeSearchElement/>
      </Provider>,
      wrapper,
    );
  });
}

// hook on a search page
if (search) {
  render(
    <Provider store={store}>
      <PageSearch/>
    </Provider>,
    search,
  );
}

// !hook on a search page elementor!
if (pageSearchElementor) {
  render(
    <Provider store={store}>
      <PageSearchEl/>
    </Provider>,
    pageSearchElementor,
  );

  const searchSidebarFilter = document.querySelectorAll('.page-search-sidebar-filter');

  if (searchSidebarFilter) {
    searchSidebarFilter.forEach(wrapper => {
      render(
        <Provider store={store}>
          <SearchFiltersEl/>
        </Provider>,
        wrapper,
      );
    });
  }
}

const searchBreadcrumbs = document.querySelectorAll('.page-search-breadcrumbs');

if (searchBreadcrumbs) {
  searchBreadcrumbs.forEach(wrapper => {
    render(
      <Provider store={store}>
        <BreadcrumbEl/>
      </Provider>,
      wrapper,
    );
  });
}

const searchFilterTop = document.querySelectorAll('.page-search-filter-top');

if (searchFilterTop) {
  searchFilterTop.forEach(wrapper => {
    render(
      <Provider store={store}>
        <SearchFilterTop/>
      </Provider>,
      wrapper,
    );
  });
}

const searchMap = document.querySelectorAll('.page-search-map');

if (searchMap) {
  searchMap.forEach(wrapper => {
    render(
      <Provider store={store}>
        <SearchMapEl/>
      </Provider>,
      wrapper,
    );
  });
}

const searchListings = document.querySelectorAll('.page-search-listings');

if (searchListings) {
  searchListings.forEach(wrapper => {
    render(
      <Provider store={store}>
        <PageSearchContentEl/>
      </Provider>,
      wrapper,
    );
  });
}

const searchDetailedEl = document.getElementById('page-search-detailed-elementor');

if (searchDetailedEl) {
  render(
    <Provider store={store}>
      <SearchDetailedEl/>
    </Provider>,
    searchDetailedEl,
  );
}
// !search page elementor end!

// hook on product single page
if (productSingle) {
  render(
    <Provider store={store}>
      <ProductSingle/>
    </Provider>,
    productSingle,
  );
}

// hook on a business single page
if (businessSingle) {
  render(
    <Provider store={store}>
      <BusinessSingle/>
    </Provider>,
    businessSingle,
  );
}

// hook on a business single elementor page
if (pageBusinessProfile) {
  render(
    <Provider store={store}>
      <BusinessSingleEl/>
    </Provider>,
    pageBusinessProfile,
  );
}

// hook on a business archive page
if (businessArchive) {
  render(
    <Provider store={store}>
      <BusinessArchive/>
    </Provider>,
    businessArchive,
  );
}

function stickyHeaderElementor() {
  const header = document.getElementById('header--main-sticky');
  if (!header) {
    return false;
  }
  if (window.pageYOffset > 100) {
    header.classList.add('sticky');
  } else {
    header.classList.remove('sticky');
  }
}

function stickyHeader() {
  const header = document.getElementById('header--main');
  if (!header) {
    return false;
  }
  if (lc_data.sticky_header === '1' && window.pageYOffset > 100) {
    header.classList.add('sticky');
  } else {
    header.classList.remove('sticky');
  }
}

// youtube video functionality.
const video = document.getElementById('videoId');
const videoOptions = video && JSON.parse(video.dataset.options);
let player = null;
if ((video && window.innerWidth > 1030) || (video && videoOptions.show_on_mobiles !== '0' && window.innerWidth < 1030)) {
  const tag = document.createElement('script');
  tag.src = 'https://www.youtube.com/iframe_api';
  const firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function onYouTubeIframeAPIReady() {
  if (!video || (videoOptions.show_on_mobiles !== '1' && window.innerWidth < 1030)) {
    return false;
  }

  const options = JSON.parse(video.dataset.options);
  player = new YT.Player('player', {
    videoId: options.id,
    suggestedQuality: 'large',
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange,
    },
    playerVars: {
      controls: 0,
      disablekb: 1,
      modestbranding: 1,
      iv_load_policy: 3,
      rel: 0,
      start: options.starts !== '' ? parseInt(options.starts, 10) : false,
      end: options.ends !== '' ? parseInt(options.ends, 10) : false,
    },
  });

  function onPlayerReady(event) {
    player.mute();
    event.target.playVideo();
  }

  function onPlayerStateChange(event) {
    if (!video) {
      return false;
    }

    const options = JSON.parse(video.dataset.options);
    const videoPlayer = document.getElementById('player');

    if (event.data === 1) {
      videoPlayer.classList.add('playing');
    }

    if (options.loop === '1' && event.data === 0) {
      if (options.starts !== '') {
        event.target.seekTo(options.starts);
      } else {
        event.target.playVideo();
      }
    } else if (event.data === 0) {
      videoPlayer.classList.add('video--hidden');
      setTimeout(() => {
        videoPlayer.remove();
      }, 1000);
    }
  }
}

// banner offset.
const bannerPosition = () => {
  const header = document.querySelector('#header--main');
  const banner = document.querySelector('.banner');
  if (header && banner) {
    banner.style.marginTop = `-${header.offsetHeight}px`;
  }
};
window.addEventListener('resize', bannerPosition);
setTimeout(() => {
  bannerPosition();
}, 300);

// After DOM has been loaded.
document.addEventListener('DOMContentLoaded', () => {
  onYouTubeIframeAPIReady();

  const body = document.querySelector('body');
  // listing contact form.
  const btnContact = document.getElementById('open-contact-modal');
  if (btnContact) {
    const btnCloseContact = document.getElementById('close-contact-modal');
    if (btnCloseContact) {
      btnCloseContact.addEventListener('click', () => {
        body.style.overflow = 'auto';
        const modal = document.getElementById('contact-modal');
        if (modal) {
          modal.classList.add('hidden');
        }
      });
    }
    document.addEventListener('keydown', (e) => {
      if (event.keyCode === 27) {
        body.style.overflow = 'auto';
        const modal = document.getElementById('contact-modal');
        if (modal) {
          modal.classList.add('hidden');
        }
      }
    });
    window.addEventListener('click', (e) => {
      if (!document.getElementById('contact-modal-inner').contains(e.target) && e.target.id !== 'open-contact-modal') {
        const modal = document.getElementById('contact-modal');
        if (modal) {
          modal.classList.add('hidden');
          body.style.overflow = 'auto';
        }
      }
    });
    btnContact.addEventListener('click', () => {
      const modal = document.getElementById('contact-modal');
      if (modal) {
        modal.classList.remove('hidden');
        body.style.overflow = 'hidden';
      }
    });
    const contactForm = document.getElementById('form-listing-single-contact');
    if (contactForm) {
      contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const headers = {
          'X-WP-Nonce': lc_data.nonce,
        };
        let url = lc_data.send_message;
        const data = new FormData(contactForm);
        axios({
          credentials: 'same-origin',
          headers,
          method: 'post',
          url,
          data,
        }).then(data => {
          if (data.data.error) {
            const error = document.getElementById('contact-form-error');
            error.classList.remove('hidden');
            error.innerHTML = data.data.message;
          }
          if (data.data.success) {
            const inner = document.getElementById('form-listing-modal-inner');
            if (inner) {
              inner.classList.add('text-green-700');
              inner.innerHTML = data.data.message;
            }
            contactForm.remove();
          }
        });
      });
    }
  }

  // currency switcher.
  const currencyTriggers = document.querySelectorAll('.currency--trigger');
  if (currencyTriggers) {
    currencyTriggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const headers = {
          'X-WP-Nonce': lc_data.nonce,
        };
        let url = lc_data.set_currency;
        axios({
          headers,
          method: 'post',
          url,
          data: {
            currency: trigger?.dataset?.currency,
          },
        }).then(data => {
          if (data.data) {
            setCookie('currency', data.data.currency, 30);
          } else {
            eraseCookie('currency');
          }
          window.location.reload();
        });
      });
    });
  }

  // buttons loaders.
  const buttonsLoad = document.querySelectorAll('.btn__load');
  if (buttonsLoad) {
    [...buttonsLoad].forEach(button => {
      button.onmousedown = (e) => {
        const svg = button.getElementsByTagName('svg');
        const img = button.getElementsByTagName('img');
        img[0].classList.remove('hidden');
        svg[0].remove();
      };
    });
  }

  // button ripple effect.
  const buttons = document.querySelectorAll('[data-animation="ripple"]');

  if (buttons) {
    [...buttons].forEach(button => {
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
    });
  }

  // cart count update.
  const cartCount = document.querySelectorAll('.cart-count');
  if (cartCount) {
    $('body').on('added_to_cart removed_from_cart wc_fragments_refreshed wc_fragment_refresh get_refreshed_fragments updated_wc_div', () => {
      const headers = {
        'X-WP-Nonce': lc_data.nonce,
      };
      axios({
        credentials: 'same-origin',
        headers,
        method: 'GET',
        url: lc_data.get_cart_count,
      }).then(data => {
        cartCount.forEach(el => {
          el.textContent = data.data;
          if (data.data !== 0 && el.classList.contains('hidden')) {
            el.classList.remove('hidden');
          } else if (data.data === 0) {
            el.classList.add('hidden');
          }
        });
      });
    });
  }

  const loaderSvg = (color = 'fff', size = 21) => {
    return `<svg id="package-loader" width="${size}" height="${size}" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#${color}">
    <g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="1">
        <circle cx="22" cy="22" r="6" stroke-opacity="0">
            <animate attributeName="r"
                 begin="1.5s" dur="3s"
                 values="6;22"
                 calcMode="linear"
                 repeatCount="indefinite" />
            <animate attributeName="stroke-opacity"
                 begin="1.5s" dur="3s"
                 values="1;0" calcMode="linear"
                 repeatCount="indefinite" />
            <animate attributeName="stroke-width"
                 begin="1.5s" dur="3s"
                 values="2;0" calcMode="linear"
                 repeatCount="indefinite" />
        </circle>
        <circle cx="22" cy="22" r="6" stroke-opacity="0">
            <animate attributeName="r"
                 begin="3s" dur="3s"
                 values="6;22"
                 calcMode="linear"
                 repeatCount="indefinite" />
            <animate attributeName="stroke-opacity"
                 begin="3s" dur="3s"
                 values="1;0" calcMode="linear"
                 repeatCount="indefinite" />
            <animate attributeName="stroke-width"
                 begin="3s" dur="3s"
                 values="2;0" calcMode="linear"
                 repeatCount="indefinite" />
        </circle>
        <circle cx="22" cy="22" r="8">
            <animate attributeName="r"
                 begin="0s" dur="1.5s"
                 values="6;1;2;3;4;5;6"
                 calcMode="linear"
                 repeatCount="indefinite" />
        </circle>
    </g>
</svg>`;
  };

  $('input[id^=qty-]').on('change', function (e) {
    const id = e.target?.id;
    const duration = parseInt(e.target.value, 10);
    const div = $(`span[data-input=${id}]`);
    const price = div[0].dataset.price;
    const discountHtml = $(`#discount--${id}`);
    let price_new = parseFloat(price) * duration;
    const discounts = div[0].dataset.discounts;
    let currentDiscount = 0;
    discounts && map(JSON.parse(discounts), (discount, index) => {
      if (parseInt(duration, 10) >= parseInt(discount.duration, 10)) {
        price_new = parseFloat(price) * duration;
        currentDiscount = parseFloat(discount.discount);
        price_new = price_new * ((100 - currentDiscount) / 100);
      }
    });

    div[0].innerHTML = `<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">${lc_data.currency_symbol}</span>${parseFloat(price_new).toFixed(2)}</bdi></span>`;
    if (currentDiscount > 0) {
      discountHtml.removeClass('hidden');
      discountHtml.find('.discount').text(`${currentDiscount}%`);
    } else {
      discountHtml.addClass('hidden');
    }
    $(`button[data-id=${id.replace('qty-', '')}]`)[0].dataset.discount = currentDiscount;
  });

  $('select[id^=qty-]').on('change', function (e) {
    const id = e.target?.id;
    const duration = parseInt(e.target.value, 10);
    const div = $(`span[data-input=${id}]`);
    const price = div[0].dataset.price;
    const discountHtml = $(`#discount--${id}`);
    let price_new = parseFloat(price);
    const discounts = div[0].dataset.discounts;
    let currentDiscount = 0;
    discounts && map(JSON.parse(discounts), (discount, index) => {
      if (parseInt(duration, 10) >= parseInt(discount.duration, 10)) {
        price_new = discount.discount;
      }
    });

    div[0].innerHTML = `<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">${lc_data.currency_symbol}</span>${parseFloat(price_new).toFixed(2)}</bdi></span>`;
    if (currentDiscount > 0) {
      discountHtml.removeClass('hidden');
      discountHtml.find('.discount').text(`${currentDiscount}%`);
    } else {
      discountHtml.addClass('hidden');
    }
    $(`button[data-id=${id.replace('qty-', '')}]`)[0].dataset.discount = currentDiscount;
  });

  const buyPackage = document.querySelectorAll('.action--buy-package');
  if (buyPackage) {
    buyPackage.forEach(buy => {
      buy.addEventListener('click', async (e) => {
        e.target.setAttribute('disabled', 'disabled');
        const qty = document.getElementById(`qty-${buy.dataset.id}`);
        const html = e.target.innerHTML;
        if (e.target.classList.contains('bg-blue-600')) {
          e.target.innerHTML = loaderSvg('fff');
        } else {
          e.target.innerHTML = loaderSvg('2d2d2d');
        }
        const headers = {
          'X-WP-Nonce': lc_data.nonce,
        };
        const url = lc_data.purchase_package;
        let data = {
          id: lc_data.current_user_id,
          wc_product: buy.dataset.id,
          quantity: qty?.value ?? 1,
        };
        if (e.target?.dataset?.discount) {
          data.discount = e.target.dataset.discount;
        }
        await axios({
          credentials: 'same-origin',
          headers,
          method: 'post',
          url,
          data,
        }).then(data => {
          if (data.data.success) {
            if (data.data.message) {
              Toastify({
                text: data.data.message,
                gravity: 'bottom',
                position: 'center',
                backgroundColor: 'linear-gradient(to right, #62f4eb, #eefcfa)',
                close: true,
                duration: 3000,
                className: 'toastify--lisfinity success',
              }).showToast();
            } else {
              window.location.href = data.data.permalink;
            }
          }
          if (data.data.error) {
            Toastify({
              text: data.data.message,
              gravity: 'bottom',
              position: 'center',
              backgroundColor: 'linear-gradient(to right, #e12d39, #f86a6a)',
              close: true,
              duration: 3000,
              className: 'toastify--lisfinity',
            }).showToast();
          }
          e.target.innerHTML = html;
          e.target.removeAttribute('disabled');
        });
      });
    });
  }

  notifications();
  compareIcon();

// sticky header
  document.addEventListener('load', stickyHeader);
  document.addEventListener('scroll', stickyHeader);
  document.addEventListener('load', stickyHeaderElementor);
  document.addEventListener('scroll', stickyHeaderElementor);

// switch image to svg inject.
  const SVGs = document.querySelectorAll('img.svg-inject');
  SVGInject(SVGs);

// use slick slider for the category items
  $('.product--carousel').not('.slick-initialized').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    arrows: true,
    dots: false,
    rtl: sliderIsRtl,
    responsive: [
      {
        breakpoint: 2050,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        },
      },
      {
        breakpoint: 1720,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        },
      },
      {
        breakpoint: 1460,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        },
      },
      {
        breakpoint: 1140,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        },
      },
      {
        breakpoint: 860,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        },
      },
      {
        breakpoint: 620,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  const sliderIsRtl = lc_data.rtl == 1;

  const taxonomySliderMobile = () => {
    const taxonomiesCategoriesElements = document.querySelectorAll('.banner--taxonomies');
    if (taxonomiesCategoriesElements) {
      taxonomiesCategoriesElements.forEach(el => {
        let options = el.dataset?.options;
        if (options) {
          options = JSON.parse(options);
          if (window.innerWidth < 640 && options?.carousel === true) {
            $(el).not('.slick-initialized').slick({
              infinite: true,
              slidesToShow: 3,
              slidesToScroll: 3,
              arrows: false,
              dots: false,
              rtl: sliderIsRtl,
            });
          }
        }
      });
    }
  };
  taxonomySliderMobile();
  window.addEventListener('resize', () => {
    taxonomySliderMobile();
  });

  const catTypesNumber = $('.category-types--slider')?.data('categoryTypesNumber') ?? 7;
  $('.category-types--slider, .taxonomies--slider, .product--carousel').not('.slick-initialized').slick({
    infinite: false,
    slidesToShow: catTypesNumber,
    slidesToScroll: catTypesNumber,
    arrows: false,
    dots: true,
    rtl: sliderIsRtl,
    responsive: [
      {
        breakpoint: 2050,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 6,
        },
      },
      {
        breakpoint: 1720,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 5,
        },
      },
      {
        breakpoint: 1460,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
        },
      },
      {
        breakpoint: 1140,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        },
      },
      {
        breakpoint: 860,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        },
      },
      {
        breakpoint: 620,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

// bookmark post
  const actionBookmarks = document.querySelectorAll('.action--bookmark');
  if (actionBookmarks) {
    actionBookmarks.forEach(actionBookmark => {
      actionBookmark.addEventListener('click', (e) => {
        const headers = new Headers();
        headers.append('X-WP-Nonce', lc_data.nonce);
        const formData = new FormData();
        const { id } = e.target.dataset;
        formData.append('product_id', id);
        if (!id) {
          return false;
        }
        fetch(`${lc_data.user}/bookmark/`, {
          method: 'POST',
          credentials: 'same-origin',
          headers,
          body: formData,
        }).then(json => json.json()).then((result) => {
          const svg = e.target.children[0];
          svg.classList.remove('fill-white', 'fill-theme');
          svg.classList.add(result.class);
        });
      });
    });
  }

  // like post
  const actionLikes = document.querySelectorAll('.action--like');
  if (actionLikes) {
    actionLikes.forEach(like => {
      like.addEventListener('click', (e) => {
        const headers = new Headers();
        headers.append('X-WP-Nonce', lc_data.nonce);
        const formData = new FormData();
        const { id } = e.target.dataset;
        formData.append('product_id', id);
        formData.append('action', 'like');
        formData.append('_wpnonce', lc_data.nonce);
        if (!id) {
          return false;
        }
        fetch(`${lc_data.product_action}/like/`, {
          method: 'POST',
          credentials: 'same-origin',
          headers,
          body: formData,
        }).then(json => json.json()).then((result) => {
          const svg = e.target.children[0];
          svg.classList.remove('fill-white');
          svg.classList.add(result.class);
        });
      });
    });
  }

// countdown timers
  const countdownTimers = document.querySelectorAll('.countdown');
  if (countdownTimers) {
    countdownTimers.forEach((timer) => {
      const endTime = timer.dataset.auctionEnds;
      functions.initializeClock(timer, endTime);
    });
  }

// product tabs
  const tabButtons = document.querySelectorAll('.product-tab');
  if (tabButtons) {
    tabButtons.forEach((tab) => {
      tab.addEventListener('click', (e) => {
        const wrapper = e.target.closest('.lisfinity-product-tabs');
        const content = e.target.dataset.tab;
        const tabContents = wrapper.querySelectorAll('.product-tabs--content');
        const elTabButtons = wrapper.querySelectorAll('.product-tab');
        elTabButtons.forEach((tabBg) => {
          tabBg.classList.remove('active');
        });
        e.target.classList.add('active');
        if (tabContents) {
          tabContents.forEach((tabContent) => {
            if (tabContent.dataset.content === content) {
              tabContent.classList.remove('hidden');
              tabContent.classList.add('block');
            } else {
              tabContent.classList.remove('block');
              tabContent.classList.add('hidden');
            }
          });
        }
      });
    });
  }
// element tabs
  const geTabButtons = document.querySelectorAll('.ge-tabs--action');
  if (geTabButtons) {
    geTabButtons.forEach((tab) => {
      tab.addEventListener('click', (e) => {
        const wrapper = e.target.closest('.ge-tabs');
        const content = e.target.dataset.id;
        const tabContents = wrapper.querySelectorAll('.ge-tab');
        const elTabButtons = wrapper.querySelectorAll('.ge-tabs--action');
        elTabButtons.forEach((tabBg) => {
          tabBg.classList.remove('active');
        });
        e.target.classList.add('active');
        if (tabContents) {
          tabContents.forEach((tabContent) => {
            if (tabContent.dataset.id === content) {
              tabContent.classList.remove('hidden');
              tabContent.classList.add('active');
            } else {
              tabContent.classList.remove('active');
              tabContent.classList.add('hidden');
            }
          });
        }
      });
    });
  }
});

// leave comment action link
const leaveCommentTrigger = document.querySelectorAll('.action--leave-comment');
leaveCommentTrigger.forEach(trigger => {
  trigger.addEventListener('click', e => {
    const commentForm = document.getElementById('commentform');
    if (commentForm) {
      commentForm.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

// load partners masonry
function masonryPartners(isElementor = false) {
  const msnry = $('.partners__masonry').masonry({
    columnWidth: 1,
    itemSelector: '.partners-item',
    fitWidth: true,
    isOriginLeft: false,
    isOriginTop: true,
    isInitLayout: false,
  });

  if (!isElementor) {
    msnry.on('layoutComplete', (e, items) => {
      e.target.style.visibility = 'visible';
    });
  }

  msnry.masonry();
}

masonryPartners();

// inject svgs
const SVGsToInject = document.querySelectorAll('img.injectable');
SVGInject(SVGsToInject, {
  afterInject: (img, svg) => {
    svg.classList.remove('hidden');
  }
});

// click on product title or image handler.
$('.ajax--lead').on('click', function (e) {
  const id = $(this).closest('article').data('id');
  axios({
    method: 'post',
    url: lc_data.update_stats,
    data: {
      product_id: id,
      type: 3,
      not_business: true,
    },
  }).then(data => {
  });
});

$(window).on('elementor/frontend/init', (e) => {
  const loadSliders = () => {
    $('.category-types--slider, .taxonomies--slider').not('.slick-initialized').slick({
      infinite: false,
      slidesToShow: 6,
      slidesToScroll: 6,
      arrows: false,
      dots: true,
      rtl: sliderIsRtl,
      responsive: [
        {
          breakpoint: 1720,
          settings: {
            slidesToShow: 5,
            slidesToScroll: 5,
          },
        },
        {
          breakpoint: 1460,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 4,
          },
        },
        {
          breakpoint: 1140,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          },
        },
        {
          breakpoint: 860,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          },
        },
        {
          breakpoint: 620,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    });
  };
  const sliderIsRtl = lc_data.rtl;
  if (params['elementor-preview']) {
    // HomPage Search
    // -----------------------------------------
    elementorFrontend.hooks.addAction('frontend/element_ready/lisfinity-hero-search.default', ($scope) => {
      const homeSearch = document.querySelectorAll('.home-search');
      // hook on headerKeyword field in a header
      if (homeSearch) {
        homeSearch.forEach(wrapper => {
          render(
            <Provider store={store}>
              <HomeSearchElement/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    // Keyword Search
    // -----------------------------------------
    elementorFrontend.hooks.addAction('frontend/element_ready/search-keyword.default', ($scope) => {
      const headerKeyword = document.querySelectorAll('.header-keyword');
      // hook on headerKeyword field in a header
      if (headerKeyword) {
        headerKeyword.forEach(wrapper => {
          render(
            <Provider store={store}>
              <HeaderKeywordEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    // Compare
    // -----------------------------------------
    elementorFrontend.hooks.addAction('frontend/element_ready/navigation-compare.default', ($scope) => {
      const headerCompareWrapper = document.querySelectorAll('.compare--wrapper');

      function compareIcon() {
        if (headerCompareWrapper) {
          headerCompareWrapper.forEach(wrapper => {
            const storeHeader = configureStoreHeader();
            render(
              <Provider store={storeHeader}>
                <HeaderCompareWrapper/>
              </Provider>,
              wrapper,
            );
          });
        }
      }

      compareIcon();
    });

    // Notifications
    // -----------------------------------------
    elementorFrontend.hooks.addAction('frontend/element_ready/navigation-notification.default', ($scope) => {
      const headerNotificationsWrapper = document.querySelectorAll('.notifications--wrapper');

      // hook on a header
      function notifications() {
        if (headerNotificationsWrapper) {
          headerNotificationsWrapper.forEach(wrapper => {
            const storeHeader = configureStoreHeader();
            render(
              <Provider store={storeHeader}>
                <HeaderNotificationsWrapper/>
              </Provider>,
              wrapper,
            );
          });
        }
      }

      notifications();
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/global', ($scope) => {
      if (!lc_data.is_elementor_single_template) {
        return;
      }
      const productSingleEl = document.getElementById('page-single-elementor');

      // product single elementor hook.
      if (productSingleEl) {
        render(
          <Provider store={store}>
            <ProductSingleEl/>
          </Provider>,
          productSingleEl,
        );
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/global', ($scope) => {

      const vendorsEl = document.getElementById('page-vendors-elementor');

      // product single elementor hook.
      if (vendorsEl) {
        render(
          <Provider store={store}>
            <AuthorsEl/>
          </Provider>,
          vendorsEl,
        );
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/global', ($scope) => {
      if (!lc_data.is_elementor_single_business_template) {
        //return;
      }
      let pageBusinessProfile = document.getElementById('page-single-business-elementor');

      if (!pageBusinessProfile) {
        pageBusinessProfile = document.getElementById('page-single-business-premium-elementor');
      }

      // product single elementor hook.
      if (pageBusinessProfile) {
        render(
          <Provider store={store}>
            <BusinessSingleEl/>
          </Provider>,
          pageBusinessProfile,
        );
      }
    });

    // Menu Default
    // ----------------------------------------
    elementorFrontend.hooks.addAction('frontend/element_ready/navigation-menu.default', ($scope) => {
      const search = document.getElementById('page-search');
      const productSingle = document.getElementById('page-single');
      const pageSecurityTips = document.getElementById('page-tips');
      const authenticate = document.getElementById('page-auth');
      const headerTaxonomy = document.getElementById('header-taxonomy');
      const businessSingle = document.getElementById('page-single-business');
      const businessArchive = document.getElementById('page-archive-vendors');
      const mobileMenuWrapper = document.getElementById('mobile-menu--wrapper');
      const mobileMenuSearch = document.getElementById('mobile-menu--search');
      const mobileMenuSearchEl = document.querySelectorAll('.mobile-menu--search');

      if (mobileMenuSearchEl) {
        mobileMenuSearchEl.forEach(wrapper => {
          render(
            <Provider store={store}>
              <MenuMobileSearchEl/>
            </Provider>,
            wrapper,
          );
        });
      }

      // hook on security tips page.
      if (pageSecurityTips) {
        render(
          <PageSecurityTips/>,
          pageSecurityTips,
        );
      }

      if (mobileMenuSearch) {
        render(
          <Provider store={store}>
            <MenuMobileSearch/>
          </Provider>,
          mobileMenuSearch,
        );
      }

      // hook on headerTaxonomy field in a header
      if (headerTaxonomy) {
        render(
          <Provider store={store}>
            <HeaderTaxonomy/>
          </Provider>,
          headerTaxonomy,
        );
      }

// hook on authentication page
      if (authenticate) {
        render(
          <PageAuth/>,
          authenticate,
        );
      }

// hook on a search page
      if (search) {
        render(
          <Provider store={store}>
            <PageSearch/>
          </Provider>,
          search,
        );
      }

// hook on product single page
      if (productSingle) {
        render(
          <Provider store={store}>
            <ProductSingle/>
          </Provider>,
          productSingle,
        );
      }

// hook on a business single page
      if (businessSingle) {
        render(
          <Provider store={store}>
            <BusinessSingle/>
          </Provider>,
          businessSingle,
        );
      }

// hook on a business archive page
      if (businessArchive) {
        render(
          <Provider store={store}>
            <BusinessArchive/>
          </Provider>,
          businessArchive,
        );
      }
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/taxonomies.default', ($scope) => {
      loadSliders();
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/categories-carousel.default', ($scope) => {
      loadSliders();
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/partners.default', ($scope) => {
      masonryPartners(true);
    });

    // elementor search page
    elementorFrontend.hooks.addAction('frontend/element_ready/global', ($scope) => {
      if (!lc_data.is_elementor_search) {
        return;
      }
      const pageSearchElementor = document.getElementById('page-search-elementor');

      if (pageSearchElementor) {
        render(
          <Provider store={store}>
            <PageSearchEl/>
          </Provider>,
          pageSearchElementor,
        );
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/search-sidebar-filter.default', ($scope) => {
      const searchSidebarFilter = document.querySelectorAll('.page-search-sidebar-filter');

      if (searchSidebarFilter) {
        searchSidebarFilter.forEach(wrapper => {
          render(
            <Provider store={store}>
              <SearchFiltersEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    // Product Single Elementor Widgets
    elementorFrontend.hooks.addAction('frontend/element_ready/product-title.default', ($scope) => {
      const productTitle = document.querySelectorAll('.elementor-product-title');
      // product single elementor hook.
      if (productTitle) {
        productTitle.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductTitleEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-id.default', ($scope) => {
      const productId = document.querySelectorAll('.elementor-product-id');
      // product single elementor hook.
      if (productId) {
        productId.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductIdEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-working-hours.default', ($scope) => {
      const productOwnerHours = document.querySelectorAll('.elementor-product-working-hours');
      // product single elementor hook.
      if (productOwnerHours) {
        productOwnerHours.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductOwnerHoursAltEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-info.default', ($scope) => {
      const productInfo = document.querySelectorAll('.elementor-product-info');
      // product single elementor hook.
      if (productInfo) {
        productInfo.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductInfoEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-breadcrumbs.default', ($scope) => {
      const productBreadcrumbs = document.querySelectorAll('.elementor-product-breadcrumbs');
      // product single elementor hook.
      if (productBreadcrumbs) {
        productBreadcrumbs.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductBreadcrumbsEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/search-breadcrumbs.default', ($scope) => {
      const searchBreadcrumbs = document.querySelectorAll('.page-search-breadcrumbs');

      if (searchBreadcrumbs) {
        searchBreadcrumbs.forEach(wrapper => {
          render(
            <Provider store={store}>
              <BreadcrumbEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-gallery.default', ($scope) => {
      const productGallery = document.querySelectorAll('.elementor-product-gallery');
      // product single elementor hook.
      if (productGallery) {
        productGallery.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductGalleryEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-specification.default', ($scope) => {
      const productSpecification = document.querySelectorAll('.elementor-product-specification');
      // product single elementor hook.
      if (productSpecification) {
        productSpecification.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductSpecificationEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/search-filter-top.default', ($scope) => {
      const searchBreadcrumbs = document.querySelectorAll('.page-search-filter-top');

      if (searchBreadcrumbs) {
        searchBreadcrumbs.forEach(wrapper => {
          render(
            <Provider store={store}>
              <SearchFilterTop/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-actions.default', ($scope) => {
      const productActions = document.querySelectorAll('.elementor-product-actions');
      // product single elementor hook.
      if (productActions) {
        productActions.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductActionsEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-description.default', ($scope) => {
      const productDescription = document.querySelectorAll('.elementor-product-description');
      // product single elementor hook.
      if (productDescription) {
        productDescription.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductDescriptionEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/search-map.default', ($scope) => {
      const searchMap = document.querySelectorAll('.page-search-map');

      if (searchMap) {
        searchMap.forEach(wrapper => {
          render(
            <Provider store={store}>
              <SearchMapEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-owner-info-icon.default', ($scope) => {
      const productInfoIcon = document.querySelectorAll('.elementor-product-owner-info-icon');
      // product single elementor hook.
      if (productInfoIcon) {
        productInfoIcon.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductOwnerInfoIconEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-sidebar-menu.default', ($scope) => {
      const productSidebarMenu = document.querySelectorAll('.elementor-product-sidebar-menu');
      // product single elementor hook.
      if (productSidebarMenu) {
        productSidebarMenu.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductSidebarMenuEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-location-map.default', ($scope) => {
      const productLocationMap = document.querySelectorAll('.elementor-product-location-map');
      // product single elementor hook.
      if (productLocationMap) {
        productLocationMap.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductLocationMapEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/search-listings.default', ($scope) => {
      const searchListings = document.querySelectorAll('.page-search-listings');

      if (searchListings) {
        searchListings.forEach(wrapper => {
          render(
            <Provider store={store}>
              <PageSearchContentEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-sticky-menu.default', ($scope) => {
      const productStickyMenu = document.querySelectorAll('.elementor-product-sticky-menu');
      // product single elementor hook.
      if (productStickyMenu) {
        productStickyMenu.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductStickyMenuEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-logo.default', ($scope) => {
      const productLogo = document.querySelectorAll('.elementor-product-logo');
      // product single elementor hook.
      if (productLogo) {
        productLogo.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductLogoEL/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/search-detailed.default', ($scope) => {
      const searchDetailedEl = document.getElementById('page-search-detailed-elementor');

      if (searchDetailedEl) {
        render(
          <Provider store={store}>
            <SearchDetailedEl/>
          </Provider>,
          searchDetailedEl,
        );
      }

    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-owner-name.default', ($scope) => {
      const productOwnerName = document.querySelectorAll('.elementor-product-owner-name');
      // product single elementor hook.
      if (productOwnerName) {
        productOwnerName.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductOwnerNameEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-owner-phone.default', ($scope) => {
      const productOwnerPhone = document.querySelectorAll('.elementor-product-owner-phone');
      // product single elementor hook.
      if (productOwnerPhone) {
        productOwnerPhone.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductOwnerPhoneEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-owner-button.default', ($scope) => {
      const productOwnerButton = document.querySelectorAll('.elementor-product-owner-button');
      // product single elementor hook.
      if (productOwnerButton) {
        productOwnerButton.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductOwnerButtonEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-financing-calculator.default', ($scope) => {
      const productFinancingCalculator = document.querySelectorAll('.elementor-product-financing-calculator');
      // product single elementor hook.
      if (productFinancingCalculator) {
        productFinancingCalculator.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductFinancingCalculatorEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-safety-tips.default', ($scope) => {
      const productSafetyTips = document.querySelectorAll('.elementor-product-safety-tips');
      // product single elementor hook.
      if (productSafetyTips) {
        productSafetyTips.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductSafetyTipsEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/product-mobile-menu.default', ($scope) => {
      const productMobileMenu = document.querySelectorAll('.elementor-product-mobile-menu');
      // product single elementor hook.
      if (productMobileMenu) {
        productMobileMenu.forEach(wrapper => {
          render(
            <Provider store={store}>
              <ProductMobileMenuEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    //business profile elementor hook

    elementorFrontend.hooks.addAction('frontend/element_ready/login-form.default', ($scope) => {
      const loginForm = document.querySelectorAll('.elementor-login-form');
      // login form elementor hook.
      if (loginForm) {
        loginForm.forEach(wrapper => {
          render(
            <Provider store={store}>
              <LoginFormEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    //business profile elementor hook

    elementorFrontend.hooks.addAction('frontend/element_ready/business-reviews.default', ($scope) => {
      const businessReviews = document.querySelectorAll('.elementor-business-reviews');
      // business profile single elementor hook.
      if (businessReviews) {
        businessReviews.forEach(wrapper => {
          render(
            <Provider store={store}>
              <BusinessReviewsEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/auth-breadcrumbs.default', ($scope) => {
      const authBreadcrumbs = document.querySelectorAll('.elementor-auth-breadcrumbs');
      // auth elementor hook.
      if (authBreadcrumbs) {
        authBreadcrumbs.forEach(wrapper => {
          render(
            <Provider store={store}>
              <AuthBreadcrumbEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/business-contact.default', ($scope) => {
      const businessContact = document.querySelectorAll('.elementor-business-contact');
      // business profile single elementor hook.
      if (businessContact) {
        businessContact.forEach(wrapper => {
          render(
            <Provider store={store}>
              <BusinessContactEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    //authors elementor hook

    elementorFrontend.hooks.addAction('frontend/element_ready/author-box.default', ($scope) => {
      const authorBox = document.querySelectorAll('.elementor-author-box');
      // product single elementor hook.
      if (authorBox) {
        authorBox.forEach(wrapper => {
          render(
            <Provider store={store}>
              <AuthorBoxEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/author-search.default', ($scope) => {
      const authorSearch = document.querySelectorAll('.elementor-author-search');
      // product single elementor hook.
      if (authorSearch) {
        authorSearch.forEach(wrapper => {
          render(
            <Provider store={store}>
              <AuthorSearchEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/register-form.default', ($scope) => {
      const registerForm = document.querySelectorAll('.elementor-register-form');
      // auth elementor hook.
      if (registerForm) {
        registerForm.forEach(wrapper => {
          render(
            <Provider store={store}>
              <RegisterFormEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/business-about.default', ($scope) => {
      const businessAbout = document.querySelectorAll('.elementor-business-about');
      // business profile single elementor hook.
      if (businessAbout) {
        businessAbout.forEach(wrapper => {
          render(
            <Provider store={store}>
              <BusinessAboutEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/business-testimonial.default', ($scope) => {
      const businessTestimonials = document.querySelectorAll('.elementor-business-testimonial');
      // business profile single elementor hook.
      if (businessTestimonials) {
        businessTestimonials.forEach(wrapper => {
          render(
            <Provider store={store}>
              <BusinessTestimonialEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/password-reset-form.default', ($scope) => {
      const passwordResetForm = document.querySelectorAll('.elementor-password-reset-form');
      // auth elementor hook.
      if (passwordResetForm) {
        passwordResetForm.forEach(wrapper => {
          render(
            <Provider store={store}>
              <PasswordResetFormEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/business-store.default', ($scope) => {
      const businessStore = document.querySelectorAll('.elementor-business-store');
      // business profile single elementor hook.
      if (businessStore) {
        businessStore.forEach(wrapper => {
          render(
            <Provider store={store}>
              <BusinessStoreEl/>
            </Provider>,
            wrapper,
          );
        });
      }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/products.default', ($scope) => {
      // product tabs
      const tabButtons = document.querySelectorAll('.product-tab');
      if (tabButtons) {
        tabButtons.forEach((tab) => {
          tab.addEventListener('click', (e) => {
            const wrapper = e.target.closest('.lisfinity-product-tabs');
            const content = e.target.dataset.tab;
            const tabContents = wrapper.querySelectorAll('.product-tabs--content');
            const elTabButtons = wrapper.querySelectorAll('.product-tab');
            elTabButtons.forEach((tabBg) => {
              tabBg.classList.remove('active');
            });
            e.target.classList.add('active');
            if (tabContents) {
              tabContents.forEach((tabContent) => {
                if (tabContent.dataset.content === content) {
                  tabContent.classList.remove('hidden');
                  tabContent.classList.add('block');
                } else {
                  tabContent.classList.remove('block');
                  tabContent.classList.add('hidden');
                }
              });
            }
          });
        });
      }
    });
// global widgets tabs element
    elementorFrontend.hooks.addAction('frontend/element_ready/lisfinity-tabs.default', ($scope) => {
      // element tabs
      const geTabButtons = document.querySelectorAll('.ge-tabs--action');
      if (geTabButtons) {
        geTabButtons.forEach((tab) => {
          tab.addEventListener('click', (e) => {
            const wrapper = e.target.closest('.ge-tabs');
            const content = e.target.dataset.id;
            const tabContents = wrapper.querySelectorAll('.ge-tab');
            const elTabButtons = wrapper.querySelectorAll('.ge-tabs--action');
            elTabButtons.forEach((tabBg) => {
              tabBg.classList.remove('active');
            });
            e.target.classList.add('active');
            if (tabContents) {
              tabContents.forEach((tabContent) => {
                if (tabContent.dataset.id === content) {
                  tabContent.classList.remove('hidden');
                  tabContent.classList.add('active');
                } else {
                  tabContent.classList.remove('active');
                  tabContent.classList.add('hidden');
                }
              });
            }
          });
        });
      }
    });
  }
});
