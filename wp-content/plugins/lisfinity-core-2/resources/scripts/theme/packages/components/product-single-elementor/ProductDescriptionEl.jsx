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
import {isEmpty} from "lodash";

/**
 * Internal dependencies
 */

const ProductDescriptionEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options} = data;
  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-description');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);


  return (
    <div
      ref={el}>{!isEmpty(product.post_content) && (elSettings['membership_description'] === 'always' || (elSettings['membership_description'] === 'logged_in' && lc_data.logged_in === '1')) &&
    <section id="productDescription" className="product--section mt-40 mb-60">
      <h5 className="font-bold text-grey-1000">{lc_data.jst[16]}</h5>
      <div className="mt-20 text-grey-800" style={{lineHeight: '1.9'}}
           dangerouslySetInnerHTML={{
             __html: product.post_content,
           }}
      >
      </div>
    </section>}</div>
  );
};

export default ProductDescriptionEl;
