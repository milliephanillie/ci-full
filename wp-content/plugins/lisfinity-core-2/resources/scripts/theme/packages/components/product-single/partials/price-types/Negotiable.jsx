/* global lc_data, React */
/**
 * External dependencies.
 */
import HandshakeIcon from '../../../../../../../images/icons/handshake.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import cx from 'classnames';

function Negotiable(props) {
  const { product, currentUser, iconSettings, handleBuyNow } = props;

  const classes = cx({
    'rounded-l': product.product_meta.sell_on_site,
    rounded: !product.product_meta.sell_on_site,
  });

  let icon = null;
  let svg = null;

  if (iconSettings?.icon_price_negotiable_label_icon !== null && iconSettings?.icon_price_negotiable_label_icon) {
    typeof iconSettings.icon_price_negotiable_label_icon['value'] === 'string' ? icon = iconSettings.icon_price_negotiable_label_icon['value'] : svg = iconSettings.icon_price_negotiable_label_icon['value']['url'];
  }

  return (
    <div className="negotiable--infos flex flex-nowrap justify-end items-center whitespace-no-wrap">

      <div className="negotiable--info  flex mx-10">
        {
          (iconSettings?.place_icon_negotiable_label_icon_price === 'yes' && (!icon && !svg || '' == icon)) || '' === iconSettings?.place_icon_negotiable_label_icon_price &&
          <ReactSVG
            src={`${lc_data.dir}dist/${HandshakeIcon}`}
            className={`relative products-icon-negotiable top-3 mr-8 w-16 h-16 fill-field-icon`}
          />
        }
        {
          svg && iconSettings?.place_icon_negotiable_label_icon_price !== '' &&
          <img src={svg} alt="cart-icon"
               className="w-20 h-20 mr-8 products-icon-negotiable fill-icon-reset pointer-events-none"/>
        }
        {
          iconSettings?.place_icon_negotiable_label_icon_price !== '' && icon &&
          <i className={`${icon} products-icon-negotiable`} style={{
            display: 'flex',
            alignSelf: 'center'
          }}
             aria-hidden="true"
          ></i>
        }
        <div
          className={`product--price on-sale flex flex-row-reverse text-grey-1100 font-semibold`}>{lc_data.jst[237]}</div>
      </div>

      <div className="flex-center ml-20">
        <div className={`p-6 px-20 bg-red-100 font-semibold product-info-negotiable-price text-red-800 ${classes}`}
             dangerouslySetInnerHTML={{ __html: product.product_meta.price_html }}>
        </div>
        {product?.product_meta?.sell_on_site ?
          <button
            type="button"
            onClick={handleBuyNow}
            className="p-6 px-20 bg-red-500 rounded-r font-semibold text-sm text-white hover:bg-red-700">
            {props.send_details ? lc_data.jst[704] : lc_data.jst[446]}
          </button> : ''
        }
      </div>

    </div>
  );
}

export default Negotiable;
