/* global React, lc_data */

/**
 * External dependencies.
 */

const LoaderChart = (props) => {

  return (
    <div className={`loader loader__auth flex w-full ${props.style === 'white' ? 'p-20 bg-white' : ''}`}>

      <div className="flex flex-col -mb-10" style={{ width: '3%' }}>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
        <div className="load flex mb-10 bg-grey-100 rounded" style={{ width: '12px', height: '20px' }}></div>
      </div>

      <div className="flex items-end" style={{ width: '97%' }}>
        <div className="flex flex-col px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '120px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '220px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '220px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '170px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '210px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '80px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '260px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '120px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '220px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '210px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '220px' }}></div>
        </div>
        <div className="px-8 w-1/12">
          <div className="load flex w-full bg-grey-100 rounded" style={{ height: '120px' }}></div>
        </div>
      </div>

    </div>
  );
};

export default LoaderChart;
