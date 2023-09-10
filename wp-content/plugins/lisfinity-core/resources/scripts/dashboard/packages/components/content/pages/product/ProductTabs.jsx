/* global lc_data, React */
/**
 * External dependencies.
 */
import {findDOMNode, Fragment, useRef} from '@wordpress/element';
import {__} from '@wordpress/i18n';
import {isEmpty, filter} from 'lodash';
import {Route, NavLink} from 'react-router-dom';
import Bids from './bids/Bids';
import ReactSVG from 'react-svg';
import BoltIcon from '../../../../../../../images/icons/bolt-alt.svg';
import EnvelopeIcon from '../../../../../../../images/icons/envelope.svg';
import HammerIcon from '../../../../../../../images/icons/construction-hammer.svg';
import UsersIcon from '../../../../../../../images/icons/users.svg';
import CoinIcon from '../../../../../../../images/icons/investment.svg';
import BanIcon from '../../../../../../../images/icons/na.svg';
import Messages from './messages/Messages';
import {useSelector} from 'react-redux';
import Promotions from './promotions/Promotions';
import BannedUsers from './banned/BannedUsers';
import Analytics from '../../partials/Analytics';
import ScrollContainer from 'react-indiana-drag-scroll';
import shiftRightIcon from '../../../../../../../images/icons/shift-right.svg';
import {sideScroll} from '../../../../../../theme/vendor/functions';

const ProductTabs = (props) => {
  const {productId} = props;
  const data = useSelector(state => state);
  const {loading, business, product, options} = data;
  const toScroll = useRef(null);

  let url = `${lc_data.myaccount}ad`;
  if (productId) {
    url += `/${productId}`;
  }

  function scrollLeft() {
    const container = findDOMNode(toScroll.current);
    sideScroll(container, 'right', '25', 100, 10);
  }

  const ad = filter(business.ads, (ad) => ad.id == productId);
  return [
    <Fragment key={0}>
      <nav className="flex flex-end items-center mb-30 px-20 bg-white rounded shadow-theme">
        <ScrollContainer ref={toScroll} className="py-20">
          <ul className="flex items-center w-full">
            <li>
              <NavLink
                exact
                to={`${lc_data.site_url}${url}`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${BoltIcon}`}
                  className="mr-8 w-16 h-16 fill-grey-900"
                />
                {lc_data.jst[176]}
              </NavLink>
            </li>
            {/*{options && options.promotions &&*/}
            {/*<li>*/}
            {/*  <NavLink*/}
            {/*    to={`${lc_data.site_url}${url}/promotions`}*/}
            {/*    className="flex-center relative py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"*/}
            {/*    activeClassName="bg-grey-100"*/}
            {/*  >*/}
            {/*    {ad && ad[0] && ad[0].promotions && Object.keys(ad[0].promotions).length > 0 && <span*/}
            {/*      className="absolute flex-center w-18 h-18 bg-red-500 rounded text-sm text-white z-1"*/}
            {/*      style={{top: '-5px', right: '-3px'}}>*/}
            {/*    {Object.keys(ad[0].promotions).length}*/}
            {/*  </span>}*/}
            {/*    <ReactSVG*/}
            {/*      src={`${lc_data.dir}dist/${CoinIcon}`}*/}
            {/*      className="mr-8 w-16 h-16 fill-grey-900"*/}
            {/*    />*/}
            {/*    {lc_data.jst[177]}*/}
            {/*  </NavLink>*/}
            {/*</li>*/}
            {/*}*/}
            {(options && options.messenger || options.messenger === 'undefined') &&
            <li>
              <NavLink
                to={`${lc_data.site_url}${url}/messages`}
                className="flex-center relative py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {ad && ad[0] && ad[0].messages && Object.keys(ad[0].messages).length > 0 && <span
                  className="absolute flex-center w-18 h-18 bg-red-500 rounded text-sm text-white z-1"
                  style={{top: '-5px', right: '-3px'}}>
                {Object.keys(ad[0].messages).length}
              </span>}
                <ReactSVG
                  src={`${lc_data.dir}dist/${EnvelopeIcon}`}
                  className="mr-8 w-16 h-16 fill-grey-900"
                />
                {lc_data.jst[132]}
              </NavLink>
            </li>
            }
            {(options && (options.disable_bidding || options.disable_bidding === 'undefined')) &&
            <li>
              <NavLink
                to={`${lc_data.site_url}${url}/bids`}
                className="flex-center relative py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                {ad && ad[0] && ad[0].bids && Object.keys(ad[0].bids).length > 0 && <span
                  className="absolute flex-center w-18 h-18 bg-red-500 rounded text-sm text-white z-1"
                  style={{top: '-5px', right: '-3px'}}>
                {Object.keys(ad[0].bids).length}
              </span>}
                <ReactSVG
                  src={`${lc_data.dir}dist/${HammerIcon}`}
                  className="mr-8 w-16 h-16 fill-grey-900"
                />
                {lc_data.jst[131]}
              </NavLink>
            </li>
            }
            <li>
              <NavLink
                to={`${lc_data.site_url}${url}/banned`}
                className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                activeClassName="bg-grey-100"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${BanIcon}`}
                  className="mr-8 w-16 h-16 fill-grey-900"
                />
                <span>
              {lc_data.jst[175]}
              </span>
              </NavLink>
            </li>
          </ul>
        </ScrollContainer>
        <button type="button" className="ml-auto pl-20" onClick={() => scrollLeft()}>
          <ReactSVG
            src={`${lc_data.dir}dist/${shiftRightIcon}`}
            className={`ml-10 w-24 h-24 fill-filter-icon`}
          />
        </button>
      </nav>

      <Route exact path={`${lc_data.site_url}${url}/`}
             component={() => <Analytics productId={productId} product={product}/>}/>
      {options && options.promotions &&
      <Route path={`${lc_data.site_url}${url}/promotions`}
             component={() => <Promotions productId={productId} product={product}/>}/>}
      {options && options.messenger && !isEmpty(product) &&
      <Route path={`${lc_data.site_url}${url}/messages`}
             component={() => <Messages productId={productId} product={product}/>}/>}
      <Route path={`${lc_data.site_url}${url}/bids`} component={() => <Bids productId={productId} product={product}/>}/>
      <Route path={`${lc_data.site_url}${url}/banned`}
             component={() => <BannedUsers productId={productId} product={product}/>}/>
    </Fragment>,
  ];
};

export default ProductTabs;
