/* global lc_data, React */

import Filters from '../../page-search/SearchFilters';
import SearchChosenFilters from '../../page-search/page-search--content/SearchChosenFilters';
import BusinessStoreContent from './BusinessStoreContent';
import BusinessStoreBreadcrumb from './BusinessStoreBreadcrumb';
import { useEffect, useState } from '@wordpress/element';
import queryString from 'query-string';
import * as actions from '../../../store/actions';
import { fetchPosts } from '../../page-search/store/actions';
import { useDispatch, useSelector } from 'react-redux';
import { map, isEmpty } from 'lodash';
import SearchDetailed from '../../page-search/SearchDetailed';
import { Fragment } from 'react';
import apiFetch from '@wordpress/api-fetch';
import { updateSearchData, updateSearchDataChosen } from '../../../store/actions';
import LoaderSearchContent from '../../loaders/LoaderSearchContent';
import LoaderSearchSidebar from '../../loaders/LoaderSearchSidebar';

const BusinessStore = (props) => {
  const { business, type, fieldOptions } = props;
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(false);
  const [detailed, setDetailed] = useState(false);

  const handleChange = () => {
    setDetailed(!detailed);
  };

  useEffect(() => {
    const params = queryString.parse(location.search);
    setDetailed((params && params?.p === 'detailed') || false);
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
      await apiFetch({ path: `${lc_data.search_builder_fields}/sidebar` }).then(result => {
        dispatch(actions.updateFieldOptions(result));
        dispatch(fetchPosts(data));
        dispatch(updateSearchData(data));
        dispatch(updateSearchDataChosen(data));
        setLoading(false);
      });
    }
  };

  const url = `${lc_data['endpoint-business']}/${business.slug}`;
  return (
    business &&
    <section className="py-86 pt-30">
      <div className="container">
        <div className="row -mx-32">

          {!detailed &&
          <Fragment>
            <div className="-mb-10 px-12 w-full">
              <BusinessStoreBreadcrumb business={business} options={props.options}/>
            </div>
            <div className="w-full bg:w-5/16 mt-20 px-12">
              <div className="filters p-16 py-20 pb-30 bg-white rounded shadow-theme">
                {!isEmpty(data.fieldOptions) && !loading &&
                <Filters type="sidebar" page="business" options={props.options} onChange={handleChange}/>
                }
                {loading && <LoaderSearchSidebar/>}
              </div>
            </div>

            <div className="w-full bg:w-11/16 px-12">
              <SearchChosenFilters onChange={handleChange} page="business" options={props.options}/>
              {!loading && <BusinessStoreContent options={props.options}/>}
              {loading && <LoaderSearchContent type="business" productClasses="w-1/2 mb-20 px-18"/>}
            </div>
          </Fragment>
          }
          {detailed &&
          <div className="w-full">
            <SearchDetailed page="business" url={url} options={props.options}
                            onChange={handleChange}
                            results={data.postsByUrl.RECEIVE_POSTS.items || false}/>
          </div>
          }
        </div>
      </div>
    </section>
  );
};

export default BusinessStore;
