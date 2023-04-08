/* global lc_data, React */
import KeywordSearchEl from './KeywordSearchEl';
import { Fragment, useEffect, useRef, useState } from '@wordpress/element';
import axios from 'axios';
import { map, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import searchIcon from '../../../../../images/icons/search.svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import SearchIcon from '../../../../../images/icons/search.svg';
import CloseIcon from '../../../../../images/icons/close.svg';
import { useSelector } from 'react-redux';

/**
 * Dependencies
 */
const HeaderKeywordEl = (props) => {
  const data = useSelector(state => state);
  const [loading, setLoading] = useState(false);
  const [keywordValue, setKeywordValue] = useState('');
  const [fields, setFields] = useState({});
  const [settings, setSettings] = useState([]);
  const [showKeyword, setShowKeyword] = useState(false);
  const [menuActivated, setMenuActivated] = useState(false);
  const [right, setRight] = useState(70);
  const [keywordSettings, setKeywordSettings] = useState({});
  const el = useRef(null);

  const keywordValueHandle = (value) => {
    setKeywordValue(value);
  };

  const goToSingleAd = (id) => {
    setLoading(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    const data = {
      id,
      method: 'permalink',
    };
    axios({
      url: lc_data.get_product_method,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data,
    }).then(response => {
      if (response.success) {
        window.location.href = response.data.permalink;
      }
      setLoading(false);
    });
  };

  useEffect(() => {
    const iconId = el.current.closest('.header-keyword');
    if (iconId && iconId.dataset && iconId.dataset.settings) {
      const keywordSettings = JSON.parse(iconId.dataset.settings);
      setKeywordSettings(keywordSettings);
    }

  }, []);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      const keyword = [];
      const headers = { 'X-WP-Nonce': lc_data.nonce };
      await axios({
        url: `${lc_data.search_builder_fields}/home`,
        credentials: 'same-origin',
        //headers,
      }).then(result => {
        if (result.data && result.data.fields) {
          setFields(result.data.fields);
          map(result.data.fields.fields, field => {
            keyword.push(field);
          });
          setSettings(keyword);
        }
        setLoading(false);
      });
    };
    fetchData();
  }, []);

  const showMenuHandler = () => {
    setMenuActivated(true);
    if (window.innerWidth > 640) {
      setRight(115);
    } else {
      setRight(70);
    }
  };

  useEffect(() => {
    setMenuActivated(true);
  }, [data.searchData.showFilters]);

  useEffect(() => {
    showMenuHandler();
    return () => window.removeEventListener('resize', showMenuHandler);
  }, []);

  return (
    <div className={`keyword--wrapper`} ref={el}>
      {!loading &&
      <div key={0} className="keyword-div flex items-center w-full">
        <form key={1} action={lc_data.page_search} className={`flex-center mx-auto w-full`}>
          <KeywordSearchEl keywordValueHandle={(value) => keywordValueHandle(value)} fields={fields}
                   searchValues={settings}
                   displayLabel = {keywordSettings.display_label}
                   type="header"/>
          <button
            type="submit"
            className={`btn btn-lg -ml-3 lisfinity-order-10 px-10 h-40 bg-blue-700 hover:bg-blue-800 w-auto bg:w-auto rounded-l-none z-10 test`}
            onClick={(e) => {
              if ('' !== keywordValue && !isNaN(keywordValue)) {
                e.preventDefault();
                goToSingleAd(keywordValue);
              }
            }}
            disabled={loading}
          >
            { ((!loading && isEmpty(keywordSettings)) || ( !loading && keywordSettings?.display_button_icon && !keywordSettings?.custom_icon_font && !keywordSettings?.custom_icon_url )) &&
            <Fragment>
              <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} id="search--icon" className="w-20 h-20 fill-white"/>
            </Fragment>
            }
            {keywordSettings?.custom_icon_font &&
            <i className={`mr-8 ${keywordSettings.custom_icon_font} fill-icon-home`} id="search--icon"></i>
            }
            {keywordSettings?.custom_icon_url &&
            <ReactSVG
              src={`${keywordSettings.custom_icon_url}`}
              id="search--icon"
              className={`mr-8 w-16 h-16 fill-icon-home`}
            />
            }
            {loading &&
            <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-20 h-20 fill-white"
                      style={{
                        top: '-13px',
                        left: '10px',
                        width: '64px',
                      }}
            />
            }
            { keywordSettings?.display_button_text && keywordSettings?.text &&
            <span>{keywordSettings.text}</span>
            }
          </button>
        </form>
      </div>
      }
    </div>
  );
};

export default HeaderKeywordEl;
