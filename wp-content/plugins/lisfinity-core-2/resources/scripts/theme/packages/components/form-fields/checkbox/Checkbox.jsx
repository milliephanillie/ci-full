/* global React, lc_data */

/**
 * External dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import ReactSVG from 'react-svg';

const Checkbox = (props) => {
  const { id, name, value, label, error, classes, checked, options } = props;
  const divClasses = classes ? classes : 'h-44 p-14 bg-grey-100 border border-grey-300 rounded';

  return (
    <div className="field flex flex-col mb-20">

      <div className={`field--checkbox ${classes ? classes : ''}`}>
        <input type="checkbox" id={id} name={name} checked={checked && checked} {...options}/>
        {label &&
        <label htmlFor={id} className="w-full font-normal" dangerouslySetInnerHTML={{
          __html: label,
        }}>
        </label>}
        {error && error[name] && <div className="field--error text-sm text-red-700 w-2/3">{error[name]}</div>}
      </div>

    </div>
  );
};

export default Checkbox;
