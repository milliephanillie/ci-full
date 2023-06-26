/* global lc_data, React */
/**
 * External dependencies.
 */
import store from '../../../index';
import * as actions from '../../store/actions';
import { Component, Fragment, createRef, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { map, get, omit, isEmpty } from 'lodash';
import Promotions from './promotions';
import Package from './package';
import { useSelector } from 'react-redux';

const PaymentPackages = (props) => {
  const [value, setValue] = useState(props.value);
  const allData = useSelector(state => state);
  const data = allData.formData;
  const { field, name } = props;
  const { product } = field;

  const loadProducts = () => {
    if (product) {
      switch (product) {
        case 'promotion':
          return <Promotions
            key={field.key}
            field={field}
            name={name}
            id={name}
            package={props?.package}
            value={isEmpty(data[name]) ? [] : data[name]}
          />;
        case 'payment_package':
          return <Package
            key={field.key}
            field={field}
            name={name}
            id={name}
            package={props?.package}
            value={isEmpty(data[name]) ? [] : data[name]}
          />;
        case 'premium_profile':
          return true;
      }
    }
  }

  return (
    loadProducts()
  )
}

export default PaymentPackages;
