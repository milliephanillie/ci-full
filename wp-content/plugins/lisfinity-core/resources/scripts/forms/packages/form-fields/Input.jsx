/**
 * External dependencies
 */

import ReactSVG from 'react-svg';
import ReactTooltip from 'react-tooltip';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import Select from 'react-select';
import {useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import {isEmpty} from 'lodash';
import {validatePrice} from "../../../theme/vendor/functions";

/**
 * Input field
 * -----------
 *
 * @param props
 * @returns {Input.props.display|*}
 * @constructor
 */
const Input = (props) => {
  const [active, setActive] = useState(false);
  const [err, setErr] = useState(false);
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const {options} = data;
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
    errorTaxonomy,
    placeholder,
    additional,
    parent
  } = props;

  const fieldType = type ? type : 'text';
  const divClasses = classes ? classes : 'h-40 p-14 border border-grey-300 rounded';
  let fieldValue = name === 'email' && !isEmpty(options?.user_email) ? options?.user_email : name === 'website' && !isEmpty(options?.user_website) ? options?.user_website : name === 'phone' && !isEmpty(options?.user_phone) ? options?.user_phone : value;

  const preventNonNumericalInput = (e) => {
    if (fieldType === 'number' || (attributes && attributes.type === 'number')) {
      e = e || window.event;
      var charCode = (typeof e.which == 'undefined') ? e.keyCode : e.which;
      var charStr = String.fromCharCode(charCode);

      if (!charStr.match(/^[0-9]+$/))
        e.preventDefault();
    }

  };
  return (
    display &&
    <div
      className={`field flex flex-col ${additional && additional.class ? additional.class : 'w-full mb-40'}`}>

      <div className="field--top flex justify-between">
        <label htmlFor={id} className="field--label flex items-center mb-6 text-sm text-grey-500">
          {label}
          {description &&
            <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                      className="relative left-6 w-14 h-14 fill-blue-700" style={{top: '-8px'}}/>
          }
        </label>
        {error && error[name] &&
          <div className="field--error text-sm text-red-700 w-2/3 text-right">{error[name]}</div>}
        {errorTaxonomy &&
          <div className="field--error text-sm text-red-700 w-2/3 text-right">{errorTaxonomy}</div>}
        {err &&
          <div className="field--error text-sm text-red-700 w-2/3 text-right">{err}</div>}
      </div>

      <div
        className={`field--wrapper relative flex items-center ${divClasses} ${active ? 'bg-transparent' : 'bg-grey-100'}`}>
        <input type={fieldType} id={id} name={name} className="w-full bg-transparent"
               placeholder={placeholder} {...attributes}
               defaultValue={fieldValue || ''}
               maxLength={attributes?.max_chars > 0 && attributes.max_chars || 99999}
               minLength={attributes?.min || 0}
               step={attributes?.step || 1}
               onKeyPress={e => preventNonNumericalInput(event)}
               onChange={(e) => {
                 if (attributes?.field_type === 'price' && !validatePrice(e.target.value)) {
                   setErr(lc_data.jst[794]);
                 }
                 if (attributes?.max_chars > 0 && e.target.value.length >= attributes.max_chars) {
                   setErr(lc_data.jst[768]);
                 } else {
                   setErr(false);
                   handleChange(e);
                 }
               }}
               autoComplete="off"
               onFocus={() => setActive(true)}
               onBlur={() => setActive(false)}
        />
        {description && <ReactTooltip/>}
      </div>

    </div>
  );
};

export default Input;
