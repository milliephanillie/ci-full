/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../../../store/actions';
import { useEffect, useState } from '@wordpress/element';
import { __, sprintf, _n } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import DollarIcon from '../../../../../../../images/icons/coin.svg';
import ViewIcon from '../../../../../../../images/icons/eye.svg';
import CancelIcon from '../../../../../../../images/icons/cross-circle.svg';
import { NavLink } from 'react-router-dom';
import ReactTooltip from 'react-tooltip';
import cx from 'classnames';
import { Fragment } from 'react';

const MyAccountOrders = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile } = data;
  const [orders, setOrders] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [localLoading, setLocalLoading] = useState(true);

  const getOrders = (page) => {
    let formData = '';
    if ('' !== page) {
      formData = {
        page,
      };
    }
    const response = actions.fetchData(lc_data.get_wc_orders, formData);
    response.then((result) => {
      setOrders(result.data);
      setLocalLoading(false);
    });
  };

  useEffect(() => {
    if (isEmpty(profile)) {
      getOrders();
    }
  }, []);

  useEffect(() => {
    getOrders(currentPage);
  }, [currentPage]);

  return (
    <Fragment>
      {localLoading && <div key={0}
                            className="modal--no-content bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[401]}</div>}
      {!localLoading && !orders && <div key={0}
                                        className="modal--no-content bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[271]}</div>}
      {!loading && !localLoading && orders &&
      <section className="p-30 bg-white rounded shadow-theme w-full lg:w-3/4">

        <div className="w-full">
          <h3 className="mb-20 font-bold">{lc_data.jst[257]}</h3>
        </div>

        <div className="orders--headings hidden sm:flex p-20 bg-grey-100 rounded-t">

          <div className="order--head w-1/12">{lc_data.jst[272]}</div>
          <div className="order--head w-3/12">{lc_data.jst[273]}</div>
          <div className="order--head w-2/12">{lc_data.jst[274]}</div>
          <div className="order--head w-3/12">{lc_data.jst[275]}</div>
          <div className="order--head w-1/12">{lc_data.jst[233]}</div>

        </div>

        <div className="orders--content flex flex-wrap">

          {orders && orders.total > 0 && map(orders.orders, order => (
            <div key={order.id} className="order--item flex flex-wrap items-center mb-1 sm:mb-0 p-20 w-full">
              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-1/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[276]}</span>
                <a href="#">
                  {sprintf(lc_data.jst[277], order.id)}
                </a>
              </div>

              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-3/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[278]}</span>
                <span>{order.date}</span>
              </div>
              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-2/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[279]}</span>
                <span>{order.status}</span>
              </div>

              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-3/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[279]}</span>
                <div dangerouslySetInnerHTML={{
                  __html: sprintf(_n(lc_data.jst[280], lc_data.jst[281], order.item_count, 'lisfinity-core'), order.formatted_total, order.item_count),
                }}/>
              </div>

              <div className="order--btn mb-10 sm:mb-0 w-full sm:w-1/12">
                <div className="flex items-center justify-between">
                  {map(order.actions, (action, action_name) => {
                    const iconBg = cx({
                      'bg-red-300': action_name === 'cancel',
                      'bg-yellow-300': action_name === 'view',
                      'bg-green-300': action_name === 'pay',
                    });
                    return (
                      action_name === 'view' ?
                        <NavLink
                          exact
                          key={action_name}
                          to={`${lc_data.site_url}${lc_data.myaccount.replace('/', '')}${action.url}`}
                          data-tip={action.name}
                          className={`flex-center p-10 w-full sm:w-auto rounded ${iconBg}`}>
                          <ReactSVG
                            src={`${lc_data.dir}dist/${ViewIcon}`}
                            className="min-w-16 w-16 h-16 fill-grey-900"
                          />
                        </NavLink>
                        :
                        <a
                          href={action.url}
                          key={action_name}
                          data-tip={action.name}
                          className={`flex-center p-10 rounded w-full sm:w-auto ${iconBg}`}
                        >
                          {action_name === 'pay' &&
                          <ReactSVG
                            src={`${lc_data.dir}dist/${DollarIcon}`}
                            className="min-w-16 w-16 h-16 fill-grey-900"
                          />
                          }
                          {action_name === 'cancel' &&
                          <ReactSVG
                            src={`${lc_data.dir}dist/${CancelIcon}`}
                            className="min-w-16 w-16 h-16 fill-grey-900"
                          />
                          }
                        </a>
                    );
                  })}
                </div>
              </div>
              <ReactTooltip/>
            </div>
          ))}

          <div className="order--pagination flex items-center p-20 w-full bg-grey-100 rounded-b">
            {orders && orders.max_num_pages > 1 &&
            <ul
              className="flex-center"
            >
              <li>{lc_data.jst[255]}</li>
              {map(orders.pages, page => (
                <li key={page} className="px-4">
                  {page === orders.page && <span className="font-bold text-grey-1000">{page}</span>}
                  {page !== orders.page &&
                  <button type="button" className="font-bold text-grey-500 hover:text-grey-1000"
                          onClick={() => setCurrentPage(page)}>{page}</button>}
                </li>
              ))}
            </ul>
            }
          </div>

        </div>

      </section>
      }
    </Fragment>
  );
};

export default MyAccountOrders;
