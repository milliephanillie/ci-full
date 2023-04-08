/* global lc_data, React */
/**
 * Dependencies.
 */
import {useDispatch, useSelector} from 'react-redux';
import {map, sortBy, isEmpty} from 'lodash';
import cx from 'classnames';
import axios from 'axios';
import {Fragment, useRef} from 'react';
import PackagesActive from './PackagesActive';
import {toast} from 'react-toastify';
import {useEffect, useState} from '@wordpress/element';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../../images/icons/loader-rings-white.svg';
import ModalDemo from '../../../../../theme/packages/components/modal/ModalDemo';
import * as actions from '../../../store/actions';
import {sprintf} from '@wordpress/i18n';
import {formatMoney} from '../../../../../theme/vendor/functions';

const PackageSubscription = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const {business, menuOpen, options} = data;
  const {packages} = business;
  const [fetching, setFetching] = useState(null);
  const [modalOpen, setModalOpen] = useState(false);
  const [days, setDays] = useState(1);
  const [price, setPrice] = useState(product?.price || 0);
  const [discount, setDiscount] = useState(0);
  const {product} = props;

  const footnoteStars = (count) => {
    let html = '';
    for (let i = 1; i <= count; i += 1) {
      html += `<span key={i} className="package--footnotes__star align-top text-red-600">*</span>`;
    }

    return html;
  };

  const buyPackage = async (id) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(id);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.purchase_package;
    let data = {
      id: lc_data.current_user_id,
      wc_product: id,
    };
    if (discount > 0) {
      data.discount = discount;
    }
    if (days > 1) {
      data.quantity = days;
    }
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        if(data.data.permalink) {
          window.location.href = data.data.permalink;
        } else {
          window.location.reload(false);
        }
      }
      if (data.data.error) {
        toast.error(data.data.message, {
          position: toast.POSITION.BOTTOM_CENTER,
          autoClose: 3000,
        });
      }
      setFetching(false);
    });
  };

  useEffect(() => {
    let currentDiscount = 0;
    product?.discounts && map(product.discounts, (discount, index) => {
      if (index === 0 && parseInt(days, 10) < parseInt(discount.duration, 10)) {
        currentDiscount = 0;
        return;
      }
      if (parseInt(days, 10) >= parseInt(discount.duration, 10)) {
        if (product?.discounts_type !== 'select') {
          currentDiscount = parseInt(discount.discount, 10);
          setPrice((product.price * days) * ((100 - parseInt(discount.discount, 10)) / 100));
        } else {
          setPrice(discount.discount);
          currentDiscount = 0;
        }
      }
    });

    if (currentDiscount === 0 && product?.discounts_type !== 'select') {
      setPrice(product.price * days);
    }
    setDiscount(currentDiscount);
  }, [days]);

  const btnClass = cx({
    'border-grey-500 hover:bg-grey-500 hover:shadow-md hover:text-white': product.style !== 2,
    'bg-blue-600 border-blue-600 text-white hover:bg-blue-800 hover:border-blue-800 hover:shadow-theme': product.style === 2,
  });
  const footnotes = [];
  return (
    <div key={product.ID}
         className="dashboard--package package relative mb-20 px-8 w-full sm:w-1/2 bg:w-1/3 xl:w-1/5">
      <article className="package relative flex flex-col p-30 bg-white rounded shadow-theme">
        <h6 className="font-semibold text-lg text-grey-900 whitespace-nowrap">{product.post_title}</h6>

        <div
          className="package--price relative flex mt-30 font-bold text-5xl text-grey-1100 whitespace-nowrap">
          {product.style === 2 &&
          <span className="package--recommended absolute h-60 bg-blue-200 rounded-r"
                style={{left: -30, width: '60%'}}></span>}
          {days > 1 ?
            <span
              className={`relative z-10 ${product.on_sale ? 'on-sale' : ''} ${product.style === 2 ? 'left-20' : ''}`}
              dangerouslySetInnerHTML={{__html: sprintf(product.price_format, `<span class="woocommerce-Price-currencySymbol">${product.currency}</span>`, formatMoney(price, product.decimals, product.decimals_separator, product.thousands_separator))}}>
                    </span>
            :
            <span
              className={`relative z-10 ${product.on_sale ? 'on-sale' : ''} ${product.style === 2 ? 'left-20' : ''}`}
              dangerouslySetInnerHTML={{__html: product.price_html}}>
                    </span>
          }
          {price < 1 || price === '' && lc_data.jst[110]}
        </div>

        {discount > 0 &&
        <div className="text-sm text-red-600 mt-20">{sprintf(lc_data.jst[137], `${discount}%`)}</div>
        }

        {product?.type === 'payment_subscription' &&
        <div className="flex items-center mt-20 -mb-10">
          <label htmlFor={`qty-${product.ID}`}
                 className="font-light text-sm text-grey-900">{lc_data.jst[145]}</label>
          <div className="mx-8 p-4 bg-grey-100 border border-grey-300 rounded" style={{width: 54}}>
            <input
              data-id={product.ID}
              id={`qty-${product.ID}`} type="number" min="1" className="w-full bg-transparent"
              onChange={e => setDays(e.target.value)}
              value={days}
            />
          </div>
          {days > 1 ?
            <span className="font-light text-sm text-grey-900">{lc_data.jst[136]}</span>
            :
            <span className="font-light text-sm text-grey-900">{lc_data.jst[135]}</span>
          }
        </div>
        }

        {product?.type !== 'payment_subscription' && product?.discounts_enabled && !isEmpty(product?.discounts) &&
        <div className="flex items-center mt-20 -mb-10">
          <label htmlFor={`qty-${product.ID}`}
                 className="font-light text-sm text-grey-900">{lc_data.jst[734]}</label>
          <div className="mx-8 p-4 bg-grey-100 border border-grey-300 rounded" style={{width: 54}}>
            {(!product?.discounts_type || 'select' !== product.discounts_type) && <input
              data-id={product.ID}
              id={`qty-${product.ID}`} type="number" min="1" className="w-full bg-transparent"
              onChange={e => setDays(e.target.value)}
              value={days}
            />
            }
            {product?.discounts_type === 'select' &&
            <select
              data-id={product.ID}
              id={`qty-${product.ID}`}
              className="w-full bg-transparent"
              onChange={e => setDays(e.target.value)}
            >
              {product?.discounts && map(product.discounts, discount => {
                return (
                  <option key={discount.duration} value={discount.duration}>{discount.duration}</option>
                );
              })}
            </select>
            }
          </div>
        </div>
        }

        <ul className="package--features flex flex-col mt-30">
          {map(product.features, (feature, index) => {

            {
              !isEmpty(feature['package-footnote']) &&
              footnotes.push(feature['package-footnote']);
            }
            return (
              <li key={index} className="relative mb-8">
                      <span className="text-grey-700"
                            dangerouslySetInnerHTML={{__html: feature['package-feature']}}></span>
                {!isEmpty(feature['package-footnote']) &&
                map(footnotes, (footnote, i) =>
                  <span key={i}
                        className="package--footnote -mr-1 leading-none align-top text-red-600">*</span>)
                }
              </li>
            );
          })}
        </ul>

        <button
          type="button"
          className={`package--buy-action flex-center mt-30 mb-20 py-8 h-40 border rounded font-semibold ${btnClass}`}
          onClick={() => buyPackage(product.ID)}
        >
          <Fragment>
            {fetching == product.ID &&
            <ReactSVG
              src={`${lc_data.dir}dist/${LoaderIcon}`}
              className="relative"
              style={{
                zoom: 0.6,
              }}
            />
            }
            {fetching != product.ID && product.text}
          </Fragment>
        </button>

        {!isEmpty(footnotes) &&
        <div className="package--footnotes absolute">
          {map(footnotes, (footnote, i) => {
            return (
              <div key={i} className="package--footnote relative mb-2 leading-tight">
                <div className="package--footnotes-star-wrapper absolute flex items-center">
                  <div className="align-top text-red-600"
                       dangerouslySetInnerHTML={{__html: footnoteStars(i + 1)}}></div>
                </div>
                <span className="footnote text-sm text-grey-800">{footnote}</span>
              </div>
            );
          })}
        </div>}

      </article>
    </div>
  );
};

export default PackageSubscription;
