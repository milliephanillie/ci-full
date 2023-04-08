/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { map, debounce, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import axios from 'axios';
import produce from 'immer';
import sortIcon from '../../../../../images/icons/star.svg';
import Select from 'react-select';
import { useDispatch, useSelector } from 'react-redux';
import { setVendors, setVendorsLoading } from '../../store/actions';

const AuthorSearchEl = (props) => {
  const state = useSelector(state => state);
  const { vendors } = state;
  const dispatch = useDispatch();
  const wrapper = useRef(null);
  const [settings, setSettings] = useState(true);
  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [data, setData] = useState(false);
  const [keyword, setKeyword] = useState('');
  const [order, setOrder] = useState('all');
  const [firstLoad, setFirstLoad] = useState(true);
  const [selectedLetter, setSelectedLetter] = useState(false);

  const sortOptions = [
    { value: 'all', label: lc_data.jst[572] },
    { value: '5', label: lc_data.jst[566] },
    { value: '4', label: lc_data.jst[567] },
    { value: '3', label: lc_data.jst[568] },
    { value: '2', label: lc_data.jst[569] },
    { value: '1', label: lc_data.jst[570] },
  ];

  const fetchData = (allData = true, paginationPage = 1) => {
    dispatch(setVendorsLoading(true));
    setLoading(true);
    setFetching(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
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
        const nextState = produce(vendors, draft => {
          draft.vendors = result.data.vendors;
        });
        setData(nextState);
        dispatch(setVendors(nextState));
      } else {
        setData(result.data);
        dispatch(setVendors(result.data));
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
      dispatch(setVendorsLoading(false));
    });
  };

  const filterBusinesses = debounce(value => {
    setKeyword(value);
  }, 275, false);

  useEffect(() => {
    if (!state.vendorsFL) {
      fetchData(false, 1);
    }
  }, [order]);

  useEffect(() => {
    if (!state.vendorsFL) {
      fetchData(false, 1);
    }
  }, [keyword]);

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.elementor-author-search');

    if (el) {
      const settingsData = JSON.parse(el.dataset.settings);
      setSettings(settingsData);
    }
  }, [data]);

  useEffect(() => {
    if (false !== selectedLetter) {
      fetchData(false, 1);
    }
  }, [selectedLetter]);


  return (
    <div className="vendors--search bg-white" ref={wrapper}>
      <div className="container flex flex-wrap items-center justify-between py-10">

        <div className="flex items-center text-lg">
          <button
            className={`capitalize letters-button ${selectedLetter === '' || !selectedLetter ? 'px-10 bg-blue-200 rounded' : ''}`}
            onClick={e => setSelectedLetter('')}
          >
            {lc_data.jst[572]}
          </button>
          {
            vendors.letters &&
            map(vendors.letters, (letter, index) => (
              <button
                key={index}
                className={`ml-10 letters-button ${selectedLetter === letter ? 'px-10 bg-blue-200 rounded' : ''}`}
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
              {(!settings?.icon || settings?.icon.value === '') &&
              <ReactSVG src={`${lc_data.dir}dist/${sortIcon}`}
                        className="mr-10 -ml-10 w-14 h-14 select-icon fill-grey-500"/>
              }
              {settings?.icon?.value && settings?.icon?.library !== 'svg' &&
              <i
                className={`${settings.icon.value} mr-10 -ml-10 w-14 h-14 select-icon`}></i>
              }
              {settings?.icon?.value.url && settings?.icon?.library === 'svg' &&
              <ReactSVG
                src={`${settings.icon.value.url}`}
                className={`w-16 h-16 mr-10 -ml-10 w-14 h-14 select-icon`}
              />
              }
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
  );
};

export default AuthorSearchEl;
