/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { sprintf, __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import LockIcon from '../../../../../../../images/icons/lock.svg';

function SafetyTips(props) {
  const { product, currentUser } = props;

  return (
    <div className="tips flex flex-wrap items-center">

      <div className="tips--icon pr-20">
        <div className="flex-center bg:mb-10 xl:mb-0 p-10 bg-blue-200 rounded-full" style={{ width: '70px', height: '70px' }}>
          <ReactSVG
            src={`${lc_data.dir}dist/${LockIcon}`}
            className="w-36 h-36 fill-icon-tips"
          />
        </div>
      </div>

      <div className="tips--content flex flex-col w-2/3 bg:w-full xl:w-2/3">
        <h6 className="widget--label mb-5 font-bold">{lc_data.jst[535]}</h6>
        <p>{sprintf(lc_data.jst[536], lc_data.site_title)}</p>
        <a
          href={product.tips_permalink}
          target="_blank"
          rel="noopener noreferrer"
          className="text-blue-600"
        >
          {lc_data.jst[537]}
        </a>
      </div>

    </div>
  );
}

export default SafetyTips;
