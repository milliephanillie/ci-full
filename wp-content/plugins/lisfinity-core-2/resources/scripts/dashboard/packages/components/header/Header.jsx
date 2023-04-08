/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../store/actions';
import ReactSVG from 'react-svg';
import MenuIcon from '../../../../../images/icons/menu.svg';
import CloseIcon from '../../../../../images/icons/close.svg';
import AlarmIcon from '../../../../../images/icons/alarm.svg';
import cx from 'classnames';
import HeaderNotifications from './HeaderNotifications';
import HeaderCompareWrapper from './HeaderCompareWrapper';
import { isEmpty } from 'lodash';
import { useEffect, useState } from '@wordpress/element';
import LeftIcon from '../../../../../images/icons/arrow-left.svg';

const Header = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, notifications, notificationsMenu } = data;
  const [isMenuOpenBtnActive, setIsMenuOpenBtnActive] = useState(true);

  const notificationsCount = notifications && notifications.length !== 0 ? notifications.length : false;
  const labelClass = cx({
    'absolute flex-center w-16 h-16 bg-red-500 rounded-full text-xs text-white leading-none': true,
  });

  const menuOpenBtnActive = () => {
    if (window.innerWidth > 1030) {
      setIsMenuOpenBtnActive(true);
    } else {
      setIsMenuOpenBtnActive(false);
    }
  };

  useEffect(() => {
    window.addEventListener('resize', menuOpenBtnActive);

    setTimeout(() => {
      menuOpenBtnActive();
    }, 40);

    return () => {
      window.removeEventListener('resize', menuOpenBtnActive);
    };
  }, []);

  const admin = !isEmpty(business.user.first_name) ? business.user.first_name : business.user.display_name;
  const parser = new DOMParser();
  const adminName = parser.parseFromString(`<!doctype html><body>${admin}`, 'text/html').body.textContent;


  return (
    <header className="header header--dashboard relative flex flex-wrap justify-between py-20 pl-20 pr-40 bg-white">

      <div className="header__left flex w-1/6">

        {data.menuOpen && isMenuOpenBtnActive &&
        <div className="logo flex items-center">
          <a href={lc_data.site_url}>
            {business.options.logo && business.options.logo.url &&
            <img src={business.options.logo.url} alt={business.options.site_title}
                 style={{ width: data.options.logo_size || 120 }}/>}
          </a>
        </div>
        }
        <button type="button"
                className={`action--menu-open relative ${data.menuOpen && isMenuOpenBtnActive ? 'right-30 ml-auto' : ''}`}
                onClick={() => dispatch(actions.setMenuOpen(!menuOpen))}>
          {!data.menuOpen &&
          <ReactSVG
            src={`${lc_data.dir}dist/${MenuIcon}`}
            className="w-24 h-24 fill-field-icon"
          />
          }
          {data.menuOpen &&
          <ReactSVG
            src={`${lc_data.dir}dist/${CloseIcon}`}
            className="w-20 h-20 fill-field-icon"
          />
          }
        </button>

      </div>

      <div className="header__right flex items-center mt-10 xxs:mt-0">

        <div className="flex mr-10 back-to-home">
          <a href={lc_data.site_url} className="flex text-grey-500">
            <ReactSVG
              src={`${lc_data.dir}dist/${LeftIcon}`}
              className="relative mr-8 w-16 h-16 fill-grey-500"
              style={{
                top: 4,
              }}
            />
            {lc_data.jst[686]}
          </a>
          <span className="ml-4 text-grey-500">|</span>
        </div>

        <div className="user flex items-center">
          <div className="user--info text-grey-500">
            {lc_data.jst[308]}
            <span
              className="text-grey-1100">{adminName}</span>
          </div>

          <figure className="user--avatar relative ml-16 w-44 h-44 rounded-xl overflow-hidden">
            <a href={`${lc_data.site_url}${lc_data.myaccount}account-edit`}
            >
              <img
                src={business.user.avatar}
                alt={business.user.display_name}
                className="absolute top-0 left-0 w-full h-full object-cover"
              />
            </a>
          </figure>
        </div>

        {business.options && business.options.compare_enabled &&
        <HeaderCompareWrapper/>
        }

        <button
          type="button"
          className="relative user--notifications ml-20"
          onClick={() => dispatch(actions.setNotificationsMenu(!notificationsMenu))}
          title={lc_data.jst[232]}
        >
          {notificationsMenu &&
          <span
            className="notification--hover absolute shadow-theme"
            style={{
              top: '-13px',
              left: '-10px',
              width: '40px',
              height: '51px',
            }}
          ></span>}
          <ReactSVG
            src={`${lc_data.dir}dist/${AlarmIcon}`}
            className="w-20 h-20 fill-icon-reset"
          />
          {notificationsCount && <span
            className={labelClass}
            style={{
              top: '-12px',
              right: '-10px',
            }}
          >{notificationsCount}</span>}
        </button>

        <HeaderNotifications/>

      </div>

    </header>
  );
};

export default Header;
