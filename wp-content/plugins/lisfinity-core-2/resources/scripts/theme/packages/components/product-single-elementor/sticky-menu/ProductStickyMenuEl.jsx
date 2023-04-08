/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import ProductStickyMenuComponent from "./ProductStickyMenuComponent";
import queryString from "query-string";

/**
 * Internal dependencies
 */

const ProductStickyMenuEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options, user} = data;
  const [stickyMenuClasses, setStickyMenuClasses] = useState('');

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;
  let elementorContainer = null;
  const params = queryString.parse(location.search);

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-sticky-menu');
    elementorContainer = el.current.closest('.elementor-section');
    if (!params['elementor-preview']) {
      elementorContainer.style.position = 'fixed';
      elementorContainer.style.top = '-1000px';
      elementorContainer.style.width = '100%';
      elementorContainer.style.zIndex = '9999';
    } else {
      let parentDiv = document.querySelectorAll('.product--single-content-wrapper');
      let explanationText = document.createElement('p');
      explanationText.innerHTML = '<b>Note:</b> To edit sticky menu, click on this message. In order to see sticky menu, you need to scroll down a bit. This explanation will appear only in the Elementor edit mode.';
      explanationText.style.backgroundColor = 'rgba(230, 246, 255, 1)';
      explanationText.style.color = 'rgba(9, 103, 210, 1)';
      explanationText.style.borderColor = 'rgba(186, 227, 255, 1)';
      explanationText.style.borderWidth = '1px';
      explanationText.style.padding = '20px 30px';
      explanationText.style.borderRadius = '3px';
      explanationText.style.position = 'relative';
      explanationText.style.left = '145px';
      let t = parentDiv[0]
      t = t.appendChild(explanationText);
    }
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  useEffect(() => {
    if (window.innerWidth <= 960) {
      setStickyMenuClasses('left-0 w-full');
    } else {
      setStickyMenuClasses('');
    }
  }, []);

  return (
    <div
      className="product--single-content-wrapper w-full py-20 pb-30 bg-white to-print px-20 bg-white shadow-theme px-10 xs:px-20 sm:px-40 bg:px-0 bg:w-4/6 bg:shadow-none"
      ref={el}>
      {product.groups && product.groups.groups &&
        <aside
          className={`product--menu-sticky flex fixed container px-0 z-10 ${stickyMenuClasses}`} style={{
          zIndex: '1000',
          top: lc_data.user_admin && !params['elementor-preview'] ? 32 : 0,
        }}>
          <ProductStickyMenuComponent product={product} currentUser={user} iconSettings={elSettings}/>
        </aside>
      }
    </div>
  );
};

export default ProductStickyMenuEl;
