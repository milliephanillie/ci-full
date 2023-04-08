/* global lc_data, React */
/**
 * External dependencies.
 */
import WalletIcon from '../../../../../../../../images/icons/wallet.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import cx from 'classnames';

function Rentable(props) {
  const { product, type } = props;

  const classes = cx({
    'rounded-l': product.sell_on_site,
    rounded: !product.sell_on_site,
  });
  return (
    <div className="auction--infos flex flex-nowrap justify-end items-center whitespace-no-wrap">

      <div className="flex-center">
        <div className="flex-center p-6">
         <ReactSVG
                  src={`${lc_data.dir}dist/${WalletIcon}`}
                  className="relative top-0 mr-6 w-14 h-14 fill-field-icon"
                />
          <div className={classes}
               dangerouslySetInnerHTML={{
                 __html: `${product.price_html}
          <span>${type === 'per_week' ? lc_data.jst[692] : type === 'per_day' ? lc_data.jst[781] : type === 'per_hour' ? lc_data.jst[780] : lc_data.jst[693]}</span>`
               }}/>
        </div>
      </div>

    </div>
  );
}

export default Rentable;
