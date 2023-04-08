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
import {debounce, isEmpty} from "lodash";
import axios from "axios";
import produce from "immer";

/**
 * Internal dependencies
 */

const AuthorsEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const options = JSON.parse(document.getElementById('page-vendors-elementor').dataset.options);
    dispatch(setOptions(options));
  }, []);


  return (
    <Fragment>
      {<LoaderGlobal loading={loading} title={lc_data.jst[697]}/>}
      {!loading && <Fragment></Fragment>}
      <ToastContainer/>
    </Fragment>
  );
};

export default AuthorsEl;
