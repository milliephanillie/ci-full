/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../store/actions';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import MenuIcon from '../../../../../images/icons/menu.svg';
import AlarmIcon from '../../../../../images/icons/alarm.svg';
import cx from 'classnames';
import { NavLink } from 'react-router-dom';
import HeaderNotifications from './HeaderNotifications';
import { setNotificationsMenu } from '../../store/actions';
import { useEffect, useRef, useState } from '@wordpress/element';
import { Fragment } from 'react';

const HeaderNotificationsWrapper = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, notifications, notificationsMenu } = data;
  const [options, setOptions] = useState({});
  const [settings, setSettings] = useState({});
  const el = useRef(null);

  const getNotifications = async () => {
    const response = actions.fetchData(lc_data.get_notifications, {
      'business': lc_data.business_id,
    });

    await response.then((result) => {
      dispatch(actions.setNotifications(result.data));
    });
  };

  useEffect(() => {
    const id = document.getElementById('main-menu-ul');
    if (id) {
      const options = JSON.parse(id.dataset.options);
      console.log(options);
      setOptions(options);
    }
    const wrapper = el.current.closest('.notifications--wrapper');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setSettings(settings);
    }

  }, []);

  useEffect(() => {
    getNotifications();

    if (lc_data.notifications_interval > 0) {
      setInterval(() => {
        getNotifications();
      }, parseInt(lc_data.notifications_interval, 10) * 1000);
    }
  }, []);

  const notificationsCount = notifications && notifications.length !== 0 ? notifications.length : false;
  const labelClass = cx({
    'absolute flex-center w-16 h-16 bg-red-500 rounded-full text-xs text-white': !props.mobile,
    'absolute flex-center w-16 h-16 bg-red-500 rounded text-xs text-white': props.mobile,
  });
  return (
    <Fragment>
      <button
        type="button"
        className={`relative user--notifications ml-20 ${notificationsMenu ? 'btn-active' : ''}`}
        onClick={() => {
          if (props.mobile) {
            window.location.href = `${lc_data.site_url}${lc_data.myaccount}/notifications`;
          } else {
            dispatch(actions.setNotificationsMenu(!notificationsMenu));
          }
        }}
        ref={el}
      >
        {notificationsMenu &&
        <span
          className="notification--hover absolute rounded-t pointer-events-none"
          style={{
            top: '-20px',
            left: '-15px',
            width: '50px',
            height: '61px',
            zIndex: '-1',
            backgroundColor: 'transparent'
          }}
        ></span>}
        {
          (!settings || !settings.custom_icon || (!settings.custom_icon_url && !settings.custom_icon_font)) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${AlarmIcon}`}
            className="w-20 h-20 fill-icon-reset pointer-events-none"
            id="notification--icon"
          />}
        {
          settings?.custom_icon && settings?.custom_icon_url &&
          <ReactSVG
            src={`${settings.custom_icon_url}`}
            className="w-20 h-20 fill-icon-reset pointer-events-none"
            id="notification--icon"
          />
        }
        {
          settings?.custom_icon && settings?.custom_icon_font &&
          <i className={settings.custom_icon_font} id="notification--icon" aria-hidden="true"
             id="notification--icon"></i>
        }
        {notificationsCount && <span
          className={`${labelClass} pointer-events-none`}
          style={{
            top: props.mobile ? '-8px' : '-12px',
            right: props.mobile ? '-6px' : '-10px',
          }}
        >{notificationsCount}</span>}
        {settings?.text &&
        <span className="notification-text">{settings?.text}</span>
        }
      </button>

      {!props.mobile &&
      <HeaderNotifications default={true}/>
      }
    </Fragment>
  );
};

export default HeaderNotificationsWrapper;
