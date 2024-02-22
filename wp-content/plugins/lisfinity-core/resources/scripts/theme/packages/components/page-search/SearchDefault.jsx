/* global lc_data, React */
/**
 * External dependencies.
 */
import * as actions from '../../store/actions';
import { Fragment, useState, useRef, useEffect, useCallback } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import { __, _x } from '@wordpress/i18n';
import Filters from './SearchFilters';
import Content from './PageSearchContent';
import SearchMap from './SearchMap';
import Select from 'react-select';
import ReactSVG from 'react-svg';
import Breadcrumb from './page-search--content/Breadcrumb';
import SearchChosenFilters from './page-search--content/SearchChosenFilters';
import { map, isEmpty } from 'lodash';
import cx from 'classnames';

/**
 * Internal dependencies
 */
import sortIcon from '../../../../../images/icons/sort-amount-asc.svg';
import mapIcon from '../../../../../images/icons/map.svg';
import { fetchPosts } from './store/actions';
import LoaderSearchSidebar from '../loaders/LoaderSearchSidebar';
import LoaderSearchContent from '../loaders/LoaderSearchContent';
import { updateSearchData, updateShowMap } from '../../store/actions';
import { getMobileOperatingSystem } from '../../../vendor/functions';

const SearchDefault = (props) => {
  const [showMap, setShowMap] = useState(false);
  const [sidebarWidth, setSidebarWidth] = useState('w-1/5');
  const data = useSelector(state => state);
  const { showFilters } = data;
  const { onChange, results, reloadMap, searchData, user } = data;
  const dispatch = useDispatch();
  const [sortOptions, setSortOptions] = useState([]);
  const [sortBy, setSortBy] = useState([]);
  const [position, setPosition] = useState({});

  const map = useRef(null);

  useEffect(() => {
    const toShowMap = props.options.show_map;

    console.log("super props")
    console.log(props)

    if (toShowMap === 'on' || toShowMap === 'maybe_on') {
      setShowMap(true);
      dispatch(actions.updateShowMap(true));
    } else {
      setShowMap(false);
      dispatch(actions.updateShowMap(false));
    }

    const body = document.getElementsByTagName('BODY')[0];
    if (body) {
      body.classList.remove('is-detailed');
    }

    sidebarWidthFunc();

    if (getMobileOperatingSystem() === 'Android' && window.innerWidth < 1100) {
      dispatch(actions.updateShowFilters(false));
    }

    formatSortOptions();

    if (window.innerWidth < 1100 && toShowMap !== 'on' && toShowMap !== 'maybe_on') {
      setShowMap(false);
      dispatch(actions.updateShowMap(false));
    }

    window.addEventListener('resize', sidebarWidthFunc);

    return () => window.removeEventListener('resize', sidebarWidthFunc);
  }, []);

  function getLocation() {
    const sData = { ...searchData };
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (pos) {
        setPosition(pos.coords);
        sData['latitude'] = pos.coords.latitude;
        sData['longitude'] = pos.coords.longitude;
        dispatch(updateSearchData(sData));
        dispatch(fetchPosts(sData));
      }, function (error) {
        alert(error.message);
        console.log(error);
      }, { timeout: 1000 });
    } else {
      console.log('Geolocation is not supported by this browser.');
    }
  }

  const changeOrder = (selected) => {
    const sData = { ...searchData };
    sData['order'] = selected.value;

    dispatch(updateSearchData(sData));

    if (selected.value === 'nearby') {
      getLocation();
    } else {
      dispatch(fetchPosts(sData));
    }
  };

  const handleShowMap = () => {
    setShowMap(!showMap);
    dispatch(actions.updateShowMap(!showMap));

    if (window.innerWidth < 1100) {
      dispatch(actions.updateShowFilters(false));
    }
    if (window.innerWidth < 1100 && showMap) {
      dispatch(actions.updateShowFilters(false));
    }
  };

  const formatSortOptions = () => {
    const chosenSortOptions = props.options.ads_sortby;
    if (!isEmpty(chosenSortOptions)) {
      chosenSortOptions.map((opt, index) => {
        if (opt === 'newest') {
          sortOptions.push(
            {value: 'date', label: lc_data.jst[184]},
          );
        }
        if (opt === 'price_asc') {
          sortOptions.push(
            {value: 'price_asc', label: lc_data.jst[393]},
          );
        }
        if (opt === 'price_desc') {
          sortOptions.push(
            {value: 'price_desc', label: lc_data.jst[394]},
          );
        }
        if (opt === 'top_rated') {
          sortOptions.push(
            {value: 'rating', label: lc_data.jst[395]},
          );
        }
        if (opt === 'nearby') {
          sortOptions.push(
            {value: 'nearby', label: lc_data.jst[396]},
          );
        }
        if (opt === 'recommended') {
          sortOptions.push(
            {value: 'recommended', label: lc_data.jst[397]},
          );
        }
      });
    }
    setSortOptions(sortOptions);
  };

  const sidebarWidthFunc = e => {
    let classes = '';
    if (window.innerWidth > 2050) {
      classes = 'w-14%';
    } else if (window.innerWidth > 1443) {
      classes = 'w-1/5';
    } else if (window.innerWidth > 1370 && window.innerWidth < 1442) {
      classes = 'w-1/4';
    } else if (window.innerWidth > 1100 && window.innerWidth < 1370) {
      classes = 'w-1/4';
    } else if (window.innerWidth > 960 && window.innerWidth < 1100) {
      classes = 'w-2/5';
    } else if (window.innerWidth > 770 && window.innerWidth < 960) {
      classes = 'w-3/5';
    } else if (window.innerWidth > 645 && window.innerWidth < 770) {
      classes = 'w-11/16';
    } else if (window.innerWidth < 645) {
      classes = 'w-full';
    }

    setSidebarWidth(classes);
    if (getMobileOperatingSystem() !== 'Android') {
      if (window.innerWidth < 1100) {
        dispatch(actions.updateShowFilters(false));
      } else {
        dispatch(actions.updateShowFilters(true));
      }
    }
  };

  const filtersHandler = () => {
    dispatch(actions.updateShowFilters(true));
    dispatch(actions.updateShowMap(false));
  };

  const handleFiltersOpen = e => {
    if (window.innerWidth < 1100 && !showFilters) {
      dispatch(actions.updateShowMap(false));
      setShowMap(false);
    }
    dispatch(actions.updateShowFilters(!showFilters));
  };

  useEffect(() => {
    if (window.innerWidth < 1100 && props.options.show_map !== 'on' && props.options.show_map !== 'maybe_on' && showFilters) {
      dispatch(actions.updateShowMap(false));
      setShowMap(false);
    }
  }, [showFilters]);

  const detailed = _x(lc_data.jst[548], 'endpoint', 'lisfinity-core');
  const filtersOpen = showFilters;
  const btnLabel = showMap ? lc_data.jst[228] : lc_data.jst[227];
  const headerClass = cx({
    'w-86%': filtersOpen,
    'w-full': !filtersOpen,
    'hidden': window.innerWidth < 645 && filtersOpen,
  });
  const mapToggleClass = cx({
    'mb-10 top-6': window.innerWidth < 770,
  });

  return (
    <Fragment>
      {filtersOpen &&
      <div className={`relative filters py-36 pt-0 px-10 bg-white ${sidebarWidth}`}>
        {(props.loading || !props.results) && <LoaderSearchSidebar/>}
        {!props.loading && props.results &&
        <Filters
          type="sidebar"
          onChange={props.onChange}
          filtersOpen={filtersOpen}
          filtersHandler={handleFiltersOpen}
          options={props.options}
        />}
      </div>
      }

      <div className={`flex flex-col bg-grey-100 ${headerClass}`}>

        <div className="search--actions flex py-20 px-24 w-full bg-white">

          <div
            className={`search--action--right flex flex-wrap w-full ${window.innerWidth < 770 && filtersOpen || window.innerWidth < 640 ? 'items-start' : 'items-center'}`}>

            {sortOptions.length > 0 &&
            <div className="search--action flex items-center mr-auto sm:mr-40">
              <label htmlFor="sortby" className="flex items-center mr-4 text-sm text-grey-500">
                <ReactSVG src={`${lc_data.dir}dist/${sortIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-icon-search"/>
                {lc_data.jst[182]}
              </label>
              <Select
                id="sortby"
                name="sortby"
                options={sortOptions}
                isSearchable={false}
                defaultValue={sortOptions[0]}
                isRtl={lc_data.rtl}
                onChange={(selected) => changeOrder(selected)}
              />
            </div>
            }

            {(props.options.show_map === 'maybe_on' || props.options.show_map === 'maybe_off') &&
            <div
              className={`search--action toggle flex items-center font-semibold ${mapToggleClass}`}>
              <ReactSVG src={`${lc_data.dir}dist/${mapIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-icon-search"/>
              <span
                className="toggle--label relative top-1 text-sm text-grey-500">{lc_data.jst[468]}</span>
              <span className="toggle--label ml-4 mr-8 min-w-44">{btnLabel}</span>
              <label htmlFor="showMap" className="switch">
                <input type="checkbox" id="showMap" className="input--toggle" onChange={handleShowMap}
                       checked={showMap}/>
                <span className="slider"></span>
              </label>
            </div>}

          </div>

        </div>

        <div className="relative flex h-full z-1">
          <div className="relative content py-36 px-col sm:px-30 w-full border-l border-grey-200">
            <Breadcrumb options={props.options}/>
            <SearchChosenFilters options={props.options}/>
            <Content filtersOpen={filtersOpen} showMap={showMap} filtersLoading={props.loading}
                  categoryContent={props.categoryContent}   options={props.options}/>
          </div>
          <SearchMap forwardedRef={map} results={results} showMap={showMap} handleShowMap={handleShowMap}
                     filtersOpen={filtersOpen}/>
        </div>
      </div>

    </Fragment>
  );
};

export default SearchDefault;
