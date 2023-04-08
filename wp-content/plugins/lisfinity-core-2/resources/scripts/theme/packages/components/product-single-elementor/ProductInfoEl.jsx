/* global lc_data, React */
/**
 * Dependencies.
 */
import { Component, createRef, Fragment, useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { getProduct, setProduct } from '../../store/actions';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import { ToastContainer } from 'react-toastify';
import { useDispatch, useSelector } from 'react-redux';
import ProductPrice from '../product-single/partials/content/ProductPrice';

/**
 * Internal dependencies
 */

const ProductInfoEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options, user } = data;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-info');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  return (

    <div className="product--info" ref={el}>
      {product &&
      <ProductPrice product={product} currentUser={user} options={options} iconSettings={elSettings}/>
      }
    </div>
  );
};

export default ProductInfoEl;
