/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { isEmpty, map } from 'lodash';
import { sprintf, __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */

function OwnerHours(props) {
  const { product } = props;
  const { hours, weekdays } = product.premium_profile;
  const currentDay = new Date().getDay();
  const currentHours = hours[currentDay];
  const [showHours, setShowHours] = useState(props.alwaysOpen || false);

  return (
    <div className="mt-30">
      {!showHours && currentHours && currentHours.hours &&
      <div className="font-light text-grey-1000">
        {currentHours.type === 'working' && currentHours.hours[0] &&
        <Fragment>
          <span dangerouslySetInnerHTML={{ __html: lc_data.jst[390] }}/>
          <strong className="mx-4 font-semibold">{currentHours.hours[0].open.slice(0, -3)}</strong>
          <span dangerouslySetInnerHTML={{ __html: lc_data.jst[391] }}/>
          <strong className="ml-2 font-semibold">{currentHours.hours[0].close.slice(0, -3)}</strong>
        </Fragment>}
        {currentHours.type === 'full' && lc_data.jst[365]}
        {currentHours.type === 'not_working' && lc_data.jst[392]}
      </div>}

      {showHours && hours &&
      map(hours, (hour, index) => {
        return (
          hour &&
          <div key={index} className="flex mt-8 font-light text-grey-1000">
            {hour.type === 'working' && hour.hours && hour.hours[0] &&
            <Fragment>
              <div className="w-1/3 font-semibold">{sprintf('%s ', hour.day)}</div>
              <Fragment>
                <strong className="mx-4">{hour.hours[0].open}</strong>
              </Fragment>
              <Fragment>
                <span>-</span>
                <strong className="ml-2">{hour.hours[0].close}</strong>
              </Fragment>
            </Fragment>
            }
            {hour.type === 'full' &&
            <Fragment>
              <div className="w-1/3 font-semibold">{sprintf('%s ', hour.day)}</div>
              <span>{lc_data.jst[365]}</span>
            </Fragment>
            }
            {hour.type === 'not_working' &&
            <Fragment>
              <div className="w-1/3 font-semibold">{sprintf('%s ', hour.day)}</div>
              <span>{lc_data.jst[543]}</span>
            </Fragment>
            }
          </div>
        );
      })
      }

      {!showHours && hours && currentHours.hours &&
      <button
        type="button"
        className="text-sm text-grey-500"
        onClick={() => setShowHours(true)}
      >
        {lc_data.jst[544]}
      </button>
      }
    </div>
  );
}

export default OwnerHours;
