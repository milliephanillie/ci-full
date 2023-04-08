/* global lc_data, React */
/**
 * Dependencies.
 */
import { Component, useEffect, useState } from '@wordpress/element';
import ProductBookmark from '../partials/ProductBookmark';
import ProductMain from '../partials/ProductMain';
import ProductContent from '../partials/ProductContent';
import ProductOwner from '../partials/ProductOwner';
import ProductTaxonomies from '../partials/ProductTaxonomies';
import { isEmpty } from 'lodash';
import ProductOwnerVerified from '../partials/ProductOwnerVerified';
import ProductTimeline from '../partials/ProductTimeline';
import { useRef } from 'react';
import { updateMapPosition } from '../../../store/actions';
import { useDispatch } from 'react-redux';

const ProductStyle4 = (props) => {
  const { product, productClasses, imageClasses, titleClasses, showMap, options, data } = props;
  const { post_status } = product;
  const dispatch = useDispatch();

  const handleMouseEnter = id => {
    dispatch(updateMapPosition([parseFloat(product.meta.location.lat), parseFloat(product.meta.location.lng)]));
    if (showMap) {
      const markers = document.querySelectorAll('.marker');
      if (markers) {
        markers.forEach(marker => {
          if (marker.dataset.markerId == product.ID) {
            const childs = marker.children;
            childs[1].classList.add('bg-blue-600', 'text-white', 'active');
          }
        });
      }
    }
  };

  const handleMouseLeave = id => {
    if (showMap) {
      const markers = document.querySelectorAll('.marker');
      if (markers) {
        markers.forEach(marker => {
          if (marker.dataset.markerId == product.ID) {
            const childs = marker.children;
            childs[1].classList.remove('bg-blue-600', 'text-white', 'active');
          }
        });
      }
    }
  };

  return (
    <article
      className={productClasses}
      onMouseEnter={() => handleMouseEnter(product.ID)}
      onMouseLeave={() => handleMouseLeave(product.ID)}
    >
      <div className="lisfinity-product relative bg-white rounded shadow-theme overflow-hidden">

        {'sold' === post_status && <span className="product__sold-out">{lc_data.jst[694]}</span>}

        {
          product?.user_verified === 1 && props.options?.['product-owner-verified'] === '1' &&
          <ProductOwnerVerified/>
        }

        <ProductBookmark product={product}/>
        <ProductMain product={product} productStyle={3} imageClasses={imageClasses} options={props.options}/>

        <div className={`flex flex-col py-22 px-24 h-full ${product.promoted_color ? 'bg-bump-color' : 'bg-white'}`}>

          <div className="mb-14">
            <ProductContent product={product} productStyle={3} titleClasses={titleClasses} settings={props.settings}/>
          </div>

          {product && !isEmpty(product.taxonomies) &&
          <div className="lisfinity-products--cf-wrapper flex flex-wrap items-center mb-20">
            <ProductTaxonomies product={product} productStyle={3} fieldOptions={props.fieldOptions}
                               options={props.options}/>
          </div>
          }

          <div className="flex items-center justify-between">
            <ProductOwner options={options} product={product} productStyle={3} type={props.type}/>
          </div>

        </div>

      </div>
    </article>
  );
};

export default ProductStyle4;
