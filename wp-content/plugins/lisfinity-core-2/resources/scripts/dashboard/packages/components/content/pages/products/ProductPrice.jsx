/* global lc_data, React */
/**
 * External dependencies.
 */
import { Fragment } from 'react';
import { useState, useEffect } from 'react';
import Auction from './price-types/Auction';
import Sale from './price-types/Sale';
import Fixed from './price-types/Fixed';
import Free from './price-types/Free';
import Negotiable from './price-types/Negotiable';
import OnCall from './price-types/OnCall';
import Rentable from './price-types/Rentable';

const ProductPrice = (props) => {
  const { product, style } = props;

  function loadPriceType() {
    switch (product.price_type) {
      case 'auction':
        return <Auction product={product} style={style}/>;
      case 'on-sale':
        return <Sale product={product} style={style}/>;
      case 'fixed':
        return <Fixed product={product} style={style}/>;
      case 'free':
        return <Free roduct={product} style={style}/>;
      case 'negotiable':
        return <Negotiable roduct={product} style={style}/>;
      case 'price_on_call':
        return <OnCall product={product} style={style}/>;
      case 'per_week':
      case 'per_month':
      case 'per_day':
      case 'per_hour':
        return <Rentable product={product} type={product.price_type} />;
      default:
        return <Fixed product={product} style={style}/>
    }
  }

  return (
    <Fragment>{loadPriceType()}</Fragment>
  );
};

export default ProductPrice;
