/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import { sprintf, __ } from '@wordpress/i18n';
import { map } from 'lodash';
import MessageIcon from '../../../../../images/icons/envelope.svg';
import ShareIcon from '../../../../../images/icons/share.svg';
import HammerIcon from '../../../../../images/icons/construction-hammer.svg';
import BookmarkIcon from '../../../../../images/icons/bookmark.svg';
import LikeIcon from '../../../../../images/icons/heart.svg';
import CloseIcon from '../../../../../images/icons/close.svg';
import { formatMoney } from '../../../../theme/vendor/functions';
import { useState } from '@wordpress/element';
import cx from 'classnames';
import axios from 'axios';
import * as actions from '../../store/actions';
import { Scrollbars } from 'react-custom-scrollbars';
import onClickOutside from 'react-onclickoutside';
import ModalDemo from '../../../../theme/packages/components/modal/ModalDemo';
import he from 'he';

const HeaderNotifications = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, notifications, notificationsMenu } = data;
  const [active, setActive] = useState(0);
  const [modalOpen, setModalOpen] = useState(false);

  HeaderNotifications.handleClickOutside = () => handleClickOutside;

  const handleClickOutside = (e) => {
    if (e.target.classList.contains('user--notifications')) {
      return false;
    }
    dispatch(actions.setNotificationsMenu(false));
  };

  const markAsRead = (id, parentId, productId, businessId, type, created_at) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.update_notifications}/mark_as_read`;
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: {
        id,
        parent: parentId,
        product: productId,
        business: businessId,
        type,
        created_at,
      },
    }).then(response => {
      dispatch(actions.setNotifications(response.data));
      dispatch(actions.setNotificationsMenu(true));
    });
  };
  return (
    <div className="header-notifications">
      {notificationsMenu &&
      <ul
        className={`header-notification absolute flex flex-col p-10 bg-white rounded ${props.default ? 'bg-white' : 'shadow-theme'}`}
        style={{
          top: props.default ? '38px' : '70px',
          right: props.default && !lc_data.rtl ? '30px' : props.default && lc_data.rtl === "" ? '30px' : '30px',
          left: lc_data.rtl ? '30px' : 'auto',
          width: notifications.length === 0 ? '240px' : '400px',
        }}>
        <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight
                    autoHeightMin={notifications.length > 2 ? 340 : (notifications.length === 2 ? 225 : (notifications.length ? 112 : 20))}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
          {notifications.length === 0 &&
          <li className="flex p-20 bg-grey-100 rounded">
            {lc_data.jst[314]}
          </li>
          }
          {notifications.length > 0 && map(notifications, notification => {
            const activeClass = cx({
              'bg-red-100': active === notification.id,
              'bg-grey-100': active !== notification.id,
            });
            return (
              notification && notification.data &&
              <li
                key={notification.id}
                className={`flex relative p-20 mb-1 ${activeClass}`}
                onMouseEnter={() => setActive(notification.id)}
                onMouseLeave={() => setActive(null)}
              >

                <button
                  className="notification--action absolute top-20 right-20"
                  onClick={() => markAsRead(notification.id, notification.parent_id, notification.product_id, notification.business_id, notification.parent_type, notification.created_at)}
                >
                  <ReactSVG
                    src={`${lc_data.dir}dist/${CloseIcon}`}
                    className="relative w-14 h-14 fill-grey-700"
                    style={{ top: '-2px' }}
                  />
                </button>

                {notification.data.thumbnail &&
                <figure
                  className="relative mr-10 border-white overflow-hidden"
                  style={{
                    width: '55px',
                    minWidth: '55px',
                    height: '50px',
                    borderWidth: '5px',
                    borderRadius: '16px',
                  }}
                >
                  <img src={notification.data.thumbnail} alt={notification.data.post_title}
                       className="absolute top-0 left-0 w-full h-full object-cover"/>
                </figure>
                }
                <div className="header-notification--main flex flex-col w-full">

                  {(notification.parent_type_human === 'message' || notification.parent_type_human === 'bid' || notification.parent_type_human === 'bookmark') &&
                  <h6
                    className="font-bold text-base text-grey-1000 leading-snug ellipsis">{he.decode(notification.data.post_title)}</h6>
                  }
                  {notification.parent_type_human === 'like' &&
                  <h6
                    className="font-bold text-base text-grey-1000 leading-snug ellipsis">{he.decode(notification.data.product_title)}</h6>
                  }

                  {notification.parent_type_human === 'message' &&
                  <div
                    className="header-notification--content mt-6 font-light text-grey-900 leading-normal ellipsis">{he.decode(notification.data.message)}</div>
                  }
                  {notification.parent_type_human === 'bid' &&
                  <div
                    className="header-notification--content mt-6 font-light text-grey-900 leading-normal ellipsis"
                  >
                  <span className="font-bold text-grey-900"
                        dangerouslySetInnerHTML={{
                          __html: sprintf(`${notification.data.price_format} - `,
                            notification.data.currency,
                            formatMoney(notification.data.amount, notification.data.decimals, notification.data.decimal_separator, notification.data.thousand_separator)
                          )
                        }}
                  >
                  </span>
                    <span className="font-light text-grey-900">{he.decode(notification.data.product_title)}</span>
                  </div>
                  }
                  {notification.parent_type_human === 'like' &&
                  <span
                    className="mt-6 font-light text-grey-900">{lc_data.jst[310]}</span>
                  }
                  {notification.parent_type_human === 'bookmark' &&
                  <div className="block mt-6 ellipsis">
                    <span className="font-bold text-grey-900">{lc_data.jst[311]}</span>
                    <span
                      className="font-light text-grey-900">{sprintf(' - %s', he.decode(notification.data.product_title))}</span>
                  </div>
                  }

                  <div className="header-notification--meta flex items-center justify-between mt-8">
                    {notification.parent_type_human === 'message' &&
                    <div className="flex items-center">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${MessageIcon}`}
                        className="mr-6 w-16 h-16 fill-grey-400"
                      />
                      <span
                        className="time font-semibold text-sm text-grey-500">{sprintf(lc_data.jst[153], notification.created_human)}</span>
                    </div>
                    }
                    {notification.parent_type_human === 'bid' &&
                    <div className="flex items-center">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${HammerIcon}`}
                        className="mr-6 w-16 h-16 fill-grey-400"
                      />
                      <span
                        className="time font-semibold text-sm text-grey-500">{sprintf(lc_data.jst[153], notification.created_human)}</span>
                    </div>
                    }
                    {notification.parent_type_human === 'bookmark' &&
                    <div className="flex items-center">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${BookmarkIcon}`}
                        className="mr-6 w-16 h-16 fill-grey-400"
                      />
                      <span
                        className="time font-semibold text-sm text-grey-500">{sprintf(lc_data.jst[153], notification.created_human)}</span>
                    </div>
                    }
                    {notification.parent_type_human === 'like' &&
                    <div className="flex items-center">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${LikeIcon}`}
                        className="mr-6 w-16 h-16 fill-grey-400"
                      />
                      <span
                        className="time font-semibold text-sm text-grey-500">{sprintf(lc_data.jst[153], notification.created_human)}</span>
                    </div>
                    }

                    {notification.parent_type_human === 'message' &&
                    <a
                      href={`${lc_data.site_url}${lc_data.myaccount}ad/${notification.data.product_id}/messages`}
                      className="flex items-center font-bold text-blue-700"
                    >
                      {lc_data.jst[312]}
                      <ReactSVG
                        src={`${lc_data.dir}dist/${ShareIcon}`}
                        className="relative ml-10 w-14 h-14 fill-blue-700"
                        style={{ top: '-2px' }}
                      />
                    </a>
                    }
                    {notification.parent_type_human === 'bid' &&
                    <a
                      href={`${lc_data.site_url}${lc_data.myaccount}ad/${notification.data.product_id}/bids`}
                      className="flex items-center font-bold text-blue-700"
                    >
                      {lc_data.jst[313]}
                      <ReactSVG
                        src={`${lc_data.dir}dist/${ShareIcon}`}
                        className="relative ml-10 w-14 h-14 fill-blue-700"
                        style={{ top: '-2px' }}
                      />
                    </a>
                    }

                  </div>

                </div>
              </li>
            );
          })}
        </Scrollbars>
      </ul>
      }
      <ModalDemo
        key={41}
        isLogged={lc_data.logged_in}
        open={modalOpen}
        closeModal={() => setModalOpen(true)}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>
    </div>
  );
};

const clickOutsideConfig = {
  handleClickOutside: () => HeaderNotifications.handleClickOutside(),
};

export default onClickOutside(HeaderNotifications, clickOutsideConfig);
