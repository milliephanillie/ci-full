/**
 * External dependencies
 */
import * as actions from '../../../dashboard/packages/store/actions';
import { __, sprintf } from '@wordpress/i18n';
import { formatMoney } from '../../../theme/vendor/functions';
import { useDispatch, useSelector } from 'react-redux';
import { useEffect, useState, useRef } from '@wordpress/element';
import { isEmpty, get, map } from 'lodash';
import { Fragment } from 'react';

const Costs = (props) => {
  const [total, setTotal] = useState(0);
  const {
    display,
    type,
    name,
    label,
    payment_package,
    calculation,
  } = props;
  const data = useSelector(state => state);

  let additionalCost = 0;
  !isEmpty(payment_package?.categories) && map(payment_package.categories, cat => {
    if (data.formData?.cf_category === cat?.category) {
      additionalCost += parseFloat(cat.price) || 0;
    }
  });

  return (
    <Fragment>
      {display && !isEmpty(data.costs) && calculation === 'simple' &&
      <div
        className="flex justify-end mt-40 w-full py-14 border-t border-b border-grey-200 font-semibold text-lg text-grey-600"
        dangerouslySetInnerHTML={{
          __html: sprintf(
            lc_data.jst[328],
            `<span class="ml-4 text-grey-900">${sprintf(payment_package.price_format, payment_package.currency,
              formatMoney(data.costs['total'][type] || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))}</span>`
          )
        }}>
      </div>
      }

      {display && !isEmpty(data.costs) && calculation === 'full' &&
      <div className="mt-40 w-full">
        <div
          className="flex justify-between w-full py-14 border-t border-grey-200 font-semibold sm:text-lg text-grey-600"
        >
          <p className="font-semibold sm:text-lg text-grey-1000">{lc_data.jst[327]}</p>
          <div
            className="flex"
            dangerouslySetInnerHTML={{
              __html: sprintf(
                lc_data.jst[328],
                `<div class="flex min-w-60 ml-4 text-grey-900 text-right">${sprintf(payment_package.price_format, payment_package.currency,
                  formatMoney(data.costs['total']['media'] || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))}</div>`
              )
            }}>
          </div>
        </div>
        <div
          className="flex justify-between w-full py-14 border-t border-grey-200 font-semibold sm:text-lg text-grey-600"
        >
          <p className="font-semibold sm:text-lg text-grey-1000">{lc_data.jst[329]}</p>
          <div
            className="flex"
            dangerouslySetInnerHTML={{
              __html: sprintf(
                lc_data.jst[330],
                `<div class="flex min-w-60 ml-4 text-grey-900 text-right">${sprintf(payment_package.price_format, payment_package.currency,
                  formatMoney(data.costs['total']['promo'] || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))}</div>`
              )
            }}>
          </div>
        </div>
        {data.costs['total']['commission'] &&
        <div
          className="flex justify-between w-full py-14 border-t border-grey-200 font-semibold sm:text-lg text-grey-600"
        >
          <p className="font-semibold sm:text-lg text-grey-1000">{lc_data.jst[731]}</p>
          <div
            className="flex"
            dangerouslySetInnerHTML={{
              __html: sprintf(
                lc_data.jst[732],
                `<div class="flex min-w-60 ml-4 text-grey-900 text-right">${sprintf(payment_package.price_format, payment_package.currency,
                  formatMoney(data.costs['total']['commission'] || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))}</div>`
              )
            }}>
          </div>
        </div>
        }
        {data.costs['total']['additional'] > 0 &&
        <div
          className="flex justify-between w-full py-14 border-t border-grey-200 font-semibold sm:text-lg text-grey-600"
        >
          <p className="font-semibold sm:text-lg text-grey-1000">{lc_data.jst[731]}</p>
          <div
            className="flex"
            dangerouslySetInnerHTML={{
              __html: sprintf(
                lc_data.jst[732],
                `<div class="flex min-w-60 ml-4 text-grey-900 text-right">${sprintf(payment_package.price_format, payment_package.currency,
                  formatMoney(data.costs['total']['additional'] || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))}</div>`
              )
            }}>
          </div>
        </div>
        }
        <div
          className="flex justify-between w-full pt-14 pb-60 border-t border-b border-grey-200 font-semibold text-lg text-grey-600"
        >
          <p className="font-semibold text-lg text-red-800">{lc_data.jst[331]}</p>
          <div
            className="flex"
            dangerouslySetInnerHTML={{
              __html: sprintf(
                lc_data.jst[332],
                `<div class="flex min-w-60 ml-4 text-red-800 text-right">${sprintf(payment_package.price_format, payment_package.currency,
                  formatMoney(((data.costs['total']['media'] || 0) + (data.costs['total']['promo']) || 0) + (data.costs['total']?.['commission'] || 0) + (data.costs['total']?.['additional'] || 0) || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))}</div>`
              )
            }}>
          </div>
        </div>
      </div>
      }
    </Fragment>
  );
};

export default Costs;
