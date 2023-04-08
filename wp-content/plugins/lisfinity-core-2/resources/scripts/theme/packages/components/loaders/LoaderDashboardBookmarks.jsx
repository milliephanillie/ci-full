/* global React, lc_data */

import ReactSVG from 'react-svg';
import CloseIcon from '../../../../../images/icons/close.svg';

const LoaderDashboardBookmarks = (props) => {
  const times = [0, 1, 2, 3, 4, 5];

  return (
    times.map((index) => (
      <div key={index} className="mb-20 px-col w-full sm:w-1/3">
        <div className="relative flex flex-wrap shadow-theme rounded overflow-hidden">
          <div className="w-1/4">
            <figure className="load relative min-h-86 bg-white">
            </figure>
          </div>
          <div className="p-20 bg-grey-100 w-3/4">
            <div className="load relative bg-white" style={{ width: '160px', height: '14px' }}>
            </div>
          </div>
        </div>
      </div>
    ))
  );
};

export default LoaderDashboardBookmarks;
