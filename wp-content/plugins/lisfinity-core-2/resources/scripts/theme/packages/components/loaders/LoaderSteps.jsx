/* global React, lc_data */

import LoaderAuth from './LoaderAuth';

/**
 * External dependencies.
 */

const LoaderSteps = (props) => {

  return (
    <div className="loader loader__steps flex flex-wrap w-full">

      <div className="w-1/4">
        <div className="-mb-20 py-40 px-20 bg-grey-300 rounded">
          <div className="flex w-full mb-20">
            <span className="load flex-center mr-4 bg-grey-400 rounded-full"
                  style={{ width: '24px', height: '24px' }}></span>
            <span className="load flex-center w-full bg-grey-400 rounded-full" style={{ height: '24px' }}></span>
          </div>
          <div className="flex w-full mb-20">
            <span className="load flex-center mr-4 bg-grey-400 rounded-full"
                  style={{ width: '24px', height: '24px' }}></span>
            <span className="load flex-center w-full bg-grey-400 rounded-full" style={{ height: '24px' }}></span>
          </div>
          <div className="flex w-full mb-20">
            <span className="load flex-center mr-4 bg-grey-400 rounded-full"
                  style={{ width: '24px', height: '24px' }}></span>
            <span className="load flex-center w-full bg-grey-400 rounded-full" style={{ height: '24px' }}></span>
          </div>
          <div className="flex w-full mb-20">
            <span className="load flex-center mr-4 bg-grey-400 rounded-full"
                  style={{ width: '24px', height: '24px' }}></span>
            <span className="load flex-center w-full bg-grey-400 rounded-full" style={{ height: '24px' }}></span>
          </div>
        </div>
      </div>

      <div className="w-3/4 p-40 bg-white rounded shadow-theme">

        <LoaderAuth/>

      </div>

    </div>
  );
};

export default LoaderSteps;
