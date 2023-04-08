/* global lc_data, React */
/**
 * Dependencies.
 */
import { NavLink, Redirect } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import { useState, useEffect, useRef } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import ProductPrice from '../products/ProductPrice';
import PencilIcon from '../../../../../../../images/icons/pencil.svg';
import CircleMinusIcon from '../../../../../../../images/icons/circle-minus.svg';
import DollarIcon from '../../../../../../../images/icons/dollar.svg';
import ReloadIcon from '../../../../../../../images/icons/reload.svg';
import cx from 'classnames';
import { Fragment } from 'react';
import axios from 'axios';

const ProductInfoChat = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, product } = data;
  const content = useRef(null);
  const [figureHeight, setFigureHeight] = useState(180);
  const [redirect, setRedirect] = useState(false);
  const [status, setStatus] = useState(product.status);

  const timelineClass = cx({
    'bg-green-500': product.percentage < 40,
    'bg-yellow-500': product.percentage >= 40 && product.percentage < 80,
    'bg-red-500': product.percentage >= 80,
  });

  useEffect(() => {
    const rect = content.current.getBoundingClientRect();
    setFigureHeight(rect.height);
  }, []);


  const deleteProduct = (id) => {
    if (!confirm(lc_data.jst[173])) {
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    }
    const url = `${lc_data.product_action}/delete`;
    let data = {
      product_id: id,
      redirect: true,
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        setRedirect(true);
      }
    });
  }

  useEffect(() => {
    setRedirect(false);
  }, [redirect]);

  const markAsSold = (id) => {
    if (!confirm(lc_data.jst[174])) {
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    }
    const url = `${lc_data.product_action}/mark-sold`;
    let data = {
      product_id: id,
      status: product.status === 'publish' ? 'sold' : 'publish',
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        setStatus(data.data.status);
      }
    });
  }

  const soldIconBgClass = cx({
    'bg-red-200': status === 'sold',
    'bg-green-200': status === 'publish',
  });
  const soldIconClass = cx({
    'fill-red-700': status === 'sold',
    'fill-green-700': status === 'publish',
  });

  return (
    <section className="flex flex-wrap mb-30 p-30 bg-white rounded shadow-theme overflow-hidden">

      <figure className="flex relative w-3/12 rounded overflow-hidden" style={{ height: `${figureHeight}px` }}>
        <img src={product.thumbnail} alt={product.title}/>
      </figure>

      <div className="flex flex-col pl-20 w-9/12" ref={content}>

        <div className="top flex justify-between items-center -mr-20">

          <ProductPrice style="single" product={product}/>

        </div>

        <div className="product-single--title mt-20">
          <h1 className="font-bold text-5xl">{product.title}</h1>
        </div>

        <div className="product-single--meta flex justify-between items-end mt-20">

          <div className="flex items-center">
            <figure className="mr-10 w-40 h-40 border-4 border-grey-100 rounded-full overflow-hidden">
              <img src={product.agent.avatar} alt={product.agent.display_name}/>
            </figure>
            <div className="flex flex-col">
              <span className="text-sm text-grey-500">{lc_data.jst[169]}</span>
              <p className="flex font-bold text-grey-1100">
                {product.agent.first_name && product.agent.last_name
                  ?
                  sprintf('%s %s', product.agent.first_name, product.agent.last_name)
                  :
                  product.agent.display_name
                }
              </p>
            </div>
          </div>

          <div className="product-single--timeline w-7/16">

            <div className="timeline relative w-full h-10 bg-grey-200 rounded overflow-hidden">
              <div
                className={`timeline--line absolute top-0 left-0 h-10 ${timelineClass}`}
                style={{ width: `${product.percentage}%` }}
              ></div>
            </div>
            <div className="flex mt-10 text-sm text-grey-500">
              {lc_data.jst[170]}
              <span
                className="ml-3 font-semibold text-grey-1100">{sprintf(lc_data.jst[153], product.submitted_human)}</span>
              <span className="mx-3">-</span>
              {product.is_active
                ?
                <Fragment>
                  {lc_data.jst[171]}
                  <span className="ml-3 font-semibold text-grey-1100">{product.expires_human}</span>
                </Fragment>
                :
                <Fragment>
                  {lc_data.jst[172]}
                  <span className="ml-3 font-semibold text-grey-1100">{product.expires_date}</span>
                </Fragment>
              }
            </div>

          </div>

        </div>

      </div>

      {redirect &&
      <Redirect to={`${lc_data.site_url}${lc_data.myaccount}products`}/>
      }

    </section>
  );
};

export default ProductInfoChat;
