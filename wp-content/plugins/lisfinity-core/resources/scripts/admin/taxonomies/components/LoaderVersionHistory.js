/* global React, lc_data */

import { sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import BoltIcon from '../../../../images/icons/bolt-alt.svg';
import { Fragment } from '@wordpress/element';
import ModalMain from './ModalMain';

const LoaderVersionHistory = (props) => {
  const times = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 1];

  return (
    <div className="loader loader__version-history">

      <div className="versions--title flex justify-between items-end mb-20 pb-20 border-b border-grey-100 animate">
        <div className="load bg-grey-100" style={{ width: 220, height: 14 }}></div>
        <div className="flex flex-col items-end -mb-4">
          <span className="load" style={{ width: 70, height: 14 }}></span>
          <span className="load mt-2" style={{ width: 30, height: 8 }}></span>
        </div>
      </div>
      <div className="versions flex flex-col">
        {times.map((index) => (
          <div key={index} className={`cf-version relative flex ${index !== 0 ? 'mt-10' : ''}`}>
                <span className="text-grey-500 bg-grey-100 rounded"
                      style={{
                        top: 3,
                        left: -23,
                        width: 2,
                        height: 4,
                      }}
                >
                      </span>
            <div className="flex flex-col">
              <div className="relative load" style={{ height: 14, width: 70 }}></div>
              <div className="relative load mt-2" style={{ height: 8, width: 40 }}></div>
            </div>
            <div className="flex flex-col items-end ml-auto">
              <div className="relative load" style={{ height: 14, width: 70 }}></div>
              <div className="relative load mt-2" style={{ height: 8, width: 40 }}></div>
            </div>
          </div>
        ))}
      </div>
      <div className="terms--pagination flex flex-wrap items-center mt-20 -mx-4 pt-20 border-t border-grey-100">
        <div
          className="load mr-4"
          style={{
            width: 36,
            height: 30,
          }}></div>
        <div
          className="load mr-4"
          style={{
            width: 36,
            height: 30,
          }}></div>
      </div>

    </div>
  );
};

export default LoaderVersionHistory;
