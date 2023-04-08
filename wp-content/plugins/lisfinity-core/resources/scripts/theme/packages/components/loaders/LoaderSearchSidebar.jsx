/* global React, lc_data */

/**
 * External dependencies.
 */

const LoaderSearchSidebar = (props) => {

  return (
    <div className="loader loader__auth flex flex-col mt-30 w-full">

      <div className="loader--field w-full mb-20">
        <div className="flex items-center justify-between">
          <div className="flex items-center">
            <div className="load flex mb-4 mr-4 bg-grey-100 rounded-full" style={{ width: '20px', height: '20px' }}></div>
            <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '20px' }}></div>
          </div>
          <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '14px' }}></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '8px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="flex flex-wrap w-full">
        <div className="loader--field w-full sm:w-1/2 sm:pr-6 mb-20">
          <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '8px' }}></div>
          <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
            <div className="flex h-16 w-192 bg-white rounded-full"></div>
          </div>
        </div>
        <div className="loader--field w-full sm:w-1/2 sm:pl-6 mb-20">
          <div className="load flex mb-4 bg-grey-100 rounded opacity-0" style={{ width: '60px', height: '8px' }}></div>
          <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
            <div className="flex h-16 w-192 bg-white rounded-full"></div>
          </div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '8px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '8px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mb-20">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '8px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field w-full mt-10">
        <div className="load flex mb-4 bg-grey-100 rounded" style={{ width: '60px', height: '8px' }}></div>
        <div className="load flex items-center w-full p-20 bg-grey-100 rounded" style={{ height: '44px' }}>
          <div className="flex h-16 w-192 bg-white rounded-full"></div>
        </div>
      </div>

      <div className="loader--field mt-20 w-full">
        <div className="load flex-center w-full h-48 bg-grey-100 rounded">
          <div className="mr-4 w-20 h-20 bg-white rounded-full"></div>
          <div className="bg-white rounded" style={{ width: 120, height: 14 }}></div>
        </div>
      </div>

      <div className="loader--field mt-16 w-full">
        <div className="load flex-center w-full h-48 bg-grey-100 rounded">
          <div className="bg-white rounded" style={{ width: 120, height: 14 }}></div>
        </div>
      </div>

    </div>
  );
};

export default LoaderSearchSidebar;
