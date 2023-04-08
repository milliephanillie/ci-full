/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { getProduct, setProduct } from '../../store/actions';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import { ToastContainer } from 'react-toastify';
import { useDispatch, useSelector } from 'react-redux';

/**
 * Internal dependencies
 */

const ProductIdEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options } = data;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-id');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);



  return (
    <div className="product--id text-grey-1000 text-13 font-light" ref={el} ><span className="id-text">{elSettings.id_text}</span>{product.ID}</div>
  );
};

export default ProductIdEl;
