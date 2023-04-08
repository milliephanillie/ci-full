/* global lc_data, React */

import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import { Fragment } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { setProduct } from '../../store/actions';

const BusinessSingleEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(true);
  const product = data?.searchData?.product;
  const [type, setType] = useState(null);
  const [options, setOptions] = useState({});

  useEffect(() => {
    let el = document.getElementById('page-single-business-elementor');
    if (!el) {
      el = document.getElementById('page-single-business-premium-elementor');
    }
    if (el) {
      setOptions(JSON.parse(el?.dataset?.options));
    }
    setLoading(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.business}/${lc_data.current_business_id}`;
    axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
    }).then(data => {
      dispatch(setProduct(data.data));
      setType(data.data);
      setLoading(false);
    });
  }, []);

  return (
    <Fragment>
    </Fragment>
  );
};

export default BusinessSingleEl;
