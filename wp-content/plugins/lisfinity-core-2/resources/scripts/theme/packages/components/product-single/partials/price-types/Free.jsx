/* global lc_data, React */
/**
 * External dependencies.
 */
import GiftIcon from '../../../../../../../images/icons/gift.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';

function Free(props) {
  const { product, currentUser, iconSettings } = props;

  let icon = null;
  let svg = null;

  if (iconSettings?.icon_price_free_icon !== null && iconSettings?.icon_price_free_icon) {
    typeof iconSettings.icon_price_free_icon['value'] === 'string' ? icon = iconSettings.icon_price_free_icon['value'] : svg = iconSettings.icon_price_free_icon['value']['url'];
  }

  return (
    <div className="auction--infos flex flex-nowrap justify-end items-center whitespace-no-wrap">

      <div className="auction--info flex mx-10">
        {
         (icon === null && svg === null || "" == icon)  &&
          <ReactSVG
            src={`${lc_data.dir}dist/${GiftIcon}`}
            className={`relative products-icon-free top-1 mr-8 w-16 h-16 fill-icon-gift`}
          />
        }
        {
          svg  &&
          <img src={svg} alt="cart-icon"
               className="w-20 h-20 mr-8 products-icon-free fill-icon-reset pointer-events-none"/>
        }
        {
           icon &&
          <i className={`${icon} products-icon-free`} style={{
            display: 'flex',
            alignSelf: 'center'
          }}
             aria-hidden="true"
          ></i>
        }
        <div className={`product--price free-price on-sale flex flex-row-reverse text-green-700 font-semibold`}>{lc_data.jst[128]}</div>
      </div>

    </div>
  );
}

export default Free;
