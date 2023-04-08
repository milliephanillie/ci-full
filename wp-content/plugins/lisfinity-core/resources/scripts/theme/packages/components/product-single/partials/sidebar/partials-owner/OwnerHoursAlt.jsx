/* global lc_data, React */
/**
 * Dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { isEmpty, map } from 'lodash';
import { sprintf, __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import ClockIcon from '../../../../../../../../images/icons/alarm-clock.svg';

function OwnerHoursAlt(props) {
  const { product } = props;
  const { hours, current_day } = product.premium_profile;
  const currentDay = current_day;
  const currentHours = hours[currentDay];
  const [showHours, setShowHours] = useState(props.alwaysOpen || false);

  return (
    <div className="profile--working-hours flex flex-wrap mt-30">
      <div
        className="profile--widget-title flex items-center mb-20 py-14 px-20 -ml-20 rounded-r bg-grey-100"
        style={{
          width: 'calc(100% + 16px)',
        }}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${ClockIcon}`}
          className="mr-10 w-16 h-16 fill-blue-700"
        />
        <span className="font-bold text-grey-900">{lc_data.jst[387]}</span>
      </div>
      <div className="flex flex-col items-start w-full">
        {!showHours && currentHours &&
        <div className="font-light text-grey-1000">
          {currentHours.type === 'working' && currentHours.hours &&
          <Fragment>
            <span dangerouslySetInnerHTML={{ __html: lc_data.jst[390] }}/>
            <strong className="mx-4 font-semibold text-blue-700">{currentHours.hours[0].open.slice(0, -3)}</strong>
            <span dangerouslySetInnerHTML={{ __html: lc_data.jst[391] }}/>
            <strong className="ml-2 font-semibold text-blue-700">{currentHours.hours[0].close.slice(0, -3)}</strong>
          </Fragment>
          }
          {currentHours.type === 'full' && <span className="font-semibold text-green-800">{lc_data.jst[365]}</span>}
          {currentHours.type === 'not_working' &&
          <span className="font-semibold text-red-700">{lc_data.jst[392]}</span>}
        </div>}

        {showHours && hours &&
        map(hours, (hour, index) => {
          return (
            hour &&
            <div key={index} className={`flex w-full font-light text-grey-1000 ${index === 0 ? 'mt-0' : 'mt-8'}`}>
              {hour.hours && hour.hours[0] && hour.type === 'working' &&
              <Fragment>
                <div className="w-1/3 font-semibold">{sprintf('%s ', hour.day)}</div>
                <Fragment>
                  <strong className="mx-4 font-semibold text-blue-700">{hour.hours[0].open}</strong>
                </Fragment>
                <Fragment>
                  <span>-</span>
                  <strong className="ml-2 font-semibold text-blue-700">{hour.hours[0].close}</strong>
                </Fragment>
              </Fragment>
              }
              {hour.type === 'full' &&
              <Fragment>
                <div className="w-1/3 font-semibold">{sprintf('%s ', hour.day)}</div>
                <span className="font-semibold text-green-800">{lc_data.jst[365]}</span>
              </Fragment>
              }
              {hour.type === 'not_working' &&
              <Fragment>
                <div className="w-1/3 font-semibold">{sprintf('%s ', hour.day)}</div>
                <span className="font-semibold text-red-700">{lc_data.jst[543]}</span>
              </Fragment>
              }
            </div>
          );
        })
        }

        {!showHours && hours && currentHours &&
        <button
          type="button"
          className="text-13 text-grey-500"
          onClick={() => setShowHours(true)}
        >
          {lc_data.jst[544]}
        </button>
        }
        {showHours && hours &&
        <button
          type="button"
          className="text-13 text-grey-500"
          onClick={() => setShowHours(false)}
        >
          {lc_data.jst[600]}
        </button>
        }
      </div>
    </div>
  );
}

export default OwnerHoursAlt;
