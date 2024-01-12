/* global lc_data, React */
/**
 * Dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import Advertisement from '../sidebar/Advertisement';
import SafetyTips from '../sidebar/SafetyTips';
import FinanceCalculator from '../sidebar/FinanceCalculator';
import Calculator from '../sidebar/Calculator';
import OwnerAlt from '../sidebar/OwnerAlt';

function ProductSidebarAlt(props) {
  const { product, currentUser, options } = props;
  return (
    <Fragment>
        {!product?.is_expired && product?.post_status != 'sold' &&
            <div className="profile p-20 bg-white rounded shadow-theme product-sidebar-owner">
                <OwnerAlt product={product} currentUser={currentUser} options={props.options}/>
            </div>
        }

      {true === true &&
        <div className='calc-wrapper'>
              <FinanceCalculator product={product} currentUser={currentUser} />
            </div>
      }

      {'0' !== options.safety_tips && (options['membership-safety-tips'] === 'always' || (options['membership-safety-tips'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <div className="profile--tips mt-30 py-30 px-20 bg-white rounded shadow-theme">
        <SafetyTips product={product} currentUser={currentUser}/>
      </div>
      }
      { options?.display_sidebar_promotion &&
        <div className="product--advertisement mt-30">
          <Advertisement product={product} options={options} is_ad={true}/>
        </div>
      }
    </Fragment>
  );
}

export default ProductSidebarAlt;
