/**
 * External dependencies.
 */
import { useState } from '@wordpress/element';
import ReactTooltip from 'react-tooltip';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import he from 'he';

const Checkbox = (props) => {
  const [isChecked, setChecked] = useState(props.checked);
  const {
    display,
    id,
    name,
    value,
    data,
    description,
    label,
    handleChange,
    checked,
    attributes,
    error,
    classes,
    additional
  } = props;

  const divClasses = classes ? classes : 'h-44 p-14 bg-grey-100 border border-grey-300 rounded';
  return (
    display && <div className="field flex flex-col mt-20 mb-40 w-full">

      {additional && additional.line_top && <hr className="mb-20 h-1 w-full bg-grey-200"/>}

      <div className={`field--checkbox relative ${classes ? classes : ''}`}>
        <input type="checkbox" id={id} name={name} checked={checked || false} onChange={handleChange} {...attributes}/>
        <label htmlFor={id} className="relative top-3 text-sm">
          {label && <span dangerouslySetInnerHTML={{ __html: he.decode(label) }}/>}
          {description &&
          <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                    className="absolute w-14 h-14 fill-blue-700" style={{ top: -8, right: -18 }}/>
          }
        </label>
        {error && error[name] &&
        <div className="field--error absolute text-sm text-red-700 w-2/3" style={{ top: 'calc(100% + 10px)' }}>
          {error[name]}
        </div>}
      </div>

      {additional && additional.line_bottom && <hr className="mt-20 h-1 w-full bg-grey-100"/>}

      {description && <ReactTooltip/>}
    </div>
  );
};

export default Checkbox;
