/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import SafetyTipsEl from "./SafetyTipsEl";

/**
 * Internal dependencies
 */

const ProductSafetyTipsEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options, currentUser} = data;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-safety-tips');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  return (
    '0' !== options.safety_tips &&
    <div className="profile--tips mt-30 py-30 px-20 bg-white rounded shadow-theme" ref={el}>
      {(elSettings['membership_safety_tips'] === 'always' || (elSettings['membership_safety_tips'] === 'logged_in' && lc_data.logged_in === '1')) &&
        <SafetyTipsEl product={product} settings={elSettings} currentUser={currentUser} />}
    </div>

  );
};

export default ProductSafetyTipsEl;
