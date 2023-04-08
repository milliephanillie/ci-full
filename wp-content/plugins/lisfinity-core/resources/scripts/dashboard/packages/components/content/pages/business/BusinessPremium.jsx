/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useEffect, useState } from '@wordpress/element';
import { map } from 'lodash';
import { sprintf, _n, __ } from '@wordpress/i18n';
import { formatMoney } from '../../../../../../theme/vendor/functions';
import axios from 'axios';
import { toast, ToastContainer } from 'react-toastify';
import { Fragment } from 'react';
import BusinessPremiumActive from './BusinessPremiumActive';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';

const BusinessPremium = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const premium = business.business.premium;
  const product = business.business.premium_product;
  const [duration, setDuration] = useState(1);
  const [price, setPrice] = useState(0);
  const [discount, setDiscount] = useState(0);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [requested, setRequested] = useState(false);
  const [notice, setNotice] = useState('');
  const [buttonDisable, setButtonDisabled] = useState(false);

  useEffect(() => {
    let currentDiscount = 0;
    map(product.discounts, (discount, index) => {
      if (index === 0 && parseInt(duration, 10) < parseInt(discount.duration, 10)) {
        currentDiscount = 0;
        return;
      }
      if (parseInt(duration, 10) >= parseInt(discount.duration, 10)) {
        currentDiscount = parseInt(discount.discount, 10);
        setPrice((product.price * duration) * ((100 - parseInt(discount.discount, 10)) / 100));
      }
    });

    if (currentDiscount === 0) {
      setPrice(product.price * duration);
    }
    setDiscount(currentDiscount);
  }, [duration]);

  const buyPackage = () => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.purchase_premium;
    let data = {
      product: business.business.ID,
      wc_product: product.ID,
      quantity: duration,
      price,
      discount,
    };
    return axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(response => {
      if (response.data.success) {
        if (response.data?.permalink) {
          window.location.href = response.data.permalink;
        } else {
          toast(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
          });
          setRequested(true);
        }
      } else {
        setFetching(false);
      }
    });
  };

  return (
    <Fragment>
      {premium &&
      <BusinessPremiumActive/>
      }
      <section className={`become-premium ${premium ? 'mt-20' : ''}`}>
        {product &&
        <div className="dashboard--premium flex shadow-theme">

          <div
            className="dashboard--premium--form relative flex flex-col items-center py-40 sm:py-60 px-20 sm:px-40 bg-white rounded-l text-center">

            <div className={`flex-center ${buttonDisable ? 'mb-5' : 'mb-20'}`}>
              <span className="font-light text-sm text-grey-900">{lc_data.jst[145]}</span>
              <div className="mx-8 p-4 bg-grey-100 border border-grey-300 rounded" style={{ width: '54px' }}>
                <input type="number" defaultValue={duration} min={1}
                       className="w-full bg-transparent"
                       onChange={e => {
                         if (parseInt(e.target.value, 10) < 0) {
                           setDuration(e.target.value.replace('-', ''));
                         } else if (parseInt(e.target.value, 10) === 0 || e.target.value === '') {
                           setDuration(1);
                           setNotice('The minimum value should be 1');
                           setButtonDisabled(true);
                         } else if (product.cost_type === 'month' && parseInt(e.target.value, 10) > 12) {
                           setDuration(12);
                           setButtonDisabled(false);
                           setNotice('');
                         } else {
                           setDuration(e.target.value);
                           setButtonDisabled(false);
                           setNotice('');
                         }
                       }}/>
              </div>
              <span className="font-light text-sm text-grey-900">{
                product.cost_type === 'day'
                  ?
                  _n(lc_data.jst[134], lc_data.jst[127], parseInt(duration, 10), 'lisfinity-core')
                  :
                  _n(lc_data.jst[135], lc_data.jst[136], parseInt(duration, 10), 'lisfinity-core')
              }</span>
            </div>
            <div className="text-xs bottom-10 text-red-500">{notice}</div>
            <div className="relative flex-center">
              <div
                className="mb-20 font-bold text-grey-1000"
                dangerouslySetInnerHTML={{
                  __html: sprintf(product.price_format, product.currency, formatMoney(price, product.decimals, product.decimals_separator, product.thousands_separator))
                }}
                style={{ fontSize: '70px' }}
              >
              </div>
              {discount !== 0 &&
              <div
                className="absolute bottom-10 text-red-500"
                dangerouslySetInnerHTML={{
                  __html: sprintf(lc_data.jst[137], `<strong>${discount}%</strong>`)
                }}
              />
              }
            </div>

            <div>
              {business.options?.account_type === 'business' &&
              <ul className="-mb-10 text-grey-900">
                <li className="mb-10">
                  <span>{lc_data.jst[104]}</span>
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[138]}</span>
                </li>
                <li className="mb-10">
                  <span>{lc_data.jst[104]}</span>
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[139]}</span>
                </li>
                <li className="mb-10">
                  <span>{lc_data.jst[104]}</span>
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[140]}</span>
                </li>
                <li className="mb-10">
                  <span>{lc_data.jst[104]}</span>
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[141]}</span>
                </li>
                <li className="mb-10">
                  <span>{lc_data.jst[104]}</span>
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[144]}</span>
                </li>
              </ul>
              }
              {business.options?.account_type !== 'business' &&
              <ul className="-mb-10 text-grey-900">
                <li className="mb-10">
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[753]}</span>
                </li>
                <li className="mb-10">
                  <span className="ml-4 font-semibold text-grey-1000">{lc_data.jst[754]}</span>
                </li>
              </ul>
              }
            </div>

            {!requested &&
            <button
              type="button"
              className={`flex-center mt-40 py-10 px-30 h-44 max-w-3/4 bg-blue-700 rounded text-white hover:bg-blue-900 ${buttonDisable ? 'cursor-not-allowed' : ''}`}
              onClick={buyPackage}
              disabled={buttonDisable}
              style={{
                width: fetching ? 140 : 'auto',
              }}
            >
              <Fragment>
                {fetching &&
                <ReactSVG
                  src={`${lc_data.dir}dist/${LoaderIcon}`}
                  className="relative"
                  style={{
                    zoom: 0.6,
                  }}
                />
                }
                {!fetching && lc_data.jst[111]}
              </Fragment>
            </button>
            }

          </div>

          {product.thumbnail &&
          <div className="dashboard--premium--content become-premium--content relative flex rounded-r overflow-hidden">
            <figure className="relative w-full">
            <span
              className="absolute w-full h-full z-1"
              style={{ background: 'linear-gradient(0deg, rgba(0, 0, 0, .5) 0%, rgba(255,255,255,0) 100%' }}
            >
            </span>
              <img
                src={product.thumbnail.url}
                alt={product.post_title}
                className="absolute top-0 left-0 w-full h-full object-cover"
              />
            </figure>
            {product.post_content &&
            <div className="absolute left-0 bottom-0 p-40 text-white z-2"
                 dangerouslySetInnerHTML={{ __html: product.post_content }}></div>
            }
          </div>
          }

        </div>
        }
        <ModalDemo
          isLogged={lc_data.logged_in}
          open={modalOpen}
          closeModal={() => setModalOpen(false)}
          title={lc_data.jst[606]}
        >
          <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
            __html: lc_data.jst[607],
          }}
          />
        </ModalDemo>
        <ToastContainer/>
      </section>
    </Fragment>
  );
};

export default BusinessPremium;
