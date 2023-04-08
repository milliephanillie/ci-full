/* global React, lc_data */

/**
 * External dependencies.
 */

const LoaderAuth = (props) => {

  return (
    <div className="loader loader__auth flex flex-col w-full">
      <div className="loader--title mb-40">
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '120px', height: '60px' }}></div>
        <div className="load flex bg-grey-100 rounded" style={{ width: '160px', height: '20px' }}></div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex mr-4 h-20 w-20 bg-white rounded-full"></div>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex mr-4 h-20 w-20 bg-white rounded-full"></div>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex mr-4 h-20 w-20 bg-white rounded-full"></div>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field flex items-center w-full mb-20">
        <div className="load mr-4 w-20 h-20 bg-grey-100 rounded"></div>
        <div className="load w-100 h-20 bg-grey-100 rounded"></div>
      </div>

      <div className="loader--field w-full">
        <div className="load flex-center w-full h-48 bg-grey-100 rounded">
          <div className="mr-4 w-20 h-20 bg-white rounded-full"></div>
          <div className="w-60 h-20 bg-white rounded"></div>
        </div>
      </div>

    </div>
  );
};

export default LoaderAuth;
