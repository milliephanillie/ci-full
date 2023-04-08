/* global lc_data, React */
import { useRef, useState } from '@wordpress/element';
import { forEach } from 'lodash';
import ReactSVG from 'react-svg';
import StarIcon from '../../../../../images/icons/star.svg';
import FullStarIcon from '../../../../../images/icons/star-filled.svg';

/**
 * External dependencies.
 */

const ChooseRating = (props) => {
  const { id, name, label, attributes, onChange } = props;
  const [value, setValue] = useState(0);
  const input = useRef(null);

  return (
    <div className="rate mb-10">
      <p className="mb-2 text-grey-900">{label}</p>
      <div className="flex items-center">
        <div className="rate--stars flex items-center mr-10">
          {value >= 1
            ?
            <ReactSVG
              src={`${lc_data.dir}dist/${FullStarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(1);
                onChange(name, 1);
              }
              }
            />
            :
            <ReactSVG
              src={`${lc_data.dir}dist/${StarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(1);
                onChange(name, 1);
              }
              }
            />
          }
          {value >= 2
            ?
            <ReactSVG
              src={`${lc_data.dir}dist/${FullStarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(2);
                onChange(name, 2);
              }
              }
            />
            :
            <ReactSVG
              src={`${lc_data.dir}dist/${StarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(2);
                onChange(name, 2);
              }
              }
            />
          }
          {value >= 3
            ?
            <ReactSVG
              src={`${lc_data.dir}dist/${FullStarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(3);
                onChange(name, 3);
              }
              }
            />
            :
            <ReactSVG
              src={`${lc_data.dir}dist/${StarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(3);
                onChange(name, 3);
              }
              }
            />
          }
          {value >= 4
            ?
            <ReactSVG
              src={`${lc_data.dir}dist/${FullStarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(4);
                onChange(name, 4);
              }
              }
            />
            :
            <ReactSVG
              src={`${lc_data.dir}dist/${StarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(4);
                onChange(name, 4);
              }
              }
            />
          }
          {value >= 5
            ?
            <ReactSVG
              src={`${lc_data.dir}dist/${FullStarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(5);
                onChange(name, 5);
              }
              }
            />
            :
            <ReactSVG
              src={`${lc_data.dir}dist/${StarIcon}`}
              className={`relative mr-2 w-22 h-22 fill-yellow-700 cursor-pointer`}
              onClick={() => {
                setValue(5);
                onChange(name, 5);
              }
              }
            />
          }
        </div>
        <p className="font-bold text-xl text-green-800">{`${value}.0`}</p>
      </div>
      <input type="hidden" name={`star[${name}]`} ref={input} value={value}/>
    </div>
  )
};

export default ChooseRating;
