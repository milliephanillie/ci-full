/* global lc_data, React */
/**
 * External dependencies.
 */
import { findDOMNode, Fragment, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { isEmpty, filter } from 'lodash';
import { Route, NavLink } from 'react-router-dom';
import { useSelector } from 'react-redux';
import ScrollContainer from 'react-indiana-drag-scroll';
import MyAccountGeneral from './MyAccountGeneral';
import MyAccountBilling from './MyAccountBilling';
import MyAccountPassword from './MyAccountPassword';
import MyAccountShipping from './MyAccountShipping';
import ReactSVG from 'react-svg';
import shiftRightIcon from '../../../../../../../images/icons/shift-right.svg';
import { sideScroll } from '../../../../../../theme/vendor/functions';
import MyAccountVerification from './MyAccountVerification';

const MyAccountTabs = (props) => {
  const { productId } = props;
  const data = useSelector(state => state);
  const { loading, business, product } = data;
  const toScroll = useRef(null);

  let url = `${lc_data.myaccount}account-edit`;

  const scrollLeft = () => {
    const container = findDOMNode(toScroll.current);
    sideScroll(container, 'right', '25', 100, 10);
  };

  return [
    <div key={0} className="w-full bg:w-11/16">
      <nav className="flex flex-end items-center mb-30 p-20 bg-white rounded shadow-theme">
        <ScrollContainer ref={toScroll}>
          <ul className="flex items-center w-full">
            <li>
              <NavLink
                exact
                to={`${lc_data.site_url}${url}`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {lc_data.jst[286]}
              </NavLink>
            </li>
            <li>
              <NavLink
                exact
                to={`${lc_data.site_url}${url}/password-reset`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {lc_data.jst[282]}
              </NavLink>
            </li>
            {data.options.page_billing &&
            <li>
              <NavLink
                exact
                to={`${lc_data.site_url}${url}/billing`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {lc_data.jst[287]}
              </NavLink>
            </li>
            }
            {data.options.page_shipping &&
            <li>
              <NavLink
                exact
                to={`${lc_data.site_url}${url}/shipping`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {lc_data.jst[288]}
              </NavLink>
            </li>
            }
            {lc_data.is_user_verification_enabled &&
            <li>
              <NavLink
                exact
                to={`${lc_data.site_url}${url}/verification`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {lc_data.jst[765]}
              </NavLink>
            </li>
            }
          </ul>
        </ScrollContainer>
        <button type="button" className="ml-auto pl-20" onClick={() => scrollLeft()}>
          <ReactSVG
            src={`${lc_data.dir}dist/${shiftRightIcon}`}
            className={`ml-10 w-24 h-24 fill-filter-icon`}
          />
        </button>
      </nav>

      {'buyer' === data.options?.account_type &&
      <Route exact path={`${lc_data.site_url}${lc_data.myaccount}/`} component={() => <MyAccountGeneral/>}/>
      }
      <Route exact path={`${lc_data.site_url}${url}`} component={() => <MyAccountGeneral/>}/>
      <Route path={`${lc_data.site_url}${url}/password-reset`} component={() => <MyAccountPassword/>}/>
      {data.options.page_billing &&
      <Route path={`${lc_data.site_url}${url}/billing`} component={() => <MyAccountBilling/>}/>
      }
      {data.options.page_shipping &&
      <Route path={`${lc_data.site_url}${url}/shipping`} component={() => <MyAccountShipping/>}/>
      }
      {lc_data.is_user_verification_enabled &&
      <Route path={`${lc_data.site_url}${url}/verification`} component={() => <MyAccountVerification/>}/>
      }

    </div>,
  ];
};

export default MyAccountTabs;
