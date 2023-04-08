/* global React, lc_data */

import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../images/icons/loader-rings.svg';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies.
 */

const LoaderChats = (props) => {

  return (
    <div
      className={`absolute w-full h-full flex-center loader loader__auth flex flex-col w-full rounded shadow-theme ${props.style === 'white' ? 'bg-white' : ''}`}>

      <div className="flex-center flex-col">
        <ReactSVG
          src={`${lc_data.dir}dist/${LoaderIcon}`}
          className="relative"
          style={{ zoom: 1 }}
        />
        <p className="mt-20 text-lg text-grey-900">{lc_data.jst[594]}</p>
      </div>

    </div>
  );
};

export default LoaderChats;
