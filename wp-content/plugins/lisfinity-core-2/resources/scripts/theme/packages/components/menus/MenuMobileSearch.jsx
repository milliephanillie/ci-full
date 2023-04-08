/* global lc_data, React */
/**
 * Dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import ReactSVG from 'react-svg';
import SearchIcon from '../../../../../images/icons/search.svg';
import CloseIcon from '../../../../../images/icons/close.svg';
import * as actions from '../../store/actions';
import { useDispatch, useSelector } from 'react-redux';
import queryString from 'query-string';

const MenuMobileSearch = (props) => {
  const data = useSelector(state => state);
  const showFilters = data.showFilters;
  const { onChange, results, reloadMap, searchData } = data;
  const dispatch = useDispatch();
  const [menuActivated, setMenuActivated] = useState(false);

  const filtersHandler = (e) => {
    dispatch(actions.updateShowFilters(!showFilters));
    dispatch(actions.updateShowMap(false));
  };

  const showMenuHandler = () => {
    if (window.innerWidth < 1030) {
      if (!data.searchData.isDetailed) {
        setMenuActivated(true);
      }
    } else {
      setMenuActivated(false);
    }
  };

  useEffect(() => {
    if (window.innerWidth < 1030) {
      if (!data.searchData.isDetailed) {
        setMenuActivated(true);
      } else {
        setMenuActivated(false);
      }
    }
  }, [data.searchData.isDetailed]);

  useEffect(() => {
    window.addEventListener('resize', showMenuHandler);

    showMenuHandler();

    return () => window.removeEventListener('resize', showMenuHandler);
  }, []);

  return (
    menuActivated &&
    <div className="filters flex-center mr-20">
      <button type="button"
              className="flex-center"
              onClick={(e) => filtersHandler(e)}>
        {!showFilters &&
        <ReactSVG
          src={`${lc_data.dir}dist/${SearchIcon}`}
          className={`relative top-5 w-22 h-22 fill-white`}
        />
        }
        {showFilters &&
        <ReactSVG
          src={`${lc_data.dir}dist/${CloseIcon}`}
          className={`relative w-18 h-18 fill-white`}
          style={{
            top: 7,
          }}
        />
        }
      </button>
    </div>
  );
};

export default MenuMobileSearch;
