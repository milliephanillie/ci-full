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

const PackagesActive = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const packages = business.active_packages;

  return (
    packages && !isEmpty(packages) &&
    <section className="packages-active mb-40 sm:mb-128">
      <div
        className="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-20 py-16 px-20 w-full bg-white rounded shadow-theme">
        <h3 className="mb-10 sm:mb-0 text-2xl sm:text-4xl font-bold text-grey-400">
          {_n(lc_data.jst[117], lc_data.jst[118], packages.length, 'lisfinity-core')}
          <span className="mx-4 text-grey-1000">{sprintf(lc_data.jst[119], packages.length)}</span>
          {_n(lc_data.jst[120], lc_data.jst[121], packages.length, 'lisfinity-core')}
        </h3>
        <button
          className="flex justify-between items-center w-full sm:w-225 px-20 h-60 bg-yellow-700 rounded shadow-theme font-bold text-white hover:bg-yellow-800"
          onClick={() => window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth',
          })}
        >
          <div className="flex flex-col text-left">
            <span className="text-sm">{lc_data.jst[204]}</span>
            <span className="font-bold text-xl">{lc_data.jst[595]}</span>
          </div>
          <ReactSVG
            src={`${lc_data.dir}dist/${PackageIcon}`}
            className="w-20 h-20 fill-white"
          />
        </button>
      </div>

      <div className="packages-active--wrapper">

        <div
          className="packages-active--header items-center justify-between mb-20 py-20 px-40 bg-white rounded shadow-theme">
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[122]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[24]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[127]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[123]}
          </div>
          <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
            {lc_data.jst[124]}
          </div>
        </div>

        <div className="packages-active--content -mb-20">
          {packages && !isEmpty(packages) && map(packages, product => {
            const timelineClass = cx({
              'bg-green-700': product.percentage < 35,
              'bg-yellow-700': product.percentage >= 35 && product.percentage < 75,
              'bg-red-700': product.percentage >= 75,
            });
            return (
              <article key={product.id}
                       className="flex flex-wrap items-center mb-20 py-20 px-40 bg-white rounded shadow-theme">

                <div className="flex flex-col package--width package--width__title">
                  <h6 className="font-semibold text-3xl">{product.title}</h6>
                  <div className="timeline relative mt-16 w-2/3 h-10 bg-grey-300 rounded overflow-hidden">
                    <div
                      className={`timeline--line absolute top-0 left-0 h-10 ${timelineClass}`}
                      style={{ width: `${product.percentage}%` }}
                    ></div>
                  </div>
                  <span
                    className="mt-10 font-semibold text-sm">{sprintf(_n(lc_data.jst[125], lc_data.jst[126], product.remaining, 'lisfinity-core'), product.remaining)}</span>
                  {product.type === 'subscription' && product.products_limit >= product.products_count &&
                  <span
                    className="mt-4 text-green-600 font-semibold text-sm"
                    dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[733], `${product.currency}${product.additional_listing}`) }}/>}
                </div>

                <div className="package--width">
                  <span className="text-grey-500">{product.products_count}</span>
                  <span className="mx-2 text-grey-900">/</span>
                  <span className="font-semibold text-grey-900">{product.products_limit}</span>
                  <span className="package--mobile-info ml-2 text-grey-500">{lc_data.jst[24]}</span>
                </div>

                <div className="package--width">
                  {product.products_duration}
                  <span className="package--mobile-info ml-2 text-grey-500">{lc_data.jst[127]}</span>
                </div>

                <div className="flex flex-col package--width xl:-mb-12">
                  <Fragment>
                    {product && product.promotion && !isEmpty(product.promotion.addon) && map(product.promotion.addon, (addon, i) =>
                      <div key={i} className="package--promotion mb-12">
                        <span>{addon.value}</span>
                        <span className="ml-4">{lc_data.jst[128]}</span>
                        <span className="ml-4 text-grey-700">{addon.title}</span>
                      </div>
                    )}
                    {product && product.promotion && isEmpty(product.promotion.addon) &&
                    <div>
                      <span className="text-grey-700">{lc_data.jst[129]}</span>
                    </div>
                    }
                  </Fragment>
                </div>

                <div className="package--width package--width__action">
                  <NavLink to={`${lc_data.site_url}${lc_data.myaccount}submit/${product.id}`}
                           className="flex justify-between items-center px-20 h-60 bg-green-700 rounded shadow-theme font-bold text-white hover:bg-green-800"
                           style={{ width: '225px' }}
                           onClick={() => {
                             dispatch(setMainIcon({
                               icon: BoltIcon,
                               color: 'fill-green-700',
                               title: lc_data.jst[116]
                             }));
                           }}
                  >
                    <div className="flex flex-col text-left">
                      <span className="text-sm">{lc_data.jst[115]}</span>
                      <span className="font-bold text-xl">{lc_data.jst[116]}</span>
                    </div>
                    <ReactSVG
                      src={`${lc_data.dir}dist/${BoltIcon}`}
                      className="w-20 h-20 fill-white"
                    />
                  </NavLink>
                </div>

              </article>
            );
          })}
        </div>

      </div>

    </section>
  );
};

export default PackagesActive;
