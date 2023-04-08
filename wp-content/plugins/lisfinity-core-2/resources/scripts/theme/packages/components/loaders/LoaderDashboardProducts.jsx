/* global React, lc_data */

const LoaderDashboardProducts = (props) => {
  const times = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

  return (
    times.map((index) => (

      <div key={index} className="loader loader__dashboard-products flex flex-col">

        <div className="product flex flex-wrap mb-20 bg-white rounded shadow-theme overflow-auto bg-white">
          <div className="load product--figure relative" style={{ height: '152px' }}>
          </div>
          <div className="relative product--title flex flex-col justify-center py-20 px-30">
            <div className="load relative" style={{ width: '60px', height: '20px' }}>
            </div>
            <div className="load relative mt-6" style={{ width: '120px', height: '20px' }}>
            </div>
            <div className="load relative mt-10" style={{ width: '180px', height: '6px' }}>
            </div>
            <div className="load relative mt-6" style={{ width: '200px', height: '4px' }}>
            </div>
          </div>
          <div className="relative product--price-table flex flex-col justify-center py-20 px-30 w-2/16">
            <div className="flex items-center">
              <div className="load relative" style={{ width: '10px', height: '10px' }}>
              </div>
              <div className="load relative ml-20" style={{ width: '60px', height: '10px' }}>
              </div>
            </div>
            <div className="flex items-center mt-10">
              <div className="load relative" style={{ width: '10px', height: '10px' }}>
              </div>
              <div className="load relative ml-10" style={{ width: '120px', height: '10px' }}>
              </div>
            </div>
          </div>
          <div className="relative product--agent flex flex-col justify-center py-20 px-30 w-3/16">
            <div className="flex items-center">
              <div className="load relative rounded-full" style={{ width: '40px', height: '40px' }}>
              </div>
              <div className="load relative ml-20" style={{ width: '120px', height: '10px' }}>
              </div>
            </div>
          </div>
          <div className="relative product--notifications flex flex-col justify-center py-20 px-30 w-4/16">
            <div className="flex items-center">
              <div className="load relative rounded" style={{ width: '120px', height: '50px' }}>
              </div>
              <div className="load relative rounded ml-2" style={{ width: '120px', height: '50px' }}>
              </div>
              <div className="load relative rounded ml-2" style={{ width: '120px', height: '50px' }}>
              </div>
            </div>
          </div>
          <div className="relative product--actions flex flex-col justify-center py-20 px-30 w-1/16">
            <div className="absolute top-20 right-60 flex-center text-sm text-grey-500">
              <div className="load relative" style={{ width: '80px', height: '8px' }}>
              </div>
              <div className="load relative ml-2" style={{ width: '18px', height: '6px' }}>
              </div>
            </div>
            <div className="flex items-center">
              <div className="load relative rounded-full" style={{ width: '40px', height: '40px' }}>
              </div>
              <div className="load relative rounded-full ml-10" style={{ width: '40px', height: '40px' }}>
              </div>
              <div className="load relative rounded-full ml-10" style={{ width: '40px', height: '40px' }}>
              </div>
            </div>
          </div>

        </div>

      </div>
    ))
  );
};

export default LoaderDashboardProducts;
