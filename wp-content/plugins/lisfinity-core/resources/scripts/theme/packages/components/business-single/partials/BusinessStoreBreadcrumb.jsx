/* global lc_data, React */

import { Fragment } from '@wordpress/element';
import { fetchPosts } from '../../page-search/store/actions';
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import { __, sprintf } from '@wordpress/i18n';
import sortIcon from '../../../../../../images/icons/sort-amount-asc.svg';
import Select from 'react-select';

const BusinessStoreBreadcrumb = (props) => {
  const { business } = props;
  const data = useSelector(state => state);
  const { postsByUrl, searchData } = data;
  const request = postsByUrl.RECEIVE_POSTS || {};
  const dispatch = useDispatch();

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (pos) {
        setPosition(pos.coords);
        searchData['latitude'] = pos.coords.latitude;
        searchData['longitude'] = pos.coords.longitude;
        dispatch(fetchPosts(searchData));
      }, function (error) {
        alert(error.message);
      }, { timeout: 1000 });
    } else {
      alert('Geolocation is not supported by this browser.');
      console.log('Geolocation is not supported by this browser.');
    }
  }

  const changeOrder = (selected) => {
    searchData['order'] = selected.value;

    if (selected.value === 'nearby') {
      getLocation();
    } else {
      dispatch(fetchPosts(searchData));
    }
  };

  const chosenSortOptions = props.options.ads_sortby;
  const sortOptions = [];
  chosenSortOptions.map((opt, index) => {
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
  return (
    business &&
    <Fragment>
      <nav className="search--breadcrumb mt-30 mb-16 py-4 px-20 bg-white rounded shadow-theme bg:mt-0">
        <ul className="flex items-center -mx-4">
          {
            request.items &&
            <li className="px-4">
              <span
                className="text-grey-1100">{sprintf(lc_data.jst[398], request.items.found_posts)}</span>
            </li>
          }
          {
            searchData['category-type'] &&
            <li className="-ml-2 px-4">
              <span className="mr-4 text-grey-700">{lc_data.jst[399]}</span>
              <span
                className="inline-block text-grey-1100 capitalize"
              >
                  {
                    searchData['category-type'] === 'common'
                      ? lc_data.jst[400]
                      : searchData['category-type'].replace(/-/, ' ')
                  }
                </span>
            </li>
          }

          <li className="ml-auto">
            <div className="search--action flex items-center">
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
                onChange={(selected) => changeOrder(selected)}
              />
            </div>
          </li>
        </ul>
      </nav>
    </Fragment>
  );
};

export default BusinessStoreBreadcrumb;
