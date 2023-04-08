/* global lc_data, React */
/**
 * Dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import {isEmpty, map} from 'lodash';
import {sprintf, __} from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import ClockIcon from '../../../../../images/icons/alarm-clock.svg';
import {useDispatch, useSelector} from 'react-redux';
import {createRef} from '@wordpress/element';

function ProductOwnerHoursAltEl(props) {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product} = data;
  const [hours, setHours] = useState({});
  const [currentDay, setCurrentDay] = useState(0);
  const [currentHours, setCurrentHours] = useState({});
  const [showHours, setShowHours] = useState(props.alwaysOpen || false);

  const [settings, setSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    if (el.current) {
      wrapper = el.current.closest('.elementor-product-working-hours');
      if (wrapper && wrapper.dataset.settings) {
        const settings = JSON.parse(wrapper.dataset.settings);
        setSettings(settings);
      }
    }
  }, []);

  useEffect(() => {
    if (!isEmpty(product?.premium_profile)) {
      setHours(product.premium_profile?.hours);
      setCurrentDay(product.premium_profile?.current_day);
      setCurrentHours(product.premium_profile?.hours[product.premium_profile?.current_day]);
    }
  }, [product]);

  return (
    !isEmpty(product) && <div className="profile--working-hours flex flex-wrap mt-30" ref={el}>
      <div
        className="profile--widget-title flex items-center mb-20 py-14 px-20 -ml-20 rounded-r bg-grey-100"
        style={{
          width: 'calc(100% + 16px)',
        }}
      >{settings?.remove_icon_action === '' && (!settings?.home_icon || settings?.home_icon.value === '') &&
      <ReactSVG
        src={`${lc_data.dir}dist/${ClockIcon}`}
        className="mr-10 w-16 h-16 fill-blue-700 working-hour-icon"
      />
      }
        {settings?.home_icon?.value && settings?.home_icon?.library !== 'svg' &&
        <i className={`mr-8 ${settings.home_icon.value} mr-10 w-16 h-16 fill-blue-700 working-hour-icon`}></i>
        }
        {settings?.home_icon?.value.url && settings?.home_icon?.library === 'svg' &&
        <ReactSVG
          src={`${settings.home_icon.value.url}`}
          className={`mr-10 w-16 h-16 fill-blue-700 working-hour-icon`}
        />
        }

        <span
          className="font-bold work-time-label text-grey-900">{settings?.work_time_label ? settings?.work_time_label : lc_data.jst[387]}</span>
      </div>
      <div className="flex flex-col items-start w-full">
        {!showHours && currentHours &&
        <div className="font-light text-grey-1000">
          {currentHours.type === 'working' && currentHours.hours &&
          <Fragment>
            <span dangerouslySetInnerHTML={{__html: lc_data.jst[390]}}/>
            <strong className="mx-4 font-semibold text-blue-700 time">{currentHours.hours[0].open.slice(0, -3)}</strong>
            <span dangerouslySetInnerHTML={{__html: lc_data.jst[391]}}/>
            <strong className="ml-2 font-semibold text-blue-700 time">{currentHours.hours[0].close.slice(0, -3)}</strong>
          </Fragment>
          }
          {currentHours.type === 'full' && <span className="font-semibold open-label text-green-800">{lc_data.jst[365]}</span>}
          {currentHours.type === 'not_working' &&
          <span className="font-semibold closed-label text-red-700">{lc_data.jst[392]}</span>}
        </div>}

        {showHours && hours &&
        map(hours, (hour, index) => {
          return (
            hour &&
            <div key={index} className={`flex w-full font-light text-grey-1000 ${index === 0 ? 'mt-0' : 'mt-8'}`}>
              {hour.hours && hour.hours[0] && hour.type === 'working' &&
              <Fragment>
                <div className="w-1/3 font-semibold days-label">{sprintf('%s ', hour.day)}</div>
                <Fragment>
                  <strong className="mx-4 font-semibold text-blue-700 time">{hour.hours[0].open.slice(0, -3)}</strong>
                </Fragment>
                <Fragment>
                  <span>-</span>
                  <strong className="ml-2 font-semibold text-blue-700 time">{hour.hours[0].close.slice(0, -3)}</strong>
                </Fragment>
              </Fragment>
              }
              {hour.type === 'full' &&
              <Fragment>
                <div className="w-1/3 font-semibold days-label">{sprintf('%s ', hour.day)}</div>
                <span className="font-semibold open-label text-green-800">{lc_data.jst[365]}</span>
              </Fragment>
              }
              {hour.type === 'not_working' &&
              <Fragment>
                <div className="w-1/3 font-semibold days-label">{sprintf('%s ', hour.day)}</div>
                <span className="font-semibold closed-label text-red-700">{lc_data.jst[543]}</span>
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

export default ProductOwnerHoursAltEl;
