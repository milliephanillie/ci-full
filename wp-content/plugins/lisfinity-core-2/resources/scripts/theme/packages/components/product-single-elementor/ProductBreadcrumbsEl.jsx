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
import ReactSVG from "react-svg";
import homeIcon from "../../../../../images/icons/home.svg";

/**
 * Internal dependencies
 */

const ProductBreadcrumbsEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options } = data;
  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-breadcrumbs');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  let icon = null;
  let svg = null;

  if (elSettings?.icon_breadcrumbs !== null && elSettings?.icon_breadcrumbs) {
    typeof elSettings.icon_breadcrumbs['value'] === 'string' ? icon = elSettings.icon_breadcrumbs['value'] : svg = elSettings.icon_breadcrumbs['value']['url'];
  }
  return (
    <Fragment>
      <nav className="search--breadcrumb container flex flex-wrap justify-between items-center" ref={el}>
        <ul className="flex items-center -mx-4">
          <li className="flex items-center px-2">
            {(icon === null && svg === null || "" == icon)  &&
            <ReactSVG
              src={`${lc_data.dir}dist/${homeIcon}`}
              className="relative mr-8 w-16 h-16 fill-icon-home products-icon-breadcrumbs"
            />
            }
            {
              svg && elSettings.place_icon_breadcrumbs !== '' &&
              <img src={svg} alt="cart-icon"
                   className="w-20 h-20 mr-8 products-icon-breadcrumbs fill-icon-reset pointer-events-none"/>
            }
            {
              elSettings.place_icon_breadcrumbs !== '' && icon &&
              <i className={`${icon} products-icon-breadcrumbs`} style={{
                display: 'flex',
                alignSelf: 'center'
              }}
                 aria-hidden="true"
              ></i>
            }
            <a href={lc_data.site_url} className="text-grey-900">{lc_data.jst[467]}</a>
          </li>
          <li className="flex items-center px-2">
            <span className="mr-4">/</span>
            <a href={`${options.page_search}`} className="text-grey-900">{lc_data.jst[24]}</a>
          </li>
          <li className="px-4">
            <span className="mr-4">/</span>
            <span
              className="font-semibold text-grey-1100">{product.post_title}</span>
          </li>
        </ul>
        <div className="information">
          <strong className="text-grey-900 current--page font-semibold">{product?.all_products?.product_position}</strong>
          <span className="mx-2 text-grey-700">{lc_data.jst[485]}</span>
          <strong className="text-grey-900 font-semibold">{product?.all_products?.products_count}</strong>
        </div>
      </nav>
    </Fragment>
  );
};

export default ProductBreadcrumbsEl;
