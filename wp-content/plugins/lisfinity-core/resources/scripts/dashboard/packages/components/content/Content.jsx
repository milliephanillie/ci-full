/* global lc_data, React */
/**
 * Dependencies.
 */
import { BrowserRouter as Router, Route, Link, NavLink } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../store/actions';
import ReactSVG from 'react-svg';
import { sprintf } from '@wordpress/i18n';
import Sidebar from './sidebar/Sidebar';
import cx from 'classnames';
import Overall from './pages/Overall';
import Products from './pages/products/Products';
import React, { Fragment } from 'react';
import Product from './pages/product/Product';
import FormSubmit from '../../../../forms/packages/components/FormSubmit';
import Packages from './packages/Packages';
import BoltIcon from '../../../../../images/icons/bolt-alt.svg';
import CrownIcon from '../../../../../images/icons/crown.svg';
import LeftIcon from '../../../../../images/icons/chevron-left.svg';
import PackageIcon from '../../../../../images/icons/package.svg';
import CartIcon from '../../../../../images/icons/cart.svg';
import FormBusinessSubmit from '../../../../forms/packages/components/FormBusinessSubmit';
import BusinessPremium from './pages/business/BusinessPremium';
import Notifications from './pages/notifications/Notifications';
import { useEffect, useRef, useState } from '@wordpress/element';
import MyAccount from './pages/woocommerce/MyAccount';
import MyAccountOrders from './pages/woocommerce/MyAccountOrders';
import MyAccountOrder from './pages/woocommerce/MyAccountOrder';
import MyAccountDownloads from './pages/woocommerce/MyAccountDownloads';
import MySubscriptions from './pages/subscriptions/MySubscriptions';
import EarningsTabs from './pages/earnings/EarningsTabs';
import { setMainIcon } from '../../store/actions';
import Commissions from '../pages/commissions/Commissions';
import { map } from 'lodash';
import axios from 'axios';
import { toast } from 'react-toastify';
import ModalNew from '../../../../theme/packages/components/modal/ModalNew';
import Spinner from '../../../../../images/icons/spinner.svg';
import SaveIcon from '../../../../../images/icons/save.svg';
import Agents from './pages/Agents';

