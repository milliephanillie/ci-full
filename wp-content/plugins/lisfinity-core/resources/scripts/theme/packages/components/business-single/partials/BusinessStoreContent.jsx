/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, useEffect, useState } from '@wordpress/element';
import { connect, useSelector } from 'react-redux';
import { __ } from '@wordpress/i18n';
import { map, get } from 'lodash';
import Pagination from '../../partials/Pagination';
import Product from '../../product-box/Product';
import LoaderSearchContent from '../../loaders/LoaderSearchContent';
import PaginationA from '../../partials/Pagination';
import PaginationFirstLast from '../../partials/PaginationOld';

const BusinessStoreContent = (props) => {
  const data = useSelector(state => state);
  const { postsByUrl, searchData } = data;
  const request = postsByUrl.RECEIVE_POSTS || {};

  return [
    data.adsLoading && <LoaderSearchContent key={3} type="business" productClasses="w-1/2 mb-20 px-18" />,

    !data.adsLoading &&
    <div key={1} className="lisfinity-products -mx-12 w-auto overflow-y-hidden">
      <div className="row row--products mx-0 overflow-y-hidden">
        {request.items
          ? map(request.items.products, (post, index) => {
            return (
              <Product
                key={index}
                product={post}
                options={props.options}
                productClasses="product-col mt-20 mb-10 px-14 w-full sm:w-1/2"
                imageClasses="h-product-2-thumb"
              />
            );
          })
          : <LoaderSearchContent type="business" productClasses="w-1/2 mb-20 px-18"/>}
      </div>
      {!data.adsLoading && request.items && request.items.max_num_pages > 1 &&
      <div key={2}>
        <PaginationA
          page={request.items.page}
          pages={request.items.max_num_pages}
          options={props.options}
          items={request.items.found_posts}
          type="business"
        />
        <PaginationFirstLast
          page={request.items.page}
          pages={request.items.max_num_pages}
          type="business"
        />
      </div>
      }
    </div>,

  ];
};

export default BusinessStoreContent;
