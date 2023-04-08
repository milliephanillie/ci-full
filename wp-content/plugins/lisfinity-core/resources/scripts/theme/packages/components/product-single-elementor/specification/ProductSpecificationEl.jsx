/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {getProduct, setProduct} from '../../../store/actions';
import LoaderProductSingle from '../../loaders/LoaderProductSingle';
import {ToastContainer} from 'react-toastify';
import {useDispatch, useSelector} from 'react-redux';
import {isEmpty} from "lodash";
import SpecificationEl from "./SpecificationEl";

/**
 * Internal dependencies
 */

const ProductSpecificationEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options} = data;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-specification');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);



  return (
    <div ref={el}>
      {
        !isEmpty(product.taxonomies) &&  (elSettings.membership_specification === 'always' || (elSettings.membership_specification === 'logged_in' && lc_data.logged_in === '1')) &&
        <section className="product--section specification--wrapper">
          <SpecificationEl product={product} settings={elSettings}/>
        </section>
      }
    </div>

  )
    ;
};

export default ProductSpecificationEl;
