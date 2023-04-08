/**
 * External dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { map } from 'lodash';
import Select from 'react-select';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../../images/icons/question-circle.svg';
import ReactTooltip from 'react-tooltip';

const SelectCustom = (props) => {
  const [values, setValues] = useState(props.value)
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
    firstEmpty,
    error,
    attributes,
  } = props;


  return (
    display && options.length > 0 && <div
      className={`field flex flex-col mb-40 ${additional && additional.class ? additional.class : 'w-full'}`}>

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

      <div className={`field--wrapper relative flex items-center`}>
        <input type="hidden" name={name} className="w-full bg-transparent"
               placeholder={placeholder} {...attributes}
               value={value || ''}
               onChange={handleChange}
               autoComplete="off"/>
        {description && <ReactTooltip/>}
      </div>

    </div>
  );
}

export default SelectCustom;
