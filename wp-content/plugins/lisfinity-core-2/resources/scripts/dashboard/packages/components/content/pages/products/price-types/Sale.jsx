/* global lc_data, React */
/**
 * External dependencies.
 */
import tagIcon from '../../../../../../../../images/icons/tag.svg';
import { __ } from '@wordpress/i18n';
import cx from 'classnames';

function Sale(props) {
  const { product, currentUser, handleBuyNow } = props;

  const classes = cx({
    'rounded-l': product.sell_on_site,
    rounded: !product.sell_on_site,
  });

  return (
    <div className="auction--infos flex">

      <div className="flex-center">
        <div className={`product--price on-sale text-red-800 ${classes}`}
             dangerouslySetInnerHTML={{ __html: product.price_html }}>
        </div>
      </div>

    </div>
  );
}

export default Sale;
