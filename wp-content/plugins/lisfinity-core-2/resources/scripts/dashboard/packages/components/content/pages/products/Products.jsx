/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState, useEffect } from '@wordpress/element';
import * as actions from '../../../../store/actions';
import { isEmpty, filter, map, sortBy, reverse } from 'lodash';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import Select from 'react-select';
import SearchIcon from '../../../../../../../images/icons/search.svg';
import sortIcon from '../../../../../../../images/icons/sort-amount-asc.svg';
import BoltIcon from '../../../../../../../images/icons/bolt-alt.svg';
import Product from './Product';
import axios from 'axios';
import { Fragment } from 'react';
import ProductsPagination from './ProductsPagination';
import LoaderDashboardProducts from '../../../../../../theme/packages/components/loaders/LoaderDashboardProducts';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ProductsPaginationFirstLast from './ProductsPaginationFirstLast';

const Products = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const [loading, setLoading] = useState(false);
  const [fieldActive, setFieldActive] = useState(false);
  const [allAds, setAllAds] = useState(business.ads);
  const [ads, setAds] = useState(business.ads);
  const [withExpired, setWithExpired] = useState(false);
  const [sortValue, setSortValue] = useState('submitted');
  const [modalOpen, setModalOpen] = useState(false);
  const sortOptions = [
    { value: 'submitted', label: lc_data.jst[235] },
    { value: 'expires', label: lc_data.jst[236] },
    { value: 'title', label: lc_data.jst[230] },
  ];
  const [smallScreen, setSmallScreen] = useState(false);

  const smallScreenCalc = () => {
    if (window.innerWidth < 780) {
      setSmallScreen(true);
    } else {
      setSmallScreen(false);
    }
  };

  useEffect(() => {
    smallScreenCalc();
    window.addEventListener('resize', smallScreenCalc);

    return () => removeEventListener('resize', smallScreenCalc);
  }, []);

  const filterAds = (value) => {
    setLoading(true);
    if (value !== '') {
      let filterAds = filter(business.ads, ad => ad.title.toLowerCase().includes(value.toLowerCase()));
      const start = 0;
      const end = start + business.ads_pagination.per_page;
      business.ads_pagination.page = 1;
      business.ads_pagination.maxPages = Math.ceil(filterAds.length / business.ads_pagination.per_page);
      setAds(filterAds.slice(start, end));
    } else {
      const start = 0;
      const end = start + business.ads_pagination.per_page;
      business.ads_pagination.page = 1;
      business.ads_pagination.maxPages = Math.ceil(business.ads.length / business.ads_pagination.per_page);
      setAds(business.ads.slice(start, end));
    }
    dispatch(actions.setBusiness(business));
    setTimeout(() => {
      setLoading(false);
    }, 200);
  };

  useEffect(() => {
    sortAds(true);
  }, [sortValue]);

  const sortAds = (resetPage = false) => {
    let orderAds;
    if ('submitted' === sortValue) {
      orderAds = reverse(sortBy(business.ads, [ad => ad[sortValue]]));
    } else {
      orderAds = sortBy(business.ads, [ad => ad[sortValue]]);
    }
    const start = (business.ads_pagination.page - 1) * business.ads_pagination.per_page;
    const end = start + business.ads_pagination.per_page;
    setAds(orderAds.slice(start, end));
  };

  const fetchAllAds = () => {
    setLoading(true);
    const data = {
      user_id: lc_data.current_user_id,
    };
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.get_all_ads,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data,
    }).then(result => {
      const start = (business.ads_pagination.page - 1) * business.ads_pagination.per_page;
      const end = start + business.ads_pagination.per_page;
      setAds(result.data.ads.slice(start, end));
      setAllAds(result.data.ads);
      dispatch(actions.setBusiness(result.data));
      setLoading(false);
    });
  };

  const fetchWithExpired = () => {
    setLoading(true);
    setWithExpired(!withExpired);
    let useExpired = {};
    if (!withExpired) {
      useExpired = { expired: true, business: business.business.ID };
    }

    const response = actions.fetchData(lc_data.get_business, useExpired);
    response.then((result) => {
      const start = (business.ads_pagination.page - 1) * business.ads_pagination.per_page;
      const end = start + business.ads_pagination.per_page;
      setAds(result.data.ads.slice(start, end));
      setAllAds(result.data.ads);
      dispatch(actions.setBusiness(result.data));
      setLoading(false);
    });
  };

  useEffect(() => {
    if (data.info.refresh_products) {
      fetchAllAds();
      data.info.refresh_products = false;
      dispatch(actions.setInfo(data.info));
    }
    const orderAds = reverse(sortBy(allAds, [ad => ad.submitted]));
    const start = (business.ads_pagination.page - 1) * business.ads_pagination.per_page;
    const end = start + business.ads_pagination.per_page;
    setAds(orderAds.slice(start, end));
  }, []);

  useEffect(() => {
    sortAds();
  }, [business.ads_pagination.page]);

  const deleteProduct = (index, id) => {
    if (!confirm(lc_data.jst[173])) {
      return false;
    }
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.product_action}/delete`;
    let data = {
      product_id: id,
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        const newAds = ads.filter((ad, i) => {
          return i !== index;
        });
        setAds(newAds);
      }
    });
  };

  return (
    <div>
      <div className="flex">
        <section className="dashboard--products w-full">

          <div className="products--filter flex flex-wrap flex-grow-0 py-20 px-30 w-full bg-white rounded shadow-theme">

            <div
              className={`products--find flex items-center my-4 mr-10 pl-20 h-44 border border-grey-300 rounded ${fieldActive ? 'bg-transparent' : 'bg-grey-100'}`}>
              <ReactSVG
                src={`${lc_data.dir}dist/${SearchIcon}`}
                className="mr-10 w-16 h-16 fill-grey-700"
              />
              <input
                type="text"
                className="pl-0 p-20 w-full bg-transparent font-semibold text-grey-900"
                placeholder={lc_data.jst[225]}
                onChange={e => filterAds(e.target.value)}
                onClick={e => e.target.setSelectionRange(0, e.target.value.length)}
                onFocus={() => setFieldActive(true)}
                onBlur={() => setFieldActive(false)}
              />
            </div>

            <div className="products--sort flex items-center my-4 mr-30"
                 style={{ width: !smallScreen ? '220px' : '160px' }}>
              <label htmlFor="sortby" className="flex items-center text-sm text-grey-500 whitespace-no-wrap z-2 -mr-6"
                     style={{ width: !smallScreen && '100px' }}>
                <ReactSVG src={`${lc_data.dir}dist/${sortIcon}`} className="mr-10 w-18 h-18 fill-grey-700"/>
                {!smallScreen && lc_data.jst[182]}
              </label>
              <Select
                options={sortOptions}
                isSearchable={false}
                defaultValue={sortOptions[0]}
                className="select-transparent w-full"
                onChange={selected => setSortValue(selected.value)}
              />
            </div>

            <div className="products--with-expired toggle flex items-center my-4 font-semibold">
              <ReactSVG src={`${lc_data.dir}dist/${BoltIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-grey-700"/>
              {!smallScreen &&
              <span
                className="toggle--label relative top-1 text-sm text-grey-500">{lc_data.jst[226]}</span>
              }
              {!smallScreen && !withExpired &&
              <span className="toggle--label ml-4 mr-8 min-w-44">{lc_data.jst[227]}</span>
              }
              {!smallScreen && withExpired &&
              <span className="toggle--label ml-4 mr-8 min-w-44">{lc_data.jst[228]}</span>
              }
              <label htmlFor="withExpired" className="switch">
                <input
                  id="withExpired"
                  type="checkbox"
                  className="input--toggle"
                  onChange={() => {
                    setWithExpired(!withExpired);
                    fetchWithExpired();
                  }}
                  checked={withExpired}
                />
                <span className="slider"></span>
              </label>
            </div>

          </div>

          <div className="products flex flex-col mt-40 -mb-10">

            {!isEmpty(ads) &&
            <Fragment>
              <div className="products--headers hidden mb-20 bg:flex">
                <div
                  className="product--header product--figure px-30 font-bold text-sm text-grey-700">{lc_data.jst[229]}</div>
                <div
                  className="product--header product--title px-30 font-bold text-sm text-grey-700">{lc_data.jst[230]}</div>
                <div
                  className="product--header product--price-table px-30 font-bold text-sm text-grey-700">{lc_data.jst[231]}</div>
                <div
                  className="product--header product--agent px-30 font-bold text-sm text-grey-700">{lc_data.jst[169]}</div>
                <div
                  className="product--header product--notifications px-30 font-bold text-sm text-grey-700">{lc_data.jst[232]}</div>
                <div
                  className="product--header product--actions px-30 font-bold text-sm text-grey-700">{lc_data.jst[233]}</div>
              </div>

              {!loading && map(ads, (ad, index) =>
                <Product key={ad.id} product={ad} deleteProduct={() => { deleteProduct(index, ad.id);}}/>)}
            </Fragment>
            }

            {isEmpty(ads) && !loading &&
            <div
              className="content flex items-center bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[234]}</div>
            }

            {loading && <LoaderDashboardProducts/>}
          </div>

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
        </section>

      </div>
      {!loading && business?.ads_pagination?.maxPages > 1 &&
      <Fragment>
        <ProductsPagination/>
        <ProductsPaginationFirstLast pagination={business.ads_pagination}/>
      </Fragment>
      }
    </div>
  );
};

export default Products;
