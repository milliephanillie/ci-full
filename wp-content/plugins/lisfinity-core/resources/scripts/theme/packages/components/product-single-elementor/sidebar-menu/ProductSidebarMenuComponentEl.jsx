/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {getProduct, productMenuActive, setProduct} from '../../../store/actions';
import LoaderProductSingle from '../../loaders/LoaderProductSingle';
import {ToastContainer} from 'react-toastify';
import {useDispatch, useSelector} from 'react-redux';
import ProductMenu from "../../product-single/partials/menu/ProductMenu";
import {map} from "lodash";
import {isInViewport} from "../../../../vendor/functions";

/**
 * Internal dependencies
 */

const ProductSidebarMenuComponentEl = (props) => {
  const [active, setActive] = useState('basic');
  const data = useSelector(state => state);
  const {product, options, user} = data;
  const dispatch = useDispatch();

  function getIds() {
    const ids = [];

    product?.groups && product?.groups?.groups &&
    map(product.groups.groups, (group, name) => {
      if (data.specificationMenu.includes(group.slug)) {
        ids.push(group.slug);
      }
    });

    return ids;
  }

  function goTo(id, e) {
    const el = document.getElementById(id);

    if (el) {
      const boundEl = el.getBoundingClientRect()
      window.scrollTo({
        top: boundEl.top + window.scrollY - boundEl.height / 2,
        behavior: 'smooth',
      });
    }
  }

  useEffect(() => {
    window.addEventListener('scroll', handleScroll, {passive: true});

    return () => window.removeEventListener('scroll', handleScroll);
  });

  const handleScroll = (e) => {
    const ids = getIds();
    ids.forEach(id => {
      const el = document.getElementById(id);
      if (isInViewport(el, 200)) {
        setActive(id);
        dispatch(productMenuActive(id));
      }
    });
  };



  return (
    <div className="flex flex-end py-16 px-14 bg-grey-100 rounded min-w-192">
      <ul className="-mb-4">
        {map(product.groups.groups, (group, name) => {
          const groupName = group.name;
          return (
            <li key={group.slug} className="mb-4">
              <a
                href="#"
                className={`flex py-4 px-10 rounded font-light no-underline hover:no-underline ${group.slug === data.productMenuActive ? 'bg-yellow-300 sidebar-menu-link-active' : 'sidebar-menu-link'}`}
                onClick={(e) => {
                  e.preventDefault();
                  goTo(group.slug, e);
                }}
              >
                {groupName}
              </a>
            </li>
          );
        })}
      </ul>
    </div>
  );
};

export default ProductSidebarMenuComponentEl;
