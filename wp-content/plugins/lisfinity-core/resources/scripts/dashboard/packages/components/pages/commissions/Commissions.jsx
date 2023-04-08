/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { sprintf, __, _n } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import cx from 'classnames';
import { Fragment } from 'react';
import ReactSVG from 'react-svg';
import BoltIcon from '../../../../../../images/icons/bolt-alt.svg';
import { NavLink } from 'react-router-dom';
import PackageIcon from '../../../../../../images/icons/package.svg';
import { setMainIcon } from '../../../store/actions';
import axios from 'axios';

const Commissions = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const { commissions } = business;

  const payCommission = async (wc_product_id, commission, product_id) => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.purchase_commission;
    let data = {
      business_id: business.business_id,
      wc_product: wc_product_id,
      price: commission,
      buyer_id: commission,
      product: product_id,
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

  return (
    <section className="packages-active mb-40">

      {!commissions || isEmpty(commissions) &&
      <div className="p-20 bg-white rounded shadow-theme">
        {lc_data.jst[719]}
      </div>
      }

      {commissions && !isEmpty(commissions) &&
      <div className="packages-active--wrapper">

        <div
          className="packages-active--header items-center justify-between mb-20 py-20 px-40 bg-white rounded shadow-theme">
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[665]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[715]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[716]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[717]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[124]}
          </div>
        </div>

        <div className="packages-active--content -mb-20">
          {commissions && !isEmpty(commissions) && map(commissions, product => {
            return (
              <article key={product.id}
                       className="flex flex-wrap items-center mb-20 py-20 px-40 bg-white rounded shadow-theme">

                <div className="flex flex-col package--width w-1/5">
                  <h6 className="font-semibold text-xl">{product.title}</h6>
                </div>

                <div className="flex flex-col package--width w-1/5">
                  <p className="font-semibold" dangerouslySetInnerHTML={{ __html: product.price }}/>
                </div>

                <div className="flex flex-col package--width w-1/5">
                  <p className="font-semibold"
                     dangerouslySetInnerHTML={{ __html: product.is_percentage ? `${product.commission_price}%` : product.commission_price }}/>
                </div>

                <div className="flex flex-col package--width w-1/5">
                  <p className="font-semibold text-red-700" dangerouslySetInnerHTML={{ __html: product.commission }}/>
                </div>

                <div className="package--width package--width__action">
                  <button
                    type="button"
                    className="flex justify-between items-center px-20 h-60 bg-red-600 rounded shadow-theme font-bold text-white hover:bg-red-700"
                    style={{ width: '225px' }}
                    onClick={() => payCommission(product.product_id, product.commission_due, product.id)}
                  >
                    <div className="flex flex-col text-left">
                      <span className="text-sm">{lc_data.jst[717]}</span>
                      <span className="font-bold text-xl">{lc_data.jst[718]}</span>
                    </div>
                    <ReactSVG
                      src={`${lc_data.dir}dist/${BoltIcon}`}
                      className="w-20 h-20 fill-white"
                    />
                  </button>
                </div>

              </article>
            );
          })}
        </div>

      </div>
      }

    </section>
  );
};

export default Commissions;
