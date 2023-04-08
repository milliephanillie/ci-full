/* global lc_data, React */

import { Fragment, useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import produce from 'immer';
import { map, debounce, isEmpty } from 'lodash';
import BusinessArchiveHeader from './BusinessArchiveHeader';
import BusinessArchiveQuery from './BusinessArchiveQuery';
import Pagination from '../partials/BusinessPagination';
import ReactSVG from 'react-svg';
import sortIcon from '../../../../../images/icons/star.svg';
import Select from 'react-select';
import LoaderProductSingle from '../loaders/LoaderProductSingle';

const BusinessArchive = (props) => {
  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [options, setOptions] = useState({});
  const [data, setData] = useState(false);
  const [keyword, setKeyword] = useState('');
  const [order, setOrder] = useState('all');
  const [firstLoad, setFirstLoad] = useState(true);
  const [selectedLetter, setSelectedLetter] = useState('');

  const sortOptions = [
    { value: 'all', label: lc_data.jst[572] },
    { value: '5', label: lc_data.jst[566] },
    { value: '4', label: lc_data.jst[567] },
    { value: '3', label: lc_data.jst[568] },
    { value: '2', label: lc_data.jst[569] },
    { value: '1', label: lc_data.jst[570] },
  ];

  const fetchData = (allData = true, paginationPage = 1) => {
    setLoading(true);
    setFetching(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    }
    const url = lc_data.business_archive;
    const fetchData = {
      page_id: lc_data.page_id,
      page: paginationPage,
    };
    if (!allData) {
      fetchData.vendorsOnly = true;
      if (!isEmpty(keyword)) {
        fetchData.keyword = keyword;
      }
      if (!isEmpty(order)) {
        fetchData.order = order.value;
      }
      if (!isEmpty(selectedLetter)) {
        fetchData.letter = selectedLetter;
      }
    }
    axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
      params: fetchData,
    }).then(result => {
      if (!allData) {
        const nextState = produce(data, draft => {
          draft.vendors = result.data.vendors;
        });
        setData(nextState);
      } else {
        setData(result.data);
        setFirstLoad(false);
      }
      const oldLoader = document.getElementById('loader');
      if (oldLoader) {
        oldLoader.classList.add('fade-out');
        setTimeout(() => {
          oldLoader.remove();
        }, 400);
      }
      setLoading(false);
      setFetching(false);
    });
  }

  const filterBusinesses = debounce(value => {
    setKeyword(value);
  }, 275, false);

  useEffect(() => {
    if (firstLoad) {
      setOptions(JSON.parse(document.getElementById('page-archive-vendors').dataset.options));
      fetchData();
    } else {
      fetchData(false, 1);
    }
  }, [keyword]);

  useEffect(() => {
    if (!firstLoad) {
      fetchData(false, 1);
    }
  }, [order]);

  useEffect(() => {
    if (!firstLoad) {
      fetchData(false, 1);
    }
  }, [selectedLetter]);

  return (
    <Fragment>
      {data &&
      <div className="relative pb-60 bg-grey-100">
        <BusinessArchiveHeader options={data.options}/>

        <div className="vendors--search bg-white">
          <div className="container flex flex-wrap items-center justify-between py-10">

            <div className="flex items-center text-lg">
              <button
                className={`capitalize ${selectedLetter === '' ? 'px-10 bg-blue-200 rounded' : ''}`}
                onClick={e => setSelectedLetter('')}
              >
                {lc_data.jst[572]}
              </button>
              {
                data.letters &&
                map(data.letters, (letter, index) => (
                  <button
                    key={index}
                    className={`ml-10 ${selectedLetter === letter ? 'px-10 bg-blue-200 rounded' : ''}`}
                    onClick={e => setSelectedLetter(letter)}
                  >
                    {letter.toUpperCase()}
                  </button>
                ))
              }
            </div>

            <div className="flex items-center mt-10 sm:mt-0 sm:ml-auto">
              <div
                className="products--find flex items-center p-20 h-44 bg-grey-100 border border-grey-300 rounded">
                <input
                  type="text"
                  className="w-full bg-transparent font-semibold text-grey-900"
                  placeholder={lc_data.jst[565]}
                  onChange={e => filterBusinesses(e.target.value)}
                />
              </div>

              <div className="search--action search--action__small-width flex items-center ml-40">
                <label htmlFor="sortby" className="flex items-center mr-4 text-sm text-grey-500">
                  <ReactSVG src={`${lc_data.dir}dist/${sortIcon}`} className="mr-10 -ml-10 w-14 h-14 fill-grey-500"/>
                  {lc_data.jst[571]}
                </label>
                <Select
                  id="sortby"
                  options={sortOptions}
                  isSearchable={false}
                  defaultValue={sortOptions[0]}
                  isRtl={lc_data.rtl}
                  onChange={(selected) => setOrder(selected)}
                />
              </div>
            </div>

          </div>
        </div>

        <div className="vendors-container container py-20">

          {!isEmpty(data.vendors.promoted) &&
          <div className="mt-20">
            <BusinessArchiveQuery data={{ query: data.vendors.promoted }} options={data.options} type="premium"
                                  loading={fetching}/>
          </div>
          }
          {!isEmpty(data.vendors) &&
          <div className="mt-20">
            <BusinessArchiveQuery data={data.vendors} options={data.options} type="default" loading={fetching}/>
          </div>
          }
          {isEmpty(data.vendors) && isEmpty(data.vendors.promoted) &&
          <div
            className="flex items-center mt-20 bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[564]}</div>
          }

          {data.vendors.max_num_pages > 1 &&
          <div className="pagination">
            <Pagination results={data.vendors} handlePagination={(e, paginationPage) => {
              fetchData(false, paginationPage);
            }}/>
          </div>
          }

        </div>
      </div>
      }
    </Fragment>
  );
}

export default BusinessArchive;
