/* global lc_data, React */

import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import BusinessSingleDefault from './route/BusinessSingleDefault';
import BusinessSinglePremium from './route/BusinessSinglePremium';
import { Fragment } from 'react';
import LoaderBusinessSingle from '../loaders/LoaderBusinessSingle';

const BusinessSingle = (props) => {
  const [loading, setLoading] = useState(true);
  const [type, setType] = useState(null);
  const [options, setOptions] = useState({});

  useEffect(() => {
    setOptions(JSON.parse(document.getElementById('page-single-business').dataset.options));
    setLoading(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.business_type}/${lc_data.current_product_id}`;
    axios({
      credentials: 'same-origin',
      //headers,
      method: 'get',
      url,
    }).then(data => {
      setType(data.data);
      setLoading(false);
    });
  }, []);

  return (
    <Fragment>
      {!loading && type && <BusinessSinglePremium options={options}/>}
      {!loading && !type && <BusinessSingleDefault options={options}/>}
    </Fragment>
  );
};

export default BusinessSingle;
