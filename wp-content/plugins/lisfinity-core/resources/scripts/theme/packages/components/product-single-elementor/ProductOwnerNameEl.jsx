/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {getProduct, setProduct} from '../../store/actions';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import {ToastContainer} from 'react-toastify';
import {useDispatch, useSelector} from 'react-redux';

/**
 * Internal dependencies
 */

const ProductOwnerNameEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options} = data;
  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-owner-name');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  return (
    <h6 className="mb-6 font-bold text-xl bg:text-base lg:text-xl" id='product-owner--name' ref={el}>
      {(elSettings['membership_name'] === 'always' || (elSettings['membership_name'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <a href={product?.premium_profile?.url}>{product?.premium_profile?.title}</a>
      }
    </h6>
  );
};

export default ProductOwnerNameEl;
