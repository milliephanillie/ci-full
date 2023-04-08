/* global lc_data, React */

import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import BusinessHeader from '../partials/BusinessHeader';
import BusinessTabs from '../partials/BusinessTabs';
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../../store/actions';

const BusinessSinglePremium = (props) => {
  const [business, setBusiness] = useState(null);
  const data = useSelector(state => state);
  const { showFilters } = data.searchData;
  const dispatch = useDispatch();


  useEffect(() => {
    dispatch(actions.updateShowFilters(false));
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.business}/${lc_data.current_product_id}`;
    axios({
      credentials: 'same-origin',
      //headers,
      method: 'get',
      url,
    }).then(data => {
      setBusiness(data.data);
      const oldLoader = document.getElementById('loader');
      if (oldLoader) {
        setTimeout(() => {
          oldLoader.classList.add('fade-out');
          setTimeout(() => {
            oldLoader.remove();
          }, 200);
        }, 600);
      }
    });
  }, []);

  return (
    business &&
    <div className="relative bg-grey-100">
      <BusinessHeader product={business} options={props.options}/>
      <BusinessTabs business={business} options={props.options}/>
    </div>
  );
};

export default BusinessSinglePremium;
