/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import CalculatorEl from './CalculatorEl';

/**
 * Internal dependencies
 */

const ProductFinancingCalculatorEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options, currentUser} = data;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-financing-calculator');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  return (

    <div className="profile--calculator mt-30 py-20 px-20 bg-white rounded shadow-theme" ref={el}>
      {options?.calculator_position !== 'content' && product?.product_meta?.price > 0 && product?.calculator &&
      <CalculatorEl product={product} currentUser={currentUser} settings={elSettings}/>
      }
    </div>
  );
};

export default ProductFinancingCalculatorEl;
