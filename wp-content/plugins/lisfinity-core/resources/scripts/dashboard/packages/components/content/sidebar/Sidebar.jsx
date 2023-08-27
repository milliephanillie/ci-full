/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { NavLink } from 'react-router-dom';
import ReactSVG from 'react-svg';
import { Fragment } from 'react';
import cx from 'classnames';
import CogIcon from '../../../../../../images/icons/cog.svg';
import PersonIcon from '../../../../../../images/icons/user.svg';
import UsersIcon from '../../../../../../images/icons/users.svg';
import BoltIcon from '../../../../../../images/icons/bolt-alt.svg';
import PackageIcon from '../../../../../../images/icons/package.svg';
import CrownIcon from '../../../../../../images/icons/crown.svg';
import JobIcon from '../../../../../../images/icons/briefcase.svg';
import NotificationIcon from '../../../../../../images/icons/alarm.svg';
import CartIcon from '../../../../../../images/icons/cart-full.svg';
import DownloadIcon from '../../../../../../images/icons/download.svg';
import LogoutIcon from '../../../../../../images/icons/exit.svg';
import HomeIcon from '../../../../../../images/icons/home.svg';
import BookmarkIcon from '../../../../../../images/icons/bookmark.svg';
import EarningsIcon from '../../../../../../images/icons/wallet.svg';
import ReactTooltip from 'react-tooltip';
import { setMainIcon } from '../../../store/actions';

