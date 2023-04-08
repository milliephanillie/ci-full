/* global lc_data, React */
/**
 * External dependencies.
 */
import tagIcon from '../../../../../../../images/icons/tag.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import cx from 'classnames';

function Sale(props) {
  const { product, currentUser, handleBuyNow } = props;

  const classes = cx({
    'rounded-l': product.product_meta.sell_on_site,
    rounded: !product.product_meta.sell_on_site,
  });

  return (
    <div className="auction--infos flex flex-nowrap justify-end items-center whitespace-no-wrap">

      <div className="flex-center ml-20">
        <div className={`product--price on-sale on-sale-container p-6 px-20 bg-red-100 font-semibold text-red-800 ${classes}`}
             dangerouslySetInnerHTML={{ __html: product.product_meta.price_html }}>
        </div>
        {product.product_meta.sell_on_site ? <button
          type="button"
          onClick={handleBuyNow}
          className="p-6 px-20 bg-red-500 rounded-r font-semibold text-sm text-white hover:bg-red-700">
          {props.send_details ? lc_data.jst[704] : lc_data.jst[446]}
        </button> : ''}
      </div>

    </div>
  );
}

export default Sale;
