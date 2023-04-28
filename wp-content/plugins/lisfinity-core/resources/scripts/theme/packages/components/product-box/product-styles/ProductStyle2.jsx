/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import ProductBookmark from '../partials/ProductBookmark';
import ProductMain from '../partials/ProductMain';
import ProductOwner from '../partials/ProductOwner';
import { storeStat } from '../../../../vendor/functions';
import ProductOwnerVerified from "../partials/ProductOwnerVerified";

const ProductStyle2 = (props) => {
  const { product, productClasses, imageClasses, titleClasses, searchData, options } = props;
  const { post_status } = product;

  useEffect(() => {
    storeStat(product.ID, 1);
  }, []);

  return (
    <article className={productClasses}>
      <div className="lisfinity-product relative rounded shadow-theme overflow-hidden">
        {'sold' === post_status && <span className="product__sold-out">{lc_data.jst[694]}</span>}
        {
          product.user_verified === 1 && props.options?.['product-owner-verified'] === '1' &&
          <ProductOwnerVerified/>
        }
        <ProductBookmark product={product}/>
        <ProductMain product={product} options={props.options}/>
        <div
          className={`lisfinity-product--owner flex flex-wrap items-center justify-between p-24 ${product.promoted_color ? 'bg-bump-color' : 'bg-white'}`}>
          <ProductOwner options={options} product={product} type={props.type}/>
        </div>
      </div>
    </article>
  );
};

export default ProductStyle2;
