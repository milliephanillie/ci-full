/**
 * External dependencies
 */

import Flatpickr from 'react-flatpickr';
import ReactSVG from 'react-svg';
import ReactTooltip from 'react-tooltip';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import { useEffect, useState } from 'react';
import moment from 'moment';

/**
 * Input field
 * -----------
 *
 * @param props
 * @returns {Input.props.display|*}
 * @constructor
 */
const DateComponent = (props) => {
  const [fieldActive, setFieldActive] = useState(false);
  const [options, setOptions] = useState({});

  const fieldType = type ? type : 'text';
  const divClasses = classes ? classes : 'h-40 p-14 border border-grey-300 rounded';
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
    payment_package,
    error,
    classes,
    icon,
    placeholder,
    additional
  } = props;

  useEffect(() => {
    let myCurrentDate = new Date();
    let myFutureDate = new Date(myCurrentDate);
    myFutureDate.setDate(myFutureDate.getDate() + parseInt(payment_package.products_duration));
    let dateValue = myFutureDate.toLocaleDateString('en-US').split('/');
    dateValue = `${dateValue[2]}-${dateValue[0]}-${dateValue[1]}`;
    let optionsObj = {
      ...props.options,
      maxDate: dateValue
    };
    setOptions(optionsObj);
  }, [props.options]);

  return (
    display &&
    <div className={`field flex flex-col ${additional && additional.class ? additional.class : 'w-full mb-40'}`}>

      <div className="field--top flex justify-between">
        <label htmlFor={id} className="field--label flex items-center mb-6 text-sm text-grey-500">
          {label}
          {description &&
          <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                    className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
          }
        </label>
        {error && error[name] &&
        <div className="field--error text-sm text-red-700 w-2/3 text-right">{error[name]}</div>}
      </div>

      <div
        className={`field--wrapper relative flex items-center ${divClasses} ${fieldActive ? 'bg-transparent' : 'bg-grey-100'}`}
        onFocus={() => setFieldActive(true)}
        onBlur={() => setFieldActive(false)}
      >
        <Flatpickr
          id={id}
          name={name}
          className={`w-full bg-transparent`}
          onValueUpdate={(selectedDates, date, instance) => {
            handleChange(date, name, 'date');
          }}
          placeholder={placeholder}
          options={options}
        />
        {description && <ReactTooltip/>}
      </div>

    </div>
  );
};

export default DateComponent;
