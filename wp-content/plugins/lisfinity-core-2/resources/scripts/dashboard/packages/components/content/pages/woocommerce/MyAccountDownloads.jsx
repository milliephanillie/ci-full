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
import DownloadIcon from '../../../../../../../images/icons/download.svg';
import ReactTooltip from 'react-tooltip';
import { Fragment } from 'react';

const MyAccountDownloads = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile } = data;
  const [downloads, setDownloads] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);

  const getOrders = (page) => {
    let formData = '';
    if ('' !== page) {
      formData = {
        page,
      }
    }
    const response = actions.fetchData(lc_data.get_wc_downloads, formData);
    response.then((result) => {
      setDownloads(result.data);
    });
  }

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
      {!downloads && <div key={0}
                          className="modal--no-content bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[256]}</div>}
      {!loading && downloads &&
      <section className="p-30 bg-white rounded shadow-theme w-full lg:w-3/4">

        <div className="w-full">
          <h3 className="mb-20 font-bold">{lc_data.jst[257]}</h3>
        </div>

        <div className="downloads--headings hidden sm:flex p-20 bg-grey-100 rounded-t">

          <div className="order--head w-3/12">{lc_data.jst[258]}</div>
          <div className="order--head w-3/12">{lc_data.jst[259]}</div>
          <div className="order--head w-2/12">{lc_data.jst[260]}</div>
          <div className="order--head w-3/12">{__('Download', 'lisfinity-core')}</div>

        </div>

        <div className="downloads--content flex flex-wrap">

          {downloads && downloads.length > 0 && map(downloads, (order, index) => (
            <div key={index} className="download--item flex flex-wrap items-center mb-1 sm:mb-0 p-20 w-full">
              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-3/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[261]}</span>
                <a href={order.product_url}>
                  {order.product_name}
                </a>
              </div>

              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-3/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[262]}</span>
                <span>{'' !== order.downloads_remaining ? order.downloads_remaining : '∞'}</span>
              </div>

              <div className="flex flex-col mb-10 sm:mb-0 w-full sm:w-2/12">
                <span className="mr-3 font-bold flex sm:hidden">{lc_data.jst[148]}</span>
                <span>{null !== order.access_expires ? order.downloads_remaining : lc_data.jst[263]}</span>
              </div>

              <div className="mb-10 sm:mb-0 w-full sm:w-1/12">
                <div className="flex items-center justify-between">
                  <a
                    href={order.download_url}
                    data-tip={lc_data.jst[264]}
                    className="flex-center p-10 rounded bg-blue-300"
                  >
                    <ReactSVG
                      src={`${lc_data.dir}dist/${DownloadIcon}`}
                      className="min-w-16 w-16 h-16 fill-grey-900"
                    />
                  </a>
                </div>
              </div>
              <ReactTooltip/>
            </div>
          ))}

          <div className="order--pagination flex items-center p-20 w-full bg-grey-100 rounded-b">
            {downloads && downloads.max_num_pages > 1 &&
            <ul
              className="flex-center"
            >
              <li>{lc_data.jst[255]}</li>
              {map(downloads.pages, page => (
                <li key={page} className="px-4">
                  {page === downloads.page && <span className="font-bold text-grey-1000">{page}</span>}
                  {page !== downloads.page &&
                  <button type="button" className="font-bold text-grey-500 hover:text-grey-1000"
                          onClick={() => setCurrentPage(page)}>{page}</button>}
                </li>
              ))}
            </ul>
            }
          </div>

        </div>

      </section>}
    </Fragment>
  );
};

export default MyAccountDownloads;
