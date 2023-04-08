/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import icon from '../../../../../images/icons/spinner-arrow.svg';
import loader from '../../../../../images/icons/loader-rings-blue.svg';
import { map, orderBy } from 'lodash';
import { Scrollbars } from 'react-custom-scrollbars';
import axios from 'axios';
import { useEffect, useState } from '@wordpress/element';
import { NavLink } from 'react-router-dom';
import ModalDemo from '../../../../theme/packages/components/modal/ModalDemo';
import he from 'he';

const ExpiringPromotions = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, options } = data;
  const [buying, setBuying] = useState(false);
  const [expiringPromotions, setExpiringPromotions] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

  const renewPromotion = async (e, promotion) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setBuying(promotion.id);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.purchase_promotion;
    let data = {
      id: lc_data.current_user_id,
      product: promotion.product_id,
      wc_product: promotion.wc_product_id,
      price: promotion.price,
      days: promotion.days,
    };
    await axios({
      credentials: 'same-origin',
      method: 'post',
      url,
      data,
      headers,
    }).then(data => {
      if (data.data.success) {
        window.location.href = data.data.permalink;
      }
    });
  };

  useEffect(() => {
    if (business && Object.keys(business.promotions).length > 0) {
      const expiring = orderBy(business.promotions, ['expires'], ['asc']);
      setExpiringPromotions(expiring.slice(0, 4));
    }
  }, []);

  return (
    options && options.promotions &&
    <div
      className="dashboard-widget--expiring-promotions flex flex-col mb-20 p-20 bg-white rounded shadow-theme w-full sm:w-1/2 xl:w-full">

      <div className="dashboard-widget--header flex justify-between items-center mb-20 px-10">
        <div className="flex-center">
          <ReactSVG
            src={`${lc_data.dir}dist/${icon}`}
            className="mr-8 w-16 h-16 fill-black"
          />
          <h6 className="font-semibold text-lg">{lc_data.jst[316]}</h6>
        </div>
        <div className="flex-center p-2 w-20 h-20 bg-grey-100 rounded">{Object.keys(expiringPromotions).length}</div>
      </div>

      {!expiringPromotions && lc_data.jst[655]}
      {expiringPromotions &&
      <div className="-mb-6">
        <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight
                    autoHeightMin={business.promotions.length >= 2 ? 240 : 102}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
          {map(expiringPromotions, (promotion, id) => {
            return (
              <article key={id} className="relative flex mb-6 p-16 bg-grey-100 rounded">
                {promotion.thumbnail &&
                <NavLink to={promotion.ad_link}>
                  <figure className="relative flex mr-10 rounded overflow-hidden"
                          style={{ width: '120px', minHeight: '75px' }}
                  >
                    <img
                      src={promotion.thumbnail}
                      alt={promotion.post_title}
                      className="absolute top-0 left-0 w-full h-full object-cover"
                    />
                  </figure>
                </NavLink>
                }
                <div className="flex flex-col w-full">
                  <p className="mb-4 uppercase font-semibold text-sm text-green-900">{promotion.position}</p>
                  <h6 className="text-base"><NavLink to={promotion.ad_link}>{he.decode(promotion.title)}</NavLink></h6>

                  <div className="flex justify-between mt-6">

                    <div className="font-light text-grey-500">
                      {lc_data.jst[171]}
                      <span className="ml-4 font-semibold text-red-500">{promotion.expires_human}</span>
                    </div>

                    {(!buying || buying !== promotion.id) &&
                    <button type="button" className="relative flex-center font-semibold text-base text-blue-700"
                            onClick={(e) => renewPromotion(e, promotion)}
                    >
                      {lc_data.jst[167]}
                      <ReactSVG
                        src={`${lc_data.dir}dist/${icon}`}
                        className="ml-4 w-14 h-14 fill-icon-contact"
                      />
                    </button>
                    }
                    {buying === promotion.id &&
                    <ReactSVG
                      src={`${lc_data.dir}dist/${loader}`}
                      className="absolute right-20"
                      style={{ zoom: 0.6 }}
                    />
                    }
                  </div>

                </div>
              </article>
            );
          })}
        </Scrollbars>
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

export default ExpiringPromotions;
