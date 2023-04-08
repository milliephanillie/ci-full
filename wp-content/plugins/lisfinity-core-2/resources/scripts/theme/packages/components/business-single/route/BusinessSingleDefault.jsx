/* global lc_data, React */

import { useEffect, useState } from '@wordpress/element';
import { Fragment } from 'react';
import axios from 'axios';
import Owner from '../../product-single/partials/sidebar/Owner';
import Breadcrumb from '../../product-single/partials/BusinessBreadcrumb';
import Product from '../../product-box/Product';
import { __ } from '@wordpress/i18n';
import { map } from 'lodash';
import Pagination from '../../partials/BusinessPagination';
import OwnerAlt from '../../product-single/partials/sidebar/OwnerAlt';

const BusinessSingleDefault = (props) => {
  const [business, setBusiness] = useState(null);

  useEffect(() => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    }
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

  const handlePagination = (e, page) => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    }
    const url = `${lc_data.business}/${lc_data.current_product_id}/?offset=${page}`;
    axios({
      credentials: 'same-origin',
      //headers,
      method: 'get',
      url,
    }).then(data => {
      setBusiness(data.data);
    });
  }

  return (
    business &&
    <section className="py-40 lg:py-86 bg-grey-100">
      <div className="container">

        <div className="row mx-0">
          <div className="mb-20 lg:mb-40 w-full">
            <Breadcrumb product={business} currentUser={lc_data.current_user_id} type="business"/>
          </div>
        </div>

        <div className="row justify-between mx-0">

          <div className="w-full lg:w-5/16 mb-20 lg:mb-0">
            <div className="p-20 bg-white rounded shadow-theme">
              <OwnerAlt product={business} currentUser={lc_data.current_user_id} businessPage={true} options={props.options}/>
            </div>
          </div>

          <div className="w-full lg:w-11/16 lg:pl-20">

            <div className="flex flex-wrap author--products w-full overflow-y-hidden">
              <Fragment>
                {business && Object.keys(business.products.products).length > 0
                  ? map(business.products.products, (post, index) => {
                    return (
                      <Product key={index} product={post} options={props.options} productClasses="mb-30 lg:px-12 w-full sm:w-1/2"
                               imageClasses="h-product-2-thumb"
                               titleClasses="text-lg"/>
                    );
                  })
                  : <div
                    className="font-bold text-sm-shadow text-grey-300">{lc_data.jst[402]}</div>}

                {business && business.products.max_num_pages > 1 &&
                <div key={2} className="w-full">
                  <Pagination
                    page={business.products.page}
                    pages={business.products.max_num_pages}
                    results={business.products}
                    handlePagination={handlePagination}
                  />
                </div>}

              </Fragment>
            </div>

          </div>

        </div>
      </div>
    </section>
  );
}

export default BusinessSingleDefault;
