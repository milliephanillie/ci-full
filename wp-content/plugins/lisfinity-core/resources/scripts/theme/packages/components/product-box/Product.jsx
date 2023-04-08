/* global lc_data, React */
/**
 * External dependencies.
 */
import ProductStyle1 from './product-styles/ProductStyle1';
import ProductStyle2 from './product-styles/ProductStyle2';
import ProductStyle3 from './product-styles/ProductStyle3';
import { useEffect, useState } from '@wordpress/element';
import ProductStyle4 from './product-styles/ProductStyle4';
import Pagination from '../partials/Pagination';
import { useRef } from 'react';

const Product = (props) => {
  const [style, setStyle] = useState(props.style || lc_data.search_product_style);
  const { product, imageClasses, titleClasses, showMap } = props;
  let { productClasses } = props;

  if (props?.style === 'custom') {
    productClasses += ' lisfinity-products--custom';
  }
  if (showMap) {
    productClasses += ' map-active';
  }

  const loadCorrectProductbox = () => {
    switch (style) {
      case '1':
        return (
          <ProductStyle1
            product={product}
            productClasses={productClasses}
            imageClasses={imageClasses}
            titleClasses={titleClasses}
            options={props.options}
            showMap={showMap}
            type={props.type ?? 'default'}
          />
        );
      case '2':
        return (
          <ProductStyle2
            product={product}
            productClasses={productClasses}
            options={props.options}
            imageClasses={imageClasses}
            titleClasses={titleClasses}
            showMap={showMap}
            type={props.type ?? 'default'}
          />
        );
      case '3':
        return (
          <ProductStyle3
            product={product}
            productClasses={productClasses}
            imageClasses={imageClasses}
            options={props.options}
            titleClasses={titleClasses}
            showMap={showMap}
            type={props.type ?? 'default'}
          />
        );
      case '4':
        return (
          <ProductStyle4
            product={product}
            productClasses={productClasses}
            imageClasses={imageClasses}
            options={props.options}
            titleClasses={titleClasses}
            showMap={showMap}
            fieldOptions={props.fieldOptions}
            settings={props.settings}
            type={props.type ?? 'default'}
          />
        );
      case 'custom':
        return (
          <ProductStyle4
            product={product}
            productClasses={productClasses}
            imageClasses={imageClasses}
            options={props.options}
            titleClasses={titleClasses}
            showMap={showMap}
            settings={props.settings}
            fieldOptions={props.fieldOptions}
            type={props.type ?? 'default'}
          />
        );
      default:
        return <ProductStyle1
          product={product}
          productClasses={productClasses}
          imageClasses={imageClasses}
          options={props.options}
          titleClasses={titleClasses}
          showMap={showMap}
          type={props.type ?? 'default'}
        />;
    }
  };

  return (
    loadCorrectProductbox()
  );
};

export default Product;
