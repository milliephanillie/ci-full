/* global lc_data, React */
/**
 * External dependencies.
 */
import { Fragment } from 'react';
import { useState, useEffect } from 'react';
import Auction from '../price-types/Auction';
import Sale from '../price-types/Sale';
import Fixed from '../price-types/Fixed';
import Free from '../price-types/Free';
import Negotiable from '../price-types/Negotiable';
import OnCall from '../price-types/OnCall';
import { __ } from '@wordpress/i18n';
import Rentable from '../price-types/Rentable';

function ProductPrice(props) {
  const { product, currentUser, fromSticky, iconSettings, options } = props;

  const handleBuyNow = () => {
    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('sell_on_site', 'yes');
    formData.append('product', product.ID);
    fetch(lc_data.buy_bid, {
      method: 'POST',
      credentials: 'same-origin',
      //headers,
      body: formData,
    }).then(json => json.json()
      .then(response => {
        if (response.success) {
          window.location.href = response.permalink;
        }
      }));
  };

  function loadPriceType() {
    switch (product?.product_meta?.price_type) {
      case 'auction':
        return <Auction product={product} currentUser={currentUser} fromSticky={fromSticky} options={props.options}
                        iconSettings={iconSettings}/>;
      case 'on-sale':
        return <Sale product={product} currentUser={currentUser} handleBuyNow={() => handleBuyNow()}
                     options={props.options}/>;
      case 'fixed':
        return <Fixed product={product} currentUser={currentUser} handleBuyNow={() => handleBuyNow()}
                      options={props.options}/>;
      case 'free':
        return <Free product={product} iconSettings={iconSettings} currentUser={currentUser} options={props.options}/>;
      case 'negotiable':
        return <Negotiable product={product} iconSettings={iconSettings} currentUser={currentUser}
                           handleBuyNow={() => handleBuyNow()} options={props.options}/>;
      case 'price_on_call':
        return <OnCall product={product} iconSettings={iconSettings} currentUser={currentUser}
                       options={props.options}/>;
      case 'per_week':
      case 'per_month':
      case 'per_day':
      case 'per_hour':
        return <Rentable product={product} currentUser={currentUser} type={product.product_meta.price_type}
                         options={props.options}/>;
      default:
        return <Fixed product={product} currentUser={currentUser} options={props.options} handleBuyNow={() => handleBuyNow()}/>;
    }
  }

  return (
    <Fragment>{product.post_status !== 'sold' ? loadPriceType() : <div
      className="px-16 py-4 bg-grey-100 rounded font-bold">{lc_data.jst[694]}</div>}</Fragment>
  );
}

export default ProductPrice;
