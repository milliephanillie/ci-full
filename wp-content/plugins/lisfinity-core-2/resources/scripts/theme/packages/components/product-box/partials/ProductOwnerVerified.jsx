/* global lc_data, React */
/**
 * External dependencies.
 */
import {Component, useEffect, useState} from '@wordpress/element';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import {filter, isEmpty} from 'lodash';
import checkmarkIcon from "../../../../../../images/icons/checkmark.svg";
import ReactTooltip from 'react-tooltip';

/**
 * Internal dependencies
 */

const ProductOwnerVerified = () => {
  const [settings, setSettings] = useState({});

  useEffect(() => {
    let idBusiness = document.getElementById('business-store');
    let idSearch = document.getElementById('search-listings');

    if (idBusiness) {
      const optionsNew = JSON.parse(idBusiness.dataset.settings);
      setSettings(optionsNew);
    } else if (idSearch) {
      const optionsNewSearch = JSON.parse(idSearch.dataset.options);
      setSettings(optionsNewSearch);
    }
  }, []);

  return (
    <div className="absolute top-20 left-20 flex-center w-32 h-32 rounded-full z-20 author-verified-container" title={lc_data.jst[710]}>
      <div className="flex-center w-full h-full bg-green-500 rounded-full author-verified-wrapper">
        {(isEmpty(settings) || isEmpty(settings?.icon)) &&
        <ReactSVG
          src={`${lc_data.dir}dist/${checkmarkIcon}`}
          className={`w-14 h-14 fill-white`}
        />
        }
        {settings?.icon && settings?.icon?.url &&
        <ReactSVG
          src={`${settings.icon.url}`}
          className={`w-14 h-14 author-verified-icon fill-white`}
        />
          }
          {settings?.icon && !settings?.icon?.url &&
          <i className={`${settings.icon} author-verified-icon h-14 w-14`}
             aria-hidden="true"></i>
          }
      </div>
    </div>
  );
};

export default ProductOwnerVerified;
