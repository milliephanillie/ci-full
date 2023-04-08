/* global lc_data, React */

const LoaderBusinessReviews = (props) => {
  const { data, options, type } = props;
  let times = [0, 1, 2, 3, 4, 5, 6];

  return (
    <div className="loader loader__dashboard-products flex flex-col">
      <div className="vendors--query flex flex-wrap mt-20 mb-10 -mx-col">
        {times.map((index) => (
          <div key={index} className="mb-20 px-col w-full sm:w-1/2 lg:w-1/3">
            <div
              className="vendor flex flex-col items-center p-30 bg-grey-200 rounded shadow-theme">
              <div className="flex flex-col mb-16 w-full">
                <div className="load rounded" style={{ width: '100%', height: '20px' }}></div>
                <div className="load rounded mt-8" style={{ width: '80%', height: '20px' }}></div>
              </div>
              <div className="flex items-center justify-between w-full">
                <div className="flex items-center mt-10">
                  <div className="load flex rounded-full mr-10" style={{
                    width: '36px',
                    height: '36px',
                  }}></div>
                  <div className="flex flex-col">
                    <div className="load rounded" style={{ width: '60px', height: '10px' }}></div>
                    <div className="load rounded mt-2" style={{ width: '120px', height: '15px' }}></div>
                  </div>
                </div>

                <div className="flex items-center mt-4">
                  <div className="load flex rounded-full mr-10" style={{
                    width: '36px',
                    height: '36px',
                  }}></div>
                  <div className="load rounded" style={{ width: '20px', height: '10px' }}></div>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default LoaderBusinessReviews;
