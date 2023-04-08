/* global React, lc_data */

import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../images/icons/spinner-black.svg';
import { useEffect, useState } from '@wordpress/element';

const LoaderGlobal = (props) => {

  useEffect(() => {
    if (!props.loading) {
      const oldLoader = document.getElementById('loader');
      if (oldLoader) {
        setTimeout(() => {
          oldLoader.classList.add('fade-out');
        }, 200);
        setTimeout(() => {
          oldLoader.remove();
        }, 400);
      }
    }
  }, [props]);

  return (
    props.loading &&
    <div className="fixed top-0 left-0 w-full h-full flex-center loader loader__global flex flex-col w-full bg-white"
         style={{ zIndex: 99999 }}>

      <div className="flex-center flex-col">
        <ReactSVG
          src={`${lc_data.dir}dist/${LoaderIcon}`}
          className="relative"
          style={{ zoom: .4 }}
        />
        <p className="text-lg text-grey-900">{props.title || ''}</p>
      </div>

    </div>
  );
};

export default LoaderGlobal;
