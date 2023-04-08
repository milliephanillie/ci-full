/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import { Component, Fragment } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { sprintf, __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../../images/icons/home.svg';

/**
 * Internal dependencies
 */

function Breadcrumb(props) {
  const { product } = props;
  const productBase = lc_data.product_permalink.product_base;

  return (
    <Fragment>
      <nav className="search--breadcrumb">
        <ul className="flex items-center -mx-4">
          <li className="flex items-center px-2">
            <ReactSVG
              src={`${lc_data.dir}dist/${homeIcon}`}
              className="relative mr-8 w-16 h-16 fill-icon-home"
            />
            <a href={lc_data.site_url} className="text-grey-900">{lc_data.jst[467]}</a>
          </li>
          {product?.product_meta.category &&
              <li className="flex items-center px-2">
                <span className="mr-4">/</span>
                <a href={`${lc_data.site_url}` + 'ad-category/' + `${product.product_meta.category}`} className="text-grey-900">Concrete Equipment</a>
              </li>

          }
          {product?.taxonomies['concrete-equipment-type'] &&
              <li className="flex items-center px-2">
                <span className="mr-4">/</span>
                <a href={'/ad-category/' + `${product.product_meta.category}` + '/?&tax%5Bconcrete-equipment-type%5D=' + `${product.taxonomies['concrete-equipment-type'].term_slug}`} className="text-grey-900">{product.taxonomies['concrete-equipment-type'].term}</a>
              </li>

          }
          {product?.taxonomies['concrete-equipment-subcategory'] &&
              <li className="flex items-center px-2">
                <span className="mr-4">/</span>
                <a href={'/ad-category/' + `${product.product_meta.category}` + '/?tax%5Bconcrete-equipment-subcategory%5D=' + `${product.taxonomies['concrete-equipment-subcategory'].term_slug}` + '&tax%5Bconcrete-equipment-type%5D=' + `${product.taxonomies['concrete-equipment-type'].term_slug}`} className="text-grey-900">{product.taxonomies['concrete-equipment-subcategory'].term}</a>
              </li>

          }
        </ul>
      </nav>
    </Fragment>
  );
}

export default Breadcrumb;
