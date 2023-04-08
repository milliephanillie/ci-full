/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef, useState, useEffect } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import { map, isEmpty, filter } from 'lodash';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import ReloadIcon from '../../../../../../../../images/icons/reload.svg';
import SearchIcon from '../../../../../../../../images/icons/search.svg';
import axios from 'axios';

const BannedUsers = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, product } = data;
  const currentUser = lc_data.current_user_id;
  const [banned, setBanned] = useState(business.banned);

  const unbanUser = (index, user_id) => {
    if (!confirm(lc_data.jst[179])) {
      return false;
    }
    const newBanned = filter(banned, (ban, i) => index !== i);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    }
    const url = `${lc_data.user_action}/unblock`;
    let data = {
      user_id: lc_data.current_user_id,
      user_to_unblock: user_id,
    };
    axios({
      credentials: 'same-origin',
      method: 'post',
      url,
      data,
      headers: headers,
    }).then(data => {
      if (data.data.success) {
        setBanned(newBanned);
      }
    });
  }

  const filterUsers = (value) => {
    if (value !== '') {
      setBanned(filter(banned, user => user.title.toLowerCase().includes(value.toLowerCase())));
    } else {
      setBanned(business.banned);
    }
  };

  const mapBanned = !isEmpty(banned) ? banned : business.banned;

  return (
    <Fragment>
      {!isEmpty(mapBanned) &&
      <div key={0} className="bids-container flex flex-col p-30 bg-white rounded theme-shadow">

        <div className="bids--header flex flex-wrap justify-between items-end">

          <div className="products--find flex items-center p-20 h-44 bg-grey-100 border border-grey-300 rounded">
            <ReactSVG
              src={`${lc_data.dir}dist/${SearchIcon}`}
              className="relative top-2 mr-10 w-20 h-20 fill-grey-700"
            />
            <input
              type="text"
              className="w-full bg-transparent font-semibold text-grey-900"
              placeholder={lc_data.jst[180]}
              onChange={e => filterUsers(e.target.value)}
            />
          </div>

        </div>

        <div className="bids flex flex-wrap mt-30 -mb-16 -mx-6">
          {map(mapBanned, (ban, index) => {
            return (
              <div
                key={index}
                className="dashboard--banned mb-16 px-6"
              >
                <div
                  className="bid relative flex py-20 px-10 pr-20 bg-grey-100 rounded"
                >
                  {ban.thumbnail &&
                  <figure className="relative flex mr-16 border-4 border-white"
                          style={{ minWidth: '50px', height: '50px', borderRadius: '16px' }}>
                    <img src={ban.thumbnail} alt={ban.title} style={{ borderRadius: '16px' }}
                         className="absolute top-0 left-0 w-full h-full object-cover"/>
                  </figure>}

                  <div className="bid--info flex flex-col justify-center w-full">
                    <p className="font-bold text-grey-1100">{ban.title}</p>
                  </div>

                  <button
                    type="button"
                    className="absolute top-10 right-10"
                    onClick={() => unbanUser(index, ban.ID)}
                  >
                    <ReactSVG
                      src={`${lc_data.dir}dist/${ReloadIcon}`}
                      className="relative top-2 w-16 h-16 fill-green-800"
                    />
                  </button>

                </div>
              </div>
            );
          })}
        </div>
      </div>}
      {isEmpty(mapBanned) &&
      <div key={0}
           className="bids-container flex flex-col p-30 bg-white rounded theme-shadow font-bold text-2xl text-grey-900">
        {lc_data.jst[178]}
      </div>}
    </Fragment>
  );
}

export default BannedUsers;
