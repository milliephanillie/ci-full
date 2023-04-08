/* global lc_data, React */
/**
 * External dependencies.
 */
import { useSelector, useDispatch } from 'react-redux';
import * as actions from '../../store/actions';
import { Fragment, useEffect, useState } from '@wordpress/element';
import { map, isEmpty } from 'lodash';
import queryString from 'query-string';
import apiFetch from '@wordpress/api-fetch';
import { setIsDetailed, updateSearchData, updateSearchDataChosen } from '../../store/actions';
import { fetchPosts } from '../page-search/store/actions';
import LoaderGlobal from '../loaders/LoaderGlobal';

const PageSearchEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { searchData } = data;
  const [loading, setLoading] = useState(true);
  const [detailed, setDetailed] = useState(false);
  const [reloadMap, setReloadMap] = useState(true);
  const [options, setOptions] = useState({});

  useEffect(() => {
    const id = document.getElementById('page-search-elementor');
    if (id) {
      const optionsNew = JSON.parse(id.dataset.options);
      setOptions(optionsNew);
      dispatch(actions.setOptions(optionsNew));
    }
    let storage = JSON.parse(localStorage.getItem('storage')) || false;

    fetchSearchFields();

    if (storage && (document.referrer.indexOf(lc_data.page_search_endpoint) > 0 || document.referrer.indexOf(lc_data.page_search_detailed_endpoint) > 0 || performance.navigation.type === performance.navigation.TYPE_RELOAD || document.referrer === '')) {
      dispatch(updateSearchData(storage));
      dispatch(updateSearchDataChosen(storage));
      dispatch(fetchPosts(storage));
      setLoading(false);
    } else {
      const parsed = queryString.parse(location.search);
      const data = {};
      if (parsed.t) {
        data['category-type'] = parsed.t;
      }
      // update search data with url query params.
      map(parsed, (param, name) => {
        if (!parsed.t && !isEmpty(param)) {
          data[name] = param;
        }
      });
      dispatch(updateSearchData(data));
      dispatch(updateSearchDataChosen(data));
      dispatch(setIsDetailed(parsed && parsed.p === 'detailed'));
      dispatch(fetchPosts(data));

    }

  }, []);

  const fetchSearchFields = async () => {
    const parsed = queryString.parse(location.search);
    let searchValues = searchData;
    if (parsed['category-type']) {
      searchValues['category-type'] = parsed['category-type'];
    }
    if (isEmpty(searchValues['category-type']) && !parsed['category-type']) {
      searchValues['category-type'] = 'common';
    }
    delete parsed['category-type'];
    map(parsed, (value, key) => !isEmpty(value) ? searchValues[key] = value : '');
    setLoading(true);
    if (!data.fieldOptions || isEmpty(data.fieldOptions)) {
      await apiFetch({ path: `${lc_data.search_builder_fields}/sidebar` }).then(result => {
        localStorage.setItem('fieldOptions', JSON.stringify(result));
        dispatch(actions.updateFieldOptions(result));
        dispatch(fetchPosts(searchValues));

        setLoading(false);
      });
    }
  };

  return (
    <Fragment>
      {<LoaderGlobal loading={data.isFetching && data.isFetching !== 'paginating'} title={lc_data.jst[697]}/>}
    </Fragment>
  );
};

export default PageSearchEl;
