/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import ProductSidebarMenuComponentEl from './ProductSidebarMenuComponentEl';
import queryString from 'query-string';

/**
 * Internal dependencies
 */

const ProductSidebarMenuEL = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product} = data;
  const [showMenuSide, setShowMenuSide] = useState('');

  useEffect(() => {
    window.addEventListener('resize', resizeHandler);
    resizeHandler();
    return () => {
      window.removeEventListener('resize', resizeHandler);
    };
  }, []);

  let elementorPreview = Object.keys(queryString.parse(location.search)).find(key => key === 'elementor-preview');
  const resizeHandler = () => {
    let el = document.querySelector('.elementor-product-sidebar-menu');
    let elementorColumn = el && el.closest('.elementor-column');
    if (elementorColumn) {
      if (window.innerWidth > 1620) {
        setShowMenuSide('side');
      } else if (window.innerWidth > 1025) {
        elementorColumn.style.position = '';
        elementorColumn.style.left = '';
      } else if (elementorPreview === undefined && window.innerWidth < 1025) {
        setShowMenuSide('');
        elementorColumn.style.position = 'fixed';
        elementorColumn.style.left = '-1000px';
      }
    }
  };
  return (
    <div>
      <Fragment>{product.groups && product.groups.groups && ('side' === showMenuSide || elementorPreview) &&
      <aside className="product--menu__aside product--menu__aside_elementor fixed flex justify-end py-24 px-30">
        <ProductSidebarMenuComponentEl product={product}/>
      </aside>
      }</Fragment>
    </div>
  );
};

export default ProductSidebarMenuEL;
