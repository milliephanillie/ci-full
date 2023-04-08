/* global lc_data, React */
/**
 * External dependencies.
 */
import {useEffect, useRef, Fragment} from 'react';
import {__} from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import clockIcon from '../../../../../../../../images/icons/alarm-clock.svg';
import * as functions from '../../../../../../vendor/functions';

function AuctionTimer(props) {
  const {product, currentUser, iconClass, iconSettings} = props;
  const timer = useRef(null);

  function initializeCountdownTimer() {
    if (product.product_meta.auction_status === 'active' && lc_data.current_time < product.product_meta.auction_ends) {
      if (timer) {
        const endTime = product.product_meta.auction_ends;
        functions.initializeClock(timer.current, endTime);
      }
    }
  }

  useEffect(() => {
    initializeCountdownTimer();
  }, []);

  let icon = null;
  let svg = null;

  if (iconSettings?.icon_price_countdown !== null && iconSettings?.icon_price_countdown) {
    typeof iconSettings.icon_price_countdown['value'] === 'string' ? icon = iconSettings.icon_price_countdown['value'] : svg = iconSettings.icon_price_countdown['value']['url'];
  }

  return (
    <div className="auction--info flex mx-10 ml-0">
      <Fragment>
        {
          !icon && !svg &&
          <ReactSVG
            src={`${lc_data.dir}dist/${clockIcon}`}
            className={`relative products-icon-countdown top-1 w-16 h-16 fill-field-icon ${iconClass}`}
          />
        }
        {
          svg && iconSettings?.place_icon_countdown_price !== '' &&
          <img src={svg} alt="cart-icon"
               className="w-20 h-20 mr-8 products-icon-countdown fill-icon-reset pointer-events-none"/>
        }
        {
          iconSettings?.place_icon_countdown_price !== '' && icon &&
          <i className={`${icon} products-icon-countdown`} style={{
            display: 'flex',
            alignSelf: 'center'
          }}
             aria-hidden="true"
          ></i>
        }
      </Fragment>

      {product.product_meta.auction_status === 'active' && lc_data.current_time < product.product_meta.auction_ends ?
        <span
          ref={timer}
          className="font-bold text-13 countdown text-grey-1100"
          data-auction-ends={product.product_meta.auction_ends}
        >
          {lc_data.jst[239]}
        </span>
        :
        <span className="font-bold text-13 countdown text-grey-1100">{lc_data.jst[240]}</span>}
    </div>
  );
}

export default AuctionTimer;
