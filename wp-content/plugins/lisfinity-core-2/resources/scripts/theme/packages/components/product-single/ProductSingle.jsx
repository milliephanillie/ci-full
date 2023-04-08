/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import Breadcrumb from './partials/ProductBreadcrumb';
import { getProduct } from '../../store/actions';
import { isEmpty } from 'lodash';
import ProductContent from './partials/content/ProductContent';
import Similar from './partials/sidebar/Similar';
import ProductMenu from './partials/menu/ProductMenu';
import ProductMenuTop from './partials/menu/ProductMenuTop';
import ProductMenuSticky from './partials/menu/ProductMenuSticky';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import { ToastContainer } from 'react-toastify';
import ProductSidebarAlt from './partials/content/ProductSidebarAlt';
import ModalNew from '../modal/ModalNew';
import React from 'react';
import OwnerAlt from "./partials/sidebar/OwnerAlt";

/**
 * Internal dependencies
 */

class ProductSingle extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: true,
      product: {},
      currentUser: {},
      showMenu: 'top',
      showSidebar: 'default',
      stickyMenuClasses: '',
      options: {},
      premiumModal: false,
    };
  }

  componentDidMount() {
    this.getProductData();
    this.getUser();
    const options = JSON.parse(document.getElementById('page-single').dataset.options);
    this.setState({ options });

    if (options && options?.listings_for_premium_only) {
      this.setState({ premiumModal: true });
    }

    window.addEventListener('resize', this.showMenu);
    this.showMenu();
  }

  componentWillUnmount() {
    window.removeEventListener('resize', this.showMenu);
  }

  showMenu = () => {
    if (window.innerWidth > 1620) {
      this.setState({ showMenu: 'side' });
    } else {
      this.setState({ showMenu: 'top' });
    }
    if (window.innerWidth <= 960) {
      this.setState({ showSidebar: 'small-screen' });
    } else {
      this.setState({ showSidebar: 'default' });
    }
    if (window.innerWidth <= 960) {
      this.setState({ stickyMenuClasses: 'left-0 w-full' });
    } else {
      this.setState({ stickyMenuClasses: '' });
    }
  };

  /**
   * Get product information
   * -----------------------
   */
  getProductData() {
    const productId = lc_data.current_product_id;
    const url = `${lc_data.product}/${productId}/?_wpnonce=${lc_data.nonce}`;

    apiFetch({ path: url })
        .then(product => {
          this.setState({ product: getProduct(product).product });
          if ((this.state.product?.premium_only && lc_data.check_for_premium)) {
            this.setState({ premiumModal: true });
          }
          this.setState({ loading: false });
          const oldLoader = document.getElementById('loader');
          if (oldLoader) {
            oldLoader.classList.add('fade-out');
            setTimeout(() => {
              oldLoader.remove();
            }, 200);
          }
        });
  }

  /**
   * Get the current user
   * --------------------
   */
  getUser() {
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(`${lc_data.user}/?_wpnonce=${lc_data.nonce}`, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
    }).then(response => response.json()).then(currentUser => this.setState({ currentUser }));
  }

  render() {

    const { loading, product, currentUser, showMenu, showSidebar, stickyMenuClasses, options } = this.state;
    return (
        <Fragment>
          {loading && <LoaderProductSingle/>}
          {!loading && product && !isEmpty(product) &&
              <Fragment>
                <section className="product--header bg-grey-100 py-24 px-10 xs:px-20 sm:px-60 bg:px-24">
                  <div className="container flex flex-wrap justify-between items-center px-0">
                    <Breadcrumb product={product} currentUser={currentUser} options={this.state.options}/>
                    <div>
                      <strong className="text-grey-900 font-semibold">{product.all_products.product_position}</strong>
                      <span className="mx-2 text-grey-700">{lc_data.jst[485]}</span>
                      <strong className="text-grey-900 font-semibold">{product.all_products.products_count}</strong>
                    </div>
                  </div>
                </section>
                {product?.is_expired &&
                    <Fragment>
                      <article className="product--main product--expired">
                        <div className="relative container flex flex-wrap px-10 xs:px-20 sm:px-60 bg:px-0">
                          {!this.state.premiumModal &&
                              <Fragment>
                                {showMenu === 'side' && product.groups && product.groups.groups &&
                                    <div>
                                      <aside className="product--menu__aside fixed flex justify-end py-24 px-30">
                                        <ProductMenu product={product}/>
                                      </aside>
                                    </div>
                                }

                                <div
                                    className="product--single-content-wrapper product--expired w-full py-20 pb-30 bg-white to-print px-20 bg-white shadow-theme px-10 xs:px-20 sm:px-40 bg:px-0 bg:w-4/6 bg:shadow-none">

                                  {showMenu === 'top' && product.groups && product.groups.groups &&
                                      <aside className="flex mt-5 mb-30">
                                        <ProductMenuTop product={product} currentUser={currentUser}/>
                                      </aside>
                                  }

                                  {product.groups && product.groups.groups &&
                                      <aside
                                          className={`product--menu-sticky flex fixed container px-0 z-10 ${stickyMenuClasses}`}>
                                        <ProductMenuSticky product={product} options={options} currentUser={currentUser}/>
                                      </aside>
                                  }

                                  {this.state.options &&
                                      <ProductContent product={product} currentUser={currentUser} options={this.state.options}/>
                                  }
                                </div>
                                {showSidebar === 'default' &&
                                    <aside className="product--single-aside w-full bg:pl-30 bg:w-2/6">
                                      {this.state.options &&
                                          <ProductSidebarAlt product={product} currentUser={currentUser} options={this.state.options}/>}
                                    </aside>
                                }
                              </Fragment>
                          }

                          {this.state.premiumModal &&
                              <div
                                  className="product--single-content-wrapper w-full py-20 pb-30 bg-white to-print px-20 bg-white shadow-theme px-10 xs:px-20 sm:px-40 bg:px-0 bg:w-4/6 bg:shadow-none">
                                <ProductContent product={product} currentUser={currentUser} options={this.state.options}
                                                premiumOnly={this.state.premiumModal}/>
                              </div>
                          }

                        </div>
                      </article>
                    </Fragment>
                }
                {!product?.is_expired &&
                    <Fragment>
                      <article className="product--main">
                        <div className="relative container flex flex-wrap px-10 xs:px-20 sm:px-60 bg:px-0">

                          {!this.state.premiumModal &&
                              <Fragment>
                                {showMenu === 'side' && product.groups && product.groups.groups &&
                                    <div>
                                      <aside className="product--menu__aside fixed flex justify-end py-24 px-30">
                                        <ProductMenu product={product}/>
                                      </aside>
                                    </div>
                                }

                                <div
                                    className="product--single-content-wrapper w-full py-20 pb-30 bg-white to-print px-20 bg-white shadow-theme px-10 xs:px-20 sm:px-40 bg:px-0 bg:w-4/6 bg:shadow-none">

                                  {showMenu === 'top' && product.groups && product.groups.groups &&
                                      <aside className="flex mt-5 mb-30">
                                        <ProductMenuTop product={product} currentUser={currentUser}/>
                                      </aside>
                                  }

                                  {product.groups && product.groups.groups &&
                                      <aside
                                          className={`product--menu-sticky flex fixed container px-0 z-10 ${stickyMenuClasses}`}>
                                        <ProductMenuSticky product={product} options={options} currentUser={currentUser}/>
                                      </aside>
                                  }

                                  {this.state.options &&
                                      <div>
                                        <ProductContent product={product} currentUser={currentUser} options={this.state.options}/>
                                      </div>
                                  }
                                </div>
                                {showSidebar === 'default' &&
                                    <aside className="product--single-aside w-full bg:pl-30 bg:w-2/6">
                                      {this.state.options &&
                                          <ProductSidebarAlt product={product} currentUser={currentUser} options={this.state.options}/>}
                                    </aside>
                                }
                              </Fragment>
                          }

                          {this.state.premiumModal &&
                              <div
                                  className="product--single-content-wrapper w-full py-20 pb-30 bg-white to-print px-20 bg-white shadow-theme px-10 xs:px-20 sm:px-40 bg:px-0 bg:w-4/6 bg:shadow-none">
                                <ProductContent product={product} currentUser={currentUser} options={this.state.options}
                                                premiumOnly={this.state.premiumModal}/>
                              </div>
                          }

                        </div>
                      </article>

                      {showSidebar === 'small-screen' &&
                          <div
                              className="relative container flex flex-wrap pt-20 bg-grey-100 px-10 sm:pt-60 xs:px-20 sm:px-60 bg:px-0">
                            <aside className="w-full bg:pl-30 bg:w-2/6">
                              {this.state.options &&
                                  <ProductSidebarAlt product={product} currentUser={currentUser} options={this.state.options}/>}
                            </aside>
                          </div>
                      }
                    </Fragment>
                }

                {this.state.options && this.state.options.ad_similar !== '0' &&
                    <Similar product={product} options={this.state.options}/>}

                <ModalNew
                    open={this.state.premiumModal}
                    closeModal={() => this.setState({ premiumModal: true })}
                    disableClosing
                    title={lc_data.jst[options?.restricted_modal_type === 'premium' ? 757 : 758]}
                >
                  {options?.restricted_modal_image?.url &&
                      <div className="image">
                        <img src={options.restricted_modal_image.url} alt="premium-modal-image"/>
                      </div>
                  }
                  <h5
                      className="font-bold text-grey-900">{lc_data.jst[options?.restricted_modal_type === 'premium' ? 759 : 760]}</h5>
                  <div className="mt-10 text-grey-600"
                       dangerouslySetInnerHTML={{ __html: lc_data.jst[options?.restricted_modal_type === 'premium' ? 761 : 762] }}/>
                  <div className="inline-block mt-20">
                    {lc_data.logged_in &&
                        <a href={`${lc_data.page_account}premium-profile`}
                           className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white">{lc_data.jst[options?.restricted_modal_type === 'premium' ? 306 : 763]}</a>
                    }
                    {!lc_data.logged_in &&
                        <a href={lc_data.page_register}
                           className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white">{lc_data.jst[options?.restricted_modal_type === 'premium' ? 306 : 763]}</a>
                    }
                  </div>
                </ModalNew>
              </Fragment>
          }
          <ToastContainer/>
        </Fragment>
    );
  }

}

export default ProductSingle;
