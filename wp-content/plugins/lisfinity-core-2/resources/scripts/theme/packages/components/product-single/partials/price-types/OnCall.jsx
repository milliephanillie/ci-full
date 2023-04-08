/* global lc_data, React */
/**
 * External dependencies.
 */
import PhoneIcon from '../../../../../../../images/icons/phone-handset.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';

function OnCall(props) {
  const { product, currentUser, iconSettings } = props;

  let icon = null;
  let svg = null;

  if (iconSettings?.icon_price_on_call_icon !== null && iconSettings?.icon_price_on_call_icon) {
    typeof iconSettings.icon_price_on_call_icon['value'] === 'string' ? icon = iconSettings.icon_price_on_call_icon['value'] : svg = iconSettings.icon_price_on_call_icon['value']['url'];
  }

  return (
    <div className="auction--infos flex flex-nowrap justify-end items-center whitespace-no-wrap">

      <div className="auction--info price-on-call flex mx-10">
        {
          !icon && !svg &&
          <ReactSVG
            src={`${lc_data.dir}dist/${PhoneIcon}`}
            className={`relative products-icon-on-call top-1 mr-8 w-16 h-16 fill-icon-call`}
          />
        }

        {
          svg && iconSettings?.place_price_on_call_icon_price !== '' &&
          <img src={svg} alt="cart-icon"
               className="w-20 h-20 mr-8 products-icon-on-call fill-icon-reset pointer-events-none"/>
        }
        {
          iconSettings?.place_price_on_call_icon_price !== '' && icon &&
          <i className={`${icon} products-icon-on-call`} style={{
            display: 'flex',
            alignSelf: 'center'
          }}
             aria-hidden="true"
          ></i>
        }
        <div
          className={`product--price on-sale flex flex-row-reverse text-blue-700 font-semibold`}>{lc_data.jst[238]}</div>
      </div>

    </div>
  );
}

export default OnCall;
