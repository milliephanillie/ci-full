/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import { Component, createRef, Fragment, useRef } from '@wordpress/element';
import { isEmpty } from 'lodash';
import { sprintf, __ } from '@wordpress/i18n';
import ProductSlider from '../slider/ProductSlider';
import Bookmark from '../product-actions/Bookmark';
import Print from '../product-actions/Print';
import Visits from '../product-actions/Visits';
import Likes from '../product-actions/Likes';
import Report from '../product-actions/Report';
import Share from '../product-actions/Share';
import PhotoSwipe from '../slider/PhotoSwipe';
import Specification from '../information/Specification';
import ProductPrice from './ProductPrice';
import Compare from '../product-actions/compare/Compare';
import CalculatorButton from '../product-actions/calculator/CalculatorButton';
import Files from '../information/Files';
import he from 'he';

function ProductContent(props) {
  const { product, currentUser, options } = props;
  const [shareDisplay, setShareDisplay] = useState('default');
  const [ElSettings, setElSettings] = useState(null);

  const el = useRef(null);

  const shareIconDisplay = () => {
    if (window.innerWidth < 640) {
      setShareDisplay('small-screen');
    } else {
      setShareDisplay('default');
    }
  };

  useEffect(() => {
    window.addEventListener('resize', shareIconDisplay);

    const wrapper = el.current.closest('.home-search');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }

    shareIconDisplay();

    return () => window.removeEventListener('resize', shareIconDisplay);
  }, []);


  return (
    <div ref={el}>

      <section className="product--section product--section__meta flex flex-wrap mb-20 justify-between items-center">
        {product?.is_expired &&
            <div className="updated-expired relative container px-20 py-86 new-orange-wrapper"><span
                className="new-orange py-10 px-20 rounded border border-red-300 bg-red-100 text-xl text-red-500">{lc_data.jst[711]}</span>
            </div>
        }
        <div className="product---header-custom flex">
          <div className="leading-snug"><h1 className="-mb-10 font-bold text-5xl text-grey-1000 leading-snug"
                                            dangerouslySetInnerHTML={{ __html: he.decode(product.post_title) }}/></div>

          {/*<div className="product--id text-grey-1000 text-13 font-light">{sprintf(lc_data.jst[504], product.ID)}</div>*/}
          {/*{props?.options?.published_date && props?.options?.published_date !== '' &&*/}
          {/*<div*/}
          {/*  className="product--date text-grey-1000 mt-10 ml-10 text-13 font-light ">{sprintf(lc_data.jst[728], props?.options?.published_date)}</div>*/}
          {!props.premiumOnly && !product?.is_expired &&
              <div className="product--info">
                <ProductPrice product={product} currentUser={currentUser} options={props.options}/>
              </div>
          }
        </div>

      </section>

      {/*<section id="basic" className="product--title mb-24">*/}
      {/*  <h1 className="-mb-10 font-bold text-5xl text-grey-1000 leading-snug"*/}
      {/*      dangerouslySetInnerHTML={{ __html: he.decode(product.post_title) }}/>*/}
      {/*</section>*/}

      {!isEmpty(product) &&
      <section className="product--section product--section__actions flex flex-wrap justify-between mb-20">
        <div className="product--actions__left flex flex-wrap">
          {!isEmpty(currentUser) && <Bookmark product={product} currentUser={currentUser}/>}
          <Print product={product}/>
          {props.options.ad_visits !== '0' && product?.views && (options['membership-listings-visits'] === 'always' || (options['membership-listings-visits'] === 'logged_in' && lc_data.logged_in === '1')) &&
          <Visits product={product}/>}
          {!isEmpty(lc_data.user_ip) && props.options.ad_likes !== '0' &&
          <Likes product={product} currentUser={currentUser}/>}
          {!isEmpty(lc_data.user_ip) && props.options.report !== 'no' &&
          <Report product={product} options={props.options}/>}
          {shareDisplay === 'small-screen' && <Share product={product}/>}
        </div>
        {shareDisplay === 'default' &&
        <div className="product--actions__right">
          <Share product={product}/>
        </div>}
      </section>
      }

      {!isEmpty(product) &&
      <Fragment>
        <ProductSlider product={product}/>
      </Fragment>
      }

      <div className="product--actions flex justify-between py-14 pl-24 pr-30 bg-grey-100">
        {props.options.calculator_position !== 'widget' && product.product_meta.price > 0 && product.calculator && product.calculator.display &&
        <CalculatorButton product={product} currentUser={currentUser}/>
        }
        {props.options.ad_compare !== '0' && <Compare product={product} currentUser={currentUser} options={options}/>}
      </div>

      {!isEmpty(product.taxonomies) && (options['membership-specification'] === 'always' || (options['membership-specification'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <section className="product--section">
        <Specification product={product}/>
      </section>
      }

      {!isEmpty(product.post_content) && (options['membership-description'] === 'always' || (options['membership-description'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <section id="productDescription" className="product--section mt-40 mb-60">
        <h5 className="font-bold text-grey-1000">{lc_data.jst[16]}</h5>
        <div className="mt-20 text-grey-800 w-full" style={{ lineHeight: '1.9' }}
             dangerouslySetInnerHTML={{
               __html: product.post_content,
             }}
        >
        </div>
      </section>}

      {!isEmpty(product.files) &&
      <Files files={product.files}/>
      }

      <div className="flex">

      <div className="product--id text-grey-1000 text-13 font-light">{sprintf(lc_data.jst[504], product.ID)}</div>
      {props?.options?.published_date && props?.options?.published_date !== '' &&
          <div
              className="product--date text-grey-1000 mt-10 ml-10 text-13 font-light ">{sprintf(lc_data.jst[728], props?.options?.published_date)}</div>
      }
      </div>

    </div>
  );
}

export default ProductContent;
