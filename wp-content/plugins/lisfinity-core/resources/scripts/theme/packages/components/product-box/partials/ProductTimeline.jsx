/* global lc_data, React */
import cx from 'classnames';
import { sprintf } from '@wordpress/i18n';
import { Fragment } from 'react';

/**
 * External dependencies.
 */

const ProductTimeline = (props) => {
  const { product } = props;

  const timelineClass = cx({
    'bg-green-500': product.percentage < 40,
    'bg-yellow-500': product.percentage >= 40 && product.percentage < 80,
    'bg-red-500': product.percentage >= 80,
  });

  return (
    product && <div className="product--timeline mt-10 order-5">
      <div className="flex flex-wrap mt-10 text-sm text-grey-500 whitespace-nowrap">
        {lc_data.jst[170]}
        <span
          className="ml-3 font-semibold text-grey-1100">{sprintf(lc_data.jst[153], product.submitted_human)}</span>
        <span className="mx-3">-</span>
        {product.is_active
          ?
          <Fragment>
            {lc_data.jst[171]}
            <span className="ml-3 font-semibold text-grey-1100">{product.expires_human}</span>
          </Fragment>
          :
          <Fragment>
            {lc_data.jst[172]}
            <span className="ml-3 font-semibold text-grey-1100">{product.expires_date}</span>
          </Fragment>
        }
      </div>

      <div className="timeline relative mt-8 w-full h-3 bg-grey-100 rounded overflow-hidden">
        <div
          className={`timeline--line absolute top-0 left-0 h-3 ${timelineClass}`}
          style={{ width: `${product.percentage}%` }}
        ></div>
      </div>
    </div>
  );
};

export default ProductTimeline;
