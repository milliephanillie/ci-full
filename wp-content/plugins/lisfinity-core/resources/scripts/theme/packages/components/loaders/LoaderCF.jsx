/* global React, lc_data */

/**
 * External dependencies.
 */

const LoaderCF = (props) => {

  return (
    <div className="loader loader__auth flex flex-col w-full">

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-grey-200 rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-grey-200 rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-grey-200 rounded-full"></div>
        </div>
      </div>

      <div className="loader--field flex items-center w-full mb-20">
        <div className="load w-100 h-20 bg-grey-100 rounded"></div>
      </div>

    </div>
  );
};

export default LoaderCF;
