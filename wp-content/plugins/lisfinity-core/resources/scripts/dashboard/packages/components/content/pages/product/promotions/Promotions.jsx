/* global lc_data, React */
/**
 * External dependencies.
 */
import { map, isEmpty, find } from 'lodash';
import ReactSVG from 'react-svg';
import { sprintf } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import ReactTooltip from 'react-tooltip';
import QuestionIcon from '../../../../../../../../images/icons/question-circle.svg';
import CalendarIcon from '../../../../../../../../images/icons/calendar.svg';
import axios from 'axios';
import * as functions from '../../../../../../../theme/vendor/functions';
import { useState } from '@wordpress/element';
import { Fragment, useEffect, useRef } from 'react';
import LoaderIcon from '../../../../../../../../images/icons/loader-rings.svg';
import ModalDemo from '../../../../../../../theme/packages/components/modal/ModalDemo';
import he from 'he';

const Promotions = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const { productId, product } = props;
  const { promotion_products } = business;
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [prices, setPrices] = useState({});
  const [error, setError] = useState([]);
  const priceValue = useRef();
  const [disablePurchase, setDisablePurchase] = useState({ disable: false, id: '' });
  const payment_package = data?.product?.package;

  const buyPromotion = async (id, price, name) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(id);
    let days = document.getElementById(name).value;
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    if (days < 1) {
      days = 1;
    }
    const url = lc_data.purchase_promotion;
    let data = {
      id: lc_data.current_user_id,
      product: productId,
      wc_product: id,
      price,
      days,
    };

    if (payment_package?.promotion_limit && payment_package?.promotion_count && payment_package.promotion_count < payment_package.promotion_limit) {
      data.price = 0;
    }

    await axios({
      credentials: 'same-origin',
      method: 'post',
      url,
      data,
      headers,
    }).then(data => {
      if (data.data.success) {
        window.location.href = data.data.permalink;
      } else {
        setFetching(false);
      }
    });
  };

  const changeText = (e, promotion) => {
    let value = e.target.value;
    let inputField = document.getElementById(promotion.post_name);
    let isValidInputField = inputField.checkValidity();
    const toChange = document.getElementById(`text-${promotion.ID}`);
    const price = document.getElementById(`price-${promotion.ID}`);
    if (isValidInputField) {
      if (data?.product?.days_remaining && value > data?.product?.days_remaining) {
        value = data?.product?.days_remaining;
        e.target.value = data?.product?.days_remaining;
      }
      let errors = error;
      errors[promotion.post_name] = { text: '', name: promotion.post_name };
      setError(errors);
      if (value == 1) {
        toChange.textContent = lc_data.jst[203];
      } else {
        toChange.textContent = lc_data.jst[206];
      }
      let priceToFormat = parseFloat(promotion.price) * parseFloat(value);
      if (payment_package?.promotions_limit > payment_package?.promotions_count) {
        priceToFormat = 0;
      }
      const p = { ...prices };
      p[promotion.ID] = priceToFormat;
      setPrices(p);
      let disable = { disable: false, id: promotion.ID };
      setDisablePurchase(disable);
    } else {
      setDisablePurchase({ disable: true, id: promotion.ID });
      let errors = error;
      errors[promotion.post_name] = { text: inputField.validationMessage, name: promotion.post_name };
      setError(errors);
    }
  };

  useEffect(() => {
    const p = {};
    map(promotion_products, (promotion) => {
      p[promotion.ID] = promotion.price;
    });
  }, []);


  return (
    <div className="promotions flex flex-wrap">
      {!isEmpty(promotion_products) &&
      <div className="row -mb-30">
        {map(promotion_products, (promotion) => {
          const promoActive = find(business.promotions, (o) => o.product_id == productId && o.promotion_position === promotion['product-type']);
          let defaultPrice = promotion?.price * parseFloat(promotion?.duration);
          return (
            promotion['product-type'] !== 'bump-up' &&
            <div key={promotion.ID} className={`dashboard--promotion-products mb-30 px-col fadeInUp ${promotion_products.length <= 2 ? 'w-full' : promotion_products.length === 3 ? 'w-1/2' : ''}`}>
              <article className="promotion rounded shadow-theme overflow-hidden">
                {promotion.thumbnail &&
                <figure className="relative h-product-2-thumb">
                  <img src={promotion.thumbnail.url} alt={promotion.post_title}
                       className="absolute top-0 left-0 w-full h-full object-cover"/>
                </figure>
                }
                <div className="flex flex-col p-30 bg-white">

                  <div className="promotion--top flex justify-between">

                    <div className="flex-center">
                      <ReactSVG src={`${lc_data.dir}dist/${CalendarIcon}`}
                                className="relative mr-8 w-16 h-16 fill-grey-700" style={{ top: '-3px' }}/>
                      <span className="text-sm text-grey-700">
                        {!isEmpty(promoActive)
                          ?
                          sprintf(lc_data.jst[207], promoActive.expires_human)
                          :
                          lc_data.jst[208]
                        }
                      </span>
                    </div>

                    {!isEmpty(promotion.post_content) &&
                    <div className="flex-center font-semibold text-sm text-blue-700" data-tip={promotion.post_content}>
                      {lc_data.jst[201]}
                      <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                                className="relative ml-6 w-14 h-14 fill-blue-700"/>
                      <ReactTooltip scrollHide={false}/>
                    </div>
                    }
                  </div>

                  <h6 className="mt-20 font-semibold text-grey-1000">{promotion.post_title}</h6>
                  {disablePurchase?.disable && disablePurchase?.id === promotion.ID && error[promotion.post_name] &&
                  <div
                    className="field--error text-xs mt-10 leading-snug text-red-700 w-full text-left">{error[promotion.post_name].text}</div>
                  }
                  <div
                    className={`flex justify-between items-center ${disablePurchase.id === promotion.ID && disablePurchase.disable ? 'mt-3' : 'mt-20'}`}>
                    <div className="flex items-center">
                      <span id={`price-${promotion.ID}`} className="font-semibold text-sm">
                        <span className="woocommerce-Price-amount amount">
                          <bdi>
                            <span className="woocommerce-Price-currencySymbol">{he.decode(promotion.currency)}</span>
                            <span
                              className="price"
                              ref={priceValue}>{functions.formatMoney(payment_package?.promotions_limit > payment_package?.promotions_count ? 0 : (prices[promotion.ID] || defaultPrice), 2, promotion.decimals_separator, promotion.thousands_separator)}</span>
                          </bdi>
                        </span>
                      </span>
                      <span className="ml-3 text-sm text-grey-700">{lc_data.jst[202]}</span>

                      <input
                        id={promotion.post_name}
                        type="number"
                        pattern="[0-9]*"
                        className="ml-8 p-2 bg-grey-100 border border-grey-300 rounded font-semibold text-grey-1000"
                        min={promotion?.duration_min_value ? promotion?.duration_min_value : 1}
                        max={!isEmpty(promotion?.duration_max_value) ? promotion?.duration_max_value : data?.product?.days_remaining}
                        defaultValue={promotion?.duration ? promotion?.duration : 1}
                        step={promotion?.duration_step ? promotion?.duration_step : 1}
                        style={{ maxWidth: '42px' }}
                        onChange={(e) => changeText(e, promotion)}
                      />

                      <span id={`text-${promotion.ID}`}
                            className="ml-6 text-sm text-grey-700">{lc_data.jst[203]}</span>
                    </div>
                    <button
                      type="button"
                      className={`flex-center btn btn-transparent py-5 px-20 h-auto border border-grey-600 rounded font-normal text-grey-1000 hover:bg-grey-1000 hover:border-grey-1000 hover:text-white ${disablePurchase.id === promotion.ID && disablePurchase.disable ? 'cursor-not-allowed' : ''}`}
                      onClick={() => buyPromotion(promotion.ID, promotion.price, promotion.post_name)}
                      disabled={disablePurchase.id === promotion.ID && disablePurchase.disable ? disablePurchase.disable : false}
                      style={{
                        width: 99,
                        height: 33,
                      }}
                    >
                      <Fragment>
                        {fetching === promotion.ID &&
                        <ReactSVG
                          src={`${lc_data.dir}dist/${LoaderIcon}`}
                          className="relative flex-center hover:fill-white"
                          style={{
                            zoom: 0.4,
                          }}
                        />
                        }
                        {fetching !== promotion.ID && lc_data.jst[204]}
                      </Fragment>
                    </button>
                  </div>

                </div>
              </article>
            </div>
          );
        })}
      </div>
      }
      {isEmpty(promotion_products) &&
      <div className="px-30 font-bold text-xl text-grey-900">
        {lc_data.jst[205]}
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
    </div>
  );
};

export default Promotions;
