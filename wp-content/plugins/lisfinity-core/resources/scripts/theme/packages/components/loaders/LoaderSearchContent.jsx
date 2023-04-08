/* global React, lc_data */

/**
 * dependencies.
 */
import { map } from 'lodash';

const LoaderSearchContent = (props) => {
  const products = {
    1: 240,
    2: 320,
    3: 400,
    4: 160,
    5: 220,
    6: 380,
    7: 140,
    8: 280,
    9: 280,
    10: 280,
    11: 280,
    12: 280,
  };

  return (
    <div
      className={`relative row row--products -mx-18 overflow-y-hidden ${props.type === 'business' ? 'mt-10 w-auto' : 'w-full'}`}>
      {map(products, (index, name) => {
        return (
          <article key={name} className={props.productClasses}>
            <div className="lisfinity-product relative h-full bg-white rounded shadow-theme overflow-hidden">
              <div className="load bg-grey-200 rounded-t"
                   style={{
                     height: 260,
                   }}
              ></div>
              <div className="flex flex-col p-20 bg-white"
                   style={{
                     height: 200,
                   }}
              >
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <div className="load mr-2 bg-grey-100 rounded-full" style={{ height: 12, width: 10 }}></div>
                    <div className="load mr-2 bg-grey-100 rounded" style={{ height: 12, width: 80 }}></div>
                  </div>
                  <div className="flex items-center">
                    <div className="load mr-2 bg-grey-100 rounded" style={{ height: 12, width: 40 }}></div>
                  </div>
                </div>
                <div className="flex items-center mt-20">
                  <div className="load mr-2 bg-grey-100 rounded" style={{ height: 24, width: index }}></div>
                </div>
                <div className="flex items-center mt-20">
                  <div className="load mr-1 bg-grey-100 rounded" style={{ height: 16, width: 40 }}></div>
                  <div className="load mr-1 bg-grey-100 rounded" style={{ height: 16, width: 30 }}></div>
                  <div className="load mr-1 bg-grey-100 rounded" style={{ height: 16, width: 40 }}></div>
                  <div className="load mr-1 bg-grey-100 rounded" style={{ height: 16, width: 30 }}></div>
                </div>
                <div className="flex items-center justify-between mt-20">
                  <div className="flex items-center">
                    <div className="load mr-2 bg-grey-100 rounded-full" style={{ height: 32, width: 32 }}></div>
                    <div className="load mr-20 bg-grey-100 rounded" style={{ height: 10, width: 20 }}></div>
                    <div className="load mr-2 bg-grey-100 rounded-full" style={{ height: 32, width: 32 }}></div>
                    <div className="load mr-2 bg-grey-100 rounded" style={{ height: 10, width: 30 }}></div>
                  </div>
                  <div className="flex items-center">
                    <div className="load bg-grey-100 rounded-full" style={{ height: 40, width: 40 }}></div>
                  </div>
                </div>
              </div>
              <span className="hidden">{name}</span>
            </div>

          </article>
        );
      })}
    </div>
  );
};

export default LoaderSearchContent;
