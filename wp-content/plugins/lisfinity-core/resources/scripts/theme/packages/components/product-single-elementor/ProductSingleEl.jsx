/* global lc_data, React */
/**
 * Dependencies.
 */
import { Component, Fragment, useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { getProduct, setOptions, setProduct, setUser } from '../../store/actions';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import { ToastContainer } from 'react-toastify';
import { useDispatch, useSelector } from 'react-redux';
import LoaderGlobal from '../loaders/LoaderGlobal';

/**
 * Internal dependencies
 */

const ProductSingleEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options, user } = data;
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getProductData();
    getUser();
    const options = JSON.parse(document.getElementById('page-single-elementor').dataset.options);
    dispatch(setOptions(options));
  }, []);

  const getProductData = () => {
    const productId = lc_data.current_listing_id;
    const url = `${lc_data.product}/${productId}`;

    apiFetch({ path: url })
      .then(product => {
        dispatch(setProduct(product));
        setLoading(false);
        const oldLoader = document.getElementById('loader');
        if (oldLoader) {
          oldLoader.classList.add('fade-out');
          setTimeout(() => {
            oldLoader.remove();
          }, 200);
        }
      });
  };

  const getUser = () => {
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(`${lc_data.user}/?_wpnonce=${lc_data.nonce}`, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
    }).then(response => response.json()).then(currentUser => dispatch(setUser(currentUser)));
  };

  return (
    <Fragment>
      {<LoaderGlobal loading={loading} title={lc_data.jst[697]}/>}
      {!loading && <Fragment></Fragment>}
      <ToastContainer/>
    </Fragment>
  );
};

export default ProductSingleEl;
