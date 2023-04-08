/* global lc_data, React */
/**
 * Dependencies.
 */
import {Fragment} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import PhotoSwipe from "../product-single/partials/slider/PhotoSwipe";
import ProductSliderEl from "./ProductSliderEl";

/**
 * Internal dependencies
 */

const ProductGalleryEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options} = data;

  return (
    product &&
    <Fragment>
      <ProductSliderEl product={product}/>
      <PhotoSwipe product={product}/>
    </Fragment>
  );
};

export default ProductGalleryEl;
