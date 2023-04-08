/* global React, lc_data */

/**
 * External dependencies.
 */
import ReactSVG from 'react-svg';
import ReactTooltip from 'react-tooltip';
import {Fragment, useEffect, useState} from '@wordpress/element';

const InputEl = (props) => {
  const {id, name, label, placeholder, icon, error, classes, options, description, elementId} = props;
  const divClasses = classes ? classes : 'h-44 p-14 bg-grey-100 border border-grey-300 rounded';
  const [active, setActive] = useState(false);

  const changeValue = (value) => {
    setValue(value);
  }

  let labelText = label === 'username' ? lc_data.jst[422] : label === 'first_name' ? lc_data.jst[241] : label === 'last_name' ? lc_data.jst[243] : label === 'password' ? lc_data.jst[283] : label === 'email' ? lc_data.jst[724] : label === 'phone' ? lc_data.jst[723] : label === 'user_login' ? lc_data.jst[429] : '';
  let placeholderText = label === 'username' ? lc_data.jst[423] : label === 'first_name' ? lc_data.jst[241] : label === 'last_name' ? lc_data.jst[243] : label === 'password' ? lc_data.jst[283] : label === 'email' ? lc_data.jst[435] : label === 'phone' ? lc_data.jst[431] : label === 'user_login' ? lc_data.jst[429] : '';

  return (
    <div className="field flex flex-col mb-20">

      <div className="field--top flex justify-between">
        <label htmlFor={id} className="field--label mb-4 text-sm text-grey-500 capitalize">{labelText}</label>
        {error && error[name] &&
        <div className="field--error text-sm text-red-700 w-2/3 text-right">{error[name]}</div>}
      </div>

      <div
        className={`field--wrapper relative flex items-center elementor-repeater-item-${elementId} ${divClasses} ${active ? 'bg-transparent' : 'bg-grey-100'}`}>
        {icon && typeof icon === 'string' &&
        <ReactSVG src={`${lc_data.dir}dist/${icon}`} className="relative mr-8 w-16 h-16 field-icon"/>
        }
        {typeof icon === 'object' && icon?.value && icon?.library !== 'svg' &&
        <i className={`mr-8 ${icon.value} field-icon`}></i>
        }
        {typeof icon === 'object' && icon?.value.url && icon?.library === 'svg' &&
        <ReactSVG
          src={`${icon.value.url}`}
          className={`mr-8 w-16 h-16 field-icon`}
        />
        }
        <input type="text" id={id} name={name} className="w-full bg-transparent"
               placeholder={placeholderText} {...options}
               autoComplete="off" onFocus={() => setActive(true)} onBlur={() => setActive(false)}/>
        {description &&
        <div
          className="field--description absolute flex-center h-30 w-30 rounded-full bg-red-200 font-light text-xl text-red-700"
          style={{right: '-40px'}}
          data-tip={description}>?</div>}
        {description && <ReactTooltip/>}
      </div>

    </div>
  );
};

export default InputEl;
