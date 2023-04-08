/* global lc_data, React */
/**
 * Dependencies.
 */
import { BrowserRouter as Router } from 'react-router-dom';
import * as actions from '../store/actions';
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useEffect, useState } from 'react';
import Header from './header/Header';
import Content from './content/Content';
import { ToastContainer } from 'react-toastify';
import LoaderDashboard from '../../../theme/packages/components/loaders/LoaderDashboard';
import HomeIcon from '../../../../images/icons/home.svg';
import { setMainIcon } from '../store/actions';
import BoltIcon from '../../../../images/icons/bolt-alt.svg';
import NotificationIcon from '../../../../images/icons/alarm.svg';
import JobIcon from '../../../../images/icons/briefcase.svg';
import CrownIcon from '../../../../images/icons/crown.svg';
import PersonIcon from '../../../../images/icons/user.svg';
import DownloadIcon from '../../../../images/icons/download.svg';
import PackageIcon from '../../../../images/icons/package.svg';
import CartIcon from '../../../../images/icons/cart-full.svg';
import BookmarkIcon from '../../../../images/icons/bookmark.svg';
import EarningsIcon from '../../../../images/icons/wallet.svg';

const Dashboard = (props) => {
  const [loading, setLoading] = useState(true);
  const [business, setBusiness] = useState({});
  const [notifications, setNotifications] = useState({});
  const [error, setError] = useState(false);
  const [key, setKey] = useState(false);
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  const getBusiness = () => {
    const response = actions.fetchData(lc_data.get_business, '');
    response.then((result) => {
      if (result.data.error) {
        setError(result.data.message);
        setBusiness(result.data.business);
        dispatch(actions.setBusiness(result.data.business));
      }
      if (!result.data.error) {
        setBusiness(result.data);
        dispatch(actions.setBusiness(result.data));
        getNotifications(result.data.business.ID);
      }
      dispatch(actions.setLoading(false));
      setLoading(false);
      const oldLoader = document.getElementById('loader');
      if (oldLoader) {
        oldLoader.classList.add('fade-out');
        setTimeout(() => {
          oldLoader.remove();
        }, 200);
      }
    });
  };

  const getNotifications = (businessId) => {
    const response = actions.fetchData(lc_data.get_notifications, {
      'business': businessId,
    });
    response.then((result) => {
      dispatch(actions.setNotifications(result.data));
    });
  };

  useEffect(() => {
    let ignore = false;

    if (!ignore) {
      getBusiness();
    }

    const id = document.getElementById('page-dashboard');
    if (id) {
      const options = JSON.parse(id.dataset.options);
      dispatch(actions.setOptions(options));
    }

    if (lc_data.notifications_interval > 0) {
      setInterval(() => {
        getNotifications(lc_data.business_id);
      }, parseInt(lc_data.notifications_interval, 10) * 1000);
    }

    return () => {
      ignore = true;
    };
  }, []);

  useEffect(() => {
    const path = window.location.pathname;
    const mainIcon = { icon: HomeIcon, color: 'fill-red-500', title: lc_data.jst[107] };
    if (path.indexOf('/my-account/ads') > 0) {
      dispatch(setMainIcon({ icon: BoltIcon, color: 'fill-blue-700', title: lc_data.jst[24] }));
    } else if (path.indexOf('notifications') > 0) {
      dispatch(setMainIcon({ icon: NotificationIcon, color: 'fill-green-700', title: lc_data.jst[232] }));
    } else if (path.indexOf('packages') > 0) {
      dispatch(setMainIcon({ icon: PackageIcon, color: 'fill-yellow-700', title: lc_data.jst[133] }));
    } else if (path.indexOf('submit') > 0) {
      dispatch(setMainIcon({ icon: BoltIcon, color: 'fill-green-700', title: lc_data.jst[116] }));
    } else if (path.indexOf('business') > 0) {
      dispatch(setMainIcon({ icon: JobIcon, color: 'fill-orange-700', title: lc_data.jst[301] }));
    } else if (path.indexOf('premium-profile') > 0) {
      dispatch(setMainIcon({ icon: CrownIcon, color: 'fill-green-700', title: lc_data.jst[146] }));
    } else if (path.indexOf('earnings') > 0) {
      dispatch(setMainIcon({ icon: EarningsIcon, color: 'fill-red-400', title: lc_data.jst[657] }));
    } else if (path.indexOf('commissions') > 0) {
      dispatch(setMainIcon({ icon: EarningsIcon, color: 'fill-red-400', title: lc_data.jst[714] }));
    } else if (path.indexOf('account-edit') > 0) {
      dispatch(setMainIcon({ icon: PersonIcon, color: 'fill-grey-700', title: lc_data.jst[303] }));
    } else if (path.indexOf('subscriptions') > 0) {
      dispatch(setMainIcon({ icon: BookmarkIcon, color: 'fill-grey-700', title: lc_data.jst[635] }));
    } else if (path.indexOf('account-orders') > 0) {
      dispatch(setMainIcon({ icon: CartIcon, color: 'fill-grey-700', title: lc_data.jst[257] }));
    } else if (path.indexOf('account-downloads') > 0) {
      dispatch(setMainIcon({ icon: DownloadIcon, color: 'fill-red-500', title: lc_data.jst[304] }));
    } else {
      dispatch(setMainIcon(mainIcon));
    }

    if (lc_data?.key) {
      if (lc_data.key.is_valid && lc_data.key.license_key === lc_data.l && lc_data.key.domain === lc_data.domain && lc_data.key.next_request && !lc_data.key.license_title.includes('Lifetime')) {
        setKey(true);
      }
    } else {
      window.location.href = lc_data.domain + '/wp-admin/admin.php?page=lisfinity-core';
    }
  }, []);

  return (
    lc_data.user_has_business &&
    <Fragment>
      {
        loading && <LoaderDashboard/>
      }
      {
        !loading &&
        <Fragment>
          <Router>
            <Header/>
            {!error && key &&
            <Content/>
            }
          </Router>
          <ToastContainer/>
        </Fragment>
      }
      {error &&
      <div
        className="relative p-40 bg-white"
        style={{
          top: '85px',
        }}
      >
        <span
          className="font-semibold text-lg text-grey-500"
        >
        {error}
        </span>
      </div>
      }
    </Fragment>
  );
};

export default Dashboard;
