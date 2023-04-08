/* global React, lc_data */

import LoaderChart from './LoaderChart';
import { Fragment, useEffect, useState } from '@wordpress/element';

/**
 * External dependencies.
 */

const LoaderDashboardWidgets = (props) => {
  return (
    <div className="flex flex-wrap xl:flex-col mt-30 xl:mt-0 w-full xl:w-5/16 xl:pl-20">
      <div
        className="dashboard-widget--active-package flex flex-col p-20 bg-white rounded shadow-theme w-full sm:w-1/2 xl:w-full">
        <div className="dashboard-widget--header flex justify-between items-center mb-20 px-10">
          <div className="flex-center mr-10">
            <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 30, height: 30 }}></div>
            <div className="load rounded mr-2 bg-grey-100" style={{ width: 160, height: 15 }}></div>
          </div>
        </div>
        <div className="load flex-center w-full rounded" style={{ height: 130 }}></div>
      </div>

      <div
        className="dashboard-widget--active-package flex flex-col mt-20 p-20 bg-white rounded shadow-theme w-full sm:w-1/2 xl:w-full">
        <div className="dashboard-widget--header flex justify-between items-center mb-20 px-10">
          <div className="flex-center mr-10">
            <div className="load rounded-full mr-4 bg-grey-100" style={{ width: 30, height: 30 }}></div>
            <div className="load flex rounded mr-2 bg-grey-100" style={{ width: 160, height: 15 }}></div>
          </div>
        </div>
        <div className="load flex-center w-full rounded" style={{ height: 130 }}></div>
        <div className="load flex items-center px-20 w-full rounded mt-20" style={{ height: 60 }}>
          <div className="rounded bg-white" style={{ width: 160, height: 15 }}></div>
          <div className="rounded-full ml-auto bg-white" style={{ width: 30, height: 30 }}></div>
        </div>
        <div className="load flex-center rounded mt-10" style={{ width: '50%', height: 15 }}></div>
      </div>
    </div>
  );
};

export default LoaderDashboardWidgets;
