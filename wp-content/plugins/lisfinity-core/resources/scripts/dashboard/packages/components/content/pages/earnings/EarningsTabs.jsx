/* global lc_data, React */
/**
 * External dependencies.
 */
import { findDOMNode, Fragment, useRef } from '@wordpress/element';
import { Route, NavLink } from 'react-router-dom';
import { useSelector } from 'react-redux';
import ScrollContainer from 'react-indiana-drag-scroll';
import ReactSVG from 'react-svg';
import shiftRightIcon from '../../../../../../../images/icons/shift-right.svg';
import { sideScroll } from '../../../../../../theme/vendor/functions';
import Earnings from './Earnings';
import EarningsReceived from './EarningsReceived';
import EarningsSettings from './EarningsSettings';
import he from 'he';

const EarningsTabs = (props) => {
  const { productId } = props;
  const data = useSelector(state => state);
  const { loading, business, product } = data;
  const { vendor } = business.business;
  const toScroll = useRef(null);

  let url = `${lc_data.myaccount}earnings`;

  const scrollLeft = () => {
    const container = findDOMNode(toScroll.current);
    sideScroll(container, 'right', '25', 100, 10);
  };

  return [
    <div key={0} className="flex flex-wrap -mx-10">
      <div className="w-full bg:w-11/16 px-10">

        <div
          className="payouts--header flex flex-col mb-20 p-20 bg-blue-100 border border-blue-200 rounded shadow-theme">
          <p className="mb-4 font-semibold text-lg">{lc_data.jst[659]}</p>
          {vendor.payment_information &&
          <div dangerouslySetInnerHTML={{ __html: vendor.payment_information }}/>
          }
        </div>

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
                  {lc_data.jst[660]}
                </NavLink>
              </li>
              <li>
                <NavLink
                  exact
                  to={`${lc_data.site_url}${url}/received`}
                  className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                  activeClassName="bg-grey-100"
                >
                  {lc_data.jst[661]}
                </NavLink>
              </li>
              <li>
                <NavLink
                  exact
                  to={`${lc_data.site_url}${url}/settings`}
                  className="flex-center py-4 px-20 rounded font-bold text-grey-900 text-lg whitespace-no-wrap"
                  activeClassName="bg-grey-100"
                >
                  {lc_data.jst[658]}
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

        <Route exact path={`${lc_data.site_url}${url}`} component={() => <Earnings/>}/>
        <Route exact path={`${lc_data.site_url}${url}/received`} component={() => <EarningsReceived/>}/>
        <Route exact path={`${lc_data.site_url}${url}/settings`} component={() => <EarningsSettings/>}/>

      </div>


      <div className="block px-10 w-full bg:w-5/16 h-auto">
        <div className="flex flex-col p-20 bg-white shadow-theme animate-up">

          <p className="mb-10 font-semibold text-lg">{lc_data.jst[662]}</p>

          <div className="mb-10 p-20 w-full bg-red-100 border border-red-200 rounded">
            <p className="text-lg text-grey-900">{lc_data.jst[660]}</p>
            <p className="mt-6 font-bold text-4xl text-red-700">{he.decode(vendor.payment_due)}</p>
          </div>

          <div className="p-20 w-full bg-green-100 border border-green-300 rounded">
            <p className="text-lg text-grey-900">{lc_data.jst[661]}</p>
            <p className="mt-6 font-bold text-4xl text-green-900">{he.decode(vendor.payment_received)}</p>
          </div>

        </div>
      </div>

    </div>,
  ];
};

export default EarningsTabs;
