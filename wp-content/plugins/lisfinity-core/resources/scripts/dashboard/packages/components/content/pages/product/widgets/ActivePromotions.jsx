/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import { sprintf, __ } from '@wordpress/i18n';
import { map, filter, isEmpty, sortBy, find } from 'lodash';
import { Scrollbars } from 'react-custom-scrollbars';
import { Fragment } from 'react';
import { NavLink } from 'react-router-dom';
import icon from '../../../../../../../../images/icons/reload.svg';
import CalendarIcon from '../../../../../../../../images/icons/calendar.svg';
import InvestmentIcon from '../../../../../../../../images/icons/investment.svg';
import axios from 'axios';
import { useState } from '@wordpress/element';
import loader from '../../../../../../../../images/icons/loader-rings-blue.svg';
import ModalDemo from '../../../../../../../theme/packages/components/modal/ModalDemo';

const ActivePromotions = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, product, options } = data;
  const { productId } = props;
  const [buying, setBuying] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

  const promotions = filter(business.promotions, (promo) => promo.product_id == product.id);

  let url = `${lc_data.myaccount}ad`;
  if (productId) {
    url += `/${productId}`;
  }

  const calculateScrollHeight = () => {
    const promotionsCount = Object.keys(promotions).length;
    let height = 120;
    if (promotionsCount === 1) {
      height = 150;
    } else if (promotionsCount > 1) {
      height = 300;
    }

    return height;
  };

  const buyPromotion = async (id, price, days, type) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setBuying(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.purchase_promotion;
    let data = {
      id: lc_data.current_user_id,
      product: productId,
      wc_product: id,
      price: price,
      days,
      type,
    };
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        window.location.href = data.data.permalink;
      }
    });
  };

  const bumpUpProduct = find(business.promotion_products, promo => promo['product-type'] === 'bump-up');
  return (
    options && options.promotions &&
    <div
      className="dashboard-widget--active-promotions flex flex-col mb-20 p-20 bg-white rounded shadow-theme w-full sm:w-1/2 xl:w-full">

      <div className="dashboard-widget--header flex justify-between items-center mb-20 px-10">
        <div className="flex-center">
          <ReactSVG
            src={`${lc_data.dir}dist/${icon}`}
            className="mr-8 w-16 h-16 fill-black"
          />
          <h6 className="font-semibold text-lg">{lc_data.jst[221]}</h6>
        </div>
        <div className="flex-center p-2 w-20 h-20 bg-grey-100 rounded">{Object.keys(promotions).length}</div>
      </div>

      <Fragment>
        {!isEmpty(promotions) &&
        <div className="-mb-10">
          <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight autoHeightMin={calculateScrollHeight()}
                      renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                      renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                      renderTrackVertical={props => <div {...props}
                                                         className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                      renderThumbVertical={props => <div {...props}
                                                         className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
            {map(sortBy(promotions, promotion => promotion.expires_at), (promotion, id) => {
              return (
                <article key={id} className="flex mb-10 p-30 bg-grey-100 rounded">
                  <div className="flex flex-col w-full">

                    <div className="promotion--top flex justify-between mb-10">

                      <div className="flex-center">
                        <ReactSVG src={`${lc_data.dir}dist/${CalendarIcon}`}
                                  className="relative mr-8 w-16 h-16 fill-grey-700" style={{ top: '-2px' }}/>
                        <div className="font-light text-grey-900">
                          {lc_data.jst[210]}
                          <span className="ml-6 font-semibold text-grey-1000">
                          {promotion.created_date}
                        </span>
                        </div>
                      </div>

                      {!buying &&
                      <button
                        type="button"
                        className="flex-center font-semibold text-base text-blue-700"
                        onClick={() => buyPromotion(promotion.wc_product_id, promotion.price, promotion.days, promotion.product_position)}
                      >
                        {lc_data.jst[167]}
                        <ReactSVG
                          src={`${lc_data.dir}dist/${icon}`}
                          className="ml-4 w-14 h-14 fill-icon-contact"
                        />
                      </button>
                      }
                      {buying &&
                      <ReactSVG
                        src={`${lc_data.dir}dist/${loader}`}
                        className="absolute"
                        style={{ zoom: 0.6, right: '60px' }}
                      />
                      }

                    </div>

                    <div>
                      <h5 className="mb-4 font-bold">{promotion.position}</h5>

                      <div className="flex justify-between mt-6">

                        <div className="flex items-center font-light text-grey-500">
                          <span className={`marker--status ${promotion.color}`}></span>
                          <span className="ml-10 font-semibold text-grey-1000">{promotion.expires_human}</span>
                          <span className="ml-4 text-grey-700">{lc_data.jst[212]}</span>
                        </div>

                      </div>
                    </div>

                  </div>
                </article>
              );
            })}
          </Scrollbars>
        </div>
        }
        {isEmpty(promotions) &&
        <div className="p-16 bg-grey-100 rounded font-semibold text-lg">
          {lc_data.jst[213]}
        </div>
        }

        <div className="promotions--buy flex flex-col mt-30">
          <NavLink
            to={`${lc_data.site_url}${url}/promotions`}
            className="flex justify-between items-center py-16 px-30 w-full h-auto bg-blue-700 rounded shadow font-bold text-xl text-white hover:bg-blue-800"
          >
            <span>{lc_data.jst[214]}</span>
            <ReactSVG
              src={`${lc_data.dir}dist/${InvestmentIcon}`}
              className="w-22 h-22 fill-white"
            />
          </NavLink>

          {undefined !== bumpUpProduct && !product.position &&
          <Fragment>
            <div className="my-10 w-full h-4 bg-grey-100 rounded"></div>

            <button
              type="button"
              className="flex justify-between items-center py-16 px-30 w-full h-auto bg-cyan-800 rounded shadow font-bold text-xl text-white hover:bg-cyan-900"
              onClick={() => buyPromotion(bumpUpProduct.ID, bumpUpProduct.price, 1, bumpUpProduct.post_name)}
            >
              <span>{lc_data.jst[215]}</span>
              <ReactSVG
                src={`${lc_data.dir}dist/${InvestmentIcon}`}
                className="w-22 h-22 fill-white"
              />
            </button>
          </Fragment>
          }

          <div className="mt-20 mx-4 product--position">
            {!product.position &&
            <p className="text-grey-700">
              {lc_data.jst[216]}
              <span className="mx-3 font-semibold text-grey-1000">{lc_data.jst[217]}</span>
              {lc_data.jst[218]}
            </p>
            }
            {product.position &&
            <p className="text-grey-700">
              {lc_data.jst[219]}
              <span className="mx-3 font-semibold text-grey-1000">{product.position}</span>
              {lc_data.jst[220]}
            </p>
            }
          </div>
        </div>

      </Fragment>
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

export default ActivePromotions;
