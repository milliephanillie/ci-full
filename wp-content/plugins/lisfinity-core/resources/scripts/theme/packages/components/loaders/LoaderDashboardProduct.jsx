/* global React, lc_data */

import LoaderChart from './LoaderChart';
import { Fragment, useEffect, useState } from '@wordpress/element';

/**
 * External dependencies.
 */

const LoaderDashboardProduct = (props) => {
  const [figureHeight, setFigureHeight] = useState(180);

  useEffect(() => {
    const figureHeight = () => {
      if (window.innerWidth < 1080) {
        setFigureHeight(310);
      }
    };

    figureHeight();
    window.addEventListener('load', figureHeight);
    window.addEventListener('resize', figureHeight);

    return () => {
      removeEventListener('load', figureHeight);
      removeEventListener('resize', figureHeight);
    };
  }, []);

  return (
    <div className="loader loader__auth flex flex-col w-full">

      <section className="flex flex-wrap mb-30 p-30 bg-white rounded shadow-theme overflow-hidden">
        <div className="load flex relative w-full xl:w-3/12 rounded overflow-hidden sm:flex"
             style={{ height: figureHeight }}></div>
        <div className="flex flex-col p-10 xl:pl-20 w-full xl:w-9/12 overflow-hidden">

          <div className="product-single--top flex flex-wrap justify-between items-center -mr-20">
            <div className="flex items-center">
              <div className="load bg-grey-100 rounded" style={{ width: 140, height: 20 }}></div>
              <div className="load bg-grey-100 ml-10 rounded" style={{ width: 120, height: 20 }}></div>
            </div>
            <div className="flex flex-wrap items-center">
              <div className="flex-center mr-10">
                <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 30, height: 30 }}></div>
                <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
              </div>
              <div className="flex-center mr-10">
                <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 30, height: 30 }}></div>
                <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
              </div>
              <div className="flex-center mr-10">
                <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 30, height: 30 }}></div>
                <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
              </div>
              <div className="flex-center mr-10">
                <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 30, height: 30 }}></div>
                <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
              </div>
            </div>
          </div>

          <div className="product-single--title mt-20">
            <div className="load rounded mr-2 bg-grey-100" style={{ width: 320, height: 35 }}></div>
          </div>

          <div className="product-single--meta flex flex-wrap justify-between items-end">

            <div className="flex items-center mt-20">
              <div className="load rounded-full mr-10 bg-grey-100" style={{ width: 40, height: 40 }}></div>
              <div className="flex flex-col">
                <div className="load rounded bg-grey-100" style={{ width: 40, height: 7 }}></div>
                <div className="load rounded bg-grey-100 mt-4" style={{ width: 100, height: 15 }}></div>
              </div>
            </div>

            <div className="product-single--timeline w-full xl:w-7/16 mt-20">
              <div className="load timeline relative w-full h-10 bg-grey-200 rounded overflow-hidden"></div>
              <div className="flex flex-wrap mt-10 rounded">
               <div className="load mr-4" style={{ width: 120, height: 10 }}></div>
                <div className="load mr-4" style={{ width: 20, height: 10 }}></div>
                <div className="load" style={{ width: 80, height: 10 }}></div>
              </div>
            </div>

          </div>

        </div>
      </section>


      <div className="flex flex-end items-center mb-30 py-30 px-20 bg-white rounded shadow-theme">
        <div className="flex-center mr-10">
          <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 20, height: 20 }}></div>
          <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
        </div>
        <div className="flex-center mr-10">
          <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 20, height: 20 }}></div>
          <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
        </div>
        <div className="flex-center mr-10">
          <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 20, height: 20 }}></div>
          <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
        </div>
        <div className="flex-center mr-10">
          <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 20, height: 20 }}></div>
          <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
        </div>
        <div className="flex-center mr-10">
          <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 20, height: 20 }}></div>
          <div className="load rounded mr-2 bg-grey-100" style={{ width: 60, height: 15 }}></div>
        </div>
        <div className="flex-center ml-auto">
          <div className="load rounded mr-4 bg-grey-100" style={{ width: 20, height: 20 }}></div>
        </div>
      </div>

      <LoaderChart style="white"/>

    </div>
  );
};

export default LoaderDashboardProduct;
