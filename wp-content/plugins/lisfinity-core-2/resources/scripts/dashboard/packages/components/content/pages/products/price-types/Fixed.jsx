/* global lc_data, React */
/**
 * External dependencies.
 */
import cx from 'classnames';
import ReactSVG from 'react-svg';
import WalletIcon from '../../../../../../../../images/icons/wallet.svg';

function Fixed(props) {
  const { product, currentUser, handleBuyNow } = props;

  const classes = cx({
    'rounded-l': product.sell_on_site,
    rounded: !product.sell_on_site,
  });

  return (
    <div className="auction--infos flex flex-nowrap justify-end items-center whitespace-no-wrap">

      <div className="flex-center">
        <ReactSVG
          src={`${lc_data.dir}dist/${WalletIcon}`}
          className="relative top-0 mr-6 w-14 h-14 fill-field-icon"
        />
        <div className={`text-grey-1100 ${classes}`}
             dangerouslySetInnerHTML={{ __html: product.price_html }}>
        </div>
      </div>

    </div>
  );
}

export default Fixed;
