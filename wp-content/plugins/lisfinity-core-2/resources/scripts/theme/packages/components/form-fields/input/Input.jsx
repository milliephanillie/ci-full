/* global React, lc_data */

/**
 * External dependencies.
 */
import ReactSVG from 'react-svg';
import ReactTooltip from 'react-tooltip';
import { useEffect, useState } from '@wordpress/element';

const Input = (props) => {
  const { id, name, label, placeholder, icon, error, classes, options, description } = props;
  const divClasses = classes ? classes : 'h-44 p-14 bg-grey-100 border border-grey-300 rounded';
  const [active, setActive] = useState(false);

  const changeValue = (value) => {
    setValue(value);
  }


  return (
    <div className="field flex flex-col mb-20">

      <div className="field--top flex justify-between">
        <label htmlFor={id} className="field--label mb-4 text-sm text-grey-500">{label}</label>
        {error && error[name] &&
        <div className="field--error text-sm text-red-700 w-2/3 text-right">{error[name]}</div>}
      </div>

      <div
        className={`field--wrapper relative flex items-center ${divClasses} ${active ? 'bg-transparent' : 'bg-grey-100'}`}>
        {icon &&
        <ReactSVG src={`${lc_data.dir}dist/${icon}`} className="relative mr-8 w-16 h-16 fill-field-icon"/>
        }
        <input type="text" id={id} name={name} className="w-full bg-transparent" placeholder={placeholder} {...options}
               autoComplete="off" onFocus={() => setActive(true)} onBlur={() => setActive(false)}/>
        {description &&
        <div
          className="field--description absolute flex-center h-30 w-30 rounded-full bg-red-200 font-light text-xl text-red-700"
          style={{ right: '-40px' }}
          data-tip={description}>?</div>}
        {description && <ReactTooltip/>}
      </div>

    </div>
  );
};

export default Input;
