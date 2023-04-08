/**
 * External dependencies
 */

import ReactSVG from 'react-svg';
import ReactTooltip from 'react-tooltip';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import Select from 'react-select';
import {useState} from '@wordpress/element';

/**
 * Input field
 * -----------
 *
 * @param props
 * @returns {Input.props.display|*}
 * @constructor
 */
const Description = (props) => {
  const [active, setActive] = useState(false);
  const {
    field,
    additional,
  } = props;

  return (
    <div
      className={`field flex flex-col ${additional && additional.class ? additional.class : 'w-full mb-40'}`}>

      <div className="field--top flex justify-between">
        <div className="field--label flex items-center mb-6 text-sm text-grey-500">
          {field?.description || ''}
        </div>
      </div>

    </div>
  );
}

export default Description;
