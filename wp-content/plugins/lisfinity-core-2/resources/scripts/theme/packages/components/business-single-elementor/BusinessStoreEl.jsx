/* global lc_data, React */

import Filters from './SearchFiltersEl';
import SearchChosenFilters from '../page-search/page-search--content/SearchChosenFilters';
import BusinessStoreContent from './BusinessStoreContentEl';
import BusinessStoreBreadcrumb from './BusinessStoreBreadcrumbEl';
import {useEffect, useState} from '@wordpress/element';
import queryString from 'query-string';
import * as actions from '../../store/actions';
import {fetchPosts} from '../page-search/store/actions';
import {useDispatch, useSelector} from 'react-redux';
import {map, isEmpty} from 'lodash';
import SearchDetailed from '../page-search/SearchDetailed';
import {createRef, Fragment} from 'react';
import apiFetch from '@wordpress/api-fetch';
import {updateSearchData, updateSearchDataChosen} from '../../store/actions';
import LoaderSearchContent from '../loaders/LoaderSearchContent';
import LoaderSearchSidebar from '../loaders/LoaderSearchSidebar';

const BusinessStoreEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(false);
  const {type, fieldOptions} = props;
  const business = data.product;

  const handleChange = (e, detailed) => {
  };

  const [options, setOptions] = useState({});
  const [settings, setSettings] = useState({});
  let wrapper = null;
  let el = createRef();

  useEffect(() => {
    let idPremium = document.getElementById('page-single-business-premium-elementor');
    let id = document.getElementById('page-single-business-elementor');
    if (idPremium) {
      const optionsNewidPremium = JSON.parse(idPremium.dataset.options);
      setOptions(optionsNewidPremium);
      dispatch(actions.setOptions(optionsNewidPremium));
    } else if (id) {
      const optionsNew = JSON.parse(id.dataset.options);
      setOptions(optionsNew);
      dispatch(actions.setOptions(optionsNew));
    } else {
      const optionsNewNoId = data.options;
      setOptions(optionsNewNoId);
      dispatch(actions.setOptions(optionsNewNoId));
    }

  }, []);

  useEffect(() => {
    wrapper = el.current.closest('.elementor-business-store');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setSettings(settings);
    }
  }, []);

  useEffect(() => {
    if (isEmpty(data.fieldOptions)) {
      fetchSearchFields();
    }
  }, []);


  const fetchSearchFields = async () => {
    setLoading(true);
    const parsed = queryString.parse(location.search);
    let data = {};
    if (parsed['category-type']) {
      data['category-type'] = parsed['category-type'];
    }
    if (isEmpty(data['category-type']) && !parsed['category-type']) {
      data['category-type'] = 'common';
    }
    delete parsed['category-type'];
    map(parsed, (value, key) => data[key] = value);
    if (!data.fieldOptions || isEmpty(data.fieldOptions)) {
      await apiFetch({path: `${lc_data.search_builder_fields}/sidebar`}).then(result => {
        dispatch(actions.updateFieldOptions(result));
        dispatch(fetchPosts(data));
        dispatch(updateSearchData(data));
        dispatch(updateSearchDataChosen(data));
        setLoading(false);
      });
    }
  };

  const params = queryString.parse(location.search);
  const detailed = params && params.p;
  const url = `${lc_data['endpoint-business']}/${business.slug}`;
  return (
    business &&
    <section className="py-86 pt-30" ref={el}>
      <div className="container">
        <div className="row -mx-32">

          {!detailed &&
          <Fragment>
            <div className="-mb-10 px-12 w-full">
              <BusinessStoreBreadcrumb business={business} options={options} settings={settings}/>
            </div>
            <div className="w-full bg:w-5/16 mt-20 px-12">
              <div className="filters p-16 py-20 pb-30 bg-white rounded shadow-theme">
                {!isEmpty(data.fieldOptions) && !loading &&
                <Filters type="sidebar" page="business" options={options} settings={settings}/>
                }
                {loading && <LoaderSearchSidebar/>}
              </div>
            </div>

            <div className="w-full bg:w-11/16 px-12 mt-20">
              <SearchChosenFilters onChange={handleChange} page="business" options={options} settings={settings}/>
              {!loading && <BusinessStoreContent options={options} settings={settings}/>}
              {loading && <LoaderSearchContent type="business" productClasses="w-1/2 mb-20 px-18"/>}
            </div>
          </Fragment>
          }
          {detailed &&
          <div className="w-full">
            <SearchDetailed page="business" url={url} options={options}
                            results={data.postsByUrl.RECEIVE_POSTS.items || false}/>
          </div>
          }
        </div>
      </div>
    </section>
  );
};

export default BusinessStoreEl;
