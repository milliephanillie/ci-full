/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { isEmpty, map } from 'lodash';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import ProductStyle3 from '../../../product-box/product-styles/ProductStyle3';

function Similar(props) {
  const { product } = props;
  const { advertisements } = product;

  return (
    !isEmpty(advertisements) &&
    <section id="similar--products similiar-products-wrapper-custom" className="py-40 bg-grey-100 px-10 xs:px-20 sm:px-40 lg:px-100 lg:py-60">
      <div className="container">
        <div className="mb-30 font-bold text-grey-1000 text-2xl">{lc_data.jst[538]}</div>
        <div className="row -mb-30 md:mb-0">
          {map(advertisements, (advertisement, index) => {
            return index !== 0 &&
              <div key={index} className="w-full px-8 mb-30 sm:w-1/2 bg:px-16 bg:w-1/3">
                <ProductStyle3 productClasses="h-full" product={advertisement} options={props.options} imageClasses="h-product-2-thumb"/>
              </div>;
          })}
        </div>
      </div>
    </section>
  );
}

export default Similar;
