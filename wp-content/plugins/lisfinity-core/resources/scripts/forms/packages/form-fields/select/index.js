/**
 * External dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { map } from 'lodash';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../../images/icons/question-circle.svg';

const SelectField = (props) => {
  const [opt, setOpt] = useState([]);
  const [active, setActive] = useState(false);
  const {
    type,
    id,
    name,
    description,
    label,
    options,
    additional,
    display,
    multiselect,
    handleChange,
    isTerm,
    firstEmpty,
    error,
    attributes,
  } = props;

  return (
    (options.length !== 0) && display &&
    <div className="field flex flex-col mb-20 w-full">
      <div className="field--top flex justify-between">
        <label htmlFor={id} className="field--label flex items-center mb-6 text-sm text-grey-500">
          {label}
          {description &&
          <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                    className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
          }
        </label>
        {error && error[name] &&
        <div className="field--error text-sm text-red-700 text-right">{error[name]}</div>}
      </div>
      <div className={`form-field flex flex-col select-custom ${additional && additional.class}`}>
        <select
          id={id}
          name={name}
          value={props.value}
          className={`lisfinity-field flex mb-20 p-10 px-20 h-44 w-full border border-grey-300 rounded font-semibold outline-none cursor-pointer ${active ? 'bg-transparent' : 'bg-grey-100'}`}
          onChange={handleChange}
          multiple={multiselect}
          onFocus={() => setActive(true)}
          onBlur={() => setActive(false)}
        >
          {firstEmpty && <option value="0">{lc_data.jst[44]}</option>}
          {isTerm
            ?
            map(options, (option, index) => (
              <option key={index} value={option.slug}>
                {option.name}
              </option>
            ))
            :
            map(options, (label, value) => (
              <option key={value} value={value}>
                {label}
              </option>
            ))
          }
        </select>
        <span className="description">{description}</span>
      </div>
    </div>
  );
};

export default SelectField;
