/* global lc_data, React */

import { map } from 'lodash';

const LoaderBusinessArchiveQuery = (props) => {
  const { data, options, type } = props;
  let times = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
  if (props.promoted) {
    times = [0, 1, 2, 3, 4, 5];
  }

  return (
    <div className="loader loader__dashboard-products flex flex-col">
      <div className="flex vendors--title items-baseline">
        <div className="mt-20 bg-grey-200 rounded" style={{
          width: '200px',
          height: '20px',
        }}></div>
      </div>

      <div className="vendors--query flex flex-wrap mt-20 mb-10 -mx-col">
        {times.map((index) => (
          <div key={index} className="mb-20 px-col w-full sm:w-1/2 lg:w-1/3">
            <div
              className="vendor flex flex-wrap sm:flex-no-wrap items-center p-20 bg-grey-200 rounded shadow-theme">
              <div
                className="relative flex-center mr-20 p-10 bg-white border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
                style={{ height: '84px', width: '90px', borderWidth: '6px' }}
              >
                <div className="load absolute w-full h-full bg-grey-100"></div>
              </div>
              <div className="flex flex-col">
                <div className="load rounded" style={{ width: '140px', height: '20px' }}></div>
                <div className="flex items-center mt-10">
                  <div className="load flex rounded-full" style={{
                    width: '36px',
                    height: '36px',
                  }}></div>
                  <div className="load flex rounded-full ml-10" style={{
                    width: '36px',
                    height: '36px',
                  }}></div>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default LoaderBusinessArchiveQuery;
