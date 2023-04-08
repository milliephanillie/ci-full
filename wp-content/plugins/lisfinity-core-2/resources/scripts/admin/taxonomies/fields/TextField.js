/**
 * External dependencies.
 */
import { Component, useEffect, useState } from '@wordpress/element';
import ReactSVG from 'react-svg';
import KeyboardIcon from '../../../../images/icons/keyboard.svg';

const TextField = (props) => {
  const [display, setDisplay] = useState(true);
  const {
    value,
    type,
    id,
    name,
    description,
    label,
    field,
    taxonomy,
    handleChange,
  } = props;

  const fieldType = type ? type : 'text';
  return (
    display &&
    <div className={`mb-20 ${field.additional && field.additional.class && field.additional.class}`}>
      <label htmlFor={id} className="lisfinity-label flex mb-10">{label}{field && field.required &&
      <span className="text-sm text-red-600 leading-none">*</span>}</label>
      <div className="flex items-center px-16 bg-grey-100 border border-grey-200 rounded">
        <ReactSVG
          src={`${lc_data.dir}dist/${KeyboardIcon}`}
          className="relative mr-8 w-18 h-18 fill-grey-700"
        />
        <input
          type={fieldType}
          id={id}
          name={name}
          defaultValue={value}
          className="py-12 w-full bg-transparent border-0 font-semibold text-grey-700"
          onChange={(e) => handleChange(name, e.target.value)}
          placeholder={field.placeholder && field.placeholder}
          autoComplete="off"
        />
      </div>
      <div className="description mt-6 text-grey-700 leading-snug" style={{ fontSize: '11px' }}>{description}</div>
    </div>
  );
};

export default TextField;
