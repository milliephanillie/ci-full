/**
 * External dependencies
 */

import Flatpickr from 'react-flatpickr';
import ReactSVG from 'react-svg';
import { useEffect, useState } from '@wordpress/element';
import { map, isEmpty } from 'lodash';
import { __ } from '@wordpress/i18n';
import cx from 'classnames';
import ClockIcon from '../../../../images/icons/alarm-clock.svg';
import { getSafe } from '../../../theme/vendor/functions';

/**
 * Input field
 * -----------
 *
 * @param props
 * @returns {Input.props.display|*}
 * @constructor
 */
const WorkingHours = (props) => {
  const [activeField1, setActiveField1] = useState(false);
  const [activeField2, setActiveField2] = useState(false);
  const {
    display,
    type,
    id,
    name,
    value,
    description,
    label,
    handleChange,
    attributes,
    error,
    classes,
    icon,
    placeholder,
    additional,
    options,
  } = props;
  const [currentDay, setCurrentDay] = useState(options.currentDay || 'monday');
  const [hours, setHours] = useState(value);

  useEffect(() => {
    const newHours = { ...hours };
    if (!hours.enable) {
      newHours.enable = 'no';
    }
    map(options.days, (dayName, day) => {
      if (!hours[day] || !hours[day].type) {
        newHours[day] = {
          type: 'working',
          hours: [
            {
              open: '08:00 AM',
              close: '04:00 PM',
            }
          ],
        };
      }
    });
    setHours(newHours);
    handleChange(hours, name, 'working_hours');

    document.onclick = (e) => {
      if (e.target.classList.contains('field-1') || e.target.classList.contains('flatpickr-field-1')) {
        setActiveField2(false);
      } else if (e.target.classList.contains('field-2') || e.target.classList.contains('flatpickr-field-2')) {
        setActiveField1(false);
      } else {
        setActiveField1(false);
        setActiveField2(false);
      }
    };

  }, []);

  const updateHoursCheckbox = (value, name) => {
    const newHours = hours;
    newHours[name].type = value;

    if (value === 'working' && (isEmpty(hours[name]?.hours[0]?.open || isEmpty(hours[name]?.hours[0]?.close)))) {
      newHours[name] = {
        type: 'working',
        hours: [
          {
           open: '08:00 AM',
           close: '04:00 PM',
          }
        ],
      };
    }

    setHours({ ...hours, ...newHours });
    handleChange(hours, name, 'working_hours');
  };

  const updateHoursTime = (value, name, type) => {
    const newHours = hours;

    if (!newHours[name].hours[0]) {
      newHours[name].hours[0] = { open: '08:00 AM', close: '04:00 PM', };
    }
    newHours[name].hours[0][type] = value;

    setHours({ ...hours, ...newHours });
    handleChange(hours, name, 'working_hours');
  };

  const fieldType = type ? type : 'text';
  const divClasses = classes ? classes : 'h-40 p-14 bg-grey-100 border border-grey-300 rounded';

  return (
    display && !isEmpty(hours) &&
    <div className={`field flex flex-col ${additional && additional.class ? additional.class : 'w-full mb-40'}`}>

      <div className="working-hours--day-type flex mb-20">
        <div className={`field--checkbox relative mr-10`}>
          <input type="checkbox" id="hours-enable"
                 onChange={() => {
                   const newHours = hours;
                   if (hours.enable === 'yes') {
                     newHours.enable = 'no';
                   } else {
                     newHours.enable = 'yes';
                   }
                   setHours({ ...hours, ...newHours });
                   handleChange(hours, name, 'working_hours');
                 }}
                 checked={hours.enable === 'yes' || false}
          />
          <label htmlFor="hours-enable" className="relative top-3 pl-4 w-full text-sm">
            {lc_data.jst[363]}
          </label>
        </div>
      </div>

      {options.days && hours.enable === 'yes' &&
      <div>

        <div className="working-hours--tabs flex items-center">
          {map(options.days, (dayName, day) => {

            const tabClasses = cx({
              'bg-grey-100': day !== currentDay,
              'bg-blue-100': day === currentDay,
            });
            return (
              <button
                key={day}
                type="button"
                className={`mr-1 py-3 px-12 rounded-t font-semibold capitalize ${tabClasses}`}
                onClick={() => setCurrentDay(day)}
              >
                {dayName}
              </button>
            );
          })}
        </div>

        <div className="working-hours--content">
          {map(options.days, (dayName, day) => {
            return (
              day === currentDay &&
              <div key={day}>

                <div className="working-hours--day-type py-20 flex">
                  <div className={`field--checkbox relative mr-10`}>
                    <input type="radio" id={`${day}-type-working`} value="working"
                           className="-mr-4"
                           onChange={(e) => updateHoursCheckbox(e.target.value, day)}
                           checked={!hours[day] || !hours[day].type || hours[day].type === 'working'}
                    />
                    <label htmlFor={`${day}-type-working`} className="relative top-3 pl-4 w-full text-sm">
                      {lc_data.jst[364]}
                    </label>
                  </div>
                  <div className={`field--checkbox relative mr-10`}>
                    <input type="radio" id={`${day}-type-full`} value="full"
                           className="-mr-4"
                           onChange={(e) => updateHoursCheckbox(e.target.value, day)}
                           checked={hours[day] && hours[day].type === 'full' || false}
                    />
                    <label htmlFor={`${day}-type-full`} className="relative top-3 pl-4 w-full text-sm">
                      {lc_data.jst[365]}
                    </label>
                  </div>
                  <div className={`field--checkbox relative mr-10`}>
                    <input type="radio" id={`${day}-type-nw`} value="not_working"
                           className="-mr-4"
                           onChange={(e) => updateHoursCheckbox(e.target.value, day)}
                           checked={hours[day] && hours[day].type === 'not_working' || false}
                    />
                    <label htmlFor={`${day}-type-nw`} className="relative top-3 pl-4 w-full text-sm">
                      {lc_data.jst[366]}
                    </label>
                  </div>
                </div>

                {hours[day].type === 'working' &&
                <div className="field--wrapper relative flex justify-between items-center">
                  {value[day]?.hours[0] && value[day].hours[0].open && !isEmpty(value[day].hours[0].open) &&
                  <div
                    className={`flatpickr-field-1 relative flex items-center px-20 w-48% border border-grey-300 rounded ${activeField1 ? 'bg-transparent' : 'bg-grey-100'}`}
                    onClick={() => setActiveField1(true)}
                  >
                    <ReactSVG
                      src={`${lc_data.dir}dist/${ClockIcon}`}
                      className="relative top-1 mr-6 w-20 h-20 fill-grey-700 pointer-events-none"
                    />
                    <Flatpickr
                      id={`${day}-open`}
                      className="field-1 py-10 w-full bg-transparent"
                      options={options}
                      onChange={(selectedDates, date, instance) => updateHoursTime(date, day, 'open')}
                      value={hours[day].hours[0].open}

                    />
                  </div>
                  }
                  {value[day].hours[0] && value[day].hours[0].close && !isEmpty(value[day].hours[0].close) &&
                  <div
                    className={`flatpickr-field-2 relative flex items-center px-20 w-48% border border-grey-300 rounded ${activeField2 ? 'bg-transparent' : 'bg-grey-100'}`}
                    onClick={() => setActiveField2(true)}
                  >
                    <ReactSVG
                      src={`${lc_data.dir}dist/${ClockIcon}`}
                      className="relative top-1 mr-6 w-20 h-20 fill-grey-700 pointer-events-none"
                    />
                    <Flatpickr
                      id={`${day}-close`}
                      className="field-2 py-10 w-full bg-transparent"
                      options={options}
                      onChange={(selectedDates, date, instance) => updateHoursTime(date, day, 'close')}
                      value={hours[day].hours[0].close}
                    />
                  </div>
                  }
                </div>
                }

              </div>
            );
          })}
        </div>

      </div>
      }

    </div>
  );
};

export default WorkingHours;
