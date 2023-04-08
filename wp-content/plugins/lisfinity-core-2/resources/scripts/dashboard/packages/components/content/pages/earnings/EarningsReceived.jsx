/* global lc_data, React */

import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useEffect, useState, orderBy } from '@wordpress/element';
import { map, filter, isEmpty } from 'lodash';
import he from 'he';

const EarningsReceived = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const premium = business.business.premium;
  const product = business.business.premium_product;
  const payouts = business.business.vendor.payouts;
  const [dues, setDues] = useState(false);

  useEffect(() => {
    setDues(filter(payouts,payout => payout.status === 'paid').reverse());
  }, [payouts]);
  return (
    <div className="payouts">

      {!isEmpty(dues) &&
      <div className="payouts--table payouts--table__due px-20 -mx-20">

        <div className="payouts--table-headers flex px-20">
          <div className="id w-1/12 font-bold text-sm text-grey-700">{lc_data.jst[663]}</div>
          <div className="title w-3/12 font-bold text-sm text-grey-700">{lc_data.jst[664]}</div>
          <div className="listing w-3/12 font-bold text-sm text-grey-700">{lc_data.jst[665]}</div>
          <div className="created w-1/12 font-bold text-sm text-grey-700">{lc_data.jst[666]}</div>
          <div className="order w-2/12 font-bold text-sm text-grey-700">{lc_data.jst[667]}</div>
          <div className="amount w-2/12 font-bold text-sm text-grey-700">{lc_data.jst[668]}</div>
        </div>


        <div className="flex flex-wrap">
          {map(dues, due => {
            return (
              <div key={due.ID}
                   className="animate-up flex p-20 mt-10 bg-white rounded shadow-theme overflow-auto w-full">
                <div className="id w-1/12">{due.ID}</div>
                <div className="title w-3/12">{due.title}</div>
                <div className="listing w-3/12">{due.product_title}</div>
                <div className="created w-1/12">{due.created}</div>
                <div className="order w-2/12">{due.order}</div>
                <div className="amount w-2/12">{he.decode(due.amount)}</div>
              </div>
            );
          })}
        </div>

      </div>
      }
      {isEmpty(dues) && <div key={0}
                             className="modal--no-content bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[669]}</div>
      }

    </div>
  );
};

export default EarningsReceived;
