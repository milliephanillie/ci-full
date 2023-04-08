/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { __ } from '@wordpress/i18n';
import { isEmpty, map, forEach } from 'lodash';
import ReactSVG from 'react-svg';
import CloseIcon from '../../../../../../../../images/icons/close.svg';
import * as functions from '../../../../../../vendor/functions';
import LoaderIcon from '../../../../../../../../images/icons/loader-rings.svg';
import axios from 'axios';

const CompareProducts = (props) => {
  const { product, currentUser, products, loading, closeModal, options } = props;
  const [compares, setCompares] = useState(null);
  const [emptyProducts, setEmptyProducts] = useState(3);
  const [empty, setEmpty] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [smallScreen, setSmallScreen] = useState(false);
  const [xsScreen, setXsScreen] = useState(false);
  let count = 0;

  const calculateEmptyProducts = (productsCount) => {
    const array = [];
    const result = 3 - productsCount;
    for (let i = 1; i <= result; i += 1) {
      array.push(lc_data.jst[515]);
    }
    setEmptyProducts(array);
  };

  const fetchCompares = () => {
    let user = lc_data.current_user_id;
    if (!user || user == 0) {
      user = lc_data.user_ip;
    }
    setFetching(true);
    const data = {
      id: user,
      type: product ? product.product_meta.category : false,
    };
    axios({
      url: lc_data.compare_products,
      method: 'POST',
      data,
    }).then(response => {
      setCompares(response.data);
      if (!isEmpty(response.data.products)) {
        calculateEmptyProducts(response.data.products.length);
      }
      if (isEmpty(response.data.products)) {
        setEmpty(true);
      }
      setFetching(false);
    });
  };

  const removeProduct = (productId) => {
    let user = lc_data.current_user_id;
    if (!user || user == 0) {
      user = lc_data.user_ip;
    }
    const formData = new FormData();
    formData.append('product', productId);
    formData.append('user_id', user);
    fetch(`${lc_data.compare_remove}`, {
      method: 'POST',
      body: formData,
    }).then(response => response.json()).then(response => {
      fetchCompares();
    });
  };

  const defineClasses = () => {
    if (window.outerWidth < 640) {
      setSmallScreen(true);
      setXsScreen(true);
    } else if (window.outerWidth < 1180) {
      setSmallScreen(true);
      setXsScreen(false);
    } else {
      setSmallScreen(false);
      setXsScreen(false);
    }
  };

  useEffect(() => {
    window.addEventListener('resize', defineClasses);
    defineClasses();
    fetchCompares();
    return () => {
      window.removeEventListener('resize', defineClasses);
    };
  }, []);
  useEffect(() => {
  }, []);


console.log(fetching)
  return (
    <section className="compare relative p-30 overflow-x-auto">

      {fetching &&
      <div className="flex-center flex-col">
        <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-20 h-20 fill-grey-1100"
                  style={{
                    top: '-13px',
                    left: '10px',
                    width: '65px',
                  }}
        />
        <p className="mt-20 text-lg text-grey-900">{lc_data.jst[552]}</p>
      </div>
      }

      {!fetching && empty &&
      <div
        className="modal--no-content flex font-bold text-sm-shadow text-grey-300">{lc_data.jst[515]}</div>
      }
      {!fetching && !isEmpty(compares) && (isEmpty(compares.taxonomies) || (!fetching && compares.taxonomies.length < 2)) &&
      <div
        className="modal--no-content flex font-bold text-7xl text-grey-300">{lc_data.jst[587]}</div>
      }

      {!fetching && !empty && !isEmpty(compares) && compares.taxonomies.length > 1 &&
      <Fragment>
        <div className="compare--labels absolute w-full" style={{ bottom: '83px' }}>
          <p className="compare--title py-16 px-30 font-semibold text-grey-500">{lc_data.jst[516]}</p>
          <div className="compare--labels py-20 px-30 w-full bg-grey-100"
               style={{ width: smallScreen ? '900px' : '94.5%' }}>
            <ul className="flex flex-col -mb-8">
              {map(compares.taxonomies, (taxonomy, index) => {
                return (
                  <li key={index} className="mb-8 font-bold text-grey-1000">{taxonomy}</li>
                );
              })}
            </ul>
          </div>
        </div>

        <div className="compare--products relative flex pt-60 -mb-col z-2"
             style={{
               width: smallScreen ? '800px' : '75%',
               marginLeft: xsScreen && smallScreen ? '32%' : smallScreen ? '25%' : '20%'
             }}>
          {!isEmpty(compares) && (isEmpty(compares.taxonomies) || compares.taxonomies.length > 1) && map(compares.products, (product, index) => {
            let productThumbnail = '';
            if (product.thumbnail) {
              productThumbnail = product.thumbnail;
            } else if (options && options?.fallback_image) {
              productThumbnail = options?.fallback_image;
            }
            return (
              <article key={index} className={`relative px-col ${smallScreen ? 'w-full' : 'md:w-1/3'}`}
                       style={{ top: '-20px' }}>
                <div className="p-10 bg-white shadow-theme rounded overflow-hidden" style={{ height: '87%' }}>
                  {productThumbnail &&
                  <figure className="relative mb-20 h-128 rounded overflow-hidden">
                    <a
                      href={product.guid}
                      className="absolute w-full h-full z-1"
                      style={{ background: 'linear-gradient(0deg, rgba(0, 0, 0, .5) 0%, rgba(255,255,255,0) 100%' }}
                    >
                    </a>
                    <img src={productThumbnail} alt={product.post_title} className="absolute h-full w-full object-cover"/>
                    <p className="absolute bottom-0 py-10 px-20 font-bold text-white z-2">{product.post_title}</p>
                  </figure>}
                  <div className="compare--taxonomies">
                    {map(product.taxonomies, (taxonomy, name) => {
                      count += 1;

                      return (
                        name === 'price' ?
                          <div key={name}
                               className={`mb-2 py-4 px-20 text-sm ${count % 2 !== 0 ? 'bg-grey-100' : ''}`}
                          >
                            <span dangerouslySetInnerHTML={{ __html: product.currency }}></span>
                            {functions.formatMoney(taxonomy.term, 2, product.decimals_separator, product.thousands_separator)}
                          </div>
                          :
                          <div key={name}
                               className={`mb-2 py-4 px-20 text-sm ${count % 2 !== 0 ? 'bg-grey-100' : ''}`}>
                            {taxonomy.term}
                          </div>
                      );
                    })}
                  </div>
                </div>
                <button className="flex-center mt-30 mb-10 mx-auto text-sm text-red-700"
                        onClick={() => removeProduct(product.ID)}>
                  {lc_data.jst[514]}
                  <ReactSVG
                    src={`${lc_data.dir}dist/${CloseIcon}`}
                    className="ml-4 w-12 h-12 fill-icon-home"
                  />
                </button>
              </article>
            );
          })}
          {map(emptyProducts, (title, index) => {
            return (
              <article key={index} className={`relative px-col ${smallScreen ? 'w-full' : 'md:w-1/3'}`}
                       style={{ top: '-20px' }}>
                <div
                  className="flex-center p-10 bg-white shadow-theme rounded overflow-hidden"
                  style={{ height: '87%' }}
                >
                  <p className="font-bold text-grey-400">{title}</p>
                </div>
              </article>
            );
          })}
        </div>
      </Fragment>
      }

    </section>
  );
};

export default CompareProducts;