const Sidebar = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, notifications } = data;
  const { menuOpen } = props;

  const premium = business.business.premium;
  const premiumLabel = premium ?
    <Fragment>
      {lc_data.jst[146]}
      <span className="absolute right-30 font-light text-green-700">{lc_data.jst[305]}</span>
    </Fragment>
    :
    <Fragment>
      {lc_data.jst[306]}
      <span className="absolute right-30 font-light text-sm text-red-600"
      >
        {lc_data.jst[307]}
      </span>
    </Fragment>;

  const notificationsCount = notifications && notifications.length !== 0 ? notifications.length : false;
  const labelClass = cx({
    'absolute top-10 right-30 flex-center w-24 h-24 bg-red-500 rounded-full text-sm text-white': menuOpen,
    'absolute top-4 right-14 flex-center w-16 h-16 bg-red-500 rounded-full text-xs text-white': !menuOpen,
  });

  return (
    <nav className="relative text-red-700">
      {data.menuOpen &&
      <div
        className={`relative flex items-center pl-20 mt-10 -mb-3 border-b border-grey-200 pb-6 font-semibold text-sm text-grey-500 ${!menuOpen ? 'opacity-0' : ''}`}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${CogIcon}`}
          className="mr-14 min-w-12 w-12 h-12 fill-grey-400"
        />
        {lc_data.jst[176]}
      </div>
      }
      {'buyer' !== data.options?.account_type &&
      <li className="list-none border-t-2 border-transparent"
          onClick={() => dispatch(setMainIcon({ icon: HomeIcon, color: 'fill-red-500', title: lc_data.jst[107] }))}
      >
        <NavLink
          exact
          to={`${lc_data.site_url}${lc_data.myaccount}`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[107]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${CogIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-red-500"
          />
          {menuOpen && lc_data.jst[107]}
        </NavLink>
      </li>
      }
      {'buyer' !== data.options?.account_type &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({ icon: BoltIcon, color: 'fill-blue-700', title: lc_data.jst[24] }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}ads`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[24]}

        >
          <ReactSVG
            src={`${lc_data.dir}dist/${BoltIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-blue-700"
          />
          {menuOpen && lc_data.jst[24]}
        </NavLink>
      </li>
      }
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: NotificationIcon,
            color: 'fill-green-700',
            title: lc_data.jst[232]
          }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}notifications`}
          className="relative flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[232]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${NotificationIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-green-700"
          />
          {menuOpen && lc_data.jst[232]}
          {notificationsCount && <span
            className={labelClass}>{notificationsCount}</span>}
        </NavLink>
      </li>
      {/*{business.options.enable_packages && 'buyer' !== data.options?.account_type && !lc_data.is_agent &&*/}
      {/*<li className="list-none"*/}
      {/*    onClick={() => dispatch(setMainIcon({*/}
      {/*      icon: PackageIcon,*/}
      {/*      color: 'fill-yellow-700',*/}
      {/*      title: lc_data.jst[133]*/}
      {/*    }))}*/}
      {/*>*/}
      {/*  <NavLink*/}
      {/*    to={`${lc_data.site_url}${lc_data.myaccount}packages`}*/}
      {/*    className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"*/}
      {/*    activeClassName="bg-grey-100 border-t-2 border-grey-200"*/}
      {/*    data-tip={lc_data.jst[133]}*/}
      {/*  >*/}
      {/*    <ReactSVG*/}
      {/*      src={`${lc_data.dir}dist/${PackageIcon}`}*/}
      {/*      className="mr-14 min-w-14 w-14 h-14 fill-yellow-700"*/}
      {/*    />*/}
      {/*    {menuOpen && lc_data.jst[133]}*/}
      {/*  </NavLink>*/}
      {/*</li>*/}
      {/*}*/}
      {business?.options?.is_business_account && 'buyer' !== data.options?.account_type && !lc_data.is_agent &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: JobIcon,
            color: 'fill-orange-700',
            title: lc_data.jst[301]
          }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}business`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[301]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${JobIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-orange-700"
          />
          {menuOpen && lc_data.jst[301]}
        </NavLink>
      </li>
      }
      {business.options.premium_profiles && !business?.business?.forced_premium && !lc_data.is_agent &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: CrownIcon,
            color: 'fill-green-700',
            title: lc_data.jst[146]
          }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}premium-profile`}
          className="relative flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${CrownIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-green-700"
            data-tip={lc_data.jst[146]}
          />
          {menuOpen && premiumLabel}
        </NavLink>
      </li>
      }
      {business.options.vendors_enabled && 'buyer' !== data.options?.account_type && !lc_data.is_agent &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: EarningsIcon,
            color: 'fill-red-500',
            title: lc_data.jst[657]
          }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}earnings`}
          className="relative flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${EarningsIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-red-500"
            data-tip={lc_data.jst[657]}
          />
          {menuOpen && lc_data.jst[657]}
        </NavLink>
      </li>
      }

      {business.options.commissions_enabled && 'buyer' !== data.options?.account_type && !lc_data.is_agent &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: EarningsIcon,
            color: 'fill-red-500',
            title: lc_data.jst[714]
          }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}commissions`}
          className="relative flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${EarningsIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-red-500"
            data-tip={lc_data.jst[714]}
          />
          {menuOpen && lc_data.jst[714]}
        </NavLink>
      </li>
      }

      {'buyer' !== data.options?.account_type && !lc_data.is_agent && data.options?.is_agents_enabled &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: UsersIcon,
            color: 'fill-cyan-800',
            title: lc_data.jst[777]
          }))}
      >
        <NavLink
          to={`${lc_data.site_url}${lc_data.myaccount}agents`}
          className="relative flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${UsersIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-cyan-800"
            data-tip={lc_data.jst[777]}
          />
          {menuOpen && lc_data.jst[777]}
        </NavLink>
      </li>
      }

      {data.menuOpen &&
      <div
        className={`relative flex items-center pl-20 mt-10 -mb-3 border-b border-grey-200 pb-6 font-semibold text-sm text-grey-500 ${!menuOpen ? 'opacity-0' : ''}`}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${CogIcon}`}
          className="mr-14 min-w-12 w-12 h-12 fill-grey-400"
        />
        {lc_data.jst[302]}
      </div>
      }
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: PersonIcon,
            color: 'fill-grey-700',
            title: lc_data.jst[303]
          }))}
      >
        <NavLink
          exact
          to={`${lc_data.site_url}${lc_data.myaccount}account-edit`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[303]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${PersonIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-grey-700"
          />
          {menuOpen && lc_data.jst[303]}
        </NavLink>
      </li>
      {data?.options?.page_bookmarks &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: BookmarkIcon,
            color: 'fill-grey-700',
            title: lc_data.jst[635]
          }))}
      >
        <NavLink
          exact
          to={`${lc_data.site_url}${lc_data.myaccount}subscriptions`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[634]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${BookmarkIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-grey-700"
          />
          {menuOpen && lc_data.jst[634]}
        </NavLink>
      </li>
      }
      {data?.options?.page_orders &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: CartIcon,
            color: 'fill-grey-700',
            title: lc_data.jst[257]
          }))}
      >
        <NavLink
          exact
          to={`${lc_data.site_url}${lc_data.myaccount}account-orders`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[257]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${CartIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-grey-700"
          />
          {menuOpen && lc_data.jst[257]}
        </NavLink>
      </li>
      }
      {data?.options?.page_download &&
      <li className="list-none"
          onClick={() => dispatch(setMainIcon({
            icon: DownloadIcon,
            color: 'fill-grey-700',
            title: lc_data.jst[304]
          }))}
      >
        <NavLink
          exact
          to={`${lc_data.site_url}${lc_data.myaccount}account-downloads`}
          className="flex items-center py-14 px-20 border-t-2 border-transparent font-bold text-base text-grey-800 leading-none"
          activeClassName="bg-grey-100 border-t-2 border-grey-200"
          data-tip={lc_data.jst[304]}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${DownloadIcon}`}
            className="mr-14 min-w-14 w-14 h-14 fill-grey-700"
          />
          {menuOpen && lc_data.jst[304]}
        </NavLink>
      </li>
      }
      <button
        className="flex items-center py-14 px-20 font-bold text-base text-red-600 leading-none"
        data-tip={lc_data.jst[588]}
        onClick={() => confirm(lc_data.jst[590]) && (window.location.href = business.options.logout_url)}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${LogoutIcon}`}
          className="mr-14 min-w-14 w-14 h-14 fill-red-600"
        />
        {menuOpen && lc_data.jst[588]}
      </button>
      {!data.menuOpen &&
      <ReactTooltip place="right"/>
      }
    </nav>
  );
};

export default Sidebar;
