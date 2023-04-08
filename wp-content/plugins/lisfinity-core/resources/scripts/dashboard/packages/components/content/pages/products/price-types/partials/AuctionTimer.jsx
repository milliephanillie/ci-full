/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useRef } from 'react';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import clockIcon from '../../../../../../../../../images/icons/alarm-clock.svg';
import * as functions from '../../../../../../../../theme/vendor/functions';
import cx from 'classnames';

function AuctionTimer(props) {
  const { product } = props;
  const timer = useRef(null);

  function initializeCountdownTimer() {
    if (product.auction_status === 'active' && lc_data.current_time < product.auction_ends) {
      if (timer) {
        const endTime = product.auction_ends;
        functions.initializeClock(timer.current, endTime);
      }
    }
  }

  useEffect(() => {
    initializeCountdownTimer();
  }, []);

  const classes = cx({
    'ml-20': props.style === 'single',
  });

  return (
    <div className={`auction--info flex ${classes}`}>
      <ReactSVG
        src={`${lc_data.dir}dist/${clockIcon}`}
        className="relative top-3 w-14 h-14 fill-field-icon"
      />
      {product.auction_status === 'active' && lc_data.current_time < product.auction_ends ?
        <span
          ref={timer}
          className="relative left-8 text-grey-1100"
          data-auction-ends={product.auction_ends}
        >
          {lc_data.jst[239]}
        </span>
        :
        <span className="relative left-8 text-grey-1100">{lc_data.jst[240]}</span>}
    </div>
  );
}

export default AuctionTimer;