const Content = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen } = data;
  const packages = business.active_packages;
  const [homeLink, setHomeLink] = useState(false);
  const [screenHeight, setScreenHeight] = useState('large');
  const [modalOpen, setModalOpen] = useState(false);
  const content = useRef();

  const sidebarClass = cx({
    'w-1/6 is-menu-opened': menuOpen,
    'w-60 is-menu-closed': !menuOpen,
  });
  const contentClass = cx({
    'w-5/6 is-menu-opened': menuOpen,
    'w-5/6 is-menu-closed': !menuOpen,
  });
  const premium = business.business.premium;

  const sidebarOpen = () => {
    if (window.innerWidth > 1480) {
      dispatch(actions.setMenuOpen(true));
      setHomeLink(false);
    } else {
      dispatch(actions.setMenuOpen(false));
      setHomeLink(true);
    }
    if (window.innerHeight < 695) {
      setScreenHeight('small');
    } else {
      setScreenHeight('large');
    }
  };

  const contentOffsetTop = () => {
    const header = document.querySelector('.header--dashboard');
    if (header) {
      content.current.style.top = `${header.offsetHeight - 4}px`;
    }
  };

  const upgradeProfile = () => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.upgrade_profile;
    return axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: {
        user_id: lc_data.current_user_id,
      }
    }).then(response => {
      if (response.data.success) {
        window.location.reload();
      }
    });
  };

  useEffect(() => {
    window.addEventListener('resize', sidebarOpen);
    window.addEventListener('resize', contentOffsetTop);

    sidebarOpen();
    setTimeout(() => {
      contentOffsetTop();
    }, 40);

    return () => {
      window.removeEventListener('resize', sidebarOpen);
      window.removeEventListener('resize', contentOffsetTop);
    };
  }, []);

  return (
    <main className="flex" ref={content}>
      <aside className={`dashboard--sidebar bg-white ${sidebarClass} ${screenHeight === 'small' ? 'absolute' : ''}`}>
        <Sidebar menuOpen={menuOpen}/>
      </aside>

      <section className={`dashboard dashboard--content p-16 sm:p-30 bg-grey-100 ${contentClass}`}
               style={{ width: !menuOpen ? 'calc(100% - 60px)' : '' }}>
        <div className="dashboard--heading flex flex-wrap justify-between mb-20">

          <div className="flex flex-col mb-20">

            {homeLink &&
            <div className="flex mb-10">
              <a href={lc_data.site_url} className="flex text-grey-500">
                <ReactSVG
                  src={`${lc_data.dir}dist/${LeftIcon}`}
                  className="relative top-4 mr-8 w-12 h-12 fill-grey-500"
                />
                {lc_data.jst[686]}
              </a>
            </div>
            }

            <div className="flex">
              <ReactSVG
                src={`${lc_data.dir}dist/${data.mainIcon.icon}`}
                className={`relative top-2 mr-8 w-16 h-16 ${data.mainIcon.color}`}
              />
              <div>
                <h3 className="mb-6 font-bold leading-none">
                  {data.mainIcon.title}
                </h3>
                <span className="font-light text-lg text-grey-700">
                  {sprintf(lc_data.jst[108], business.user.display_name)}
                </span>
              </div>
            </div>

          </div>

          {data.options.account_type === 'buyer' &&
          <div className="flex flex-wrap w-full sm:w-auto">
            <Fragment>
              {data.options.premium_profiles && !premium &&
              <NavLink
                to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-red-600 rounded shadow-theme font-bold text-white hover:bg-red-700"
              >
                <div className="flex flex-col text-left">
                  <span className="text-sm">{lc_data.jst[109]}</span>
                  <span className="font-bold text-xl">{lc_data.jst[751]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CrownIcon}`}
                  className="w-20 h-20 fill-white"
                />
              </NavLink>
              }
              {data.options.premium_profiles && premium?.expires_human && !lc_data.is_agent &&
              <NavLink
                to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-white rounded shadow-theme font-bold text-grey-1000 hover:bg-white"
              >
                <div className="flex flex-col text-left">
                  {premium?.expires_human &&
                  <span
                    className="font-normal text-sm">{sprintf(lc_data.jst[103], premium.expires_human)}</span>}
                  <span className="font-bold text-xl">{lc_data.jst[751]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CrownIcon}`}
                  className="w-20 h-20 fill-grey-1000"
                />
              </NavLink>
              }
              <button
                onClick={() => setModalOpen(true)}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-green-600 rounded shadow-theme font-bold text-white hover:bg-green-700"
              >
                <div className="flex flex-col text-left">
                  <span className="text-sm">{lc_data.jst[749]}</span>
                  <span className="font-bold text-xl">{lc_data.jst[750]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CartIcon}`}
                  className="w-20 h-20 fill-white"
                />
              </button>
            </Fragment>
          </div>
          }

          {data.options.vendor_approved &&
          <div className="flex flex-wrap w-full sm:w-auto">
            {business.options.premium_profiles && data.options.account_type === 'business' &&
            <Fragment>
              {!premium &&
              <NavLink
                to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-red-600 rounded shadow-theme font-bold text-white hover:bg-red-700"
              >
                <div className="flex flex-col text-left">
                  <span className="text-sm">{lc_data.jst[109]}</span>
                  <span className="font-bold text-xl">{lc_data.jst[104]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CrownIcon}`}
                  className="w-20 h-20 fill-white"
                />
              </NavLink>
              }
              {business.options?.account_type === 'business' && premium?.expires_human &&
              <NavLink
                to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-white rounded shadow-theme font-bold text-grey-1000 hover:bg-white"
              >
                <div className="flex flex-col text-left">
                  {premium?.expires_human &&
                  <span
                    className="font-normal text-sm">{sprintf(lc_data.jst[103], premium.expires_human)}</span>}
                  <span className="font-bold text-xl">{lc_data.jst[104]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CrownIcon}`}
                  className="w-20 h-20 fill-grey-1000"
                />
              </NavLink>
              }
            </Fragment>
            }
            {data.options.account_type === 'personal' &&
            <Fragment>
              {data.options.premium_profiles && !premium &&
              <NavLink
                to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-red-600 rounded shadow-theme font-bold text-white hover:bg-red-700"
              >
                <div className="flex flex-col text-left">
                  <span className="text-sm">{lc_data.jst[109]}</span>
                  <span className="font-bold text-xl">{lc_data.jst[751]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CrownIcon}`}
                  className="w-20 h-20 fill-white"
                />
              </NavLink>
              }
              {data.options.premium_profiles && premium?.expires_human &&
              <NavLink
                to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-white rounded shadow-theme font-bold text-grey-1000 hover:bg-white"
              >
                <div className="flex flex-col text-left">
                  {premium?.expires_human &&
                  <span
                    className="font-normal text-sm">{sprintf(lc_data.jst[103], premium.expires_human)}</span>}
                  <span className="font-bold text-xl">{lc_data.jst[751]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CrownIcon}`}
                  className="w-20 h-20 fill-grey-1000"
                />
              </NavLink>
              }
              {data.options.vendors_enabled && !lc_data.is_agent &&
              <button
                onClick={() => setModalOpen(true)}
                className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-green-600 rounded shadow-theme font-bold text-white hover:bg-green-700"
              >
                <div className="flex flex-col text-left">
                  <span className="text-sm">{lc_data.jst[749]}</span>
                  <span className="font-bold text-xl">{lc_data.jst[752]}</span>
                </div>
                <ReactSVG
                  src={`${lc_data.dir}dist/${CartIcon}`}
                  className="w-20 h-20 fill-white"
                />
              </button>
              }
            </Fragment>
            }
            <NavLink
              to={business.options.enable_packages ? `${lc_data.site_url}${lc_data.myaccount}packages` : `${lc_data.site_url}${lc_data.myaccount}submit`}
              className={`flex justify-between items-center mt-10 sm:mt-0 px-20 w-full sm:w-225 ${data?.mainIcon?.title === lc_data.jst[133] || data?.mainIcon?.title === lc_data.jst[116] ? 'bg-grey-200 text-grey-300 hover:bg-grey-200 hover:text-grey-300 cursor-default' : 'bg-blue-700 text-white hover:bg-blue-800'} rounded shadow-theme font-bold ${lc_data.jst[106].length > 12 ? 'h-auto' : 'h-60'}`}
              onClick={() => {
                if (business.options.enable_packages) {
                  dispatch(setMainIcon({ icon: PackageIcon, color: 'fill-yellow-700', title: lc_data.jst[133] }));
                } else {
                  dispatch(setMainIcon({ icon: BoltIcon, color: 'fill-green-700', title: lc_data.jst[116] }));
                }
              }
              }
            >
              <div className={`flex flex-col text-left ${lc_data.jst[106].length > 18 ? 'py-10' : ''}`}>
                <span className="text-sm">{lc_data.jst[105]}</span>
                <span className="font-bold text-xl">{lc_data.jst[106]}</span>
              </div>
              <ReactSVG
                src={`${lc_data.dir}dist/${BoltIcon}`}
                className={`w-20 h-20 ${data?.mainIcon?.title === lc_data.jst[133] || data?.mainIcon?.title === lc_data.jst[116] ? 'fill-grey-300' : 'fill-white'}`}
              />
            </NavLink>
          </div>
          }

        </div>

        {!data.options.vendor_approved && data.options.account_type !== 'buyer' &&
        <div
          className="mb-20 p-20 bg-blue-100 rounded border border-blue-300 font-semibold text-lg text-blue-500">{lc_data.jst[687]}</div>
        }

        {packages &&
        <div className="packages--active w-full">
          {map(packages, p => {
            return p.type === 'subscription' &&
              <div key={p.id} className="flex items-center premium-profile mb-20">

                <div className="dashboard--premium flex flex-wrap p-30 bg-white rounded shadow-theme w-full">
                  <div className="w-full">
                    <div className="flex flex-wrap items-center justify-between">
                      <h4 className="font-bold">{p.title}</h4>

                      <div className="flex flex-wrap">
                        <div className="flex items-center mr-10 py-6 px-12 bg-blue-100 rounded text-grey-900">
                          <span className="mr-6">{lc_data.jst[147]}</span>
                          <strong>{p.starts_at}</strong>
                        </div>

                        <div className="flex items-center py-6 px-12 bg-red-100 rounded text-grey-900">
                          <span className="mr-6">{lc_data.jst[148]}</span>
                          <strong>{p.expires_at}</strong>
                        </div>
                      </div>

                    </div>

                  </div>
                </div>
              </div>;
          })}
        </div>
        }

        <div>
          {data.loading && <div></div>}
          {!data.loading &&
          <Fragment>
            {'buyer' !== data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}/`}
              render={() => <Overall {...props} options={data.options}/>}
            />
            }
            {'buyer' === data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}/`}
              render={props => <MyAccount {...props} />}
            />
            }
            {data.options.vendor_approved && 'buyer' !== data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}ads`}
              render={() => <Products/>}
            />
            }
            {data.options.vendor_approved && 'buyer' !== data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}ad/:id`}
              render={props => <Product {...props} />}
            />
            }
            {data.options.vendor_approved && 'buyer' !== data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}edit/:id`}
              render={props => <FormSubmit {...props} edit={true} options={data.options}/>}
            />
            }
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}notifications`}
              render={() => <Notifications/>}
            />
            {data.options.vendor_approved && 'buyer' !== data.options?.account_type &&
                <Route
                    path={`${lc_data.site_url}${lc_data.myaccount}submit/`}
                    render={props => <FormSubmit {...props} test={"one"} options={data.options}/>}
                />
            }
            {/*{data.options.vendor_approved && !business.options.enable_packages && 'buyer' !== data.options?.account_type &&*/}
            {/*<Route*/}
            {/*  path={`${lc_data.site_url}${lc_data.myaccount}submit/`}*/}
            {/*  render={props => <FormSubmit {...props} options={data.options}/>}*/}
            {/*/>*/}
            {/*}*/}
            {data.options.vendor_approved && business.options.enable_packages && 'buyer' !== data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}submit/:package`}
              render={props => <FormSubmit {...props} test={"two"} options={data.options}/>}
            />
            }
            {data.options.vendor_approved && business.options.enable_packages && 'buyer' !== data.options?.account_type &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}packages`}
              render={props => <Packages {...props} options={data.options}/>}
            />
            }
            {!lc_data.is_agent &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}business`}
              render={props => <FormBusinessSubmit {...props} options={data.options}/>}
            />
            }
            {((data.options.vendor_approved && data.options.premium_profiles && !business?.business?.forced_premium) || (data.options.account_type === 'buyer')) && !lc_data.is_agent &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
              render={props => <BusinessPremium {...props}/>}
            />
            }
            {data.options.vendor_approved && business.options.vendors_enabled && 'buyer' !== data.options?.account_type && !lc_data.is_agent &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}earnings`}
              render={props => <EarningsTabs {...props}/>}
            />
            }
            {business.options.commissions_enabled && !lc_data.is_agent &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}commissions`}
              render={props => <Commissions {...props}/>}
            />
            }
            {'buyer' !== data.options?.account_type && !lc_data.is_agent && data.options?.is_agents_enabled &&
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}agents`}
              render={props => <Agents {...props} />}
            />
            }
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}account-edit`}
              render={props => <MyAccount {...props} />}
            />
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}subscriptions`}
              render={props => <MySubscriptions {...props} />}
            />
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}account-orders`}
              render={props => <MyAccountOrders {...props} />}
            />
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}view-order/:order`}
              render={props => <MyAccountOrder {...props} />}
            />
            <Route
              path={`${lc_data.site_url}${lc_data.myaccount}account-downloads`}
              render={props => <MyAccountDownloads {...props} />}
            />
          </Fragment>
          }

        </div>


      </section>

      {modalOpen &&
      <div
        className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <ModalNew
          open={modalOpen}
          closeModal={() => setModalOpen(false)}
          title={lc_data.jst[755]}
        >
          <p className="mb-20">{lc_data.jst[756]}</p>
          <Fragment>
            {data.options.account_type === 'buyer' && !lc_data.is_agent &&
            <button
              onClick={() => upgradeProfile()}
              className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-green-600 rounded shadow-theme font-bold text-white hover:bg-green-700"
            >
              <div className="flex flex-col text-left">
                <span className="text-sm">{lc_data.jst[749]}</span>
                <span className="font-bold text-xl">{lc_data.jst[750]}</span>
              </div>
              <ReactSVG
                src={`${lc_data.dir}dist/${CartIcon}`}
                className="w-20 h-20 fill-white"
              />
            </button>
            }
            {data.options.account_type === 'personal' && !lc_data.is_agent &&
            <button
              onClick={() => upgradeProfile()}
              className="flex justify-between items-center sm:mr-10 px-20 w-full sm:w-225 h-60 bg-green-600 rounded shadow-theme font-bold text-white hover:bg-green-700"
            >
              <div className="flex flex-col text-left">
                <span className="text-sm">{lc_data.jst[749]}</span>
                <span className="font-bold text-xl">{lc_data.jst[752]}</span>
              </div>
              <ReactSVG
                src={`${lc_data.dir}dist/${CartIcon}`}
                className="w-20 h-20 fill-white"
              />
            </button>
            }
          </Fragment>
        </ModalNew>
      </div>
      }

    </main>
  );
};

export default Content;
