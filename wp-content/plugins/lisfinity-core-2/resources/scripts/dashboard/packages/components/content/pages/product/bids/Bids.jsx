/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef, useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { map, isEmpty, sortBy, reverse, filter } from 'lodash';
import ReactSVG from 'react-svg';
import { sprintf, __ } from '@wordpress/i18n';
import sortIcon from '../../../../../../../../images/icons/sort-amount-asc.svg';
import Select from 'react-select';
import SearchIcon from '../../../../../../../../images/icons/search.svg';
import ClockIcon from '../../../../../../../../images/icons/alarm-clock.svg';

const Bids = (props) => {
  const { productId, product } = props;
  const [owner, setOwner] = useState(false);
  const [bids, setBids] = useState({});
  const [contactInfo, setContactInfo] = useState(0);
  const [filteredBids, setFilteredBids] = useState(bids);
  const currentUser = lc_data.current_user_id;
  const sortOptions = [
    { value: 'new', label: lc_data.jst[184] },
    { value: 'old', label: lc_data.jst[185] },
    { value: 'amount', label: lc_data.jst[186] },
    { value: 'amount_asc', label: lc_data.jst[187] },
  ];

  const sortBids = (value) => {
    switch (value) {
      case 'new':
        setBids(reverse(sortBy(bids, [bid => bid['created_at']])));
        break;
      case 'old':
        setBids(sortBy(bids, [bid => bid['created_at']]));
        break;
      case 'amount_asc':
        setBids(sortBy(bids, [bid => bid['amount']]));
        break;
      case 'amount':
        setBids(reverse(sortBy(bids, [bid => bid['amount']])));
        break;
    }
  };

  /**
   * Load bids
   */
  const getBids = () => {
    const url = `${lc_data.bids}${productId}/?owner=${currentUser}&_wpnonce=${lc_data.nonce}`;
    apiFetch({ path: url }).then(bids => setBids(bids));
  };

  const handleMarkAsRead = () => {
    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('product', productId);
    formData.append('type', 'status');
    formData.append('page', 'bids');

    fetch(lc_data.update_bid, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
      if (response.success) {
      }
    }));
  };

  useEffect(() => {
    if (product.product_owner == lc_data.current_user_id) {
      setOwner(true);
    }
    getBids();
    handleMarkAsRead();
  }, []);

  const filterBids = (value) => {
    if (value !== '') {
      setFilteredBids(filter(bids, bid => bid.post_title.toLowerCase().includes(value.toLowerCase())));
    } else {
      setFilteredBids(bids);
    }
  };

  const mapBids = !isEmpty(filteredBids) ? filteredBids : bids;

  return (
    <Fragment>
      {!isEmpty(bids) &&
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
              placeholder={lc_data.jst[181]}
              onChange={e => filterBids(e.target.value)}
            />
          </div>

          <div className="products--sort flex items-center" style={{ width: '220px' }}>
            <label htmlFor="sortby" className="flex items-center text-sm text-grey-500" style={{ width: '140px' }}>
              <ReactSVG src={`${lc_data.dir}dist/${sortIcon}`} className="mr-10 ml-10 w-18 h-18 fill-grey-700"/>
              {lc_data.jst[182]}
            </label>
            <Select
              options={sortOptions}
              isSearchable={false}
              defaultValue={sortOptions[0]}
              className="select-transparent w-full"
              onChange={selected => sortBids(selected.value)}
            />
          </div>

        </div>

        <div className="bids flex flex-wrap mt-30 -mb-16 -mx-6">
          {map(mapBids, (bid, index) => {
            return (
              <div
                key={index}
                className="dashboard--bids mb-16 px-6"
              >
                {(0 === contactInfo || bid.id !== contactInfo) &&
                <div
                  className="bid relative flex py-20 px-10 pr-20 bg-grey-100 rounded"
                >
                  {bid.thumbnail &&
                  <figure className="relative flex mr-16 border-4 border-white"
                          style={{ minWidth: '50px', height: '50px', borderRadius: '16px' }}>
                    <img src={bid.thumbnail} alt={bid.post_tite} style={{ borderRadius: '16px' }}
                         className="absolute top-0 left-0 w-full h-full object-cover"/>
                    {bid.status !== 'seen' &&
                    <span className="marker--online absolute w-10 h-10 bg-green-800 rounded-full"
                          style={{ top: '-4px', right: '-4px' }}></span>
                    }
                  </figure>}

                  <div className="bid--info flex flex-col w-full">
                    <p className="font-bold text-grey-1100">{bid.post_title}</p>
                    {!isEmpty(bid?.message) &&
                    <p>{bid.message}</p>
                    }
                    <div className="flex flex-wrap justify-between mt-10">
                      <span className="text-grey-1000" dangerouslySetInnerHTML={{ __html: bid.amount_html }}></span>
                      <div className="text-sm text-grey-700">
                        <div className="flex-center">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${ClockIcon}`}
                            className="mr-4 w-16 h-16 fill-grey-700"
                          />
                          {sprintf(lc_data.jst[153], bid.created_human)}
                        </div>
                      </div>
                    </div>
                  </div>

                  <button type="button" className="bid--contact absolute top-16 right-20 text-sm text-grey-500"
                          onClick={() => setContactInfo(bid.id)}
                  >{lc_data.jst[689]}</button>

                </div>
                }
                {bid.id === contactInfo &&
                <div
                  className="bid relative flex flex-col p-20 bg-grey-100 rounded"
                >
                  {!bid.email && <a href={`mailto:${bid.user_email}`}>{bid.user_email}</a>}
                  {bid?.email &&
                  <a href={`mailto:${bid.email}`}>{bid.email}</a>
                  }
                  {bid?.phones &&
                  map(bid.phones, (phone, key) => {
                    return (<a key={key} href={`tel:${phone['profile-phone']}`}>{phone['profile-phone']}</a>);
                  })
                  }
                  <button type="button" className="bid--contact absolute top-16 right-20 text-sm text-grey-500"
                          onClick={() => setContactInfo(0)}
                  >X
                  </button>
                </div>
                }
              </div>
            );
          })}
        </div>
      </div>
      }
      {isEmpty(bids) &&
      <div
        className="bids-container flex flex-col p-30 bg-white rounded theme-shadow font-bold text-2xl text-grey-900">
        {lc_data.jst[183]}
      </div>}
    </Fragment>
  );
};

export default Bids;
