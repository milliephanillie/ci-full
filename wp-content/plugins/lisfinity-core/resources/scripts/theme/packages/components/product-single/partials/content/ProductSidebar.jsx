/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Owner from '../sidebar/Owner';
import Advertisement from '../sidebar/Advertisement';
import SafetyTips from '../sidebar/SafetyTips';
import FinanceCalculator from '../sidebar/FinanceCalculator';
import Calculator from '../sidebar/Calculator';

function ProductSidebar(props) {
  const { product, currentUser, options } = props;

  return (
    <Fragment>

        {product?.post_status != 'sold' &&
            <div className="profile p-20 bg-white rounded shadow-theme product-sidebar-owner">
                <Owner product={product} currentUser={currentUser} options={props.options}/>
            </div>
        }


      {options.calculator_position !== 'content' && product.product_meta.price > 0 && product.calculator && product.calculator.display &&
      <div className="profile--calculator mt-20 py-20 px-20 bg-white rounded shadow-theme">
        <Calculator product={product} currentUser={currentUser}/>
      </div>}

      <FinanceCalculator product={product} currentUser={currentUser} />

      {'0' !== options.safety_tips &&
      <div className="profile--tips mt-20 py-30 px-20 bg-white rounded shadow-theme">
        <SafetyTips product={product} currentUser={currentUser}/>
      </div>
      }

      <div className="product--advertisement mt-20">
        <Advertisement product={product} is_ad={true}/>
      </div>

    </Fragment>
  );
}

export default ProductSidebar;
