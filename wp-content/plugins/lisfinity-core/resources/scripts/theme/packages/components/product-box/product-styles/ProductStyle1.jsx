/* global lc_data, React */

/**
 * Internal dependencies
 */
import ProductBookmark from '../partials/ProductBookmark';
import ProductMain from '../partials/ProductMain';
import { storeStat } from '../../../../vendor/functions';
import { useEffect } from '@wordpress/element';
import ProductOwnerVerified from "../partials/ProductOwnerVerified";

const ProductStyle1 = (props) => {
  const { product, productClasses, imageClasses, titleClasses, heightClass, is_ad } = props;
  const { post_status } = product;
  return (
    <article className={productClasses}>
      <div className="lisfinity-product relative rounded shadow-theme overflow-hidden">
        {'sold' === post_status && <span className="product__sold-out">{lc_data.jst[694]}</span>}
        {
          product.user_verified === 1 && props.options?.['product-owner-verified'] === '1' &&
          <ProductOwnerVerified/>
        }
        <ProductBookmark product={product}/>
        <ProductMain product={product} imageClasses={imageClasses} heightClass={heightClass} is_ad={is_ad} options={props.options} />
      </div>
    </article>
  );
}

export default ProductStyle1;
