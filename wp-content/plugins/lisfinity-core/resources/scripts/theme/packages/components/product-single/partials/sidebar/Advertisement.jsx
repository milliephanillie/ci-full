/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { isEmpty } from 'lodash';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import ProductStyle1 from '../../../product-box/product-styles/ProductStyle1';

function Advertisement(props) {
  const { product, is_ad, options} = props;
  const { advertisements } = product;

  return (
    !isEmpty(advertisements) &&
    <Fragment>
      <div className="mb-10 px-20 text-grey-300 text-sm">{lc_data.jst[518]}</div>
      <ProductStyle1 product={advertisements[0]} options={props.options} heightClass="h-product-2-thumb" is_ad={is_ad}/>
    </Fragment>
  );
}

export default Advertisement;
