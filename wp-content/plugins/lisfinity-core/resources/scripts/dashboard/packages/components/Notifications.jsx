/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { map, isEmpty, forEach } from 'lodash';
import apiFetch from '@wordpress/api-fetch';

class Notifications extends Component {
  constructor(props) {
    super(props);
    this.state = {
      notifications: {},
    };

    this.handleMarkAsRead = this.handleMarkAsRead.bind(this);
  }

  componentWillMount() {
    this.getNotifications();
  }

  getNotifications() {
    const url = `${lc_data.notifications}/${lc_data.current_user_id}/?_wpnonce=${lc_data.nonce}`;
    apiFetch({ path: url }).then(notifications => this.setState({ notifications }));
  }

  handleMarkAsRead(e, type, id) {
    e.preventDefault();
    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('id', id);
    formData.append('type', 'status');

    fetch(lc_data.message_update, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
      if (response.success) {
        this.getNotifications();
      }
    }));
  }

  handleReadBid(e, id) {
    e.preventDefault();
    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('id', id);
    formData.append('type', 'status');

    fetch(lc_data.update_bid, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
      if (response.success) {
        this.getNotifications();
      }
    }));
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { notifications } = this.state;
    return [
      !isEmpty(notifications)
        ?
        map(notifications, (notificationGroup) => {
          return map(notificationGroup, (notification, index) => {
            let messageUrl = `/${lc_data.myaccount}products/${notification.product_id}/messages`;
            if (notification.type === 'bid') {
              messageUrl = `/${lc_data.myaccount}products/${notification.product_id}/bids`;
            }
            return (
              <div key={index} className="notification mt-2 rounded shadow">

                <div className="notification--header flex items-center">
                  <span className="notification--header__time flex">
                    <i className="material-icons">watch_later</i>
                    <span>{notification.created_at}</span>
                  </span>
                  <span className="notification--header__author flex">
                    <img src={notification.avatar} alt="avatar" className="w-8 h-8 rounded-full"/>
                    <span>{notification.display_name}</span>
                  </span>
                </div>

                <div className="notification--content flex justify-between">

                  <div className="notification--content__info">
                    <h4>{notification.product_title}</h4>
                    {notification.type === 'message' &&
                    <p>{notification.message}</p>
                    }
                    {notification.type === 'bid' &&
                    <p>{notification.amount}</p>
                    }
                  </div>

                  <div className="notification--content__actions">
                    <a href={messageUrl}>
                      <i className="material-icons-outlined">remove_red_eye</i>
                      <span>{__('View', 'lisfinity-core')}</span>
                    </a>
                    {notification.type === 'message' &&
                    <button
                      type="button"
                      onClick={e => this.handleMarkAsRead(e, notification.type, notification.chat_id)}
                    >
                      <span>{__('Mark as read', 'lisfinity-core')}</span>
                      <i className="material-icons-outlined">close</i>
                    </button>
                    }
                    {notification.type === 'bid' &&
                    <button
                      type="button"
                      onClick={e => this.handleReadBid(e, notification.id)}
                    >
                      <span>{__('Mark as read', 'lisfinity-core')}</span>
                      <i className="material-icons-outlined">close</i>
                    </button>
                    }
                  </div>

                </div>
              </div>
            );
          });
        })
        :
        <div key={0} className="message-info">{__('No new notifications', 'lisfinity-core')}</div>,
    ];
  }
}

export default Notifications;
