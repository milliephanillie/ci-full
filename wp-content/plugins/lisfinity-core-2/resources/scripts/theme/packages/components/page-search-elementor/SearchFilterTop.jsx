/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import { sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../images/icons/home.svg';
import { isEmpty } from 'lodash';
import sortIcon from '../../../../../images/icons/sort-amount-asc.svg';
import Select from 'react-select';
import mapIcon from '../../../../../images/icons/map.svg';
import { updateSearchData, updateShowMap } from '../../store/actions';
import { fetchPosts } from '../page-search/store/actions';
import * as actions from '../../store/actions';
import cx from 'classnames';

const SearchFilterTop = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { searchData } = data;
  const  options  = data.options;
  const [items, setItems] = useState({});
  const [settings, setSettings] = useState({});
  const [sortOptions, setSortOptions] = useState([]);
  const [position, setPosition] = useState({});
  const [showMap, setShowMap] = useState(false);
  const wrapper = useRef(null);

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.page-search-filter-top');

    if (el) {
      const settingsData = JSON.parse(el.dataset.options);
      setSettings(settingsData);
    }
  }, [data]);

  useEffect(() => {
    setItems(data.postsByUrl.RECEIVE_POSTS?.items || {});
  }, [data.postsByUrl]);

  const handleShowMap = () => {
    setShowMap(!showMap);
    dispatch(actions.updateShowMap(!showMap));

    if (window.innerWidth < 1100) {
      dispatch(actions.updateShowFilters(false));
    }
    if (window.innerWidth < 1100 && showMap) {
      dispatch(actions.updateShowFilters(false));
    }

    const el = document.querySelector('.elementor-widget-search-map');
    if (el) {
      if (showMap) {
        el.classList.add('hidden');
      } else {
        el.classList.remove('hidden');
      }
    }
  };

  const formatSortOptions = () => {
    const chosenSortOptions = options?.ads_sortby;
    chosenSortOptions?.map((opt, index) => {
      if (opt === 'newest') {
        sortOptions.push(
          { value: 'date', label: lc_data.jst[184] },
        );
      }
      if (opt === 'price_asc') {
        sortOptions.push(
          { value: 'price_asc', label: lc_data.jst[393] },
        );
      }
      if (opt === 'price_desc') {
        sortOptions.push(
          { value: 'price_desc', label: lc_data.jst[394] },
        );
      }
      if (opt === 'top_rated') {
        sortOptions.push(
          { value: 'rating', label: lc_data.jst[395] },
        );
      }
      if (opt === 'nearby') {
        sortOptions.push(
          { value: 'nearby', label: lc_data.jst[396] },
        );
      }
      if (opt === 'recommended') {
        sortOptions.push(
          { value: 'recommended', label: lc_data.jst[397] },
        );
      }
    });

    setSortOptions(sortOptions);
  };

  useEffect(() => {
    const toShowMap = options?.show_map;
    if (toShowMap === 'on' || toShowMap === 'maybe_on') {
    setShowMap(true);
      dispatch(actions.updateShowMap(true));
    } else {
      setShowMap(false);
      dispatch(actions.updateShowMap(false));
    }
    formatSortOptions();

    if (window.innerWidth < 1100) {
      setShowMap(false);
      dispatch(actions.updateShowMap(false));
    }
  }, []);

  const changeOrder = (selected) => {
    const sData = { ...searchData };
    sData['order'] = selected.value;

    dispatch(updateSearchData(sData));

    if (selected.value === 'nearby') {
      getLocation();
    } else {
      dispatch(fetchPosts(searchData, true));
    }
  };

  function getLocation() {
    const sData = { ...searchData };
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (pos) {
        setPosition(pos.coords);
        sData['latitude'] = pos.coords.latitude;
        sData['longitude'] = pos.coords.longitude;
        dispatch(updateSearchData(sData));
        dispatch(fetchPosts(sData, true));
      }, function (error) {
        alert(error.message);
        console.log(error);
      }, { timeout: 1000 });
    } else {
      console.log('Geolocation is not supported by this browser.');
    }
  }

  const btnLabel = showMap ? lc_data.jst[228] : lc_data.jst[227];
  const mapToggleClass = cx({
    'mb-10 top-6': window.innerWidth < 770,
  });

  return (
    <div className="page-search--actions search--actions flex w-full" ref={wrapper}>

      <div
        className={`search--action--right flex flex-wrap w-full ${window.innerWidth < 770 && options.filtersOpen || window.innerWidth < 640 ? 'items-start' : 'items-center'}`}>

        {settings.display_sortby && sortOptions.length > 0 &&
        <div className="search--action search--action__sortby flex items-center mr-auto sm:mr-40">
          <label htmlFor="sortby" className="flex search-filter-top-label items-center mr-4 text-sm text-grey-500">
            {!settings.sort_icon &&
            <ReactSVG src={`${lc_data.dir}dist/${sortIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-icon-search"/>
            }
            {settings?.sort_icon?.value && settings?.sort_icon?.library !== 'svg' &&
            <i className={`mr-8 ${settings.sort_icon.value} fill-icon-search`}></i>
            }
            {settings?.sort_icon?.value.url && settings?.sort_icon?.library === 'svg' &&
            <ReactSVG
              src={`${settings.sort_icon.value.url}`}
              className={`mr-8 w-18 h-18 fill-icon-search`}
            />
            }
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

        {(options?.show_map === 'maybe_on' || options?.show_map === 'maybe_off') && settings?.display_map &&
        <div
          className={`search--action search--action__map toggle flex items-center font-semibold ${mapToggleClass}`}>
          {!settings.map_icon &&
          <ReactSVG src={`${lc_data.dir}dist/${mapIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-icon-search"/>
          }
          {settings?.map_icon?.value && settings?.map_icon?.library !== 'svg' &&
          <i className={`mr-8 ${settings.map_icon.value} fill-icon-search`}></i>
          }
          {settings?.map_icon?.value.url && settings?.map_icon?.library === 'svg' &&
          <ReactSVG
            src={`${settings.map_icon.value.url}`}
            className={`mr-8 w-18 h-18 fill-icon-search`}
          />
          }
          <span
            className="toggle--label toggle--label__label relative top-1 text-sm text-grey-500">{lc_data.jst[468]}</span>
          <span className="toggle--label toggle--label__value ml-4 mr-8 min-w-44">{btnLabel}</span>
          <label htmlFor="showMap" className="switch">
            <input type="checkbox" id="showMap" className="input--toggle" onChange={handleShowMap}
                   checked={showMap}/>
            <span className="slider"></span>
          </label>
        </div>}

      </div>

    </div>
  );
};

export default SearchFilterTop;
