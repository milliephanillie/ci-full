/* global lc_data, React */
/**
 * Dependencies.
 */
import { NavLink } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import { sprintf, __ } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import Checkbox from '../../../../../../theme/packages/components/form-fields/checkbox/Checkbox';
import { Fragment, useEffect, useRef, useState } from '@wordpress/element';
import produce from 'immer';
import { formatMoney } from '../../../../../../theme/vendor/functions';
import ReactTooltip from 'react-tooltip';
import axios from 'axios';
import * as actions from '../../../../store/actions';
import posed, { PoseGroup } from 'react-pose';
import cx from 'classnames';
import ClockIcon from '../../../../../../../images/icons/alarm-clock.svg';
import EnvelopeIcon from '../../../../../../../images/icons/envelope.svg';
import HammerIcon from '../../../../../../../images/icons/construction-hammer.svg';
import EyeIcon from '../../../../../../../images/icons/eye.svg';
import TrashIcon from '../../../../../../../images/icons/trash.svg';
import HeartIcon from '../../../../../../../images/icons/heart.svg';
import BookmarkIcon from '../../../../../../../images/icons/bookmark.svg';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import he from 'he';

const Notifications = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, notifications } = data;
  const [marked, setMarked] = useState([]);
  const [modalOpen, setModalOpen] = useState(false);
  const Notification = posed.div({
    enter: { y: 0, opacity: 1 },
    exit: { y: 100, opacity: 0, transition: 200 },
  });

  const markAsRead = (id, parentId, productId, businessId, type, created_at) => {
    if (!confirm(lc_data.jst[159])) {
      return false;
    }
    if (lc_data.is_demo) {
      //setModalOpen(true);
      //return false;
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
    });
  };

  const markAllAsRead = () => {
    if (!confirm(lc_data.jst[160])) {
      return false;
    }
    if (lc_data.is_demo) {
      //setModalOpen(true);
      //return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.update_all_notifications}/mark_selected_as_read`;
    const formData = [];
    map(notifications, notification => {
      if (marked.includes(notification.id)) {
        formData.push(notification);
      }
    });
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: {
        notifications: formData,
        business: business.business.ID,
      },
    }).then(response => {
      dispatch(actions.setNotifications(response.data));
    });
  };

  const btnClass = cx({
    'bg-grey-100 rounded font-semibold text-grey-400 cursor-default': isEmpty(marked),
    'bg-blue-700 rounded font-semibold text-white hover:bg-blue-800': !isEmpty(marked),
  });
  return (
    <section className="notifications flex flex-wrap">
      {isEmpty(notifications) &&
      <div
        className="modal--no-content flex-center bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[161]}</div>}
      {!isEmpty(notifications) &&
      <Fragment>
        <div className="products--filter flex flex-grow-0 py-20 px-30 w-full bg-white rounded shadow-theme">

          <div className="relative top-10 mr-20">
            <Checkbox label={lc_data.jst[151]} id={`markAll`} name={`markAll`}
                      checked={marked.length === notifications.length} options={{
              onChange: (e) => {
                setMarked(produce(marked, draft => {
                  if (draft.length === notifications.length) {
                    draft.length = 0;
                  } else {
                    draft.length = 0;
                    map(notifications, notification => {
                      draft.push(notification.id);
                    });
                  }
                }));
              },
            }}/>
          </div>

          <button
            type="button"
            onClick={markAllAsRead}
            className={`flex-center py-8 px-30 ${btnClass}`}
            disabled={isEmpty(marked)}
          >
            {lc_data.jst[152]}
          </button>

        </div>

        <div className="flex flex-col mt-20 w-full">
          <PoseGroup>
            {map(notifications, notification => {
              let endpoint = '';
              if (notification.parent_type_human === 'message') {
                endpoint = 'messages';
              } else if (notification.parent_type_human === 'bid') {
                endpoint = 'bids';
              }
              let icon = EnvelopeIcon;
              if (notification.parent_type_human === 'message') {
                icon = EnvelopeIcon;
              } else if (notification.parent_type_human === 'bid') {
                icon = HammerIcon;
              } else if (notification.parent_type_human === 'like') {
                icon = HeartIcon;
              } else if (notification.parent_type_human === 'bookmark') {
                icon = BookmarkIcon;
              }
              return (
                notification && notification.data &&
                <Notification key={notification.id}
                              className="notification--element flex mt-20 p-30 w-full bg-white rounded shadow-theme">

                  <div className="flex flex-col w-full">

                    <div className="flex flex-wrap items-center justify-between mb-20 w-full">

                      <div className="flex items-center">

                        <div className="relative top-8 mr-20">
                          <Checkbox id={`read-${notification.id}`} name={`read-${notification.id}`}
                                    checked={marked.includes(notification.id)} options={{
                            onChange: (e) => {
                              setMarked(produce(marked, draft => {
                                if (marked.includes(notification.id)) {
                                  draft.splice(marked.indexOf(notification.id), 1);
                                } else {
                                  draft.push(notification.id);
                                }
                              }));
                            },
                          }}/>
                        </div>

                        {notification && notification.data && notification.data.thumbnail &&
                        <figure
                          className="relative mr-20 border-2 border-grey-200 rounded-full shadow-theme overflow-hidden"
                          style={{ height: '36px', width: '36px', minWidth: '36px' }}>
                          <img src={notification.data.thumbnail}
                               alt={notification.data.title && notification.data.title}
                               className="absolute top-0 left-0 w-full h-full object-cover"/>
                        </figure>
                        }
                        {notification && notification.data && notification.data.post_title &&
                        <span className="font-bold text-grey-600">{notification.data.post_title}</span>
                        }
                      </div>

                      <div className="flex flex-wrap sm:flex-no-wrap items-center">

                        <div className="flex items-center mr-40">
                          <div className="flex-center whitespace-no-wrap">
                            <ReactSVG
                              src={`${lc_data.dir}dist/${ClockIcon}`}
                              className="relative mr-8 min-w-14 h-14 fill-grey-500"
                            />
                            <span
                              className="text-grey-500">{sprintf(lc_data.jst[153], notification.created_human)}</span>
                          </div>
                          <div className="flex-center ml-20">
                            <ReactSVG
                              src={`${lc_data.dir}dist/${icon}`}
                              className="relative mr-8 w-14 h-14 fill-grey-500"
                            />
                            <span className="text-grey-500">{notification.parent_type_title}</span>
                          </div>
                        </div>

                        <div className="product--actions flex items-center mt-10 sm:mt-0 w-1/16">

                          {endpoint !== '' &&
                          <NavLink
                            to={`${lc_data.site_url}${lc_data.myaccount}ad/${notification.product_id}/${endpoint}`}
                            className="flex-center mr-10 min-w-40 w-40 h-40 bg-yellow-300 rounded-full">
                            <ReactSVG
                              src={`${lc_data.dir}dist/${EyeIcon}`}
                              className="w-16 h-16 fill-grey-900"
                            />
                          </NavLink>}

                          <button
                            className="flex-center min-w-40 w-40 h-40 bg-red-200 rounded-full"
                            data-tip={lc_data.jst[168]}
                            onClick={e => markAsRead(notification.id, notification.parent_id, notification.product_id, notification.business_id, notification.parent_type, notification.created_at)}
                          >
                            <ReactSVG
                              src={`${lc_data.dir}dist/${TrashIcon}`}
                              className="w-16 h-16 fill-grey-900"
                            />
                          </button>
                          <ReactTooltip delayShow={400}/>

                        </div>

                      </div>

                    </div>

                    <div>
                      {notification.parent_type_human === 'message' &&
                      <div>
                        <h6 className="mb-10 font-bold text-grey-900">
                          <a href={notification?.data?.permalink} className="font-bold text-grey-600">
                            {he.decode(notification.data.product_title)}
                          </a>
                        </h6>
                        <p className="text-grey-900">{he.decode(notification.data.message)}</p>
                      </div>}
                      {notification.parent_type_human === 'bid' &&
                      <div>
                        <h6 className="mb-10 font-bold text-grey-900">
                          <a href={notification?.data?.permalink}>
                            {he.decode(notification.data.product_title)}
                          </a>
                        </h6>
                        {notification?.data?.message &&
                        <p className="mb-10 text-lg text-grey-700">{he.decode(notification.data.message)}</p>}
                        <p
                          className="inline-block py-4 px-16 bg-red-500 rounded font-bold text-xl text-white"
                          dangerouslySetInnerHTML={{
                            __html: sprintf(notification.data.price_format, notification.data.currency, formatMoney(notification.data.amount, notification.data.decimals, notification.data.decimals_separator, notification.data.thousands_separator))
                          }}
                        ></p>
                      </div>}
                      {notification.parent_type_human === 'report' &&
                      <div>
                        <h6 className="mb-10 font-bold text-grey-900">
                          <a href={notification?.data?.permalink}>
                            {he.decode(notification.data.product_title)}
                          </a>
                        </h6>
                        <p className="text-grey-900">{lc_data.jst[155]}</p>
                      </div>
                      }
                      {notification.parent_type_human === 'bookmark' &&
                      <div>
                        <h6 className="mb-10 font-bold text-grey-900">
                          <a href={notification?.data?.permalink}>
                            {he.decode(notification.data.product_title)}
                          </a>
                        </h6>
                        <p className="text-grey-900">{lc_data.jst[156]}</p>
                      </div>
                      }
                      {notification.parent_type_human === 'like' &&
                      <div>
                        <h6 className="mb-10 font-bold text-grey-900">
                          <a href={notification?.data?.permalink}>
                            {he.decode(notification?.data.product_title)}
                          </a>
                        </h6>
                        <p className="text-grey-900">{lc_data.jst[157]}</p>
                      </div>
                      }
                      {notification.parent_type_human === 'share' &&
                      <div>
                        <h6 className="mb-10 font-bold text-grey-900">{he.decode(notification.data.product_title)}</h6>
                        <p
                          className="text-grey-900">{sprintf(lc_data.jst[158], notification.data.share_type)}</p>
                      </div>
                      }
                    </div>

                  </div>

                </Notification>
              );
            })}
          </PoseGroup>
        </div>
      </Fragment>
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

    </section>
  );
};

export default Notifications;
